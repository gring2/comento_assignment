<?php


namespace Tests\Feature\Api;

use App\Models\Post;
use App\Models\User;
use App\UserCase\Post\DestroyUseCase;
use App\UserCase\Post\UpdateUseCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostApiTest extends TestCase
{
    use RefreshDatabase;

    public function testSave()
    {
        $usr = User::factory()->create();

        $resp = $this->actingAs($usr)->postJson(route('api.post.store'), ['title' => 'title', 'body' => 'body']);

        $resp->assertSuccessful();
        $resp->assertJson([
            'post_id' => 1
        ]);
    }

    public function testSave_Return400_When_Parameter_Is_Invalid()
    {
        $usr = User::factory()->create();

        $resp = $this->actingAs($usr)->postJson(route('api.post.store'), ['title' => 'title',]);

        $resp->assertStatus(422);
    }

    public function testGet()
    {
        $usr = User::factory()->create();
        $post = Post::factory()->make();
        $usr->posts()->save($post);

        $resp = $this->actingAs($usr)->getJson(route('api.post.show', $post->id));

        $resp->assertSuccessful();
        $resp->assertJson([
            'id' => $post->id,
            'title' => $post->title,
            'body' => $post->body,
            'created_at' => $post->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $post->updated_at->format('Y-m-d H:i:s'),
            'author' => [
                'name' => $post->author->name,
                'email' => $post->author->email,
            ]
        ]);
    }

    public function testGet_404_If_not_exist()
    {
        $usr = User::factory()->create();

        $resp = $this->actingAs($usr)->getJson(route('api.post.show', 2000));

        $resp->assertNotFound();
    }

    public function testDestory()
    {
        $usr = User::factory()->create();
        $post = Post::factory()->make();
        $usr->posts()->save($post);

        $resp = $this->actingAs($usr)->deleteJson(route('api.post.destroy', $post->id));

        $resp->assertSuccessful();
        $resp->assertJson([
            'post_id' => $post->id,
        ]);
    }

    public function testDestory_403_If_wrong_relation()
    {
        $this->app->bind(DestroyUseCase::class, function () {
            $mock = \Mockery::mock(DestroyUseCase::class);
            $mock->shouldNotReceive('invoke');

            return $mock;
        });

        $usr = User::factory()->create();
        $post = Post::factory()->make();
        $usr->posts()->save($post);
        $wrongUser = User::factory()->create();

        $resp = $this->actingAs($wrongUser)->deleteJson(route('api.post.destroy', $post->id));

        $resp->assertStatus(403);
    }

    public function testUpdate()
    {
        $usr = User::factory()->create();
        $post = Post::factory()->make();
        $usr->posts()->save($post);

        $resp = $this->actingAs($usr)->patchJson(route('api.post.update', $post->id), ['title' => 'title', 'body' => 'body']);

        $resp->assertSuccessful();
        $resp->assertJson([
            'post_id' => 1
        ]);

        $this->assertEquals('title', $usr->posts()->first()->title);
        $this->assertEquals('body', $usr->posts()->first()->body);
    }

    public function testUpdate_do_not_change_if_params_do_not_exist()
    {
        $usr = User::factory()->create();
        $post = Post::factory()->make();
        $usr->posts()->save($post);

        $resp = $this->actingAs($usr)->patchJson(route('api.post.update', $post->id));

        $resp->assertSuccessful();
        $resp->assertJson([
            'post_id' => 1
        ]);

        $this->assertEquals($post->title, $usr->posts()->first()->title);
        $this->assertEquals($post->body, $usr->posts()->first()->body);
    }

    public function testUpdate_403_If_wrong_relation()
    {
        $this->app->bind(UpdateUseCase::class, function () {
            $mock = \Mockery::mock(UpdateUseCase::class);
            $mock->shouldNotReceive('invoke');

            return $mock;
        });

        $usr = User::factory()->create();
        $post = Post::factory()->make();
        $usr->posts()->save($post);
        $wrongUser = User::factory()->create();

        $resp = $this->actingAs($wrongUser)->patchJson(route('api.post.update', $post->id));

        $resp->assertStatus(403);
    }
}
