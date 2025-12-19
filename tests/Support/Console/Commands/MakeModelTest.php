<?php

declare(strict_types=1);

namespace Tests\Support\Console\Commands;

use Tests\TestCase;
use Support\Models\Model;
use Illuminate\Support\Str;
use PHPUnit\Metadata\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Support\Console\Commands\MakeModel;

#[CoversClass(MakeModel::class)]
class MakeModelTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan(MakeModel::class, ['name' => 'Post'])
            ->assertSuccessful();        
    }

    #[Test]
    public function related_files_are_created(): void
    {
        $this->assertFileExists(app_path('Models/Posts/Post.php'), 'Post model should be created and singular');
        $this->assertFileExists(app_path('Models/Posts/Factory.php'), 'Factory should be created');
        $this->assertFileExists(app_path('Models/Posts/Builder.php'), 'Eloquent Builder should be created');
        $this->assertFileExists(app_path('Models/Posts/ServiceProvider.php'), 'ServiceProvider should be created');
        $this->assertFileExists(app_path('Models/Posts/PostTest.php'), 'Domain Model Test should be created');
        $this->assertFileExists(app_path('Models/Posts/Posts.php'), 'Collection should be created and plural');
    }

    #[Test]
    public function model_contains_the_correct_attributes(): void
    {
        $model = file_get_contents(app_path('Models/Posts/Post.php'));

        $this->assertStringContainsString('use Illuminate\Database\Eloquent\Concerns\HasUuids;', $model);
        $this->assertStringContainsString('use HasUuids;', $model, 'All models should use UUID primary key by default');

        $this->assertStringContainsString('use Illuminate\Database\Eloquent\Attributes\UseFactory;', $model);
        $this->assertStringContainsString('#[UseFactory(Factory::class)]', $model, 'All models shouldhave a factory');

        $this->assertStringContainsString('use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;', $model);
        $this->assertStringContainsString('#[UseEloquentBuilder(Builder::class)]', $model, 'All models should have an eloquent builder');

        $this->assertStringContainsString('use Illuminate\Database\Eloquent\Attributes\CollectedBy;', $model);
        $this->assertStringContainsString('#[CollectedBy(Posts::class)]', $model, 'All models should have a collection');
    }

    #[Test]
    public function factory_defines_the_correct_model(): void
    {
        $factory = file_get_contents(app_path('Models/Posts/Factory.php'));

        $this->assertStringContainsString('protected $model = Post::class;', $factory);
    }

    #[Test]
    public function builder_extends_the_correct_class(): void
    {
        $builder = file_get_contents(app_path('Models/Posts/Builder.php'));

        $this->assertStringContainsString('use Illuminate\Database\Eloquent\Builder as EloquentBuilder;', $builder);
        $this->assertStringContainsString('extends EloquentBuilder', $builder);
        $this->assertStringContainsString('implements Filterable', $builder, 'Builder should implement Filterable interface');
    }

    #[Test]
    public function service_provider_registers_the_model_as_a_morph(): void
    {
        $serviceProvider = file_get_contents(app_path('Models/Posts/ServiceProvider.php'));

        $this->assertStringContainsString('use App\Models\Posts\Post;', $serviceProvider);
        $this->assertStringContainsString('Relation::enforceMorphMap([', $serviceProvider);
        $this->assertStringContainsString('\'post\' => Post::class,', $serviceProvider);
    }

    #[Test]
    public function it_creates_a_test_for_the_domain_model(): void
    {
        $test = file_get_contents(app_path('Models/Posts/PostTest.php'));

        $this->assertStringContainsString('#[CoversClass(Post::class)]', $test);
        $this->assertStringContainsString('class PostTest extends TestCase', $test);
        $this->assertStringContainsString('#[Test]', $test);
    }

    #[Test]
    public function collection_is_created(): void
    {
        $collection = file_get_contents(app_path('Models/Posts/Posts.php'));

        $this->assertStringContainsString('use Illuminate\Database\Eloquent\Collection;', $collection);
        $this->assertStringContainsString('@extends Collection<int, Post>', $collection);
        $this->assertStringContainsString('class Posts extends Collection', $collection);
    }

    #[Test]
    public function it_created_and_registers_semantic_events(): void
    {
        $model = file_get_contents(app_path('Models/Posts/Post.php'));

        $this->assertStringContainsString('protected $dispatchesEvents = [', $model);

        foreach (new Model()->getObservableEvents() as $event) {
            $class = ucfirst($event);
            $this->assertStringContainsString("'{$event}' => Events\\{$class}::class,", $model);
            $this->assertFileExists(app_path('Models/Posts/Events/'.$class.'.php'));
        }
    }
}