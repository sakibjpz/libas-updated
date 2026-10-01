# Setup Guide

## Environment Configuration

### Required Environment Variables

Copy `.env.example` to `.env` and configure the following:

```env
APP_NAME=Laravel
APP_ENV=local
APP_DEBUG=false
APP_URL=http://localhost

# Database
DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=
```

### Optional: AI Services Configuration

To enable AI-powered chatbot features:

```env
# OpenAI Configuration
OPENAI_API_KEY=sk-your-openai-api-key
OPENAI_MODEL=gpt-3.5-turbo

# Google Gemini Configuration
GEMINI_API_KEY=your-gemini-api-key
```

**Note**: Without API keys, the chatbot will use pattern-based fallback responses which still work well for common queries.

### Optional: Steadfast Shipping Configuration

To enable Steadfast shipping integration:

```env
STEADFAST_API_KEY=your_steadfast_api_key
STEADFAST_SECRET_KEY=your_steadfast_secret_key
STEADFAST_BASE_URL=https://portal.packzy.com/api/v1
```

Get your API credentials from [Steadfast Portal](https://portal.packzy.com).

## Database Setup

### SQLite (Default)

```bash
touch database/database.sqlite
php artisan migrate
php artisan db:seed
```

### MySQL/PostgreSQL

Update `.env` with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

Then run migrations:

```bash
php artisan migrate
php artisan db:seed
```

## Asset Compilation

### Development

```bash
npm run dev
```

### Production

```bash
npm run build
```

## Running the Application

### Development Server

```bash
php artisan serve
```

Access at: `http://localhost:8000`

### Full Development Stack (Server + Queue + Logs + Vite)

```bash
composer run dev
```

## Chatbot Configuration

The chatbot uses a hybrid approach:

1. **Pattern Matching**: Predefined patterns for common queries (order tracking, product search, etc.)
2. **AI Fallback**: OpenAI/Gemini for complex queries (if API keys configured)
3. **Smart Fallback**: Keyword-based responses when AI is unavailable

### Managing Chatbot Intents

Chatbot intents and patterns are seeded from database seeders:

- `ChatbotIntentsSeeder` - Intent definitions
- `ChatbotPatternsSeeder` - Pattern matching rules
- `ProductInquiryPatternsSeeder` - Product-related patterns
- `OrderTrackingPatternsSeeder` - Order tracking patterns
- `ReturnPolicyPatternsSeeder` - Return policy patterns
- `ContactInfoPatternsSeeder` - Contact information patterns
- `FallbackPatternsSeeder` - Fallback responses

To modify chatbot behavior:
1. Edit the seeders in `database/seeders/`
2. Run `php artisan db:seed --class=YourSeeder`

## Admin Panel

Access admin panel at: `/admin/dashboard`

Default admin credentials are created by `UsersTableSeeder`.

## Steadfast Integration

### Testing Connection

Visit `/test-steadfast` to test your Steadfast API connection.

### Sending Orders to Steadfast

1. Go to Admin > Orders
2. Click "Send to Steadfast" on an order
3. Order will be validated and sent to Steadfast
4. Tracking code will be saved to the order

### Tracking Orders

Use the Steadfast section in admin panel to:
- Check balance
- Track orders by consignment/invoice/tracking code
- View returns and payments
- View police stations (delivery zones)

## Troubleshooting

### Chatbot Not Responding

1. Check if API keys are configured in `.env`
2. Check logs: `php artisan pail`
3. If API keys missing, chatbot uses pattern-based fallback

### Steadfast API Errors

1. Verify API credentials in `.env`
2. Check Steadfast API status
3. Check logs for detailed error messages

### Images Not Displaying

1. Ensure `public/products/` directory exists
2. Check file permissions
3. Verify image path in Product model

## Production Deployment

1. Set `APP_DEBUG=false` in `.env`
2. Set `APP_ENV=production` in `.env`
3. Run `php artisan config:cache`
4. Run `php artisan route:cache`
5. Run `npm run build`
6. Configure web server (Apache/Nginx) to point to `public/` directory
7. Set proper file permissions for `storage/` and `bootstrap/cache/`

## Security Considerations

- Never commit `.env` file
- Use strong APP_KEY (generated via `php artisan key:generate`)
- Keep API keys secure
- Enable HTTPS in production
- Use queue workers for background jobs in production
