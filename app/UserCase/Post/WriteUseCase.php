<?php


namespace App\UserCase\Post;

use App\Boundaries\PostInputBoundary;
use App\Models\Post;
use App\Models\User;
use App\ViewModel\WritePostViewModel;
use App\Repository\PostRepository;

class WriteUseCase
{
    private $repository;
    private $presenter;
    public function __construct(PostRepository $repository, WritePostViewModel $presenter)
    {
        $this->repository = $repository;
        $this->presenter = $presenter;
    }

    public function invoke(PostInputBoundary $boundary): WritePostViewModel
    {
        $user = $boundary->getUser();

        $title = $boundary->get('title');
        $body = $boundary->get('body');

        $post = new Post(['title' => $title, 'body' => $body]);

        $valid = $this->validate($user, $post);

        if (!$valid) {
            throw new InvalidWritePostParameterException();
        }

        $saved = $this->repository->save($user, $post);

        if (!$saved) {
            throw new \Exception("fail to save post user: {$user->id}, title: {$post->title}, body: {$post->body}");
        }

        $this->presenter->load($saved);

        return $this->presenter;
    }

    private function validate(User $user, Post $post)
    {
        if ($user == null) {
            return false;
        }

        if ($post->title == null || $post->body == null) {
            return false;
        }

        return true;
    }
}
