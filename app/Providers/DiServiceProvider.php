<?php

namespace App\Providers;

use App\Repository\PostDBRepository;
use App\UserCase\Post\WriteUseCase;
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
    }
}