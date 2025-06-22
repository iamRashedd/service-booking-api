# <a href="https://service-booking-w9y4.onrender.com" /> Service Booking API </a>

## Framework and packages Used 
    Laravel : 10.10
    PHP: 8.2
    Laravel-Sanctum: 3.3
    Sentry-Laravel: 4.15

## Tools
    Visual studio code 
    Docker
    Postman
    HeidiSQL
    Git

## Email Preview

![Model](https://raw.githubusercontent.com/iamRashedd/service-booking-api/refs/heads/main/public/email_sample.png)

## Features Implemented
- **Required Features:**
  - **Service Listing API**: Retrieve available services with filtering by category and price range. Caching implemented for improved performance.
  - **Customer Registration/Login**: User authentication using Laravel Sanctum, with protected API endpoints.
  - **Add to Cart API**: Authenticated users can add multiple services to their cart. Adding multiple service with same property will increase quantity in cart.
  - **Checkout API**: Converts cart to an order, saving a snapshot of selected services and total price inside order items. Checkout email has also been implemented.
  - **Order History API**: Returns a user's previous orders with details. 

- **Bonus Features**:
  - Email notifications sent after successful order placement using Queue.
  - Caching for service listing using Laravel's cache system.
  - Feature tests for the checkout flow.
  - Deployed to Render (URL provided below).

- **Extra Features**:
  - Used Form requests
  - Used service classes
  - Restful routes
  - Docker support for containerized development and deployment.
  - Sentry integration for error logging and debugging.
  - Custom admin routes for command and query tools to manage services and orders.
  - Checkout can be made with either cart_id or cart_items as only selected items from cart will be ordered.
  - Seed for sample services

## Assumptions
- Services have a fixed price; dynamic pricing is not implemented.
- Schedule time is stored as a datetime string; no complex scheduling logic (e.g., availability checks).
- Quantity in cart items defaults to 1 if not specified and will increase while adding duplicate service properties.
- Admin tools created as Render shell is only for paid service.
- Artisan route for running required command for render such as (optimize:clear, migrate:fresh --force, queue:work --once)

## Areas for Improvement (if given more time)
- Auth email verification
- Auth password reset
- Add schedule to discard services with passed schedule time.
- Order invoice as PDF.
- Caching other api for optimization.
- Render deployment has some issues which require some time (e.g. sentry).
- Implement pagination for large service and order lists.
- Add API rate limiting for security.
- Expand test coverage for edge cases .
- Add remove from cart api
- Add various api related to services and carts for customers (e.g removeItem, delivery address, coupons).
- Enhance admin tools for service and order updates.

# <a href="https://github.com/iamRashedd/service-booking-api/blob/main/Service_booking_schema.svg" target="_blank">Database Structure</a>

https://github.com/iamRashedd/service-booking-api/blob/main/Service_booking_schema.svg

https://github.com/iamRashedd/service-booking-api/blob/main/service_booking_api.sql

# Setup Instructions (Docker)

## Prerequisites
- Docker
- Docker Compose
- Git

## Installation
1. **Clone the Repository**:
    ```bash
    git clone https://github.com/iamrashedd/service-booking-api.git
    cd service-booking-api
    ```

2. **Environment Setup**:
   
   Copy `.env.example` to `.env`:
     ```bash
     cp .env.example .env
     ```
   Update `.env` with your database, mail, and Sentry settings (ensure database config same as `docker-compose.yml`).

3. **Build and Start Containers**:
   
   Run Docker Compose to build and start the application, database, and Redis services:
     ```bash
     docker-compose up -d --build
     ```

4. **Generate Application Key**:
   
   Run the Artisan command to generate the app key:
     ```bash
     docker-compose exec app php artisan key:generate
     ```

5. **Database Setup**:
   
   Run migrations and seed the database:
     ```bash
     docker-compose exec app php artisan migrate --seed
     ```

6. **Access the Application**:
   
   The application is available at `http://localhost`.
   
   API endpoints can be tested using the provided Postman collection.

7. **Config email**:
   
   Update `.env` with own email config:
    ```bash
    MAIL_MAILER=smtp
    MAIL_HOST=smtp.mailtrap.io
    MAIL_PORT=2525
    MAIL_USERNAME=your_username
    MAIL_PASSWORD=your_password
    MAIL_ENCRYPTION=tls
    MAIL_FROM_ADDRESS=from@example.com
    MAIL_FROM_NAME="${APP_NAME}"
    ```
    Clear config cache:
    ```bash
    docker-compose exec app php artisan config:clear
    ```
8. **Run Queue**

    Update `.env` for queue connection
    ```bash
    QUEUE_CONNECTION=database
    ```
    Run queue worker
    ```bash
    docker-compose exec app php artisan queue:work
    ```

8. **<a href="https://github.com/getsentry/sentry-laravel">Sentry</a> Setup** (Optional):
  
      Configure the Sentry DSN with this command:
      ```bash
      php artisan sentry:publish --dsn=___PUBLIC_DSN___
      ```
      It creates the config file (config/sentry.php) and adds the DSN to your .env file.
      ```env
      SENTRY_LARAVEL_DSN=___PUBLIC_DSN___
      ```
    
     Test Sentry by triggering builtin command.
     ```bash
     docker-compose exec app php artisan sentry:test
     ```

### Testing
Run the test suite (includes checkout flow tests) inside the app container:
```bash
docker-compose exec app php artisan test
```

## .env.example
```env
APP_NAME="ServiceBookingAPI"
APP_ENV=local
APP_KEY={app_key}
APP_DEBUG=true
APP_URL={app_url}

#same as dockerfile or edit in docerfile then build again
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=service_booking
DB_USERNAME=laravel
DB_PASSWORD=root

SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=665
MAIL_USERNAME={username}
MAIL_PASSWORD={app_password}
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS={email}
MAIL_FROM_NAME={app_name}

QUEUE_CONNECTION=database

SENTRY_LARAVEL_DSN={sentry-dsn}
SENTRY_TRACES_SAMPLE_RATE=1.0
SENTRY_ENVIRONMENT=local
SENTRY_ENABLE_LOGS=true

ADMIN_KEY={custom_key_for_admin_tools}
```

## <a href="https://github.com/iamRashedd/service-booking-api/blob/main/service-booking.postman_collection.json" target="_blank">API Collection</a>

A Postman collection is included in the repository: <a href="https://github.com/iamRashedd/service-booking-api/blob/main/service-booking.postman_collection.json" target="_blank">`service-booking.postman_collection.json`</a>. 

#### **Key endpoints:**

- **Auth:**
  - **[POST] /api/auth/register**: Register a new user with `name`, `email`, `password`, and `password_confirmation`.
  - **[POST] /api/auth/login**: Authenticate with `email` and `password` to receive a token.
  - **[POST] /api/auth/logout**: Log out the authenticated user (requires Bearer token).
- **Services:**
  - **[GET] /api/services**: List services (supports `category_id`, `price_min`, and `price_max` query params).
- **Cart:**
  - **[POST] /api/cart**: Add a service to the cart (requires `service_id`, `schedule_time`, optional `quantity` and `note`, requires Bearer token).
  - **[GET] /api/cart**: Retrieve all cart items (requires Bearer token).
  - **[GET] /api/cart/{id}**: Retrieve a specific cart item by ID (requires Bearer token).
- **Order**
  - **[POST] /api/checkout**: Create an order from cart (accepts either `cart_id` or `cart_items` array, requires Bearer token).
  - **[GET] /api/orders**: Retrieve order history (requires Bearer token).
  - **[GET] /api/user**: Retrieve authenticated user details (requires Bearer token).
- **Admin Routes** (protected):
  - **[POST] /api/tools/artisan**: Execute artisan commands like `queue:work --once` with `ADMIN_KEY` as `key` and `command` query param.
  - **[POST] /api/tools/query**: Run SQL queries like `select * from users` with `ADMIN_KEY` as `key` and `sql` query param.


## Files/Folders

-Modified files/folders (default files exist as it was) 

- `app/models` - Contains all the Eloquent models
- `app/Http/Controllers/Api` - Contains all the api controllers
- `app/Http/Requests` - Contains all the form requests
- `app/jobs` - Contains the job for sending order email
- `app/mail` - Contains the mailer
- `app/services` - Contains the service classes
- `config` - Contains all the application configuration files
- `database/migrations` - Contains all the database migrations
- `database/seeds` - Contains the database seeder
- `routes` - Contains all the api routes defined in api.php file
- `resources/views/emails` - Contains email template
- `tests/Feature/` - Contains checkout test


# <a href="https://service-booking-w9y4.onrender.com">Live Production URL</a>

#### The application is deployed and accessible at: https://service-booking-w9y4.onrender.com

## License

The project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).