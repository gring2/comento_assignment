<?php


namespace App\UserCase\Post;

use App\Boundaries\PostInputBoundary;
use App\Repository\PostRepository;
use App\ViewModel\GetPostViewModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\UnauthorizedException;
use InvalidArgumentException;
use TypeError;

class GetUseCase
{
    private $repository;
    private $viewModel;
    public function __construct(PostRepository $repository, GetPostViewModel $viewModel)
    {
        $this->repository = $repository;
        $this->viewModel = $viewModel;
    }
    public function invoke(PostInputBoundary $boundary): GetPostViewModel
    {
        // TODO: Implement invoke() method.
        $user = $boundary->getUser();

        $postId = $boundary->get('id');

        $post = $this->repository->get($postId);

        if (!$post) {
            throw new ModelNotFoundException("user: {$user->id} post: {$postId} is not found");
        }

        $this->viewModel->load($post);

        return $this->viewModel;
    }
}
