<?php


namespace Tests\Feature\Api;


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
}
