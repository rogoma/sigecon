<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Sin sesion iniciada, la pagina principal redirige al login.
     */
    public function test_home_redirects_guests_to_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('login'));
    }

    /**
     * La pagina de login se muestra correctamente.
     */
    public function test_login_page_is_displayed(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    /**
     * Las rutas protegidas redirigen al login cuando no hay sesion.
     */
    public function test_protected_routes_redirect_guests_to_login(): void
    {
        $response = $this->get('/users');

        $response->assertRedirect(route('login'));
    }
}
