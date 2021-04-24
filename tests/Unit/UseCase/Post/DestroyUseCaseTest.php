<?php


namespace Tests\Unit\UseCase\Post;


use App\Boundaries\DestroyPostInputBoundary;
use App\Boundaries\PostInputBoundary;
use App\Models\Post;
use App\Models\User;
use App\Repository\PostDBRepository;
use App\UserCase\Post\DestroyPostUseCase;
use App\ViewModel\DestroyPostJsonViewModel;
use App\ViewModel\DestroyPostViewModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\UnauthorizedException;
use Tests\TestCase;

class DestroyUseCaseTest extends TestCase
{
    public function testInvoke_Return_Presenter()
    {
        $repository = \Mockery::mock(PostDBRepository::class);
        $repository->shouldReceive('destroy')->andReturn(1);

        $viewModel = new DestroyPostJsonViewModel();
        $post = new Post();
        $post->setAttribute('id', 1);

        $inputBoundary = new DestroyPostInputBoundary(new User(), $post);

        $useCase = new DestroyPostUseCase($repository, $viewModel);

        $result = $useCase->invoke($inputBoundary);

        $this->assertInstanceOf(DestroyPostViewModel::class, $result);
    }

    public function testInvoke_Throw_Exception_If_Repository_works_wrong()
    {
        $this->expectException(\Exception::class);

        $repository = \Mockery::mock(PostDBRepository::class);
        $repository->shouldReceive('destroy')->andReturn(false);

        $viewModel = new DestroyPostJsonViewModel();
        $post = new Post();

        $inputBoundary = new DestroyPostInputBoundary(new User(), $post);

        $useCase = new DestroyPostUseCase($repository, $viewModel);

        $useCase->invoke($inputBoundary);
    }

    public function testInvoke_Throw_ModelNotFoundException_If_Boundary_return_null_post()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = \Mockery::mock(PostDBRepository::class);
        $repository->shouldReceive('destroy')->andReturn(false);

        $viewModel = new DestroyPostJsonViewModel();

        $inputBoundary = \Mockery::mock(PostInputBoundary::class);
        $inputBoundary->shouldReceive('get')->withArgs(['post'])->andReturn(null);
        $inputBoundary->shouldReceive('getUser')->andReturn(new User());

        $useCase = new DestroyPostUseCase($repository, $viewModel);

        $useCase->invoke($inputBoundary);
    }

    public function testInvoke_Throw_UnauthorizedException_If_Boundary_return_null_user()
    {
        $this->expectException(UnauthorizedException::class);

        $repository = \Mockery::mock(PostDBRepository::class);
        $repository->shouldReceive('destroy')->andReturn(false);

        $viewModel = new DestroyPostJsonViewModel();

        $inputBoundary = \Mockery::mock(PostInputBoundary::class);
        $inputBoundary->shouldReceive('get')->withArgs(['post'])->andReturn(new Post());
        $inputBoundary->shouldReceive('getUser')->andReturn(null);

        $useCase = new DestroyPostUseCase($repository, $viewModel);

        $useCase->invoke($inputBoundary);
    }
}
