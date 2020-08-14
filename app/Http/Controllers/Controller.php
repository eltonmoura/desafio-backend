<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;

class Controller extends BaseController
{
    // Paginate config
    const PAGINATE_LIMIT = 20;
    const PAGINATE_MAX_LIMIT = 1000;

    // Search config
    const MIN_SEARCH_LEN = 3;

    protected function beforeStore(Request $request, Model $obj) : Model
    {
        // Can be override
        return $obj;
    }

    protected function beforeUpdate(Request $request, Model $obj) : Model
    {
        // Can be override
        return $obj;
    }

    protected function beforeDestroy(Request $request, Model $obj) : Model
    {
        // Can be override
        return $obj;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return Response
     */
    public function index(Request $request)
    {
        try {
            $queryBuilder = $this->model::query();
            $queryBuilder = $this->defaultRelationships($queryBuilder);
            $queryBuilder = $this->defaultSearch($request, $queryBuilder);

            $result = $this->paginate($request, $queryBuilder);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        try {
            $obj = $this->model::create($request->all());
            $obj = $this->beforeStore($request, $obj);
            $obj->save();

            return response()->json($obj, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        try {
            $queryBuilder = $this->model::query();

            if (isset($this->withRelationships)) {
                foreach ($this->withRelationships as $table) {
                    $queryBuilder = $queryBuilder->with($table);
                }
            }

            $obj = $queryBuilder->find($id);

            if (!$obj) {
                return response()->json(['error' => 'obj_not_found'], Response::HTTP_NOT_FOUND);
            }

            return response()->json($obj, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
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
        try {
            $obj = $this->model::find($id);
            if (!$obj) {
                return response()->json(['error' => 'obj_not_found'], Response::HTTP_NOT_FOUND);
            }

            $obj->fill($request->all());
            $obj = $this->beforeUpdate($request, $obj);
            $obj->push();

            return response()->json($obj, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        try {
            $obj = $this->model::find($id);
            if (!$obj) {
                return response()->json(['error' => 'obj_not_found'], Response::HTTP_NOT_FOUND);
            }
            $obj = $this->beforeDestroy($request, $obj);
            $obj->delete();
            return response()->json(['ok']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Receive a request and a query object and handle the pagination parameters, returning the
     * result of the paged query.
     *
     * @param  Request $request
     * @param  QueryBuilder $queryBuilder
     * @return array
     */
    protected function paginate(Request $request, QueryBuilder $queryBuilder)
    {
        $limit = $request->input('_limit', self::PAGINATE_LIMIT);
        $page = $request->input('_page', 1);

        // protection against users who try to return a very large list
        $limit = ($limit > self::PAGINATE_MAX_LIMIT) ? self::PAGINATE_MAX_LIMIT : $limit;

        $total = $queryBuilder->count();
        $result = $queryBuilder->skip($limit * ($page - 1))->take($limit)->get();

        return [
            'count' => count($result),
            'total' => $total,
            'results' => $result,
        ];
    }

    /**
     * Receives a request and a query object and handles the default search
     *
     * @param  Request $request
     * @param  QueryBuilder $queryBuilder
     * @return QueryBuilder $queryBuilder
     */
    protected function defaultSearch(Request $request, QueryBuilder $queryBuilder) : QueryBuilder
    {
        $search = $request->input('search');

        if (!empty($search) && isset($this->searchFields)) {
            if (strlen($search) < self::MIN_SEARCH_LEN) {
                throw new \Exception('Enter a search term greater than ' . self::MIN_SEARCH_LEN . ' characters');
            }
            $queryBuilder = $queryBuilder->where(function ($query) use ($search) {
                foreach ($this->searchFields as $field) {
                    $query->orWhere($field, 'like', '%' . $search . '%');
                }
            });
        }

        return $queryBuilder;
    }

    /**
     * Receives a query object and includes its relationships
     *
     * @param  Request $request
     * @param  QueryBuilder $queryBuilder
     * @return QueryBuilder $queryBuilder
     */
    protected function defaultRelationships(QueryBuilder $queryBuilder) : QueryBuilder
    {
        if (isset($this->withRelationships)) {
            foreach ($this->withRelationships as $table) {
                $queryBuilder = $queryBuilder->with($table);
            }
        }

        return $queryBuilder;
    }
}
