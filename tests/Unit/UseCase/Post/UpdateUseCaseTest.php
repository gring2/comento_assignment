<?php

namespace Tests\Unit\UseCase\Post;

use App\Boundaries\UpdatePostInputBoundary;
use App\Models\Post;
use App\Models\User;
use App\UserCase\Post\UpdateUseCase;
use App\ViewModel\UpdatePostJsonViewModel;
use App\Repository\PostDBRepository;
use Illuminate\Validation\UnauthorizedException;
use Tests\TestCase;

class UpdateUseCaseTest extends TestCase
{
    public function testInvoke_Return_Presenter()
    {
        $repository = \Mockery::mock(PostDBRepository::class);
        $repository->shouldReceive('update')->andReturn(true);

        $viewModel = new UpdatePostJsonViewModel();

        $post = new Post(['title' => 'title', 'body' => 'body', 'id' => 1]);
        $user =  new User();

        $inputBoundary = new UpdatePostInputBoundary($user, $post);

        $useCase = new UpdateUseCase($repository, $viewModel);

        $result = $useCase->invoke($inputBoundary);

        $this->assertInstanceOf(UpdatePostJsonViewModel::class, $result);
    }

    public function testInvoke_Do_not_execute_sql_If_Title_and_Body_are_null()
    {
        $repository = \Mockery::mock(PostDBRepository::class);
        $repository->shouldNotReceive('update')->andReturn(true);

        $viewModel = new UpdatePostJsonViewModel();

        $post = new Post();
        $user =  new User();

        $inputBoundary = new UpdatePostInputBoundary($user, $post);

        $useCase = new UpdateUseCase($repository, $viewModel);

        $useCase->invoke($inputBoundary);
    }

    public function testInvoke_Throw_UnauthorizedException_When_null_user()
    {
        $this->expectException(UnauthorizedException::class);
        $repository = new PostDBRepository();
        $viewModel = new UpdatePostJsonViewModel();

        $post = new Post(['title' => 'title', 'body' => 'body']);
        $post->setAttribute('id', 1);

        $user =  null;

        $inputBoundary = new UpdatePostInputBoundary($user, $post);

        $useCase = new UpdateUseCase($repository, $viewModel);

        $useCase->invoke($inputBoundary);
    }

    public function testInvoke_Throw_Exception_When_Repository_return_false()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("fail to update post user: 100, id: 1, title: title, body: body");

        $repository = \Mockery::mock(PostDBRepository::class);
        $repository->shouldReceive('update')->andReturn(false);

        $viewModel = new UpdatePostJsonViewModel();

        $post = new Post(['title' => 'title', 'body' => 'body']);
        $post->setAttribute('id', 1);

        $user =  new User();
        $user->setAttribute('id', 100);

        $inputBoundary = new UpdatePostInputBoundary($user, $post);

        $useCase = new UpdateUseCase($repository, $viewModel);

        $useCase->invoke($inputBoundary);
    }
}
