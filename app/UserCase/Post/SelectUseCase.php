<?php


namespace App\UserCase\Post;


use App\Repository\PostRepository;
use App\ViewModel\SelectPostViewModel;

class SelectUseCase
{
    private $repository;
    private $viewModel;

    public function __construct(PostRepository $repository, SelectPostViewModel $viewModel)
    {
        $this->repository = $repository;
        $this->viewModel = $viewModel;
    }

    public function invoke($perPage)
    {
        $result = $this->repository->select($perPage);

        $this->viewModel->load($result);

        return $this->viewModel;
    }
}
