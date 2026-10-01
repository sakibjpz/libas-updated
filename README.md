# E-Commerce Platform with AI Chatbot

A Laravel 12-based e-commerce platform with integrated AI-powered chatbot support for Bangla and English languages. Features include product management, shopping cart, order processing, Steadfast shipping integration, and intelligent customer support.

## Features

- **Product Management**: Categories, sizes, colors with price adjustments
- **Shopping Cart**: Session-based cart with size/color variants
- **Order Management**: Order tracking, status updates, Steadfast shipping integration
- **AI Chatbot**: OpenAI and Google Gemini integration with pattern-based fallback
- **Multi-language Support**: Bangla and English
- **Admin Panel**: Products, orders, banners, menus, contact messages management
- **Authentication**: User registration, login with role-based access (admin/customer)

## Requirements

- PHP >= 8.2
- Composer
- Node.js & NPM
- SQLite (default) or MySQL/PostgreSQL

## Installation

1. Clone the repository
```bash
git clone <repository-url>
cd libasbd-clone-cursor
```

2. Install dependencies
```bash
composer install
npm install
```

3. Configure environment
```bash
cp .env.example .env
php artisan key:generate
```

4. Set up database
```bash
touch database/database.sqlite
php artisan migrate
php artisan db:seed
```

5. Build assets
```bash
npm run dev
```

6. Start development server
```bash
php artisan serve
```

## Configuration

### AI Services (Optional)

To enable AI chatbot features, add your API keys to `.env`:

```env
OPENAI_API_KEY=your_openai_api_key
OPENAI_MODEL=gpt-3.5-turbo
GEMINI_API_KEY=your_gemini_api_key
```

Without API keys, the chatbot will use pattern-based fallback responses.

### Steadfast Shipping API

To enable Steadfast shipping integration:

```env
STEADFAST_API_KEY=your_api_key
STEADFAST_SECRET_KEY=your_secret_key
STEADFAST_BASE_URL=https://portal.packzy.com/api/v1
```

## Project Structure

- `app/Http/Controllers/` - Application controllers
- `app/Models/` - Eloquent models
- `app/Services/` - Business logic (Chatbot, Steadfast)
- `app/Services/Chatbot/` - Chatbot intent handlers
- `database/migrations/` - Database migrations
- `database/seeders/` - Database seeders (chatbot patterns)
- `resources/views/` - Blade templates
- `routes/web.php` - Web routes

## Development

Run all services (server, queue, logs, vite):
```bash
composer run dev
```

Run tests:
```bash
composer run test
```

## Admin Access

Default admin user is created via seeder. Access admin panel at `/admin/dashboard`.

## License

MIT License
