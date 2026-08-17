<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## # IdeaNest

IdeaNest is a Laravel-based idea-sharing forum where registered users can create, manage, discuss, and vote on ideas. The project uses Laravel, Livewire, Tailwind CSS/Vite, and SQLite.

## Features

* User registration and login
* Create, view, edit, and delete ideas
* Comment on ideas
* Edit and delete comments
* Upvote ideas
* Remove an existing vote
* Idea categories and filtering
* Idea status management
* Authorization so users can only modify their own content
* Responsive user interface
* Database migrations and seed data
* Automated feature and unit tests

---

## Requirements

Before running the project, ensure the following are installed:

* PHP 8.2 or newer
* Composer
* Node.js 20 or newer
* npm
* Git

Verify the installations with:

```bash
php --version
composer --version
node --version
npm --version
git --version
```

---

## Installation and Setup

### 1. Clone the Repository

```bash
git clone https://github.com/DawitZelleke/Idea-forum.git
```

Enter the Laravel project directory:

```bash
cd Idea-forum
```

If the repository contains an additional `ideanest` folder, enter it:

```bash
cd ideanest
```

The correct directory should contain:

```text
artisan
composer.json
package.json
app/
database/
resources/
routes/
```

---

### 2. Install PHP Dependencies

Run:

```bash
composer install
```

This creates the `vendor` directory.

The `vendor` directory is intentionally excluded from GitHub because it is generated automatically by Composer.

---

### 3. Install Front-End Dependencies

Run:

```bash
npm install
```

This creates the `node_modules` directory.

`node_modules` is also intentionally excluded from GitHub.

---

### 4. Create the Environment File

If `.env` does not already exist, create it from `.env.example`.

#### Windows PowerShell

```powershell
Copy-Item .env.example .env
```

#### macOS/Linux

```bash
cp .env.example .env
```

---

### 5. Generate the Laravel Application Key

Run:

```bash
php artisan key:generate
```

A successful result should display:

```text
Application key set successfully.
```

---

## Database Setup

IdeaNest uses SQLite.

### 6. Create the SQLite Database

#### Windows PowerShell

```powershell
New-Item -ItemType File -Path database\database.sqlite -Force
```

#### macOS/Linux

```bash
touch database/database.sqlite
```

The following file should now exist:

```text
database/database.sqlite
```

Ensure the `.env` file contains an SQLite configuration such as:

```env
DB_CONNECTION=sqlite
```

---

### 7. Run Database Migrations and Seeders

Run:

```bash
php artisan migrate --seed
```

This creates the required database tables and inserts the provided demonstration data.

If a completely fresh development database is required, use:

```bash
php artisan migrate:fresh --seed
```

> `migrate:fresh` deletes existing tables and data before recreating the database.

---

## Build the Front End

Run:

```bash
npm run build
```

This compiles the project's Vite/Tailwind assets.

For active front-end development, the development server can instead be started with:

```bash
npm run dev
```

---

## Clear Laravel Cache

Before starting the application, the Laravel cache can be cleared with:

```bash
php artisan optimize:clear
```

---

## Run Automated Tests

Run:

```bash
php artisan test
```

This executes the project's feature and unit tests, including tests for authentication, ideas, comments, and voting functionality.

---

## Run the Application

Start the Laravel development server:

```bash
php artisan serve
```

Laravel should display an address similar to:

```text
http://127.0.0.1:8000
```

Open the address in a web browser.

---

## Demo Account

The seeded database includes a demonstration account:

```text
Email: demo@ideanest.test
Password: password
```

A new user may also be created through the registration page.

---

## Quick Setup

After cloning the repository, the main commands required to run the application are:

```bash
composer install
npm install
php artisan key:generate
php artisan migrate --seed
npm run build
php artisan optimize:clear
php artisan test
php artisan serve
```

If the SQLite database does not yet exist, create `database/database.sqlite` before running the migrations.

---

## Important Git Notes

The following generated or machine-specific files/directories are intentionally not stored in GitHub:

```text
vendor/
node_modules/
.env
database/database.sqlite
```

They are recreated locally through the installation instructions above.

The important dependency files that are stored in GitHub include:

```text
composer.json
composer.lock
package.json
package-lock.json
```

These files allow Composer and npm to install the required packages.

---

## Troubleshooting

### `vendor/autoload.php` Not Found

Run:

```bash
composer install
```

### `No application encryption key has been specified`

Run:

```bash
php artisan key:generate
```

### SQLite Database Does Not Exist

Create:

```text
database/database.sqlite
```

Then run:

```bash
php artisan migrate --seed
```

### Front-End Styling Is Missing

Run:

```bash
npm install
npm run build
```

### Laravel Cache Issues

Run:

```bash
php artisan optimize:clear
```

### Check Available Routes

Run:

```bash
php artisan route:list
```

---

## Recommended Evaluation Setup

For a clean evaluation of the project:

```bash
git clone https://github.com/DawitZelleke/Idea-forum.git
cd Idea-forum
composer install
npm install
```

Create `.env` from `.env.example`, create `database/database.sqlite`, and then run:

```bash
php artisan key:generate
php artisan migrate --seed
npm run build
php artisan test
php artisan serve
```

Then open:

```text
http://127.0.0.1:8000
```

The application should now be ready for evaluation.
