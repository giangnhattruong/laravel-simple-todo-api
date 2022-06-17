<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTodoRequest;
use App\Http\Requests\UpdateTodoRequest;
use App\Models\Color;
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
    public function index(Request $request)
    {
        // Check for filter, if there is no filter, return all
        $pageSize = $request->pageSize;

        // Map filter color to array of color ids
        $colorIds = $this->stringServices->toArrayOfColorId($request->color);

        $filterStatus = $request->status;


    
        // Filter all
        $todos = DB::table('todos')
            ->join('colors', 'colors.id', '=', 'todos.color_id')
            ->select('todos.text', 'todos.completed', 'colors.name')
            ->distinct()->paginate($pageSize);
        // or
        // $todos = Todo::with('color')->paginate($pageSize);

        // Filter trash only
        // $todos = DB::table('todos')
        //     ->leftjoin('colors', 'colors.id', '=', 'todos.color_id')
        //     ->select('todos.id', 'todos.text', 'todos.created_at', 'todos.completed', 'todos.deleted_at', 'colors.name')
        //     ->where('todos.deleted_at', '!=', null)
        //     ->orderBy('created_at', 'desc')
        //     ->distinct()->paginate($pageSize);
        // or
        // $todos = Todo::with('color')->onlyTrashed()->paginate($pageSize);

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
        return Todo::with('color')->find($id);
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
        $currentTodo = Todo::find($id);
        $newTodoInfos = $request->validated();

        // Format new todo text
        $newTodoText = isset($newTodoInfos['text']) ? $newTodoInfos['text'] : $currentTodo->text;
        
        // Format & map color string(green/blue/orange/purple/red) to color_id
        $newTodoColorString = isset($newTodoInfos['color']) ? $newTodoInfos['color'] : '';
        $newTodoColor = Color::where('name', 'ilike' , strtolower($newTodoColorString))->first();
        $newTodoColor = $newTodoColor === null ? $currentTodo->color_id : $newTodoColor->id;

        // Format & map completed string(true/false) to number(0/1)
        $newTodoStatusString = isset($newTodoInfos['completed']) ? $newTodoInfos['completed'] : '';
        switch ($newTodoStatusString) {
            case "true":
                $newTodoStatus = 1;
                break;
            case "false":
                $newTodoStatus = 0;
                break;
            default:
                $newTodoStatus = $currentTodo->completed;
        }

        $currentTodo->update([
            'text' => $newTodoText,
            'color_id' => $newTodoColor,
            'completed' => $newTodoStatus,
        ]);

        return Todo::find($id);
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

    /**
     * Mark all or mark selected todos as completed.
     *
     * @param Request $request
     * @return void
     */
    public function markCompleted(Request $request) {
        $todoIdsString = $request->ids;
        $todoIds = $this->stringServices->toArrayOfNumber($todoIdsString);

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

    /**
     * Clear all completed todos or clear selected completed todos.
     *
     * @param Request $request
     * @return void
     */
    public function clearCompleted(Request $request) {
        $todoIdsString = $request->ids;
        $todoIds = $this->stringServices->toArrayOfNumber($todoIdsString);

        if (empty($todoIds)) {
            return Todo::where('completed', static::COMPLETED)
                ->delete();
        }

        return Todo::whereIn('id', $todoIds)
            ->where('completed', static::COMPLETED)
            ->delete();
    }
}
