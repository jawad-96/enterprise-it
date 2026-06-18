<?php

namespace Tests\Feature;

use App\Models\Resource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CMSTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_update_and_delete_resource()
    {
        // 1. Create (store)
        $payload = [
            'category' => 'blog',
            'title' => 'Test Post Title',
            'body' => 'Test Post Body Content',
            'image_url' => 'https://example.com/image.jpg',
        ];

        $response = $this->postJson('/api/resources', $payload);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['id', 'title', 'slug', 'body', 'image_url', 'category', 'published_at']
            ]);

        $resourceId = $response->json('data.id');

        // 2. Update (PUT)
        $updatePayload = [
            'title' => 'Updated Post Title',
            'body' => 'Updated Post Body Content',
        ];

        $updateResponse = $this->putJson("/api/resources/{$resourceId}", $updatePayload);

        $updateResponse->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Resource updated successfully.',
                'data' => [
                    'id' => $resourceId,
                    'title' => 'Updated Post Title',
                    'slug' => 'updated-post-title',
                    'body' => 'Updated Post Body Content',
                ]
            ]);

        // 3. Delete (DELETE)
        $deleteResponse = $this->deleteJson("/api/resources/{$resourceId}");

        $deleteResponse->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Resource deleted successfully.'
            ]);

        // Verify resource is deleted
        $this->assertDatabaseMissing('resources', ['id' => $resourceId]);
    }
}
