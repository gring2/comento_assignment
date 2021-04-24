<?php


namespace App\ViewModel;


use App\Models\Post;

class GetPostJsonViewModel implements GetPostViewModel
{
    private $post;
    public function load(Post $post)
    {
        $this->post = $post;
    }

    public function display()
    {
        return [
            'id' => $this->post->id,
            'title' => $this->post->title,
            'body' => $this->post->body,
            'created_at' => $this->post->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->post->created_at->format('Y-m-d H:i:s'),
            'author' => [
                'id' => $this->post->author->id,
                'name' => $this->post->author->name,
                'email' => $this->post->author->email,
            ]
        ];
    }

}
