<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Project;
use App\Models\Comments;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function storage(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'description' => 'required',
                'dateEnd' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }

            $Task = Task::create([
                'name' => $request->get('name'),
                'description' => $request->get('description'),
                'dateEnd' => $request->get('dateEnd'),
                'status' => 1,
                'userId' => $request->get('user'),
                'project' => $request->get('project'),
            ]);

            if(!empty($Task)){
                return redirect()->back()->with('success', 'Registro exitoso');
            }else{
                return redirect()->back()->with('error', 'Errores');
            }


        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function listTaks(Request $request)
    {
        try {
            $Task = Task::all();
            $Users = User::all();
            $Projects = Project::all();
            return view('task', ['data' => $Task, 'users' => $Users, 'projects' => $Projects]);

        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }

    public function read(Request $request, $id)
    {
        try {
            $Task = Task::find($id);

            return response()->json(['data' => $Task], 200);

        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }

    public function update(Request $request)
    {
        try {
            $Task = Task::where('id',$request->id)->update([
                'name' => $request->get('name'),
                'description' => $request->get('description'),
                'dateEnd' => $request->get('dateEnd'),
                'status' => $request->get('status'),
                'userId' => $request->get('userId'),
                'project' => $request->get('project'),
            ]);

            return redirect()->back()->with('success', 'Edición exitosa');

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function delete(Request $request)
    {
        try {
            $Task = Task::find($request->id);
            $Task->delete();

            return response()->json([
                'code' => 200,
                'success' => 'Eliminacion exitosa'
            ]);

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function taskForProject(Request $request)
    {
        try {
            $TaskForProject = Task::select('id','name')->where('project', $request->id)->get()->toArray();

            return response()->json([
                'code' => 200,
                'taskForProject' => $TaskForProject
            ], 200);

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function commentTask(Request $request)
    {
        try {
            $Comments = Comments::create([
                'idTask' => $request->get('id'),
                'description' => $request->get('description'),
                'date' => now(),
                'userId' => Auth::user()->id,
            ]);
            return redirect()->back()->with('success', 'Comentario agregado correctamente');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function listComment(Request $request)
    {
        try {
            $Comments = Comments::join('users', 'users.id', '=', 'Comments.userId')
            ->where('idTask', $request->id)
            ->select('idTask','users.name','description','date')
            ->get()->toArray();

            return response()->json([
                'code' => 200,
                'Comments' => $Comments
            ], 200);
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }
}
