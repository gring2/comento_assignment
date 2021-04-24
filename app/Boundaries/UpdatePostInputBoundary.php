<?php


namespace App\Boundaries;

use App\Models\Post;
use App\Models\User;

class UpdatePostInputBoundary implements PostInputBoundary
{
    private $post;
    private $title;
    private $body;
    private $user;
    /**
     * UpdatePostInputBoundary constructor.
     */
    public function __construct(?User $user, Post $post)
    {
        $this->post = $post;
        $this->user = $user;
    }

    public function get(string $name, $default = null)
    {
        switch ($name) {
            case 'title':
                return $this->post->title;
            case 'post':
                return $this->post;
            case 'id':
                return $this->post->id;
            case 'body':
                return $this->post->body;
            default:
                return null;
        }
    }

    public function getUser(): ?User
    {
        return $this->user;
    }
}
