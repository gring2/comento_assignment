<?php


namespace App\UserCase\Post;


use App\Boundaries\PostInputBoundary;
use App\Presenter\WritePostPresenter;

interface PostUseCase
{
    public function invoke(PostInputBoundary $boundary): WritePostPresenter;
}
