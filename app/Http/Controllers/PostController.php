<?php

namespace App\Http\Controllers;

use App\Boundaries\DestroyPostInputBoundary;
use App\Boundaries\UpdatePostInputBoundary;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Requests\WritePostRequest;
use App\Models\Post;
use App\UserCase\Post\DestroyUseCase;
use App\UserCase\Post\SelectUseCase;
use App\UserCase\Post\UpdateUseCase;
use App\UserCase\Post\WriteUseCase;
use App\ViewModel\GetPostJsonViewModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PostController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Post::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request, SelectUseCase $useCase)
    {
        //
        $result = $useCase->invoke($request->get('per_page'));
        return response()->json($result->display(), 200);

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param WritePostRequest $request
     * @param WriteUseCase $useCase
     * @return JsonResponse
     * @throws \Exception
     */
    public function store(WritePostRequest $request, WriteUseCase $useCase)
    {
        //
        $result = $useCase->invoke($request);

        return response()->json($result->display(), 200);
    }

    /**
     * Display the specified resource.
     *
     * @param Post $post
     * @param GetPostJsonViewModel $viewModel
     * @return JsonResponse
     */
    public function show(Post $post, GetPostJsonViewModel $viewModel)
    {
        //

        $viewModel->load($post);
        return response()->json($viewModel->display(), 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePostRequest $request
     * @param UpdateUseCase $useCase
     * @return JsonResponse
     * @throws \Exception
     */
    public function update(UpdatePostRequest $request, Post $post, UpdateUseCase $useCase)
    {
        //
        $post->title = $request->get('title');
        $post->body = $request->get('body');

        $input = new UpdatePostInputBoundary($request->user(), $post);
        $result = $useCase->invoke($input);

        return response()->json($result->display(), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Post $post
     * @param DestroyUseCase $useCase
     * @return JsonResponse
     */
    public function destroy(Request $request, Post $post, DestroyUseCase $useCase)
    {
        $input = new DestroyPostInputBoundary($request->user(), $post);
        $result = $useCase->invoke($input);

        return response()->json($result->display(), 200);
    }
}
