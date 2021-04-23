<?php


namespace App\Repository;


use App\Models\Post;
use App\Models\User;

class PostDBRepository implements PostRepository
{
    public function save(User $user, Post $post)
    {
        return $user->posts()->save($post);
    }
}
