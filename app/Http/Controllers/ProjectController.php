<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Project;
use JWTAuth;

class ProjectController extends Controller
{
    protected $user;
    public function __construct(Request $request)
    {
        $token = $request->header('Authorization');
        if($token != '')
            $this->user = JWTAuth::parseToken()->authenticate();
    }
    public function storage(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'description' => 'required',
                'startDate' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('error', 'Todos los campos son obligatorios');
            }

            $Project = Project::create([
                'title' => $request->get('title'),
                'description' => $request->get('description'),
                'startDate' => $request->get('startDate'),
            ]);

            if(!empty($Project)){
                return redirect()->back()->with('success', 'Registro exitoso');
            }


        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function listProjects(Request $request)
    {
        try {
            $projects = Project::all();
            return view('projects', ['data' => $projects]);

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
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

            return redirect()->back()->with('success', 'Actualizacion exitosa');

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function delete(Request $request)
    {
        try {
            $project = Project::find($request->id);
            $project->delete();


            return response()->json([
                'code' => 200,
                'success' => 'Eliminacion exitosa'
            ]);
            //return response()->json(['data' => $project], 200);

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
