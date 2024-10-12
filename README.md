# Project README

## Installation Guide

### Using Docker

To set up the application using Docker, please follow these steps:

1. **Start the Docker Containers:**

   Execute the following commands to build and start the Docker containers:

   ```bash
   docker compose up -d
   docker compose exec -it app /bin/bash
   php artisan migrate --seed

2. **Setting Up the Application Locally:**

   If you prefer to run the application on your local machine, execute the following commands:

   ```bash
   composer install
   php artisan migrate --seed

3. **Testing the API:**
    Use the following base URL for local testing:
    http://localhost:8000/api

## Payment Integration Note
This project integrates with MyFatoorah for payment processing. Please note that the webhook functionality requires valid account credentials. An alternative integration with Stripe has been established, but the code for utilizing the Stripe iframe is currently commented out.

## Features Implemented
   - Admin Login Functionality: Admin login functionality has been added in UserSeeder.
   - Order Management: Capabilities for listing and creating orders, updating order statuses, and placing   orders.
   - Automated Test Cases: Test cases for order creation and listing have been implemented.

 4. **Payment Configurations:**
    It is aleardy exists in .env.example file  

 5. **Running Tests:**
      To run the automated tests, execute:
      ```bash
      php artisan test

 6. **Permission Issues:**
    If you encounter permission-related issues with the storage/logs directory while testing on Postman, you can resolve this by running:
    ```bash
    sudo chown www-data:www-data -R ./storage       