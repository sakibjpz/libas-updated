@extends('admin.layouts.app')

@section('title', 'Add New Product')
@section('page_title', 'Add New Product')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
    <li class="breadcrumb-item active">Add New</li>
@endsection

@section('content')
<div class="ep-wrapper">

    {{-- ── Page Header ── --}}
    <div class="ep-page-header">
        <div class="ep-page-header__left">
            <div class="ep-page-header__icon">
                <i class="fas fa-plus"></i>
            </div>
            <div>
                <h1 class="ep-page-header__title">Add New Product</h1>
                <p class="ep-page-header__subtitle">Fill in the details below to list a new product</p>
            </div>
        </div>
        <div class="ep-page-header__actions">
            <a href="{{ route('admin.products.index') }}" class="ep-btn ep-btn--ghost">
                <i class="fas fa-arrow-left"></i> Back to Products
            </a>
        </div>
    </div>

    {{-- ── Error Banner ── --}}
    @if ($errors->any())
    <div class="ep-alert ep-alert--error">
        <div class="ep-alert__icon"><i class="fas fa-exclamation-circle"></i></div>
        <div>
            <strong>Please fix the following errors:</strong>
            <ul class="ep-alert__list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
        @csrf

        <div class="ep-grid">

            {{-- ══════════════════════════════════
                 LEFT COLUMN — Product Details
            ══════════════════════════════════ --}}
            <div class="ep-col ep-col--main">

                {{-- Core Info --}}
                <div class="ep-card">
                    <div class="ep-card__header">
                        <span class="ep-card__header-dot ep-card__header-dot--blue"></span>
                        <h2 class="ep-card__title">Product Information</h2>
                    </div>
                    <div class="ep-card__body">

                        <div class="ep-field">
                            <label class="ep-label" for="name">Product Name <span class="ep-required">*</span></label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   class="ep-input @error('name') ep-input--error @enderror"
                                   value="{{ old('name') }}"
                                   placeholder="Enter product name"
                                   required>
                            @error('name')
                                <span class="ep-field-error"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</span>
                            @enderror
                        </div>

                        <div class="ep-row-2">
                            <div class="ep-field">
                                <label class="ep-label" for="category_id">Category <span class="ep-required">*</span></label>
                                <div class="ep-select-wrap">
                                    <select name="category_id" id="category_id" class="ep-select @error('category_id') ep-input--error @enderror" required>
                                        <option value="">— Select Category —</option>
                                        @foreach(\App\Models\Category::whereNull('parent_id')->with('children')->orderBy('name')->get() as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                            @foreach($category->children as $subCategory)
                                                <option value="{{ $subCategory->id }}" {{ old('category_id') == $subCategory->id ? 'selected' : '' }}>
                                                    &nbsp;&nbsp;— {{ $subCategory->name }}
                                                </option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                    <i class="fas fa-chevron-down ep-select-arrow"></i>
                                </div>
                                @error('category_id')
                                    <span class="ep-field-error"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</span>
                                @enderror
                            </div>

                            <div class="ep-field">
                                <label class="ep-label" for="brand">Brand</label>
                                <input type="text"
                                       name="brand"
                                       id="brand"
                                       class="ep-input @error('brand') ep-input--error @enderror"
                                       value="{{ old('brand') }}"
                                       placeholder="e.g., Aarong, Kay Kraft">
                                @error('brand')
                                    <span class="ep-field-error"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="ep-field">
                            <label class="ep-label" for="description">Description</label>
                            <textarea name="description"
                                      id="description"
                                      class="ep-textarea @error('description') ep-input--error @enderror"
                                      rows="5"
                                      placeholder="Detailed product description...">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="ep-field-error"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- ── SEO ── --}}
                <div class="ep-card">
                    <div class="ep-card__header">
                        <span class="ep-card__header-dot ep-card__header-dot--amber"></span>
                        <h2 class="ep-card__title"><i class="fas fa-search ep-card__title-icon"></i> SEO <span class="ep-optional">Optional</span></h2>
                    </div>
                    <div class="ep-card__body">
                        <div class="ep-field">
                            <label class="ep-label" for="slug">URL Slug</label>
                            <input type="text"
                                   name="slug"
                                   id="slug"
                                   class="ep-input @error('slug') ep-input--error @enderror"
                                   value="{{ old('slug') }}"
                                   placeholder="Auto-generated from product name">
                            @error('slug')
                                <span class="ep-field-error"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</span>
                            @enderror
                        </div>
                        <div class="ep-field">
                            <label class="ep-label" for="meta_title">Meta Title <span class="ep-optional">~60 chars</span></label>
                            <input type="text"
                                   name="meta_title"
                                   id="meta_title"
                                   maxlength="255"
                                   class="ep-input @error('meta_title') ep-input--error @enderror"
                                   value="{{ old('meta_title') }}"
                                   placeholder="Defaults to product name">
                            @error('meta_title')
                                <span class="ep-field-error"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</span>
                            @enderror
                        </div>
                        <div class="ep-field">
                            <label class="ep-label" for="meta_description">Meta Description <span class="ep-optional">~160 chars</span></label>
                            <textarea name="meta_description"
                                      id="meta_description"
                                      maxlength="500"
                                      class="ep-textarea @error('meta_description') ep-input--error @enderror"
                                      rows="3"
                                      placeholder="Defaults to product description">{{ old('meta_description') }}</textarea>
                            @error('meta_description')
                                <span class="ep-field-error"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</span>
                            @enderror
                        </div>
                        <div class="ep-field">
                            <label class="ep-label" for="meta_keywords">Meta Keywords <span class="ep-optional">comma separated</span></label>
                            <input type="text"
                                   name="meta_keywords"
                                   id="meta_keywords"
                                   maxlength="500"
                                   class="ep-input @error('meta_keywords') ep-input--error @enderror"
                                   value="{{ old('meta_keywords') }}"
                                   placeholder="e.g., abaya, burkha, stone work, premium">
                            @error('meta_keywords')
                                <span class="ep-field-error"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</span>
                            @enderror
                        </div>
                        <div class="ep-field">
                            <label class="ep-label" for="landing_theme">Landing Page Theme <span class="ep-optional">for /lp/{slug}</span></label>
                            <select name="landing_theme" id="landing_theme" class="ep-input @error('landing_theme') ep-input--error @enderror">
                                @foreach(\App\Http\Controllers\ProductLandingController::THEMES as $key => $label)
                                    <option value="{{ $key }}" {{ old('landing_theme', 'gold') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('landing_theme')
                                <span class="ep-field-error"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ── Sizes & Colors ── --}}
                <div class="ep-variants-grid">

                    {{-- Sizes --}}
                    <div class="ep-card">
                        <div class="ep-card__header">
                            <span class="ep-card__header-dot ep-card__header-dot--green"></span>
                            <h2 class="ep-card__title"><i class="fas fa-tag ep-card__title-icon"></i> Sizes <span class="ep-optional">Optional</span></h2>
                        </div>
                        <div class="ep-card__body">
                            <div class="ep-field">
                                <label class="ep-label">Available Sizes</label>
                                <select name="sizes[]" id="sizes" class="ep-select ep-select--multi" multiple="multiple" data-placeholder="Pick sizes…">
                                    @foreach($sizes as $size)
                                        <option value="{{ $size->id }}" {{ in_array($size->id, old('sizes', [])) ? 'selected' : '' }}>
                                            {{ $size->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="sizeDetails" class="ep-variant-table-wrap" style="display:none;">
                                <p class="ep-variant-table-label">Size Details</p>
                                <div class="ep-table-scroll">
                                    <table class="ep-table">
                                        <thead>
                                            <tr>
                                                <th>Size</th>
                                                <th>Stock</th>
                                                <th>Price Adj. (৳)</th>
                                            </tr>
                                        </thead>
                                        <tbody id="sizeFields"></tbody>
                                    </table>
                                </div>
                                <p class="ep-hint">Stock: available qty &nbsp;·&nbsp; Price Adj.: extra cost for this size</p>
                            </div>
                        </div>
                    </div>

                    {{-- Colors --}}
                    <div class="ep-card">
                        <div class="ep-card__header">
                            <span class="ep-card__header-dot ep-card__header-dot--purple"></span>
                            <h2 class="ep-card__title"><i class="fas fa-palette ep-card__title-icon"></i> Colors <span class="ep-optional">Optional</span></h2>
                        </div>
                        <div class="ep-card__body">
                            <div class="ep-field">
                                <label class="ep-label">Available Colors</label>
                                <select name="colors[]" id="colors" class="ep-select ep-select--multi" multiple="multiple" data-placeholder="Pick colors…">
                                    @foreach($colors as $color)
                                        <option value="{{ $color->id }}" data-hex="{{ $color->hex_code }}" {{ in_array($color->id, old('colors', [])) ? 'selected' : '' }}>
                                            {{ $color->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="colorDetails" class="ep-variant-table-wrap" style="display:none;">
                                <p class="ep-variant-table-label">Color Details</p>
                                <div class="ep-table-scroll">
                                    <table class="ep-table">
                                        <thead>
                                            <tr>
                                                <th>Color</th>
                                                <th>Stock</th>
                                                <th>Price Adj. (৳)</th>
                                            </tr>
                                        </thead>
                                        <tbody id="colorFields"></tbody>
                                    </table>
                                </div>
                                <p class="ep-hint">Stock: available qty &nbsp;·&nbsp; Price Adj.: extra cost for this color</p>
                            </div>
                        </div>
                    </div>

                </div>{{-- /ep-variants-grid --}}

                {{-- ── Image Upload ── --}}
                <div class="ep-card">
                    <div class="ep-card__header">
                        <span class="ep-card__header-dot ep-card__header-dot--amber"></span>
                        <h2 class="ep-card__title"><i class="fas fa-image ep-card__title-icon"></i> Product Image</h2>
                    </div>
                    <div class="ep-card__body ep-image-section ep-image-section--create">

                        <div class="ep-upload-zone" id="uploadZone">
                            <input type="file" name="image" id="image" class="ep-upload-input @error('image') ep-input--error @enderror" accept="image/*">
                            <div class="ep-upload-zone__content" id="uploadContent">
                                <div class="ep-upload-zone__icon"><i class="fas fa-cloud-upload-alt"></i></div>
                                <p class="ep-upload-zone__text">Drop your image here or click to browse</p>
                                <p class="ep-upload-zone__hint">Max 2MB &nbsp;·&nbsp; Recommended 800 × 800 px</p>
                            </div>
                            <div id="imagePreview" class="ep-upload-preview" style="display:none;">
                                <img id="previewImage" src="#" alt="Preview">
                                <p class="ep-upload-preview__name" id="previewName"></p>
                            </div>
                        </div>

                        @error('image')
                            <span class="ep-field-error mt-2"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</span>
                        @enderror

                    </div>
                </div>

                <div class="ep-card">
                    <div class="ep-card__header">
                        <span class="ep-card__header-dot ep-card__header-dot--amber"></span>
                        <h2 class="ep-card__title"><i class="fas fa-images ep-card__title-icon"></i> Gallery Images</h2>
                    </div>
                    <div class="ep-card__body">
                        <div class="ep-upload-zone" id="galleryZone">
                            <input type="file" name="gallery_images[]" id="gallery" class="ep-upload-input @error('gallery_images') ep-input--error @enderror" accept="image/*" multiple>
                            <div class="ep-upload-zone__content">
                                <div class="ep-upload-zone__icon"><i class="fas fa-images"></i></div>
                                <p class="ep-upload-zone__text">Drop multiple images or click to browse</p>
                                <p class="ep-upload-zone__hint">Max 10 images · 2MB each · shown as thumbnails on product page</p>
                            </div>
                        </div>
                        <div id="galleryPreview" style="display:flex; flex-wrap:wrap; gap:10px; margin-top:12px;"></div>
                        @error('gallery_images')
                            <span class="ep-field-error mt-2"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="ep-card">
                    <div class="ep-card__header">
                        <span class="ep-card__header-dot ep-card__header-dot--amber"></span>
                        <h2 class="ep-card__title"><i class="fab fa-youtube ep-card__title-icon"></i> YouTube Video</h2>
                    </div>
                    <div class="ep-card__body">
                        <div class="ep-field">
                            <input type="url" name="youtube_url" id="youtube_url" class="ep-input @error('youtube_url') ep-input--error @enderror" value="{{ old('youtube_url') }}" placeholder="https://www.youtube.com/watch?v=...">
                            <p class="ep-upload-zone__hint" style="margin-top:8px;">Paste a YouTube watch / share / shorts link — it will show as a video on the product page.</p>
                            @error('youtube_url')
                                <span class="ep-field-error mt-2"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

            </div>{{-- /ep-col--main --}}

            {{-- ══════════════════════════════════
                 RIGHT COLUMN — Pricing & Stock
            ══════════════════════════════════ --}}
            <div class="ep-col ep-col--sidebar">

                <div class="ep-card ep-card--sticky">
                    <div class="ep-card__header">
                        <span class="ep-card__header-dot ep-card__header-dot--rose"></span>
                        <h2 class="ep-card__title"><i class="fas fa-tag ep-card__title-icon"></i> Pricing & Stock</h2>
                    </div>
                    <div class="ep-card__body">

                        <div class="ep-field">
                            <label class="ep-label" for="price">Selling Price (৳) <span class="ep-required">*</span></label>
                            <div class="ep-input-prefix-wrap">
                                <span class="ep-input-prefix">৳</span>
                                <input type="number" name="price" id="price"
                                       class="ep-input ep-input--prefixed @error('price') ep-input--error @enderror"
                                       value="{{ old('price') }}"
                                       min="0" step="1" required placeholder="0">
                            </div>
                            @error('price')
                                <span class="ep-field-error"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</span>
                            @enderror
                        </div>

                        <div class="ep-field">
                            <label class="ep-label" for="original_price">Original Price (৳)</label>
                            <div class="ep-input-prefix-wrap">
                                <span class="ep-input-prefix">৳</span>
                                <input type="number" name="original_price" id="original_price"
                                       class="ep-input ep-input--prefixed @error('original_price') ep-input--error @enderror"
                                       value="{{ old('original_price') }}"
                                       min="0" step="1" placeholder="0">
                            </div>
                            @error('original_price')
                                <span class="ep-field-error"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</span>
                            @enderror
                        </div>

                        <div class="ep-field">
                            <label class="ep-label" for="discount">Discount (%)</label>
                            <div class="ep-input-prefix-wrap">
                                <input type="number" name="discount" id="discount"
                                       class="ep-input ep-input--suffixed @error('discount') ep-input--error @enderror"
                                       value="{{ old('discount') }}"
                                       min="0" max="100" step="1" placeholder="0">
                                <span class="ep-input-suffix">%</span>
                            </div>
                            @error('discount')
                                <span class="ep-field-error"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Live savings pill --}}
                        <div class="ep-savings-pill" id="savingsPill" style="display:none;">
                            <i class="fas fa-bolt"></i>
                            <span id="savingsText">Customer saves ৳0</span>
                        </div>

                        <div class="ep-divider"></div>

                        <div class="ep-field">
                            <label class="ep-label" for="stock">Stock Quantity</label>
                            <div class="ep-stock-wrap">
                                <button type="button" class="ep-stock-btn" id="stockMinus"><i class="fas fa-minus"></i></button>
                                <input type="number" name="stock" id="stock"
                                       class="ep-input ep-input--stock @error('stock') ep-input--error @enderror"
                                       value="{{ old('stock', 0) }}"
                                       min="0" step="1">
                                <button type="button" class="ep-stock-btn" id="stockPlus"><i class="fas fa-plus"></i></button>
                            </div>
                            @error('stock')
                                <span class="ep-field-error"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</span>
                            @enderror
                        </div>

                        <div class="ep-stock-badge" id="stockBadge">
                            <span id="stockStatus"></span>
                        </div>

                    </div>
                    <div class="ep-card__footer">
                        <button type="submit" class="ep-btn ep-btn--primary ep-btn--full">
                            <i class="fas fa-save"></i> Save Product
                        </button>
                    </div>
                </div>

            </div>{{-- /ep-col--sidebar --}}

        </div>{{-- /ep-grid --}}

    </form>
</div>{{-- /ep-wrapper --}}
@endsection


@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Sora:wght@600;700&display=swap" rel="stylesheet">

<style>
/* ═══════════════════════════════════════════════
   CSS CUSTOM PROPERTIES
═══════════════════════════════════════════════ */
:root {
    --ep-bg:           #f0f2f8;
    --ep-surface:      #ffffff;
    --ep-surface-2:    #f7f8fc;
    --ep-border:       #e4e7f0;
    --ep-border-focus: #4f6ef7;

    --ep-text-primary:   #1a1d2e;
    --ep-text-secondary: #5a617a;
    --ep-text-muted:     #9aa0b8;

    --ep-blue:    #4f6ef7;
    --ep-blue-lt: #eef1fe;
    --ep-green:   #22c87a;
    --ep-green-lt:#e8faf2;
    --ep-purple:  #b08c3d;
    --ep-purple-lt:#f0ebff;
    --ep-amber:   #c9a24b;
    --ep-amber-lt:#fff8e7;
    --ep-rose:    #f43f5e;
    --ep-rose-lt: #fff0f3;
    --ep-red:     #ef4444;

    --ep-radius:    12px;
    --ep-radius-sm: 8px;
    --ep-radius-xs: 6px;

    --ep-shadow-sm: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
    --ep-shadow-md: 0 4px 16px rgba(0,0,0,.08);
    --ep-shadow-lg: 0 8px 32px rgba(0,0,0,.12);

    --ep-transition: .18s ease;

    font-family: 'Plus Jakarta Sans', sans-serif;
}

/* ═══════════════════════════════════════════════
   WRAPPER / LAYOUT
═══════════════════════════════════════════════ */
.ep-wrapper {
    padding: 0 0 48px;
    min-height: 100vh;
    background: var(--ep-bg);
}

.ep-grid {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 24px;
    align-items: start;
}

.ep-variants-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-top: 20px;
}

@media (max-width: 1100px) {
    .ep-grid { grid-template-columns: 1fr; }
    .ep-col--sidebar { order: -1; }
    .ep-card--sticky { position: static !important; }
}
@media (max-width: 700px) {
    .ep-variants-grid { grid-template-columns: 1fr; }
    .ep-row-2 { grid-template-columns: 1fr; }
}

/* ═══════════════════════════════════════════════
   PAGE HEADER
═══════════════════════════════════════════════ */
.ep-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    padding: 28px 0 24px;
}
.ep-page-header__left {
    display: flex;
    align-items: center;
    gap: 16px;
}
.ep-page-header__icon {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    background: linear-gradient(135deg, var(--ep-green) 0%, #5fe0a4 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 22px;
    box-shadow: 0 4px 12px rgba(34,200,122,.35);
}
.ep-page-header__title {
    font-family: 'Sora', sans-serif;
    font-size: 22px;
    font-weight: 700;
    color: var(--ep-text-primary);
    margin: 0;
    line-height: 1.2;
}
.ep-page-header__subtitle {
    font-size: 13px;
    color: var(--ep-text-muted);
    margin: 3px 0 0;
}
.ep-page-header__actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

/* ═══════════════════════════════════════════════
   ALERT
═══════════════════════════════════════════════ */
.ep-alert {
    display: flex;
    gap: 14px;
    align-items: flex-start;
    background: #fff5f5;
    border: 1px solid #fecaca;
    border-radius: var(--ep-radius);
    padding: 16px 20px;
    margin-bottom: 20px;
    animation: ep-slide-down .25s ease;
}
.ep-alert--error .ep-alert__icon { color: var(--ep-red); font-size: 18px; line-height: 1.4; }
.ep-alert__list { margin: 6px 0 0; padding-left: 18px; font-size: 13.5px; color: #b91c1c; }

/* ═══════════════════════════════════════════════
   CARD
═══════════════════════════════════════════════ */
.ep-card {
    background: var(--ep-surface);
    border-radius: var(--ep-radius);
    border: 1px solid var(--ep-border);
    box-shadow: var(--ep-shadow-sm);
    overflow: hidden;
    animation: ep-fade-in .3s ease both;
}
.ep-card + .ep-card { margin-top: 20px; }

.ep-card__header {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 16px 22px;
    border-bottom: 1px solid var(--ep-border);
    background: var(--ep-surface-2);
}
.ep-card__header-dot {
    width: 10px; height: 10px;
    border-radius: 50%;
    flex-shrink: 0;
}
.ep-card__header-dot--blue   { background: var(--ep-blue);   box-shadow: 0 0 0 3px var(--ep-blue-lt); }
.ep-card__header-dot--green  { background: var(--ep-green);  box-shadow: 0 0 0 3px var(--ep-green-lt); }
.ep-card__header-dot--purple { background: var(--ep-purple); box-shadow: 0 0 0 3px var(--ep-purple-lt); }
.ep-card__header-dot--amber  { background: var(--ep-amber);  box-shadow: 0 0 0 3px var(--ep-amber-lt); }
.ep-card__header-dot--rose   { background: var(--ep-rose);   box-shadow: 0 0 0 3px var(--ep-rose-lt); }

.ep-card__title {
    font-family: 'Sora', sans-serif;
    font-size: 14px;
    font-weight: 600;
    color: var(--ep-text-primary);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 6px;
}
.ep-card__title-icon { color: var(--ep-text-muted); font-size: 13px; }
.ep-card__body { padding: 22px; }
.ep-card__footer {
    padding: 16px 22px;
    border-top: 1px solid var(--ep-border);
    background: var(--ep-surface-2);
}
.ep-card--sticky { position: sticky; top: 80px; }

/* Optional badge */
.ep-optional {
    font-size: 10.5px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: var(--ep-text-muted);
    background: var(--ep-surface-2);
    border: 1px solid var(--ep-border);
    border-radius: 20px;
    padding: 2px 8px;
    margin-left: 4px;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

/* ═══════════════════════════════════════════════
   FORM ELEMENTS
═══════════════════════════════════════════════ */
.ep-label {
    display: block;
    font-size: 12.5px;
    font-weight: 600;
    color: var(--ep-text-secondary);
    text-transform: uppercase;
    letter-spacing: .5px;
    margin-bottom: 7px;
}
.ep-required { color: var(--ep-rose); margin-left: 2px; }

.ep-field { margin-bottom: 20px; }
.ep-field:last-child { margin-bottom: 0; }

.ep-row-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.ep-input,
.ep-select,
.ep-textarea {
    width: 100%;
    padding: 10px 14px;
    font-size: 14px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--ep-text-primary);
    background: var(--ep-surface);
    border: 1.5px solid var(--ep-border);
    border-radius: var(--ep-radius-sm);
    transition: border-color var(--ep-transition), box-shadow var(--ep-transition);
    appearance: none;
    outline: none;
    box-sizing: border-box;
}
.ep-input:focus,
.ep-select:focus,
.ep-textarea:focus {
    border-color: var(--ep-border-focus);
    box-shadow: 0 0 0 3px rgba(79,110,247,.12);
}
.ep-input--error {
    border-color: var(--ep-red) !important;
    box-shadow: 0 0 0 3px rgba(239,68,68,.1);
}
.ep-input--sm { padding: 7px 10px; font-size: 13px; }
.ep-textarea { resize: vertical; min-height: 120px; }

.ep-select-wrap { position: relative; }
.ep-select { padding-right: 36px; cursor: pointer; }
.ep-select-arrow {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--ep-text-muted);
    font-size: 12px;
    pointer-events: none;
}

.ep-field-error {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    color: var(--ep-red);
    margin-top: 5px;
}
.ep-field-error.mt-2 { margin-top: 8px; }

/* Input prefix/suffix */
.ep-input-prefix-wrap { position: relative; display: flex; align-items: center; }
.ep-input-prefix,
.ep-input-suffix {
    position: absolute;
    font-size: 13.5px;
    font-weight: 600;
    color: var(--ep-text-muted);
    pointer-events: none;
    z-index: 1;
}
.ep-input-prefix { left: 13px; }
.ep-input-suffix { right: 13px; }
.ep-input--prefixed { padding-left: 30px; }
.ep-input--suffixed { padding-right: 36px; }

/* Stock control */
.ep-stock-wrap { display: flex; }
.ep-stock-btn {
    width: 38px;
    height: 42px;
    border: 1.5px solid var(--ep-border);
    background: var(--ep-surface-2);
    color: var(--ep-text-secondary);
    cursor: pointer;
    font-size: 13px;
    transition: background var(--ep-transition);
    flex-shrink: 0;
}
.ep-stock-btn:first-child { border-radius: var(--ep-radius-sm) 0 0 var(--ep-radius-sm); border-right: none; }
.ep-stock-btn:last-child  { border-radius: 0 var(--ep-radius-sm) var(--ep-radius-sm) 0; border-left: none; }
.ep-stock-btn:hover { background: var(--ep-blue-lt); color: var(--ep-blue); }
.ep-input--stock { border-radius: 0; text-align: center; }

/* Savings pill */
.ep-savings-pill {
    display: flex;
    align-items: center;
    gap: 8px;
    background: var(--ep-green-lt);
    color: #166534;
    border-radius: 8px;
    padding: 9px 14px;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 4px;
    animation: ep-fade-in .2s ease;
}
.ep-savings-pill i { color: var(--ep-green); }

/* Stock badge */
.ep-stock-badge { margin-top: 8px; font-size: 12.5px; font-weight: 600; }
.ep-stock-badge .in-stock  { color: var(--ep-green); }
.ep-stock-badge .low-stock { color: var(--ep-amber); }
.ep-stock-badge .no-stock  { color: var(--ep-red); }

.ep-divider { height: 1px; background: var(--ep-border); margin: 20px 0; }

/* ═══════════════════════════════════════════════
   VARIANT TABLES
═══════════════════════════════════════════════ */
.ep-variant-table-wrap { margin-top: 20px; }
.ep-variant-table-label {
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .6px;
    color: var(--ep-text-muted);
    margin-bottom: 10px;
}
.ep-table-scroll { overflow-x: auto; border-radius: var(--ep-radius-sm); border: 1px solid var(--ep-border); }
.ep-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.ep-table thead tr { background: var(--ep-surface-2); }
.ep-table th {
    padding: 10px 12px;
    text-align: left;
    font-weight: 600;
    color: var(--ep-text-secondary);
    font-size: 11.5px;
    text-transform: uppercase;
    letter-spacing: .4px;
    border-bottom: 1px solid var(--ep-border);
}
.ep-table td {
    padding: 9px 12px;
    border-bottom: 1px solid var(--ep-border);
    color: var(--ep-text-primary);
    vertical-align: middle;
}
.ep-table tr:last-child td { border-bottom: none; }
.ep-table tr:hover td { background: var(--ep-surface-2); }
.ep-hint { font-size: 11.5px; color: var(--ep-text-muted); margin-top: 8px; }

/* Color swatch */
.ep-swatch {
    display: inline-block;
    width: 18px; height: 18px;
    border-radius: 4px;
    border: 1px solid rgba(0,0,0,.1);
    vertical-align: middle;
    margin-right: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,.15);
}

/* ═══════════════════════════════════════════════
   IMAGE UPLOAD
═══════════════════════════════════════════════ */
.ep-image-section--create { display: block; }

.ep-upload-zone {
    position: relative;
    border: 2px dashed var(--ep-border);
    border-radius: var(--ep-radius);
    background: var(--ep-surface-2);
    transition: border-color var(--ep-transition), background var(--ep-transition);
    cursor: pointer;
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.ep-upload-zone:hover,
.ep-upload-zone.ep-drag-over {
    border-color: var(--ep-green);
    background: var(--ep-green-lt);
}
.ep-upload-zone:hover .ep-upload-zone__icon { color: var(--ep-green); transform: translateY(-4px); }
.ep-upload-input {
    position: absolute;
    inset: 0;
    opacity: 0;
    cursor: pointer;
    width: 100%;
    height: 100%;
    z-index: 2;
}
.ep-upload-zone__content { text-align: center; padding: 32px; pointer-events: none; }
.ep-upload-zone__icon {
    font-size: 40px;
    color: var(--ep-text-muted);
    margin-bottom: 12px;
    transition: color var(--ep-transition), transform var(--ep-transition);
}
.ep-upload-zone__text { font-size: 14px; font-weight: 600; color: var(--ep-text-secondary); margin: 0 0 6px; }
.ep-upload-zone__hint { font-size: 12.5px; color: var(--ep-text-muted); margin: 0; }

.ep-upload-preview { text-align: center; padding: 24px; pointer-events: none; width: 100%; }
.ep-upload-preview img { max-height: 180px; border-radius: 10px; box-shadow: var(--ep-shadow-md); }
.ep-upload-preview__name { font-size: 12px; color: var(--ep-text-muted); margin-top: 10px; }

/* ═══════════════════════════════════════════════
   BUTTONS
═══════════════════════════════════════════════ */
.ep-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 9px 18px;
    font-size: 13.5px;
    font-weight: 600;
    font-family: 'Plus Jakarta Sans', sans-serif;
    border-radius: var(--ep-radius-sm);
    border: 1.5px solid transparent;
    cursor: pointer;
    text-decoration: none;
    transition: all var(--ep-transition);
    white-space: nowrap;
    letter-spacing: .2px;
}
.ep-btn--primary {
    background: linear-gradient(135deg, var(--ep-green) 0%, #34d492 100%);
    color: #fff;
    border-color: transparent;
    box-shadow: 0 2px 8px rgba(34,200,122,.35);
}
.ep-btn--primary:hover { box-shadow: 0 4px 16px rgba(34,200,122,.45); transform: translateY(-1px); }
.ep-btn--primary:active { transform: translateY(0); }

.ep-btn--ghost {
    background: var(--ep-surface);
    color: var(--ep-text-secondary);
    border-color: var(--ep-border);
}
.ep-btn--ghost:hover { background: var(--ep-surface-2); color: var(--ep-text-primary); }

.ep-btn--full { width: 100%; justify-content: center; padding: 12px; font-size: 14px; }

/* ═══════════════════════════════════════════════
   SELECT2 OVERRIDES
═══════════════════════════════════════════════ */
.select2-container--default .select2-selection--multiple {
    border: 1.5px solid var(--ep-border) !important;
    border-radius: var(--ep-radius-sm) !important;
    min-height: 42px !important;
    padding: 4px 8px !important;
    font-family: 'Plus Jakarta Sans', sans-serif;
}
.select2-container--default.select2-container--focus .select2-selection--multiple {
    border-color: var(--ep-border-focus) !important;
    box-shadow: 0 0 0 3px rgba(79,110,247,.12) !important;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background: var(--ep-blue-lt);
    border: 1px solid #c7d2fe;
    border-radius: 6px;
    color: var(--ep-blue);
    font-size: 12.5px;
    font-weight: 600;
    padding: 2px 10px 2px 8px;
    margin-top: 5px;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: var(--ep-blue);
    margin-right: 6px;
}
.select2-dropdown { border: 1.5px solid var(--ep-border); border-radius: var(--ep-radius-sm); box-shadow: var(--ep-shadow-md); }
.select2-results__option { font-size: 13.5px; padding: 9px 14px; }
.select2-results__option--highlighted { background: var(--ep-blue) !important; }
.select2-search--dropdown .select2-search__field {
    border: 1.5px solid var(--ep-border);
    border-radius: var(--ep-radius-xs);
    padding: 6px 10px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 13px;
}

/* ═══════════════════════════════════════════════
   ANIMATIONS
═══════════════════════════════════════════════ */
@keyframes ep-fade-in {
    from { opacity: 0; transform: translateY(6px); }
    to   { opacity: 1; transform: translateY(0); }
}
@keyframes ep-slide-down {
    from { opacity: 0; transform: translateY(-8px); }
    to   { opacity: 1; transform: translateY(0); }
}

.ep-col--main .ep-card:nth-child(1) { animation-delay: .05s; }
.ep-col--main .ep-card:nth-child(2) { animation-delay: .1s; }
.ep-col--sidebar .ep-card           { animation-delay: .08s; }
</style>
@endpush


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function () {

    // ── Select2 init ──
    $('#sizes').select2({ placeholder: 'Select sizes', width: '100%' });
    $('#colors').select2({
        placeholder: 'Select colors',
        width: '100%',
        templateResult: function (opt) {
            if (!opt.id) return opt.text;
            var hex = $(opt.element).data('hex');
            return hex
                ? $('<span><span class="ep-swatch" style="background:' + hex + ';"></span>' + opt.text + '</span>')
                : opt.text;
        },
        templateSelection: function (opt) {
            if (!opt.id) return opt.text;
            var hex = $(opt.element).data('hex');
            return hex
                ? $('<span><span class="ep-swatch" style="background:' + hex + ';display:inline-block;width:12px;height:12px;border-radius:3px;margin-right:6px;vertical-align:middle;border:1px solid rgba(0,0,0,.1);"></span>' + opt.text + '</span>')
                : opt.text;
        }
    });

    // ── Dynamic size rows ──
    $('#sizes').on('change', function () {
        var selected = $(this).val();
        if (selected && selected.length > 0) {
            $('#sizeDetails').show();
            var rows = '';
            $.each(selected, function (i, sizeId) {
                var sizeName = $('#sizes option[value="' + sizeId + '"]').text().trim();
                rows += '<tr>';
                rows += '<td><strong>' + sizeName + '</strong><input type="hidden" name="size_ids[]" value="' + sizeId + '"></td>';
                rows += '<td><input type="number" name="size_stock_' + sizeId + '" class="ep-input ep-input--sm" value="0" min="0" placeholder="0"></td>';
                rows += '<td><input type="number" name="size_price_' + sizeId + '" class="ep-input ep-input--sm" value="0" step="1" placeholder="0"></td>';
                rows += '</tr>';
            });
            $('#sizeFields').html(rows);
        } else {
            $('#sizeDetails').hide();
            $('#sizeFields').html('');
        }
    }).trigger('change');

    // ── Dynamic color rows ──
    $('#colors').on('change', function () {
        var selected = $(this).val();
        if (selected && selected.length > 0) {
            $('#colorDetails').show();
            var rows = '';
            $.each(selected, function (i, colorId) {
                var colorName = $('#colors option[value="' + colorId + '"]').text().trim();
                var hex       = $('#colors option[value="' + colorId + '"]').data('hex') || '#ccc';
                rows += '<tr>';
                rows += '<td><span class="ep-swatch" style="background:' + hex + ';"></span><strong>' + colorName + '</strong><input type="hidden" name="color_ids[]" value="' + colorId + '"></td>';
                rows += '<td><input type="number" name="color_stock_' + colorId + '" class="ep-input ep-input--sm" value="0" min="0" placeholder="0"></td>';
                rows += '<td><input type="number" name="color_price_' + colorId + '" class="ep-input ep-input--sm" value="0" step="1" placeholder="0"></td>';
                rows += '</tr>';
            });
            $('#colorFields').html(rows);
        } else {
            $('#colorDetails').hide();
            $('#colorFields').html('');
        }
    }).trigger('change');

    // ── Drag-over state ──
    var zone = document.getElementById('uploadZone');
    if (zone) {
        zone.addEventListener('dragover',  function () { zone.classList.add('ep-drag-over'); });
        zone.addEventListener('dragleave', function () { zone.classList.remove('ep-drag-over'); });
        zone.addEventListener('drop',      function () { zone.classList.remove('ep-drag-over'); });
    }

    // ── Image preview ──
    $('#image').on('change', function () {
        var file = this.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#previewImage').attr('src', e.target.result);
                $('#previewName').text(file.name);
                $('#uploadContent').hide();
                $('#imagePreview').show();
            };
            reader.readAsDataURL(file);
        }
    });

    // ── Gallery images preview ──
    $('#gallery').on('change', function () {
        var $preview = $('#galleryPreview').empty();
        $.each(this.files, function (i, file) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('<img>').attr('src', e.target.result).css({
                    width: '90px', height: '90px', 'object-fit': 'cover',
                    'border-radius': '8px', border: '1px solid #e5e7eb'
                }).appendTo($preview);
            };
            reader.readAsDataURL(file);
        });
    });

    // ── Price / discount calculations ──
    function updateSavings() {
        var original = parseFloat($('#original_price').val()) || 0;
        var selling  = parseFloat($('#price').val())          || 0;
        if (original > 0 && selling > 0 && original > selling) {
            var discount = Math.round(((original - selling) / original) * 100);
            $('#discount').val(discount);
            var savings = original - selling;
            $('#savingsText').text('Customer saves ৳' + savings.toLocaleString() + ' (' + discount + '% off)');
            $('#savingsPill').show();
        } else {
            $('#savingsPill').hide();
        }
    }

    $('#original_price, #price').on('input', updateSavings);

    $('#discount').on('input', function () {
        var discount = parseFloat($(this).val()) || 0;
        var original = parseFloat($('#original_price').val()) || 0;
        if (original > 0 && discount > 0 && discount <= 100) {
            var selling = original - (original * discount / 100);
            $('#price').val(Math.round(selling));
            var savings = original - Math.round(selling);
            $('#savingsText').text('Customer saves ৳' + savings.toLocaleString() + ' (' + discount + '% off)');
            $('#savingsPill').show();
        } else {
            $('#savingsPill').hide();
        }
    });

    // ── Stock +/- buttons ──
    $('#stockPlus').on('click', function () {
        var v = parseInt($('#stock').val()) || 0;
        $('#stock').val(v + 1).trigger('input');
    });
    $('#stockMinus').on('click', function () {
        var v = parseInt($('#stock').val()) || 0;
        if (v > 0) $('#stock').val(v - 1).trigger('input');
    });

    // ── Stock status badge ──
    function updateStockBadge() {
        var qty = parseInt($('#stock').val()) || 0;
        var badge = $('#stockBadge');
        if (qty === 0)       badge.html('<span class="no-stock"><i class="fas fa-times-circle"></i> Out of stock</span>');
        else if (qty <= 5)   badge.html('<span class="low-stock"><i class="fas fa-exclamation-circle"></i> Low stock (' + qty + ' left)</span>');
        else                 badge.html('<span class="in-stock"><i class="fas fa-check-circle"></i> In stock (' + qty + ' units)</span>');
    }
    $('#stock').on('input', updateStockBadge);
    updateStockBadge();
});
</script>
@endpush