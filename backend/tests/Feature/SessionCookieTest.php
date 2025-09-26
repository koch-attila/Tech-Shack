<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SessionCookieTest extends TestCase
{
    use RefreshDatabase;

    public function test_session_cookie_domain_is_correct()
    {
        $response = $this->get('/sanctum/csrf-cookie');
        $cookie = $response->headers->getCookies();

        $found = false;
        foreach ($cookie as $c) {
            if ($c->getName() === config('session.cookie') && $c->getDomain() === config('session.domain')) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found, 'Session cookie domain should match config value');
    }
}