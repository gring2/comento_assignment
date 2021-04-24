<?php

namespace Tests\Unit\UseCase\Post;

use App\Http\Requests\UpdatePostRequest;
use App\Models\User;
use App\UserCase\Post\UpdateUseCase;
use App\ViewModel\UpdatePostJsonViewModel;
use App\Repository\PostDBRepository;
use Illuminate\Validation\UnauthorizedException;
use InvalidArgumentException;
use Tests\TestCase;

class UpdateUseCaseTest extends TestCase
{
    public function testInvoke_Return_Presenter()
    {
        $repository = \Mockery::mock(PostDBRepository::class);
        $repository->shouldReceive('update')->andReturn(true);

        $viewModel = new UpdatePostJsonViewModel();

        $inputBoundary = new UpdatePostRequest(['title' => 'title', 'body' => 'body', 'id' => 1]);

        $inputBoundary->setUserResolver(function () {
            return new User();
        });

        $useCase = new UpdateUseCase($repository, $viewModel);

        $result = $useCase->invoke($inputBoundary);

        $this->assertInstanceOf(UpdatePostJsonViewModel::class, $result);
    }

    public function testInvoke_Throw_InvalidArgumentException_When_Post_has_invalid_Param()
    {
        $this->expectException(InvalidArgumentException::class);
        $repository = new PostDBRepository();
        $viewModel = new UpdatePostJsonViewModel();

        $inputBoundary = new UpdatePostRequest();

        $inputBoundary->setUserResolver(function () {
            return new User();
        });

        $useCase = new UpdateUseCase($repository, $viewModel);

        $useCase->invoke($inputBoundary);
    }

    public function testInvoke_Throw_UnauthorizedException_When_null_user()
    {
        $this->expectException(UnauthorizedException::class);
        $repository = new PostDBRepository();
        $viewModel = new UpdatePostJsonViewModel();

        $inputBoundary = new UpdatePostRequest(['title' => 'title', 'body' => 'body', 'id' => 1]);

        $inputBoundary->setUserResolver(function () {
            return null;
        });

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

        $inputBoundary = new UpdatePostRequest(['title' => 'title', 'body' => 'body', 'id' => 1]);

        $inputBoundary->setUserResolver(function () {
            $user = new User();
            $user->setAttribute('id', 100);

            return $user;
        });

        $useCase = new UpdateUseCase($repository, $viewModel);

        $useCase->invoke($inputBoundary);
    }
}
