<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Project;

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
                return redirect()->back();
            }else{
                dd("error");
            }


        } catch (\Throwable $th) {
            dd($th->getMessage());
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

            return response()->json(['data' => $Task], 200);

        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            $Task = Task::find($id);
            $Task->delete();

            return response()->json(['data' => $Task], 200);

        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }

    public function taskForProject(Request $request)
    {
        try {
            $TaskForProject = Task::select()->where('project', $request->id)->get()->toArray();

            return response()->json([
                'code' => 200,
                'data' => $TaskForProject
            ], 200);

        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }
}
