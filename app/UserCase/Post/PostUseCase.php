<?php


namespace App\UserCase\Post;


use App\Boundaries\PostInputBoundary;
use App\ViewModel\WritePostViewModel;

interface PostUseCase
{
    public function invoke(PostInputBoundary $boundary): WritePostViewModel;
}
