<?php

namespace Tests\Feature\Repository;

use App\Models\Post;
use App\Models\User;
use App\Repository\PostDBRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostDBRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function testSave()
    {

        $user = User::factory()->create();
        $post = Post::factory()->make();

        $repository = new PostDBRepository();
        $result = $repository->save($user, $post);

        $this->assertInstanceOf(Post::class, $result);

        $this->assertEquals(1, $result->id);
        $this->assertEquals($user->id, $result->author->id);
    }

    public function testGet()
    {
        $user = User::factory()->create();
        $post = Post::factory()->make();
        $user->posts()->save($post);

        $repository = new PostDBRepository();
        $result = $repository->get($post->id);

        $this->assertInstanceOf(Post::class, $result);
        $this->assertEquals($post->id, $result->id);
    }

}
