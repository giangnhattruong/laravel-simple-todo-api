<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTodoRequest;
use App\Http\Requests\UpdateTodoRequest;
use App\Models\Todo;
use App\Services\StringServicesInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Throwable;

class TodoController extends Controller
{
    private $stringServices;

    const COMPLETED = 1;
    const NOT_COMPLETED = 0;

    public function __construct(StringServicesInterface $stringServices) {
        $this->stringServices = $stringServices;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Check for filter, if there is no filter, return all
    
        // Filter all
        // $todos = DB::table('todos')
        //     ->join('colors', 'colors.id', '=', 'todos.color_id')
        //     ->select('todos.text', 'todos.completed', 'colors.name')
        //     ->distinct()->get();
        // or
        $todos = Todo::with('color')->get();

        // Filter trash only
        // $todos = DB::table('todos')
        //     ->leftjoin('colors', 'colors.id', '=', 'todos.color_id')
        //     ->select('todos.id', 'todos.text', 'todos.created_at', 'todos.completed', 'todos.deleted_at', 'colors.name')
        //     ->where('todos.deleted_at', '!=', null)
        //     ->orderBy('created_at', 'desc')
        //     ->distinct()->get();
        // or
        // $todos = Todo::with('color')->onlyTrashed()->get();

        // Filter 

        return $todos;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTodoRequest $request)
    {
        return Todo::create([
            'text' => $request->validated()['text'],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Todo::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTodoRequest $request, $id)
    {
        $newTodoInfos = $request->validated();

        return Todo::find($id)
            ->update([

            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Todo::find($id)->delete();
    }

    public function markCompleted(Request $request) {
        $todoIdsString = $request->ids;
        $todoIds = $this->stringServices->toArray($todoIdsString);

        if (empty($todoIds)) {
            return Todo::query()
                ->where('completed', static::NOT_COMPLETED)
                ->update([
                    'completed' => static::COMPLETED
                ]);
        }

        return Todo::whereIn('id', $todoIds)
            ->where('completed', static::NOT_COMPLETED)
            ->update([
                'completed' => static::COMPLETED
            ]);
    }

    public function clearCompleted(Request $request) {
        $todoIdsString = $request->ids;
        $todoIds = $this->stringServices->toArray($todoIdsString);

        if (empty($todoIds)) {
            return Todo::where('completed', static::COMPLETED)
                ->delete();
        }

        return Todo::whereIn('id', $todoIds)
            ->where('completed', static::COMPLETED)
            ->delete();
    }
}
