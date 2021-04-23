<?php


namespace App\ViewModel;


use App\Models\Post;

class WritePostJsonViewModel implements WritePostViewModel
{
    private $post;
    public function display()
    {
        return ['post_id' => $this->post->id];
    }

    public function load(Post $post)
    {
        $this->post = $post;
    }
}
