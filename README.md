KykysBlog - Artist Information Platform
About
KykysBlog is a web application built with Symfony that allows users to discover and share information about music artists. The platform integrates with the Deezer API to fetch artist details and enables community interaction through comments.

Features
Artist Posts: View detailed information about artists including:

Profile pictures
Number of albums
Fan count
Radio availability
Links to Deezer profiles
User Features:

User authentication system
Comment on posts
PDF export of artist information
Admin privileges for content management
Technical Stack:

PHP 8.2+
Symfony 7.2
Doctrine ORM
Bootstrap 5
Twig templating
DomPDF for PDF generation

Installation
# Clone the repository
git clone [repository-url]

# Install dependencies
composer install

# Configure environment
cp .env .env.local
# Edit database configuration in .env.local

# Create database and run migrations
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

Usage
Start the Symfony server:
symfony serve
Access the application at http://localhost:8000

Create an account to start interacting with the platform
