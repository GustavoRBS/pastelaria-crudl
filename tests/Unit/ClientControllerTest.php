<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\ClientController;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_create_client_successfully()
    {
        $clientData = [
            'email' => 'client@example.com',
            'name' => 'Test Client',
            'phone' => '123456789',
            'birth_date' => '1999-02-22',
            'address' => '123 Street',
            'neighborhood' => 'Test Neighborhood',
            'postal_code' => '12345'
        ];

        $response = $this->post('/api/client/create', $clientData);

        $response->assertStatus(201);

        $response->assertJsonFragment([
            'email' => 'client@example.com',
            'name' => 'Test Client'
        ]);
    }

    public function test_create_client_validation_error()
    {
        $response = $this->post('/api/client/create', [
            'email' => 'invalid-email',
            'name' => '',
            'phone' => '',
        ]);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'message',
            'data' => ['email', 'name', 'phone']
        ]);
    }

    public function test_create_client_email_already_exists()
    {
        $existingClient = Client::factory()->create([
            'email' => 'existing@example.com',
            'name' => 'Existing Client',
        ]);

        $clientData = [
            'email' => 'existing@example.com',
            'name' => 'New Client',
            'phone' => '987654321',
            'birth_date' => '1990-01-01',
            'address' => '456 Avenue',
            'neighborhood' => 'New Neighborhood',
            'postal_code' => '67890'
        ];

        $response = $this->post('/api/client/create', $clientData);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'message',
            'data' => ['email']
        ]);

        $this->assertContains('The email has already been taken.', $response->json('data.email'));
    }

    public function test_create_client_missing_fields()
    {
        $response = $this->post('/api/client/create', [
            'email' => 'client@example.com',
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'data' => ['name', 'phone', 'birth_date', 'address', 'neighborhood', 'postal_code']
        ]);
    }

    public function test_get_client_successfully()
    {
        $client = Client::factory()->create([
            'email' => 'client@example.com',
            'name' => 'Test Client',
        ]);

        $response = $this->get("/api/client/detail/{$client->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'email' => 'client@example.com',
            'name' => 'Test Client'
        ]);
    }

    public function test_get_client_not_found()
    {
        $response = $this->get("/api/client/detail/9999");

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'message' => 'Client not found.'
        ]);
    }

    public function test_update_client_successfully()
    {
        $client = Client::factory()->create([
            'email' => 'old_email@example.com',
            'name' => 'Old Name',
        ]);

        $response = $this->put('/api/client/detail/' . $client->id, [
            'email' => 'new_email@example.com',
            'name' => 'New Name',
            'phone' => '987654321',
            'birth_date' => '1999-02-22',
            'address' => 'Street',
            'neighborhood' => 'New Neighborhood',
            'postal_code' => '54321'
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'email' => 'new_email@example.com',
            'name' => 'New Name'
        ]);
    }

    public function test_update_client_validation_error()
    {
        $client = Client::factory()->create();

        $response = $this->put('/api/client/detail/' . $client->id, [
            'email' => 'invalid-email',
            'name' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'data' => ['email', 'name']
        ]);
    }

    public function test_delete_client_successfully()
    {
        $client = Client::factory()->create();

        $controller = new ClientController();
        $response = $controller->deleteClient($client->id);

        $this->assertNotNull(Client::withTrashed()->find($client->id)->deleted_at);

        $this->assertEquals(202, $response->getStatusCode());
    }
}
