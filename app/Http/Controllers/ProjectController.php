<?php

namespace App\Http\Controllers;

use App\Http\Requests\Project\Store;
use App\Models\Project;
use App\Models\TaskProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function getProject(Request $request,$slug)
    {
        $project=Project::with('tasks.task_members.members')->where('projects.slug',$slug)->first();
        return response(['data'=>$project]);
    }
    public function index(Request $request)
    {
        $query = $request->get('query');
        $projects = Project::with(['task_progress']);

        if (!is_null($query)  && $query !== '') {
            $projects->where('name', 'like', '%' . $query . '%')
                ->orderBy('id', 'desc');

            return response(['data' => $projects->paginate(10)], 200);
        }
        return response(['data' => $projects->paginate(10)], 200);
    }
    public function store(Request $request)
    {
        $rules = [
            'name'=>'required',
            'startDate'=>'required',
            'endDate'=>'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response($validator->errors()->all(), 422);
        }
        $inputs = $request->all();
        $inputs['status']=  Project::NOT_STARTED;
        $inputs['slug']=  Project::createSlug($inputs['name']);
        $project = Project::create($inputs);

        TaskProgress::created([
            'projectId' => $project->id,
            'pinned_on_dashboard' => TaskProgress::NO_PINNED_ON_DASHBOARD,
            'progress' => TaskProgress::INITIAL_PROJECT_PERCENT,
        ]);



        return response(['message' => 'Project Created Successfully','project'=>$project],200);
    }
    public function update(Request $request)
    {
        $rules = [
            'id'=>'required',
            'name'=>'required',
            'startDate'=>'required',
            'endDate'=>'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response($validator->errors()->all(), 422);
        }
        $inputs = $request->all();
        $inputs['slug']=  Project::createSlug($inputs['name']);
        $project = Project::find($inputs['id']);
        if ($project) {
            $project->update($inputs);
            return response(['message' => 'Project Updated Successfully','project'=>$project],200);
        } else {
            // Handle the case where the project was not found
            return response()->json(['error' => 'Project not found'], 404);
        }
    }
    public function pinnedProject(Request $request)
    {
        $inputs = $request->all();
        $errors= Validator::make($inputs,[
            'projectId'=>'required|numeric',
        ]);
        if ($errors->fails()){
            return response($errors->errors()->all(),422);
        }
        TaskProgress::where('projectId',$inputs['projectId'])->update([
            'pinned_on_dashboard' => TaskProgress::PINNED_ON_DASHBOARD
        ]);
        return response(['message'=>'Project Pinned On Dashboard!']);
    }


}
