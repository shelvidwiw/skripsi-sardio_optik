<?php

namespace Tests\Unit\Iterasi3;

use App\Http\Controllers\Admin\AdminAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminAuthControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    #[Test]
    public function show_login_displays_login_page_when_user_is_not_authenticated(): void
    {
        Auth::shouldReceive('check')
            ->once()
            ->andReturn(false);

        $controller = new AdminAuthController();

        $response = $controller->showLogin();

        $this->assertEquals('admin.login', $response->name());
    }

    #[Test]
    public function show_login_redirects_admin_to_dashboard(): void
    {
        $admin = Mockery::mock();

        $admin->shouldReceive('isAdmin')
            ->once()
            ->andReturn(true);

        Auth::shouldReceive('check')
            ->once()
            ->andReturn(true);

        Auth::shouldReceive('user')
            ->once()
            ->andReturn($admin);

        $controller = new AdminAuthController();

        $response = $controller->showLogin();

        $this->assertTrue($response->isRedirect());

        $this->assertEquals(
            route('admin.dashboard'),
            $response->getTargetUrl()
        );
    }

    #[Test]
    public function show_login_displays_login_page_for_non_admin_user(): void
    {
        $user = Mockery::mock();

        $user->shouldReceive('isAdmin')
            ->once()
            ->andReturn(false);

        Auth::shouldReceive('check')
            ->once()
            ->andReturn(true);

        Auth::shouldReceive('user')
            ->once()
            ->andReturn($user);

        $controller = new AdminAuthController();

        $response = $controller->showLogin();

        $this->assertEquals('admin.login', $response->name());
    }

    #[Test]
    public function login_redirects_admin_to_dashboard_with_valid_credentials(): void
    {
        $request = Request::create(
            '/admin/login',
            'POST',
            [
                'email' => 'admin@example.com',
                'password' => 'password',
                'remember' => true,
            ]
        );

        $request->setLaravelSession(
            $this->app['session.store']
        );

        $admin = Mockery::mock();

        $admin->shouldReceive('isAdmin')
            ->once()
            ->andReturn(true);

        Auth::shouldReceive('attempt')
            ->once()
            ->with(
                [
                    'email' => 'admin@example.com',
                    'password' => 'password',
                ],
                true
            )
            ->andReturn(true);

        Auth::shouldReceive('user')
            ->once()
            ->andReturn($admin);

        $controller = new AdminAuthController();

        $response = $controller->login($request);

        $this->assertTrue($response->isRedirect());

        $this->assertStringContainsString(
            route('admin.dashboard'),
            $response->getTargetUrl()
        );
    }

    #[Test]
    public function login_logs_out_non_admin_user(): void
    {
        $request = Request::create(
            '/admin/login',
            'POST',
            [
                'email' => 'user@example.com',
                'password' => 'password',
            ]
        );

        $request->setLaravelSession(
            $this->app['session.store']
        );

        $user = Mockery::mock();

        $user->shouldReceive('isAdmin')
            ->once()
            ->andReturn(false);

        Auth::shouldReceive('attempt')
            ->once()
            ->andReturn(true);

        Auth::shouldReceive('user')
            ->once()
            ->andReturn($user);

        Auth::shouldReceive('logout')
            ->once();

        $controller = new AdminAuthController();

        $response = $controller->login($request);

        $this->assertTrue($response->isRedirect());

        $this->assertEquals(
            'Akun ini bukan akun admin.',
            $response->getSession()->get('errors')->first('email')
        );
    }

    #[Test]
    public function login_returns_error_when_credentials_are_invalid(): void
    {
        $request = Request::create(
            '/admin/login',
            'POST',
            [
                'email' => 'admin@example.com',
                'password' => 'wrong-password',
            ]
        );

        $request->setLaravelSession(
            $this->app['session.store']
        );

        Auth::shouldReceive('attempt')
            ->once()
            ->andReturn(false);

        $controller = new AdminAuthController();

        $response = $controller->login($request);

        $this->assertTrue($response->isRedirect());

        $this->assertEquals(
            'Email atau password salah.',
            $response->getSession()->get('errors')->first('email')
        );
    }

    #[Test]
    public function login_validates_required_email_and_password(): void
    {
        $request = Request::create(
            '/admin/login',
            'POST',
            []
        );

        $request->setLaravelSession(
            $this->app['session.store']
        );

        $controller = new AdminAuthController();

        $this->expectException(
            \Illuminate\Validation\ValidationException::class
        );

        $controller->login($request);
    }

    #[Test]
    public function logout_logs_out_user_and_redirects_to_admin_login(): void
    {
        $request = Request::create(
            '/admin/logout',
            'POST'
        );

        $request->setLaravelSession(
            $this->app['session.store']
        );

        Auth::shouldReceive('logout')
            ->once();

        $controller = new AdminAuthController();

        $response = $controller->logout($request);

        $this->assertTrue($response->isRedirect());

        $this->assertEquals(
            route('admin.login'),
            $response->getTargetUrl()
        );
    }
}