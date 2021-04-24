<?php


namespace App\Boundaries;


use App\Models\Post;
use App\Models\User;

class DestroyPostInputBoundary implements PostInputBoundary
{
    private $user;
    private $post;

    public function __construct(User $user, Post $post)
    {
        $this->user = $user;
        $this->post = $post;
    }

    public function get(string $name, $default = null)
    {
        if ($name == 'post') {
            return $this->post;
        }

        return null;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }
}
