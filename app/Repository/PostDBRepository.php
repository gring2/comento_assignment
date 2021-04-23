<?php


namespace App\Repository;


use App\Models\Post;
use App\Models\User;

class PostDBRepository implements PostRepository
{

    public function write(User $user, Post $post): bool
    {
        return true;
    }
}
