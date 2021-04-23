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
    public function save(User $user, Post $post);

    public function get(User $user, $id);
}
