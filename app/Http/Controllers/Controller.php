<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

abstract class Controller extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return Response
     */
    public function index(Request $request)
    {
        $result = $this->repository->findAll();
        return response()->json($result, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->repository->create($request->all());
        return response()->json(['ok'], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $result = $this->repository->find($id);
        return response()->json($result, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $result = $this->repository->update($request->all(), $id);
        return response()->json($result, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        $this->repository->delete($id);
        return response()->json(['ok'], Response::HTTP_OK);
    }
}
