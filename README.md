# Background Runner

This project implements a custom system for running background tasks in Laravel applications. It allows you to execute PHP classes and methods with advanced features like retries, error handling, and execution delays.


## Features
- Execute PHP classes and methods in the background.
- Configure retries and execution delays.
- Detailed logs for success and error cases.
- Global helper for easy use within Laravel code.
- Artisan command to run jobs from the command line.
- Included unit tests to validate functionality.


## Installation

### Requirements
- PHP 8.x
- Laravel 8.x or higher
- Composer

### Steps
1. Clone the repository:
   ```bash
   git clone https://github.com/Alexpedrasa10/Background.git
   cd Background
    ```

2. Install dependencies:
    ```bash
    composer install
    ```

3. Set up the environment: Copy the .env.example file to `.env`:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. configure your database credentials:
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=nombre_de_base_datos
    DB_USERNAME=usuario
    DB_PASSWORD=contraseña
    ```

5. Run migrations:
    ```bash
    php artisan migrate
    ```

6. Ensure log permissions: Make sure storage/logs is writable:
    ```bash
    chmod -R 775 storage/logs
    ```

7. Optionally: Run unit tests:
    ```bash
    php artisan test
   ```

## Usage
Global Helper: `Background::runBackgroundJob`

The global helper allows you to run background jobs directly from your Laravel code.

### Syntax

```php
Background::runBackgroundJob($class, $method, $parameters, $delay = 1, $retries = 3);
```

- `$class`: Job class (e.g., App\Jobs\Maths).
- `$method`: Method to execute within the class.
- `$parameters`: Array of parameters to pass to the method.
- `$delay`: (Optional) Delay in seconds before executing the job.
- `$retries`: (Optional) Number of retry attempts if the job fails.

### Example:

```php
Background::runBackgroundJob('App\Jobs\Maths', 'sum', [1, 6, 9, 10], 5, 3);
```

This command runs the sum method of the App\Jobs\Maths class, a 5-second delay, and retries the job 3 times in case of failure.


## Artisan Command: `run:job`

The Artisan command allows you to run jobs from the command line.

### Syntax

```bash
php artisan run:job 'Class' method '[parameters]' retries delay
```

- `$class`: Job class (e.g., App\Jobs\Maths).
- `$method`: Method to execute within the class.
- `$parameters`: Array of parameters to pass to the method.
- `$delay`: (Optional) Delay in seconds before executing the job.
- `$retries`: (Optional) Number of retry attempts if the job fails.

### Example

```bash
php artisan run:job 'App\Jobs\Maths' sum '[1, 6, 9]' 4 1
```

This runs the sum method of the App\Jobs\Maths class with 4 retries and a 1-second delay.


## Security

Classes and methods are validated to prevent malicious code execution.
Ensure that only approved classes are registered in the config/background_jobs.php file:

```php
return [
    'allowed_classes' => [
        App\Jobs\Maths::class,
        App\Jobs\EmailNotification::class,
    ],
];
```


## Unit Testing

Unit tests are included to validate the system's behavior.
### Run Tests

```bash
php artisan test
```

### Coverage

- Job execution with parameters.
- Error handling.
- Retries and delays.


Powered by Alex Pedrasa 
