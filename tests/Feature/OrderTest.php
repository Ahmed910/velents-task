<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\Pagination;
use App\Enums\UserRoles;
use App\Filters\Order\OrderFilters;
use App\Models\Order;
use App\Models\User;
use App\Services\Order\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_service_returns_paginated_orders()
    {
        // Arrange: Create some orders
        Order::factory()->count(30)->create();

        // Act: Call the index method with a basic filter
        $filters = new OrderFilters(request());
        $ordersPaginated = app(OrderService::class)->index($filters);

        // Assert: Check if pagination works
        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $ordersPaginated);
        $this->assertEquals(Pagination::PAGINATION_COUNT->value, $ordersPaginated->perPage());
        $this->assertEquals(1, $ordersPaginated->currentPage());
    }

    public function test_admin_can_get_paginated_orders()
    {
        Role::create(['name' => 'admin', 'guard_name' => 'api']);
        // Arrange: Create an admin user with the 'admin' role
        $admin = User::factory()->create([
            'name' => 'Admin User',  // Ensure 'name' field is set
        ]);
        $admin->assignRole(UserRoles::ADMIN->value);  // Assign the 'admin' role using Spatie

        // Authenticate the user as an admin
        $this->actingAs($admin, 'api');  // Assume you're using 'api' guard

        // Create some orders in the database
        Order::factory()->count(30)->create();

        // Act: Simulate a GET request to the /orders endpoint
        $response = $this->getJson('/api/orders');

        // Assert: Check if the response status is 200 (OK)
        $response->assertStatus(200);

        // Assert: Ensure the response contains paginated data
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'product_name',
                    'quantity',
                    'price',
                    'status',
                    'created_at',
                    'created_at_for_humans',
                ]
            ],
            'first_page_url',
            'from',
            'last_page',
            'last_page_url',
            'links' => [
                '*' => [
                    'url',
                    'label',
                    'active'
                ]
            ],
            'next_page_url',
            'path',
            'per_page',
            'prev_page_url',
            'to',
            'total'
        ]);
    }

    public function test_order_controller_applies_filters_and_returns_transformed_data()
    {
        Role::create(['name' => 'admin', 'guard_name' => 'api']);
        // Arrange: Create an admin user with the 'admin' role
        $admin = User::factory()->create([
            'name' => 'Admin User',  // Ensure 'name' field is set
        ]);
        $admin->assignRole(UserRoles::ADMIN->value);  // Assign the 'admin' role using Spatie

        // Authenticate the user as an admin
        $this->actingAs($admin, 'api');  // Assume you're using 'api' guard
        // Arrange: Create orders with different statuses
        Order::factory()->create(['status' => OrderStatus::PENDING->value]);
        Order::factory()->create(['status' => OrderStatus::PAID->value]);

        // Act: Send a request to the /orders route with a filter
        $response = $this->getJson('/api/orders?status=paid');

        // Assert: Ensure only filtered data is returned
        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'status' => 'paid',
            ]);
    }

    public function test_order_controller_returns_empty_data_when_no_orders()
    {
        Role::create(['name' => 'admin', 'guard_name' => 'api']);
        // Arrange: Create an admin user with the 'admin' role
        $admin = User::factory()->create([
            'name' => 'Admin User',  // Ensure 'name' field is set
        ]);
        $admin->assignRole(UserRoles::ADMIN->value);  // Assign the 'admin' role using Spatie

        // Authenticate the user as an admin
        $this->actingAs($admin, 'api');  // Assume you're using 'api' guard

        // Act: Send a request to the /orders route with no orders in the database
        $response = $this->getJson('/api/orders');

        // Assert: Ensure response is empty but with valid structure
        $response->assertStatus(200)
            ->assertJsonCount(0, 'data')
            ->assertJsonStructure([
                'data',
                'first_page_url',
                'from',
                'last_page',
                'last_page_url',
                'links' => [
                    '*' => [
                        'url',
                        'label',
                        'active'
                    ]
                ],
                'next_page_url',
                'path',
                'per_page',
                'prev_page_url',
                'to',
                'total'
            ]);
    }

    public function test_admin_can_create_order()
    {
        // Create the 'admin' role for the 'api' guard
        Role::create(['name' => 'admin', 'guard_name' => 'api']);

        // Arrange: Create an admin user and assign the 'admin' role
        $admin = User::factory()->create([
            'name' => 'Admin User',
        ]);
        $admin->assignRole('admin');  // Assign the 'admin' role

        // Authenticate the user as an admin
        $this->actingAs($admin, 'api');

        // Act: Prepare data for a new order
        $orderData = [
            'product_name' => 'Test Product',
            'quantity' => 5,
            'price' => 99.99
        ];

        // Make a POST request to create a new order
        $response = $this->postJson('/api/orders', $orderData);

        // Assert: Check if the response status is 201 Created
        $response->assertStatus(201);

        // Assert: Ensure the response has the correct structure
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'id',
                'product_name',
                'quantity',
                'price',
                'status',
                'created_at',
                'created_at_for_humans',
            ]
        ]);

        // Assert: Ensure the order was created in the database
        $this->assertDatabaseHas('orders', [
            'product_name' => 'Test Product',
            'quantity' => 5,
            'price' => 99.99
        ]);
    }

    public function test_non_admin_cannot_create_order()
    {
        // Create the 'admin' role for the 'api' guard
        Role::create(['name' => 'client', 'guard_name' => 'api']);
        // Arrange: Create a non-admin user
        $user = User::factory()->create([
            'name' => 'Non-Admin User',
        ]);
        $user->assignRole('client');  // Assign a non-admin role

        // Authenticate the user as a regular user
        $this->actingAs($user, 'api');

        // Act: Attempt to create a new order
        $orderData = [
            'product_name' => 'Test Product',
            'quantity' => 5,
            'price' => 99.99
        ];

        $response = $this->postJson('/api/orders', $orderData);

        // Assert: Non-admin users should be forbidden
        $response->assertStatus(403);
    }

    public function test_validation_fails_if_required_fields_are_missing()
    {
        // Create the 'admin' role for the 'api' guard
        Role::create(['name' => 'admin', 'guard_name' => 'api']);
        // Arrange: Create an admin user and assign the 'admin' role
        $admin = User::factory()->create([
            'name' => 'Admin User',
        ]);
        $admin->assignRole('admin');  // Assign the 'admin' role

        // Authenticate the user as an admin
        $this->actingAs($admin, 'api');

        // Act: Attempt to create a new order with invalid data
        $orderData = [
            'product_name' => '',  // Invalid: required field is empty
            'quantity' => 'abc',   // Invalid: not an integer
            'price' => 99.9999     // Invalid: more than 8 decimals
        ];

        $response = $this->postJson('/api/orders', $orderData);

        // Assert: The response should have validation errors
        $response->assertStatus(422);  // Unprocessable Entity

        $response->assertJsonValidationErrors([
            'product_name',
            'quantity'
        ], "data.errors");

        // Optionally, you can check that the "message" and "status" fields exist
        $response->assertJsonFragment([
            'message' => 'The product name field is required.',
            'status' => false,
        ]);
    }
}
