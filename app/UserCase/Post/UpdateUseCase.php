<?php


namespace App\UserCase\Post;


use App\Boundaries\PostInputBoundary;
use App\Models\Post;
use App\Models\User;
use App\Repository\PostRepository;
use App\ViewModel\WritePostViewModel;
use InvalidArgumentException;
use Illuminate\Validation\UnauthorizedException;

class UpdateUseCase
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
            $post_id = $boundary->get('id');

            $title = $boundary->get('title');
            $body = $boundary->get('body');

            $newPost = new Post(['title' => $title, 'body' => $body]);

            $valid = $this->validate($newPost);
        } catch (\TypeError $e) {
            throw new InvalidArgumentException();
        }

        if ($valid) {
            $this->saveChange($user, $post_id, $newPost);
        }

        $newPost->id = $post_id;
        $this->viewModel->load($newPost);

        return $this->viewModel;
    }

    private function validate(Post $post)
    {
        if ($post->title == null && $post->body == null) {
            return false;
        }

        return true;
    }

    private function saveChange($user, $post_id, $newPost)
    {
        $saved = $this->repository->update($user, $post_id, $newPost);

        if (!$saved) {
            throw new \Exception("fail to update post user: {$user->id}, id: {$post_id}, title: {$newPost->title}, body: {$newPost->body}");
        }
    }
}
