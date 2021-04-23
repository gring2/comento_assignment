<?php

namespace Tests\UserCase\Post;

use App\Http\Requests\WritePostRequest;
use App\Models\User;
use App\Presenter\WritePostPresenter;
use App\Presenter\WritePostJsonPresenter;
use App\Repository\PostDBRepository;
use App\UserCase\Post\InvalidWritePostParameterException;
use App\UserCase\Post\WriteUseCase;
use Tests\TestCase;

class WriteUseCaseTest extends TestCase
{
    public function testInvoke_Return_Presenter()
    {
        $repository = new PostDBRepository();
        $presenter = new WritePostJsonPresenter();

        $inputBoundary = new WritePostRequest(['title' => 'title', 'body' => 'body']);

        $inputBoundary->setUserResolver(function () {
            return new User();
        });

        $useCase = new WriteUseCase($repository, $presenter);

        $result = $useCase->invoke($inputBoundary);

        $this->assertInstanceOf(WritePostPresenter::class, $result);
    }

    public function testInvoke_Throw_InvalidWritePostParameterException_When_Post_has_invalid_Param()
    {
        $this->expectException(InvalidWritePostParameterException::class);
        $repository = new PostDBRepository();
        $presenter = new WritePostJsonPresenter();

        $inputBoundary = new WritePostRequest();

        $inputBoundary->setUserResolver(function () {
            return new User();
        });

        $useCase = new WriteUseCase($repository, $presenter);

        $useCase->invoke($inputBoundary);
    }

    public function testInvoke_Throw_InvalidWritePostParameterException_When_null_user()
    {
        $this->expectException(InvalidWritePostParameterException::class);
        $repository = new PostDBRepository();
        $presenter = new WritePostJsonPresenter();

        $inputBoundary = new WritePostRequest(['title' => 'title', 'body' => 'body']);

        $inputBoundary->setUserResolver(function () {
            return null;
        });

        $useCase = new WriteUseCase($repository, $presenter);

        $useCase->invoke($inputBoundary);
    }

    public function testInvoke_Throw_Exception_When_Repository_return_false()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("fail to save post user: 100, title: title, body: body");

        $repository = \Mockery::mock(PostDBRepository::class);
        $repository->shouldReceive('write')->andReturn(false);

        $presenter = new WritePostJsonPresenter();

        $inputBoundary = new WritePostRequest(['title' => 'title', 'body' => 'body']);

        $inputBoundary->setUserResolver(function () {
            $user = new User();
            $user->setAttribute('id', 100);

            return $user;
        });

        $useCase = new WriteUseCase($repository, $presenter);

        $useCase->invoke($inputBoundary);
    }
}
