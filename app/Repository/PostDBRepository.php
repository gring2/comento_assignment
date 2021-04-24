<?php


namespace App\Repository;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PostDBRepository implements PostRepository
{
    public function save(User $user, Post $post)
    {
        return $user->posts()->save($post);
    }

    public function get($id)
    {
        return Post::find($id);
    }

    public function destroy(Post $post, User $user)
    {
        try {
            return $user->posts()->findOrFail($post->id)->delete();
        } catch (ModelNotFoundException $e) {
            return false;
        }
    }

    public function update(User $user, $post_id, Post $post)
    {
        $cnt = $user->posts()->where('id', $post_id)->update($post->getAttributes());

        return $cnt == 1;
    }
}
