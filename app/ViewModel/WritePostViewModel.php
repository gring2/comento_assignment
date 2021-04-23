<?php


namespace App\ViewModel;


use App\Models\Post;

interface WritePostViewModel
{
    public function load(Post $post);
    public function toJson();
}
