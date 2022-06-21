<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTodoRequest;
use App\Http\Requests\UpdateTodoRequest;
use App\Http\Resources\TodoResource;
use App\Models\Color;
use App\Models\Todo;
use App\Services\StringServicesInterface;
use Illuminate\Database\Eloquent\Builder;
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
    const DEFAULT_PAGE_SIZE = 9;

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
        // Get page size
        $pageSize = $request->pageSize === null ? static::DEFAULT_PAGE_SIZE : $request->pageSize;

        // Base query
        $baseTodoQuery = Todo::query();

        // Filter by color
        $colorIds = $this->stringServices->toArrayOfColorId($request->colors);
        if (!empty($colorIds)) {
            $baseTodoQuery = $baseTodoQuery->whereHas('color', function (Builder $query) use($colorIds) {
                $query->whereIn('id', $colorIds);
            });
        }

        // Filter by status
        switch ($request->status) {
            case 'active':
                $baseTodoQuery = $baseTodoQuery
                    ->where('completed', static::NOT_COMPLETED);
                break;
            case 'completed':
                $baseTodoQuery = $baseTodoQuery
                    ->where('completed', static::COMPLETED);
                break;
            case 'deleted':
                $baseTodoQuery = $baseTodoQuery->onlyTrashed();
                break;
            case 'all':
            case '':
                break;
            default:
                $baseTodoQuery = $baseTodoQuery->where('id', 0);
        }

        // Sort result
        switch ($request->sortBy) {
            case 'dateDesc':
                $baseTodoQuery = $baseTodoQuery->orderBy('created_at', 'desc');
                break;
            case 'dateAsc':
                $baseTodoQuery = $baseTodoQuery->orderBy('created_at', 'asc');
                break;
            case 'nameDesc':
                $baseTodoQuery = $baseTodoQuery->orderBy('text', 'desc');
                break;
            case 'nameAsc':
                $baseTodoQuery = $baseTodoQuery->orderBy('text', 'asc');
                break;
            default:
                $baseTodoQuery = $baseTodoQuery->orderBy('created_at', 'desc');
        }

        // Get final result
        $todos = $baseTodoQuery->with('color')->distinct()->paginate($pageSize);

        if (empty($todos->toArray()['data'])) {
            return response()->json(
                $data = ['message' => 'Todos not found'],
                $status = 404
            );
        }

        return TodoResource::collection($todos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTodoRequest $request)
    {
        $todo = Todo::create([
            'text' => $request->validated()['text'],
        ]);

        return new TodoResource(Todo::with('color')->find($todo->id));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $todo = Todo::with('color')->find($id);

        if ($todo === null) {
            return response()->json(
                $data = ['message' => 'Todo not found'],
                $status = 404
            );
        }

        return new TodoResource($todo);
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

        if ($currentTodo === null) {
            return response()->json(
                $data = ['message' => 'Todo not found'],
                $status = 404
            );
        }

        $newTodoInfos = $request->validated();  // Deny if no input was set
        $errorMessage = []; // To collect errors cause by input

        // Set todo's new text, if no text was set then get the previous todo's text
        $newTodoText = isset($newTodoInfos['text']) ? $newTodoInfos['text'] : $currentTodo->text;
        
        // Set todo's new color, if no color was set then get the previous todo's color
        $newTodoColorString = isset($newTodoInfos['color']) ? strtolower($newTodoInfos['color']) : '';
        $newTodoColor = Color::where('name', '=' , ucfirst($newTodoColorString))->first();

        if ($newTodoColorString !== '' && $newTodoColor === null) {
            array_push($errorMessage, 'Color not found, please try again.');
        } else {
            $newTodoColor = $newTodoColor === null ? $currentTodo->color_id : $newTodoColor->id;
        }

        // Set todo's new completed status, if no status was set then get the previous todo's status
        $newTodoStatusString = isset($newTodoInfos['completed']) ? $newTodoInfos['completed'] : '';
        switch ($newTodoStatusString) {
            case "true":
                $newTodoStatus = 1;
                break;
            case "false":
                $newTodoStatus = 0;
                break;
            case "":
                $newTodoStatus = $currentTodo->completed;
                break;
            default:
                array_push($errorMessage, 'Invalid status, please try again.');
        }

        if (!empty($errorMessage)) {
            return response()->json(
                $data = ['message' => $errorMessage],
                $status = 404,
            );
        }

        $currentTodo->update([
            'text' => $newTodoText,
            'color_id' => $newTodoColor,
            'completed' => $newTodoStatus,
        ]);

        return new TodoResource(Todo::with('color')->find($id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $todo = Todo::find($id);

        if ($todo === null) {
            return response()->json(
                $data = ['message' => 'Todo not found'],
                $status = 404
            );
        }

        $todo->delete();

        return new TodoResource(Todo::with('color')->onlyTrashed()->find($id));
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

        if (!isset($todoIdsString)) {
            $result = Todo::where('completed', static::NOT_COMPLETED)
                ->update([
                    'completed' => static::COMPLETED
                ]);
        } else {
            $result = Todo::whereIn('id', $todoIds)
                ->where('completed', static::NOT_COMPLETED)
                ->update([
                    'completed' => static::COMPLETED
                ]);
        }

        return $result === 0 ? 
            response()->json(
                $data = ['message' => "No todos were marked."],
                $status = 404
            ) : 
            response()->json(
                $data = ['message' => $result . " todo(s) were marked as completed."],
                $status = 200
            );
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

        if (!isset($todoIdsString)) {
            $result = Todo::where('completed', static::COMPLETED)
                ->delete();
        } else {
            $result = Todo::whereIn('id', $todoIds)
                ->where('completed', static::COMPLETED)
                ->delete();
        }

        return $result === 0 ? 
            response()->json(
                $data = ['message' => "No todos were cleared."],
                $status = 404
            ) : 
            response()->json(
                $data = ['message' => $result . " todo(s) were cleared."],
                $status = 200
            );
    }
}
