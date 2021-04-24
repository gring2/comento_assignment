<?php


namespace App\UserCase\Post;

use App\Boundaries\PostInputBoundary;
use App\Models\Post;
use App\Models\User;
use App\ViewModel\WritePostViewModel;
use App\Repository\PostRepository;
use Illuminate\Validation\UnauthorizedException;
use InvalidArgumentException;

class WriteUseCase
{
    private $repository;
    private $viewModel;
    public function __construct(PostRepository $repository, WritePostViewModel $viewModel)
    {
        $this->repository = $repository;
        $this->viewModel = $viewModel;
    }

    public function invoke(PostInputBoundary $boundary): WritePostViewModel
    {
        $user = $boundary->getUser();

        if (!$user instanceof User) {
            throw new UnauthorizedException();
        }

        try {
            $title = $boundary->get('title');
            $body = $boundary->get('body');

            $post = new Post(['title' => $title, 'body' => $body]);

            $valid = $this->validate($post);
        } catch (\TypeError $e) {
            throw new InvalidArgumentException();
        }

        if (!$valid) {
            throw new InvalidArgumentException();
        }

        $saved = $this->repository->save($user, $post);

        if (!$saved) {
            throw new \Exception("fail to save post user: {$user->id}, title: {$post->title}, body: {$post->body}");
        }

        $this->viewModel->load($saved);

        return $this->viewModel;
    }

    private function validate(Post $post)
    {
        if ($post->title == null || $post->body == null) {
            return false;
        }

        return true;
    }
}
