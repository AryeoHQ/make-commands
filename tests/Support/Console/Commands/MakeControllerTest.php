<?php

declare(strict_types=1);

namespace Tests\Support\Console\Commands;

use Tests\TestCase;
use PHPUnit\Metadata\CoversClass;
use Support\Console\Enums\Endpoints;
use PHPUnit\Framework\Attributes\Test;
use Support\Console\Enums\EndpointType;
use Support\Console\Enums\ActionMethods;
use Support\Console\Commands\MakeController;

#[CoversClass(MakeController::class)]
class MakeControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        if (! is_dir(app_path('Http/Api/V1'))) {
            mkdir(app_path('Http/Api/V1'), 0755, true);
        }

        if (! is_dir(app_path('Models/Posts'))) {
            mkdir(app_path('Models/Posts'), 0755, true);
        }

        if (! is_dir(app_path('Models/Users'))) {
            mkdir(app_path('Models/Users'), 0755, true);
        }
    }

    #[Test]
    public function it_creates_a_controller_and_related_files(): void
    {
        $this->artisan(MakeController::class)
            ->expectsChoice('What is the API version?', 'V1', ['V1', 'Create new API version'])
            ->expectsSearch('What is the name of the domain model?', 'Post', '', ['Posts', 'Users'])
            ->expectsChoice('What type of endpoint would you like to create?', EndpointType::Rest->value, array_column(EndpointType::cases(), 'value'))
            ->expectsChoice('What endpoints would you like to create?', [Endpoints::Index->value, Endpoints::Show->value], array_column(Endpoints::cases(), 'value'))
            ->assertSuccessful();

        $this->assertFileExists(app_path('Http/Api/V1/Posts/Index/Controller.php'));
        $this->assertFileExists(app_path('Http/Api/V1/Posts/Index/Request.php'));
        $this->assertFileExists(app_path('Http/Api/V1/Posts/Index/ControllerTest.php'));

        $this->assertFileExists(app_path('Http/Api/V1/Posts/Show/Controller.php'));
        $this->assertFileExists(app_path('Http/Api/V1/Posts/Show/Request.php'));
        $this->assertFileExists(app_path('Http/Api/V1/Posts/Show/ControllerTest.php'));
    }

    #[Test]
    public function it_creates_a_controller_and_registers_the_route(): void
    {
        $this->artisan(MakeController::class)
            ->expectsChoice('What is the API version?', 'V1', ['V1', 'Create new API version'])
            ->expectsSearch('What is the name of the domain model?', 'Post', '', ['Posts', 'Users'])
            ->expectsChoice('What type of endpoint would you like to create?', EndpointType::Rest->value, array_column(EndpointType::cases(), 'value'))
            ->expectsChoice('What endpoints would you like to create?', [Endpoints::Show->value], array_column(Endpoints::cases(), 'value'))
            ->assertSuccessful();

        $controller = file_get_contents(app_path('Http/Api/V1/Posts/Show/Controller.php'));

        $this->assertStringContainsString('use Support\Routing\Attributes\Route;', $controller, 'Controller should use the Route attribute');
        $this->assertStringContainsString('use Support\Routing\Enums\Method;', $controller, 'Controller should use the Method enum');
        $this->assertStringContainsString('final class Controller', $controller, 'Controller should be final');
        $this->assertStringContainsString("name: 'api.V1.posts.show',", $controller, 'Controller should have the correct route name');
        $this->assertStringContainsString("uri: 'api/v1/posts/{post}',", $controller, 'Controller should have the correct route URI');
        $this->assertStringContainsString("methods: Method::Get,", $controller, 'Controller should have the correct route method');
        $this->assertStringContainsString('public function __invoke(Request $request, Post $post)', $controller, 'Controller should have route model binding');
    }

    #[Test]
    public function it_creates_an_action_controller_and_registers_the_route(): void
    {
        $this->artisan(MakeController::class)
            ->expectsChoice('What is the API version?', 'V1', ['V1', 'Create new API version'])
            ->expectsSearch('What is the name of the domain model?', 'Post', '', ['Posts', 'Users'])
            ->expectsChoice('What type of endpoint would you like to create?', EndpointType::Action->value, array_column(EndpointType::cases(), 'value'))
            ->expectsQuestion('What is the name of the action? (ie: PayInvoice, Download, etc.)', 'Publish')
            ->assertSuccessful();

        $controller = file_get_contents(app_path('Http/Api/V1/Posts/Actions/Publish/Controller.php'));

        $this->assertStringContainsString('use Support\Routing\Attributes\Route;', $controller, 'Controller should use the Route attribute');
        $this->assertStringContainsString('use Support\Routing\Enums\Method;', $controller, 'Controller should use the Method enum');
        $this->assertStringContainsString('final class Controller', $controller, 'Controller should be final');
        $this->assertStringContainsString("name: 'api.V1.posts.actions.publish',", $controller, 'Controller should have the correct route name');
        $this->assertStringContainsString("uri: 'api/v1/posts/{post}/actions/publish',", $controller, 'Controller should have the correct route URI');
        $this->assertStringContainsString("methods: Method::Post,", $controller, 'Controller should have the correct route method');
        $this->assertStringContainsString('public function __invoke(Request $request, Post $post)', $controller, 'Controller should have route model binding');
    }

    #[Test]
    public function it_creates_a_request_and_related_files(): void
    {
        $this->artisan(MakeController::class)
            ->expectsChoice('What is the API version?', 'V1', ['V1', 'Create new API version'])
            ->expectsSearch('What is the name of the domain model?', 'Post', '', ['Posts', 'Users'])
            ->expectsChoice('What type of endpoint would you like to create?', EndpointType::Rest->value, array_column(EndpointType::cases(), 'value'))
            ->expectsChoice('What endpoints would you like to create?', [Endpoints::Show->value], array_column(Endpoints::cases(), 'value'))
            ->assertSuccessful();

        $request = file_get_contents(app_path('Http/Api/V1/Posts/Show/Request.php'));

        $this->assertStringContainsString('use Illuminate\Foundation\Http\FormRequest;', $request, 'Request should use the FormRequest class');
        $this->assertStringContainsString('final class Request extends FormRequest', $request, 'Request should be final');
    }

    #[Test]
    public function it_creates_a_test_for_the_controller(): void
    {
        $this->artisan(MakeController::class)
            ->expectsChoice('What is the API version?', 'V1', ['V1', 'Create new API version'])
            ->expectsSearch('What is the name of the domain model?', 'Post', '', ['Posts', 'Users'])
            ->expectsChoice('What type of endpoint would you like to create?', EndpointType::Rest->value, array_column(EndpointType::cases(), 'value'))
            ->expectsChoice('What endpoints would you like to create?', [Endpoints::Show->value], array_column(Endpoints::cases(), 'value'))
            ->assertSuccessful();

        $test = file_get_contents(app_path('Http/Api/V1/Posts/Show/ControllerTest.php'));

        $this->assertStringContainsString('class ControllerTest extends TestCase', $test, 'Test should be a subclass of TestCase');
        $this->assertStringContainsString('#[CoversClass(Controller::class)]', $test, 'Test should use the CoversClass attribute');
    }
}