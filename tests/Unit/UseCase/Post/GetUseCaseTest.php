<?php

namespace Tests\Unit\UseCase\Post;

use App\Boundaries\GetPostBoundary;
use App\Models\Post;
use App\Models\User;
use App\Repository\PostRepository;
use App\UserCase\Post\GetUseCase;
use App\ViewModel\GetPostJsonViewModel;
use App\ViewModel\GetPostViewModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use PHPUnit\Framework\TestCase;

class GetUseCaseTest extends TestCase
{
    public function testInvokeReturnViewModel()
    {
        $repository = \Mockery::mock(PostRepository::class);
        $repository->shouldReceive('get')->andReturn(new Post());
        $viewModel = \Mockery::mock(GetPostViewModel::class);
        $viewModel->shouldReceive('load');

        $request = new GetPostBoundary(new User(), '1');

        $useCase = new GetUseCase($repository, $viewModel);


        $result = $useCase->invoke($request);

        $this->assertInstanceOf(GetPostViewModel::class, $result);
    }

    public function testInvoke_throw_Model_not_found_Exception()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage("user: 100 post: 1 is not found");

        $repository = \Mockery::mock(PostRepository::class);
        $repository->shouldReceive('get')->andReturn(null);
        $viewModel = new GetPostJsonViewModel();

        $u = new User();
        $u->setAttribute('id', 100);
        $request = new GetPostBoundary($u, '1');

        $useCase = new GetUseCase($repository, $viewModel);


        $result = $useCase->invoke($request);

        $this->assertInstanceOf(GetPostViewModel::class, $result);
    }
}
