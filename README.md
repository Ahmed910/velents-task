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
This project integrates with MyFatoorah for payment processing. Please note that the webhook functionality requires valid account credentials. An alternative integration with Stripe has been established, but the code for utilizing the Stripe is currently commented out.

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

7. **Stripe CLI Configuration For Listening Webhook Locally:**
    first plz uncomment stripe integration in place order function in order service, then follow this instructions to set up stripe cli using apt pkg manager
   - Add the GPG key for the Stripe CLI
   ```bash
   curl -s https://packages.stripe.dev/api/security/keypair/stripe-cli-gpg/public | gpg --dearmor | sudo tee /usr/share/keyrings/stripe.gpg

- Add the Stripe CLI repository to your sources list
   ```bash
   echo "deb [signed-by=/usr/share/keyrings/stripe.gpg] https://packages.stripe.dev/stripe-cli-debian-local stable main" | sudo tee -a /etc/apt/sources.list.d/stripe.list

- Update the package list
   ```bash
   sudo apt update

- Install the Stripe CLI
   ```bash
   sudo apt install stripe

- Log in to Stripe using your API key
   ```bash
   stripe login --api-key whsec_c3be9f9300d19e83e85d66ebe71faae65834273bbe5de6ac6b6f2495fb214024        