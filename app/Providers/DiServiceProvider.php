<?php

namespace App\Providers;

use App\Repository\PostDBRepository;
use App\UserCase\Post\DestroyUseCase;
use App\UserCase\Post\GetUseCase;
use App\UserCase\Post\SelectUseCase;
use App\UserCase\Post\UpdateUseCase;
use App\UserCase\Post\WriteUseCase;
use App\ViewModel\DestroyPostJsonViewModel;
use App\ViewModel\GetPostJsonViewModel;
use App\ViewModel\SelectPostJsonViewModel;
use App\ViewModel\UpdatePostJsonViewModel;
use App\ViewModel\WritePostJsonViewModel;
use Illuminate\Support\ServiceProvider;

class DiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->app->bind(WriteUseCase::class, function () {
            return new WriteUseCase(new PostDBRepository(), new WritePostJsonViewModel());
        });

        $this->app->bind(DestroyUseCase::class, function () {
            return new DestroyUseCase(new PostDBRepository(), new DestroyPostJsonViewModel());
        });

        $this->app->bind(UpdateUseCase::class, function () {
            return new UpdateUseCase(new PostDBRepository(), new UpdatePostJsonViewModel());
        });

        $this->app->bind(SelectUseCase::class, function () {
            return new SelectUseCase(new PostDBRepository(), new SelectPostJsonViewModel());
        });
    }
}
