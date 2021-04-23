<?php


namespace App\Repository;


use App\Models\Post;
use App\Models\User;

interface PostRepository
{
    public function write(User $user, Post $post): bool;
}
