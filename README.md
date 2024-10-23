## News API

   A comprehensive News API built with Laravel that collects news from multiple sources and allows users to personalize their experience by managing preferences for categories, sources, and authors.


## Key Features

   User Authentication
   - Registration, Login, and Logout using Laravel Sanctum.
   
   News Aggregation
   - Fetch articles from various sources (e.g., NewsAPI, The Guardian, BBC News).
   Store aggregated articles in a local database for filtering and searching.
   
   Search and Filter
   - Search articles by keyword, date, category, and source.
   
   Personalized News Feed
   - Users receive a customized feed based on their preferences (categories, sources, authors).
   
   Optimized Storage
   - Efficient storage and indexing of articles to boost performance.

   Docker Integration
   - Simplified Docker-based environment setup for development and deployment.

   API Documentation
   - API documentation auto-generated with Swagger/OpenAPI.
   
   Testing
   - Comprehensive unit and feature tests to ensure code reliability.


## Setup Instructions

   1. Install Composer Dependencies
      composer install

   2. Configure .env File
      Update the necessary environment variables in the .env file (e.g., database credentials).

   3. Modify Docker Configuration
      Update the project path in docker-compose.yml for the app and nginx services.
      Ensure correct file permissions for the storage and bootstrap/cache directories.

   4. Build and Run Docker Containers
      docker-compose build app
      docker-compose up -d

   5. Run Database Migrations
         docker-compose exec app php artisan migrate
      To seed the database with initial data:
         docker-compose exec app php artisan db:seed

   6. Generate Swagger Documentation
      php artisan l5-swagger:generate

   7. Fetch Articles Manually
      php artisan articles:fetch

   8. Testing the Application
      php artisan test

   9. Scheduling News Fetching
      Laravel's scheduler will automatically fetch news at regular intervals.
         php artisan schedule:work
      For production, set up a cron job to handle this:
      * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1

   10. Access API Documentation
      API documentation will be accessible at:
         127.0.0.1:8082/api/documentation

      
## API Endpoints Overview

   1. User Registration

      POST /api/register
      Register a new user by providing necessary details (e.g., name, email, password).
   
   2. User Login

      POST /api/login
      Authenticate a user by providing valid credentials (email and password) to receive a Bearer token.
      
   3. User Logout

      POST /api/logout
      Log out the authenticated user and invalidate the Bearer token (requires authentication).
      
   4. Send Password Reset Email

      POST /api/password/email
      Send an email containing a password reset link, allowing the user to generate a token for password recovery.
      
   5. Password Reset

      POST /api/password/reset
      Reset the user’s password by providing the reset token (from the email) along with the new password.


## Additional Notes

   1. Data Aggregation
   Articles are periodically fetched from external news APIs using Laravel's scheduler and stored in the local database. All filtering and searching operations are performed on this locally stored data, ensuring efficient retrieval without querying live sources in real time.

   2. Caching
   Caching mechanisms are implemented to enhance performance, especially for frequently accessed routes. This reduces the load on the database and speeds up response times for common requests.

   3. Security
   Laravel Sanctum is used to handle API token-based authentication. Middleware ensures that sensitive endpoints are protected and only accessible to authenticated users with valid Bearer tokens.

   4. Performance Optimization
   Indexing is applied to the articles table, enabling faster search operations. Additionally, rate limiting is implemented on public endpoints to prevent abuse and maintain optimal system performance.


## Docker Development Workflow

1. Build Docker Containers
   Build the required containers for the application:
   -  docker-compose build

2. Start Docker Containers
   Launch the application in the background:
   - docker-compose up -d

3. Stop Docker Containers
   Stop and remove the running containers:
   - docker-compose down