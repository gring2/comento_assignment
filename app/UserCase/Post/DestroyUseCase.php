<?php


namespace App\UserCase\Post;

use App\Boundaries\PostInputBoundary;
use App\Models\Post;
use App\Models\User;
use App\Repository\PostRepository;
use App\ViewModel\DestroyPostViewModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\UnauthorizedException;

class DestroyUseCase
{
    private $repository;
    private $viewModel;

    public function __construct(PostRepository $repository, DestroyPostViewModel $viewModel)
    {
        $this->repository = $repository;
        $this->viewModel = $viewModel;
    }

    public function invoke(PostInputBoundary $boundary): DestroyPostViewModel
    {
        $post = $boundary->get('post');
        $user = $boundary->getUser();

        if (!$post instanceof Post) {
            throw new ModelNotFoundException();
        }

        if (!$user instanceof User) {
            throw new UnauthorizedException();
        }

        DB::transaction(function () use ($post, $user) {
            $result = $this->repository->destroy($post, $user);
            if (!$result) {
                throw new \Exception("Internal exception delete post: {$post->id}, user: {$user->id}");
            }
        });

        $this->viewModel->load($post->id);

        return $this->viewModel;
    }
}
