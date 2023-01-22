<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $todos = Todo::whereBelongsTo(auth()->user())->get();

        return response()->json([
            "success" => true,
            "message" => "Todos list.",
            "data" => $todos
        ]);
    }
    public function getAverage($todos)
    {
        $time_diff = [];
        foreach ($todos as $todo) {
            if ($todo->status === 'completed') {
                $interval = $todo->created_at->diff($todo->updated_at);
                array_push($time_diff, $interval->i);
            }
        }
        if (count($time_diff) > 0) {
            $average = array_sum($time_diff) / count($time_diff);
        } else {
            $average = 0;
        }
        return $average;
    }
    public function todosReport()
    {
        $todos = Todo::whereBelongsTo(auth()->user())->get();

        $createdTodosCount = $todos->count();

        $deletedTodosCount = Todo::whereBelongsTo(auth()->user())->onlyTrashed()->count();

        $todosWithTrashed = Todo::whereBelongsTo(auth()->user())->withTrashed()->get();

        $average = $this->getAverage($todos);
        $averageWithTrashed = $this->getAverage($todosWithTrashed);


        return response()->json([
            "status" => true,
            "message" => "Todos report.",
            "data" => [
                "created" => $createdTodosCount,
                "deleted" => $deletedTodosCount,
                "average" => $average,
                "averageWithTrashed" => $averageWithTrashed
            ]
        ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = $request->validate([
            'title' => 'required|string|max:191',
            'description' => 'nullable|string|max:255',
        ]);

        $validation['user_id'] = auth()->user()->id;
        $todo = Todo::create($validation);

        return response()->json([
            "success" => true,
            "message" => "Todo created successfully.",
            "data" => $todo
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request['id'] = (int)$id;

        $validation = $request->validate([
            'id' => 'required|integer|exists:todos,id',
            'title' => 'nullable|string|max:191',
            'description' => 'nullable|string|max:255',
            'status' => 'required',
            'status' => Rule::in(['pending', 'in_progress', 'completed']),
        ]);
        $todos = Todo::whereBelongsTo(auth()->user())->get();
        $todo = $todos->find($validation['id']);

        if (!isset($todo)) {
            return response()->json([
                "status" => false,
                "message" => "The selected todo does not exists.",
                "data" => []
            ], 404);
        }
        if ($todo->status === 'completed' && $validation['status'] !== 'completed') {
            return response()->json([
                "status" => false,
                "message" => "The status can not be changed anymore, actual status: Completed.",
                "data" => []
            ], 400);
        }
        $todo->update($validation);

        return response()->json([
            "success" => true,
            "message" => "Todo updated successfully.",
            "data" => $todo
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
        Todo::whereBelongsTo(auth()->user())->where('id', $id)->delete();
        return response()->json([
            "status" => true,
            "message" => "Todo successfully deleted!",
            "data" => []
        ]);
    }
}
