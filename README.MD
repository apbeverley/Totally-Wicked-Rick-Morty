# Totally Wicked Rick and Morty Encyclopedia

A web-based encyclopedia built with Laravel, Inertia.js, and Vue.js, using data from the [Rick and Morty API](https://rickandmortyapi.com).

## 🚀 Features

- **Character Listing**: Browse all characters from the show.
- **Advanced Filtering**: Filter characters by Name, Status, Species, and Gender.
- **Pagination**: Efficiently navigate through the character database.
- **Detailed Profiles**: View in-depth information about each character, including origin and episode appearances.
- **Caching**: API responses are cached for 1 hour to improve performance and respect rate limits.
- **Responsive Design**: Clean and usable interface across different devices.

## 🛠️ Technical Stack

- **PHP 8.3+**
- **Laravel 11+**
- **Inertia.js**
- **Vue.js 3**
- **Tailwind CSS**

## ⚙️ Installation & Setup

Follow these steps to get the project running locally:

### 1. Prerequisites
Ensure you have the following installed:
- PHP 8.3 or higher
- Composer
- Node.js & NPM

### 2. Standard Setup (Recommended)
The project includes a convenience script for one-command setup. Run this in your terminal:

```bash
composer setup
```

This command will:
- Install PHP dependencies (`composer install`)
- Create your `.env` file from `.env.example`
- Generate the application key
- Install Node dependencies (`npm install`)
- Build the frontend assets (`npm run build`)

### 3. Manual Setup
If you prefer to run the steps individually:

```bash
# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Database setup (SQLite by default)
touch database/database.sqlite
php artisan migrate

# Build assets
npm run build
```

## 🖥️ Running the Application

To start the development server and asset compilation:

```bash
composer dev
```
*Note: This runs the Laravel server, Vite, and other necessary processes concurrently.*

Alternatively, run them separately:
```bash
php artisan serve
npm run dev
```

Visit `http://localhost:8000` in your browser.
