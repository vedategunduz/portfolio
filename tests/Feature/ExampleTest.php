<?php

namespace Tests\Feature;

use App\Http\Middleware\LogPageHistory;
// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->withoutMiddleware(LogPageHistory::class)->get('/');

        $response->assertStatus(302);
        $this->assertMatchesRegularExpression(
            '#/(tr|en)$#',
            $response->headers->get('Location', '')
        );
    }
}
