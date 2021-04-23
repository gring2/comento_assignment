<?php


namespace App\ViewModel;


use App\Models\Post;

interface GetPostViewModel
{
    public function load(Post $post);
    public function display();
}
