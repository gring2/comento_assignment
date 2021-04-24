<?php

namespace Tests\Feature\Repository;

use App\Models\Post;
use App\Models\User;
use App\Repository\PostDBRepository;
use Illuminate\Database\Eloquent\Model;
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

    public function testDestroy()
    {
        $user = User::factory()->create();
        $posts = Post::factory()->count(4)->make();
        $user->posts()->saveMany($posts);

        $repository = new PostDBRepository();
        $result = $repository->destroy($posts[0], $user);

        $this->assertEquals(true, $result);

        $this->assertEquals(3, Post::count());
    }

    public function testDestroy_Return_False_If_Wrong_relation_is_passed()
    {
        $user = User::factory()->create();
        $posts = Post::factory()->count(4)->make();
        $user->posts()->saveMany($posts);
        $wronngUser = User::factory()->create();

        $repository = new PostDBRepository();
        $result = $repository->destroy($posts[0], $wronngUser);

        $this->assertEquals(false, $result);

        $this->assertEquals(4, Post::count());
    }

    public function testUpdate_Return_true_If_success()
    {
        $user = User::factory()->create();
        $posts = Post::factory()->count(4)->make();
        $user->posts()->saveMany($posts);

        $repository = new PostDBRepository();

        $newPost = new Post(['title' => 'new title', 'body' => 'new body']);
        $beforeTitle = $posts[1]->title;
        $beforeBody = $posts[1]->body;

        $success = $repository->update($user, $posts[1]->id, $newPost);
        $this->assertTrue($success);
        $after = $user->posts()->find($posts[1]->id);

        $this->assertNotEquals($beforeTitle, $after->title);
        $this->assertNotEquals($beforeBody, $after->body);
    }

    public function testUpdate_Return_false_If_can_not_update()
    {
        $user = User::factory()->create();
        $posts = Post::factory()->count(4)->make();
        $user->posts()->saveMany($posts);
        $wrongUser = User::factory()->create();
        $repository = new PostDBRepository();

        $newPost = new Post(['title' => 'new title', 'body' => 'new body']);
        $beforeTitle = $posts[1]->title;
        $beforeBody = $posts[1]->body;

        $success = $repository->update($wrongUser, $posts[1]->id, $newPost);
        $this->assertFalse($success);
        $after = $user->posts()->find($posts[1]->id);

        $this->assertEquals($beforeTitle, $after->title);
        $this->assertEquals($beforeBody, $after->body);
    }
}
