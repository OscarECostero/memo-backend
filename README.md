# HeyMemoBackend

Welcome to HeyMemoBackend! This is the backend repository for the HeyMemo application.

## Getting Started

Follow these steps to get the HeyMemoBackend up and running on your local machine.

### Prerequisites

-   PHP
-   Composer
-   Docker (optional)

### Installation Steps

1. **Clone the repository**:
   git clone [URL_OF_YOUR_REPO]

2. **Go to folder**:

```bash
    cd memo-backend
```

3. **Install Dependencies using Composer:**

```bash
    composer install
```

4. **Set Database Credentials**:

    DB_CONNECTION=mysql

    DB_HOST=127.0.0.1

    DB_PORT=3306

    DB_DATABASE=your_database_name

    DB_USERNAME=your_database_username

    DB_PASSWORD=your_database_password

5. **Using Laravel's built-in server:**

```bash
    php artisan serve
```

6. **Using Docker:**

```bash
    docker-compose up
```

7. **Run Migrations:**

```bash
    php artisan migrate
```
8. **Run Seeders:**

```bash
    php artisan db:seed --class=MemoTestSeeder
```

## Visit [localhost:8000/graphiql](http://localhost:8000/graphiql) to explore the available queries and mutations.

