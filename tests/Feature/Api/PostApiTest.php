<?php


namespace Tests\Feature\Api;


use App\Models\Post;
use App\Models\User;
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

    public function testSaveGet()
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
}
