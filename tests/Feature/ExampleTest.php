<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_homepage_returns_success(): void
    {
        $response = $this->get("http://meditech.local/");
        
        $response->assertOk();
    }
}