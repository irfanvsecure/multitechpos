<?php

namespace Tests\Feature;

use Tests\Support\LoadsSiteContent;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use LoadsSiteContent;

    protected function setUp(): void
    {
        parent::setUp();
        $this->loadSiteContent();
    }

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
