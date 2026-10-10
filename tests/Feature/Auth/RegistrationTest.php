<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Livewire\Volt\Volt;

describe('when registration is enabled', function () {
    beforeEach(fn () => enableRegistration());

    test('registration screen can be rendered', function () {
        $response = $this->get('/register');

        $response
            ->assertOk()
            ->assertSeeVolt('pages.auth.register');
    });

    test('new users can register', function () {
        $component = Volt::test('pages.auth.register')
            ->set('name', 'Test User')
            ->set('username', 'testuser')
            ->set('email', 'test@example.com')
            ->set('password', 'password')
            ->set('password_confirmation', 'password');

        $component->call('register');

        $component->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticated();
    });

    test('the login page links to registration', function () {
        $this->get(route('login'))->assertSee(route('register'));
    });
});

describe('when registration is disabled', function () {
    test('registration is disabled by default', function () {
        expect(config('auth.registration_enabled'))->toBeFalse();
    });

    test('registration screen is not available', function () {
        $this->get('/register')->assertNotFound();
    });

    test('the login page does not link to registration', function () {
        $this->get(route('login'))
            ->assertOk()
            ->assertDontSee('Register here');
    });

    test('the registration component does not create accounts', function () {
        Volt::test('pages.auth.register')
            ->set('name', 'Test User')
            ->set('username', 'testuser')
            ->set('email', 'test@example.com')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->call('register')
            ->assertNotFound();

        $this->assertGuest();
        expect(User::where('email', 'test@example.com')->exists())->toBeFalse();
    });
});
