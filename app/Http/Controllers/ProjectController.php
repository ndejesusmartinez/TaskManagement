<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Project;

class ProjectController extends Controller
{
    public function storage(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'description' => 'required',
                'startDate' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }

            $Project = Project::create([
                'title' => $request->get('title'),
                'description' => $request->get('description'),
                'startDate' => $request->get('startDate'),
            ]);

            if(!empty($Project)){
                return redirect()->back();
            }else{
                dd("error");
            }


        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }

    public function listProjects(Request $request)
    {
        try {
            $projects = Project::all();
            return view('projects', ['data' => $projects]);
            //return response()->json(['data' => $project], 200);

        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }

    public function read(Request $request, $id)
    {
        try {
            $project = Project::find($id);

            return response()->json(['data' => $project], 200);

        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }

    public function update(Request $request)
    {
        try {
            $project = Project::where('id',$request->id)->update([
                'title' => $request->get('title'),
                'description' => $request->get('description'),
                'startDate' => $request->get('startDate'),
            ]);

            return redirect()->back();

        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }

    public function delete(Request $request)
    {
        try {
            $project = Project::find($request->id);
            $project->delete();

            return response()->json([
                'code' => 200,
                'msg' => 'exitoso'
            ]);
            //return response()->json(['data' => $project], 200);

        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }
}
