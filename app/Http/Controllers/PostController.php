<?php

namespace App\Http\Controllers;

use App\Boundaries\GetPostBoundary;
use App\Http\Requests\WritePostRequest;
use App\UserCase\Post\GetUseCase;
use App\UserCase\Post\WriteUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
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
     * @param  int  $id
     * @return JsonResponse
     */
    public function show(Request $request, $id, GetUseCase $useCase)
    {
        //

        $input = new GetPostBoundary($request->user(), $id);
        $result = $useCase->invoke($input);

        return response()->json($result->display(), 200);
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
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
