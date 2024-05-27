<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;
use App\Models\User;
use App\Notifications\TodoCreated;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ajax_todo()
    {
        $todos = Todo::all();
        return response()->json([
            'data' => $todos
        ]);
    }

    public function index()
    {
        return view('todo.index');
    }

    public function create(){

        return view('todo.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project' => 'required|string|max:255',
            'name_requested' => 'required|string|max:255',
            'dept' => 'required|string|max:255',
            'desc_project' => 'required|string',
            'status_project' => 'required|in:Done,Pending,On Progress',
            'requested_date' => 'nullable|date',
            'deadline' => 'nullable|date',
        ]);

        $todo = Todo::create($validated);

        // Mengirim notifikasi ke semua pengguna
        $users = User::all();
        foreach ($users as $user) {
            $user->notify(new TodoCreated($todo));
        }

        return response()->json($todo, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function show(Todo $todo)
    {
        return response()->json($todo);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Todo $todo)
    {
        $validated = $request->validate([
            'project' => 'sometimes|required|string|max:255',
            'name_requested' => 'sometimes|required|string|max:255',
            'dept' => 'sometimes|required|string|max:255',
            'desc_project' => 'sometimes|required|string',
            'status_project' => 'sometimes|required|in:Done,Pending,On Progress',
            'requested_date' => 'nullable|date',
            'deadline' => 'nullable|date',
        ]);

        $todo->update($validated);

        return response()->json($todo);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $todo = Todo::find($id);
        $todo->delete();

        return response()->json(null, 204);
    }
}
