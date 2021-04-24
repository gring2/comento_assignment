<?php


namespace Tests\Feature\Api;

use App\Models\Post;
use App\Models\User;
use App\UserCase\Post\DestroyPostUseCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostApiTest extends TestCase
{
    use RefreshDatabase;

    public function testSavePost()
    {
        $usr = User::factory()->create();

        $resp = $this->actingAs($usr)->postJson(route('api.post.store'), ['title' => 'title', 'body' => 'body']);

        $resp->assertSuccessful();
        $resp->assertJson([
            'post_id' => 1
        ]);
    }

    public function testSavePostReturn400_When_Parameter_Is_Invalid()
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

    public function testGetPost404IfNotExist()
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
        $this->app->bind(DestroyPostUseCase::class, function () {
            $mock = \Mockery::mock(DestroyPostUseCase::class);
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
}
