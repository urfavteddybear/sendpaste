# SendPaste - Secure Text Sharing

SendPaste is a minimalist, secure pastebin application built with Laravel and Tailwind CSS. It features end-to-end encryption, automatic expiration, and a clean, user-friendly interface.

## 🔒 Security Features

- **End-to-End Encryption**: All content is encrypted in the database using Laravel's built-in encryption
- **Database Admin Protection**: Database administrators cannot read paste content
- **Automatic Expiration**: Pastes automatically expire based on user-defined timeframes
- **Password Protection**: Optional password protection for additional security
- **No Logging**: No access logs are kept to maintain privacy
- **Random URLs**: Each paste gets a unique 6-character random slug

## ✨ Features

- **Clean Minimalist Design**: Built with Tailwind CSS for a beautiful, responsive interface
- **Multiple Expiration Options**: 1 week, 1 month, 3 months, 6 months, 1 year, or never
- **Syntax Highlighting Support**: Pre-configured for multiple programming languages
- **View Counter**: Track how many times a paste has been accessed
- **Raw Text Access**: Direct access to plain text content via `/slug/raw`
- **Copy to Clipboard**: Easy copy functionality for both links and content
- **Responsive Design**: Works perfectly on desktop and mobile devices

## 🚀 Installation

1. Clone the repository:
```bash
git clone <repository-url> sendpaste
cd sendpaste
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install Node.js dependencies:
```bash
npm install
```

4. Copy environment file and configure:
```bash
cp .env.example .env
php artisan key:generate
```

5. Configure your database in `.env`:
```env
DB_CONNECTION=mariadb
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sendpaste
DB_USERNAME=root
DB_PASSWORD=
```

6. Run migrations:
```bash
php artisan migrate
```

7. Build assets and start the server:
```bash
npm run build
php artisan serve
```

## 🔧 Configuration

### Automatic Cleanup

The application includes automatic cleanup of expired pastes. To enable this:

1. Set up your server's cron to run Laravel's scheduler:
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

2. Or run cleanup manually:
```bash
# Dry run to see what would be deleted
php artisan pastes:cleanup --dry-run

# Actually delete expired pastes
php artisan pastes:cleanup
```

### Customization

- **Maximum paste size**: Edit the validation rules in `PasteController.php` (default: 500KB)
- **Slug length**: Modify the `generateUniqueSlug()` method in `Paste.php` (default: 6 characters)
- **Rate limiting**: Adjust limits in the controller methods

## 📡 API

SendPaste includes a simple REST API for programmatic access:

### Create a paste

```bash
POST /api/paste
Content-Type: application/json

{
    "content": "Your text here",
    "title": "Optional title",
    "language": "text",
    "expiration": "1week"
}
```

### Response

```json
{
    "slug": "abc123",
    "url": "https://yoursite.com/abc123",
    "expires_at": "2024-01-15T10:30:00.000000Z"
}
```

### Get raw content

```bash
GET /slug/raw
```

### Expiration Options

- `1week` - 1 week
- `1month` - 1 month  
- `3months` - 3 months
- `6months` - 6 months
- `1year` - 1 year
- `never` - Never expires

## 🛡️ Security Considerations

1. **HTTPS**: Always use HTTPS in production to protect data in transit
2. **App Key**: Ensure `APP_KEY` is properly set and kept secret
3. **Database Access**: Limit database access to essential personnel only
4. **Regular Updates**: Keep Laravel and dependencies updated
5. **Rate Limiting**: The application includes built-in rate limiting to prevent abuse

## 🗂️ File Structure

```
app/
├── Http/Controllers/
│   └── PasteController.php      # Main application logic
├── Models/
│   └── Paste.php                # Paste model with encryption
└── Console/Commands/
    └── CleanupExpiredPastes.php # Cleanup command

resources/views/
├── layouts/
│   └── app.blade.php            # Main layout
└── paste/
    ├── create.blade.php         # Paste creation form
    ├── show.blade.php           # Paste display
    └── password.blade.php       # Password protection

database/migrations/
└── create_pastes_table.php      # Database schema
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## 📝 License

This project is open-sourced software licensed under the MIT license.

## 🙏 Acknowledgments

- Built with [Laravel](https://laravel.com/)
- Styled with [Tailwind CSS](https://tailwindcss.com/)
- Inspired by privacy-focused pastebin services

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
