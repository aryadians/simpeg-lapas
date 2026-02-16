# Security Policy

## Supported Versions

Currently, the following versions of SIMPEG Lapas are supported with security updates:

| Version | Supported          |
| ------- | ------------------ |
| 1.x     | :white_check_mark: |
| < 1.0   | :x:                |

## Reporting a Vulnerability

We take the security of SIMPEG Lapas seriously. If you discover any security-related issues, please do not use the public issue tracker. Instead, please email the maintainers directly.

Email: [security@example.com] (Please replace with actual contact email)

We will acknowledge your report within 48 hours and provide a timeline for a fix if necessary. We ask you to follow responsible disclosure practices and give us time to address the issue before making it public.

### What to include in your report:

- A description of the vulnerability.
- Steps to reproduce the issue (PoC).
- Potential impact of the vulnerability.
- Any suggested fixes or mitigations.

## Security Best Practices for SIMPEG Lapas

When deploying this application, ensure you follow these security best practices:

1.  **Keep Laravel Updated:** Regularly run `composer update` to ensure you have the latest security patches for the framework and its dependencies.
2.  **Environment Configuration:** Never commit your `.env` file. Ensure `APP_DEBUG` is set to `false` in production.
3.  **App Key:** Ensure you have a unique `APP_KEY` generated (`php artisan key:generate`).
4.  **Secure Headers:** Use a web server configuration that includes security headers (HSTS, X-Content-Type-Options, etc.).
5.  **Database Security:** Use strong passwords for your database and restrict access to the database server.
