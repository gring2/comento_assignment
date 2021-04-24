<?php


namespace App\Repository;


use App\Models\Post;
use App\Models\User;

interface PostRepository
{
    /***
     * @param User $user
     * @param Post $post
     * @return mixed bool|Post
     */
    public function select($perPage);

    public function save(User $user, Post $post);

    public function get($id);

    public function destroy(Post $post, User $user);

    public function update(User $user, $post_id, Post $post);

}
