<?php

namespace App\Http\Controllers;

use App\Http\Resources\StateResource;
use App\Models\State;
use Illuminate\Http\Request;

class StateController extends Controller
{
    /**
     * Create a new StateController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request['include']) {
            return StateResource::collection(State::with(explode(',', $request['include']))->get(), 200);
        } else {
            return StateResource::collection(State::all(), 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'code' => 'required|string|unique:states',
            'country_id' =>  'required|numeric|exists:countries,id',
        ]);

        $state = State::create($request->all());
        return new StateResource($state, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\State  $state
     * @return \Illuminate\Http\Response
     */
    public function show( Request $request, State $state)
    {
        if ($request['include']) {
            return StateResource::collection(State::where('id',$state->id)->with(explode(',', $request['include']))->get(),200);
        } else {
            return new StateResource($state, 200);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\State  $state
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, State $state)
    {
        $request->validate([
            'name' => 'string',
            'code' => 'string|unique:states',
        ]);

        $state->update($request->all());

        return new StateResource($state, 202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\State  $state
     * @return \Illuminate\Http\Response
     */
    public function destroy(State $state)
    {
        $state->delete();
        return response()->json(['message' => 'Deleted successfully'], 204);
    }
}
