# Warehouse-Management-System

A web-based application built with the Laravel framework to manage products, orders, and customers for an e-commerce platform. This project serves as a robust backend solution for handling core e-commerce operations.

## About The Project

This project is designed to provide a comprehensive management system for an Amazon-like e-commerce business. It includes features for managing inventory, tracking sales, handling customer data, and more.

### Built With

*   [Laravel](https://laravel.com/)
*   [MySQL](https://www.mysql.com/)
*   [Bootstrap](https://getbootstrap.com/)
*   [Blade](https://laravel.com/docs/blade)

## Getting Started

To get a local copy up and running, follow these simple steps.

### Prerequisites

Make sure you have the following software installed on your system:
*   PHP (version specified in `composer.json`)
*   Composer
*   Node.js & npm
*   A web server (like Apache or Nginx)
*   A database server (like MySQL or MariaDB)

### Installation

1.  **Clone the repository**
    ```sh
    git clone https://github.com/your_username/amazon-management-system.git
    cd amazon-management-system
    ```

2.  **Install PHP dependencies**
    ```sh
    composer install
    ```

3.  **Install JavaScript dependencies**
    ```sh
    npm install && npm run dev
    ```

4.  **Set up the environment file**
    Create a copy of the `.env.example` file and name it `.env`.
    ```sh
    copy .env.example .env
    ```
    Then, generate an application key:
    ```sh
    php artisan key:generate
    ```

5.  **Configure your `.env` file**
    Open the `.env` file and update the database connection details (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, etc.).

6.  **Run database migrations**
    This will create the necessary tables in your database.
    ```sh
    php artisan migrate
    ```

7.  **(Optional) Seed the database**
    If you want to populate the database with sample data, run the seeder.
    ```sh
    php artisan db:seed
    ```

8.  **Serve the application**
    You can now start the local development server.
    ```sh
    php artisan serve
    ```
    The application will be available at `http://127.0.0.1:8000`.
