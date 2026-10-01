<?php

namespace App\Console\Commands;

use App\Services\ImageOptimizer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class OptimizeImages extends Command
{
    protected $signature = 'images:optimize {--dry-run : Report savings without writing files or touching the DB}';

    protected $description = 'Resize and recompress images in public/ to reduce page weight';

    /**
     * dir (relative to base_path) => [max dimension, db map]
     * db map: table => column(s) storing the file basename
     */
    private array $dirs = [
        'public/banners'          => ['max' => 1600, 'db' => ['banners' => ['image']]],
        'public/categories'       => ['max' => 800,  'db' => ['categories' => ['image']]],
        'public/products-images'  => ['max' => 1200, 'db' => ['products' => ['image', 'gallery']]],
        'public/images'           => ['max' => 1200, 'db' => []],
        'public/public/products-images' => ['max' => 1200, 'db' => []],
        'banners'                 => ['max' => 1600, 'db' => []],
        'categories'              => ['max' => 800,  'db' => []],
        'products-images'         => ['max' => 1200, 'db' => []],
        'images'                  => ['max' => 1200, 'db' => []],
    ];

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $totalOld = $totalNew = $changed = $skipped = 0;

        foreach ($this->dirs as $dir => $cfg) {
            $abs = base_path($dir);
            if (!is_dir($abs)) {
                continue;
            }

            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($abs, \FilesystemIterator::SKIP_DOTS)
            );

            foreach ($files as $file) {
                if (!$file->isFile() || !preg_match('/\.(jpe?g|png|webp|gif)$/i', $file->getFilename())) {
                    continue;
                }

                $path = $file->getPathname();

                if ($dryRun) {
                    $info = @getimagesize($path);
                    if ($info && (filesize($path) > 200 * 1024 || max($info[0], $info[1]) > $cfg['max'])) {
                        $this->line(sprintf('%s  %s  %sx%s', $this->kb(filesize($path)), $path, $info[0], $info[1]));
                    }
                    continue;
                }

                // Only allow PNG->JPG renames in DB-mapped dirs — files under
                // images/ are referenced by filename in code and must not rename.
                $result = ImageOptimizer::optimize($path, $cfg['max'], 82, !empty($cfg['db']));
                if (!$result['changed']) {
                    $skipped++;
                    continue;
                }

                $changed++;
                $totalOld += $result['old_size'];
                $totalNew += $result['new_size'];
                $this->line(sprintf(
                    '%s -> %s  %s%s',
                    $this->kb($result['old_size']),
                    $this->kb($result['new_size']),
                    $result['path'],
                    $result['renamed'] ? '  (renamed .png -> .jpg)' : ''
                ));

                if ($result['renamed']) {
                    $this->updateDbRefs(basename($path), basename($result['path']), $cfg['db']);
                }
            }
        }

        if ($dryRun) {
            $this->info('Dry run — listed candidate files only.');
            return self::SUCCESS;
        }

        // Fixup pass: if optimized files were deployed without this command
        // running the rename itself, DB rows may still point at *.png while only
        // the *.jpg sibling exists on disk. Repair those references.
        $this->fixStalePngRefs();

        $this->info(sprintf(
            'Done. Optimized %d file(s), %s -> %s saved. %d already OK.',
            $changed,
            $this->kb($totalOld),
            $this->kb($totalNew),
            $skipped
        ));

        return self::SUCCESS;
    }

    private function fixStalePngRefs(): void
    {
        $dirFor = [
            'banners'    => 'banners',
            'categories' => 'categories',
            'products'   => 'products-images',
        ];
        $colFor = [
            'banners'    => ['image'],
            'categories' => ['image'],
            'products'   => ['image'],
        ];

        foreach ($dirFor as $table => $dir) {
            foreach ($colFor[$table] as $col) {
                DB::table($table)->where($col, 'like', '%.png')->pluck($col, 'id')->each(function ($name, $id) use ($table, $dir, $col) {
                    $jpg = preg_replace('/\.png$/i', '.jpg', $name);
                    if (!file_exists(public_path("$dir/$name")) && file_exists(public_path("$dir/$jpg"))) {
                        DB::table($table)->where('id', $id)->update([$col => $jpg]);
                        $this->line("   DB fix: {$table}.{$col} {$name} -> {$jpg}");
                    }
                });
            }
        }

        // products.gallery JSON arrays
        DB::table('products')->whereNotNull('gallery')->get(['id', 'gallery'])->each(function ($row) {
            $gallery = json_decode($row->gallery, true);
            if (!is_array($gallery)) {
                return;
            }
            $dirty = false;
            $gallery = array_map(function ($f) use (&$dirty) {
                if (is_string($f) && str_ends_with(strtolower($f), '.png')) {
                    $jpg = preg_replace('/\.png$/i', '.jpg', $f);
                    if (!file_exists(public_path("products-images/$f")) && file_exists(public_path("products-images/$jpg"))) {
                        $dirty = true;
                        return $jpg;
                    }
                }
                return $f;
            }, $gallery);
            if ($dirty) {
                DB::table('products')->where('id', $row->id)->update(['gallery' => json_encode($gallery)]);
                $this->line("   DB fix: products #{$row->id} gallery");
            }
        });
    }

    private function updateDbRefs(string $oldName, string $newName, array $dbMap): void
    {
        foreach ($dbMap as $table => $columns) {
            foreach ($columns as $column) {
                if ($column === 'gallery') {
                    // gallery is a JSON array of filenames on products
                    DB::table('products')
                        ->where('gallery', 'like', '%"' . $oldName . '"%')
                        ->get(['id', 'gallery'])
                        ->each(function ($row) use ($oldName, $newName) {
                            $gallery = json_decode($row->gallery, true) ?: [];
                            $gallery = array_map(fn($f) => $f === $oldName ? $newName : $f, $gallery);
                            DB::table('products')->where('id', $row->id)->update(['gallery' => json_encode($gallery)]);
                        });
                    continue;
                }

                $n = DB::table($table)->where($column, $oldName)->update([$column => $newName]);
                if ($n) {
                    $this->line("   DB: {$table}.{$column} {$oldName} -> {$newName} ({$n} row)");
                }
            }
        }
    }

    private function kb(int $bytes): string
    {
        return $bytes >= 1048576
            ? number_format($bytes / 1048576, 1) . 'MB'
            : number_format($bytes / 1024, 0) . 'KB';
    }
}
