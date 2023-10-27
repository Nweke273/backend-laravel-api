<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Services\DataSourceService;

class DataSourceControllerTest extends TestCase
{
    public function testArticlesEndpoint()
    {

        // Create a user and add some sources, categories, and authors for testing
        $user = User::factory()->create();
        // Add sources, categories, and authors as needed
        $user->sources()->createMany(['source' => 'nyt']);
        $user->categories()->createMany(['category' => 'technology']);
        $user->authors()->createMany(['author' => 'John Doe']);
        // Mock the DataSourceService
        $dataSourceService = $this->mock(DataSourceService::class);

        // Define the expected call to fetchArticles
        $dataSourceService->shouldReceive('fetchArticles')->with(10, [
            'q' => 'news',
            'from' => null,
            'to' => null,
            'author' => ['John Doe', 'Jane Smith'],
            'category' => ['technology', 'science'],
        ], ['nyt', 'news-api'])->andReturn(['status' => 'success', 'message' => 'Request successful', 'articles' => []]);

        // Acting as the authenticated user
        $this->actingAs($user);

        // Making a GET request to the articles endpoint
        $response = $this->get('/api/v1/articles');

        // Assert the response
        $response->assertStatus(200)->assertJson([
            'status' => 'success',
            'message' => 'Request successful',
        ]);
    }
}
