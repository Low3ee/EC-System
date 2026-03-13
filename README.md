# Renters and Boarders Easy Collection System

A streamlined, web-based CRUD application designed for small-scale real estate managers to digitize tenant records and automate rent tracking.

## Project Overview
Managing rental properties manually via notebooks or spreadsheets often leads to lost records and payment confusion. This system provides a centralized dashboard to manage tenants, assign rooms, and log payments with a clear audit trail.

### Key Features
- **Tenant Management**: Full CRUD (Create, Read, Update, Delete) for tenant profiles.
- **Room Assignment**: Track room availability and occupant history.
- **Payment Logging**: Record partial or full rent payments.
- **Status Monitoring**: Visual indicators for 'Paid', 'Partial', and 'Overdue' statuses.
- **Payment History**: A dedicated ledger for every tenant.

## Tech Stack
- **Backend**: Laravel 12 (PHP 8.x)
- **Frontend**: Blade Templates & Tailwind CSS
- **Database**: MySQL
- **Tools**: Composer, NPM, Vite



## Getting Started

### Prerequisites
- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL/XAMPP

### Installation
1. **Clone the repository**
   ```bash
   git clone [https://github.com/Low3ee/EC-System](https://github.com/Low3ee/EC-System)
   cd EC-System

2. **Install Dependencies**
    ```bash 
    composer install
    npm install

3. **Environment Setup**
    ```bash
    cp .env.example .env
    php artisan key:generate

 ***Note: Update your .env file with your database credentials.***

4. **Run Migrations**
    ```bash 
    php artisan migrate

5. **Start Development Server**
    ```bash 
    php artisan serve