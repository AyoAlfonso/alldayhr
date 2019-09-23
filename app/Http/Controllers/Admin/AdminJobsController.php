<?php

namespace App\Http\Controllers\Admin;

use App\DocumentType;
use App\Helper\Reply;
use App\Http\Requests\StoreJob;
use App\Job;
use App\JobCategory;
use App\JobLocation;
use App\JobQuestion;
use App\JobSkill;
use App\Question;
use App\Skill;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Company;

class AdminJobsController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('menu.jobs');
        $this->pageIcon = 'icon-badge';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(!$this->user->can('view_jobs'), 403);

        $this->companies = Company::all();
        $this->totalJobs = Job::count();
        $this->activeJobs = Job::where('status', 'active')->count();
        $this->inactiveJobs = Job::where('status', 'inactive')->count();

        return view('admin.jobs.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(!$this->user->can('add_jobs'), 403);

        $this->categories = JobCategory::all();
        $this->locations = JobLocation::all();
        $this->questions = Question::all();
        $this->companies = Company::all();
        $this->documents = DocumentType::all();
        return view('admin.jobs.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreJob $request)
    {
        abort_if(!$this->user->can('add_jobs'), 403);

        $job = new Job();
        $job->slug = null;
        $job->company_id = $request->company;
        $job->title = $request->title;
        $job->job_description = $request->job_description;
        $job->job_requirement = $request->job_requirement;
        $job->total_positions = $request->total_positions;
        $job->location_id = $request->location_id;
        $job->category_id = $request->category_id;
        $job->start_date = $request->start_date;
        $job->end_date = $request->end_date;
        $job->status = $request->status ? 'Active' : 'Inactive';
        $job->required_info_json = json_encode($request->required_info);
        $job->required_docs_json = json_encode($request->required_docs);
        $job->job_roles_json = json_encode($request->job_roles);
        $job->save();

        if (!is_null($request->skill_id)) {
            JobSkill::where('job_id', $job->id)->delete();

            foreach ($request->skill_id as $skill) {
                $jobSkill = new JobSkill();
                $jobSkill->skill_id = $skill;
                $jobSkill->job_id = $job->id;
                $jobSkill->save();
            }
        }

        // Save Question for job
        if (!is_null($request->question)) {
            JobQuestion::where('job_id', $job->id)->delete();

            foreach ($request->question as $question) {
                $jobQuestion = new JobQuestion();
                $jobQuestion->question_id = $question;
                $jobQuestion->job_id = $job->id;
                $jobQuestion->save();
            }
        }

        return Reply::redirect(route('admin.jobs.index'), __('menu.jobs') . ' ' . __('messages.createdSuccessfully'));
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
        abort_if(!$this->user->can('edit_jobs'), 403);
        $this->job = Job::find($id);
        $this->categories = JobCategory::all();
        $this->locations = JobLocation::all();
        $this->skills = Skill::where('category_id', $this->job->category_id)->get();
        $this->jobQuestion = JobQuestion::where('job_id', $id)->pluck('question_id')->toArray();
        $this->questions = Question::all();
        $this->companies = Company::all();
        $this->documents = DocumentType::all();

        return view('admin.jobs.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreJob $request, $id)
    {
        abort_if(!$this->user->can('edit_jobs'), 403);

        $job = Job::find($id);
        $job->company_id = $request->company;
        $job->title = $request->title;
        $job->job_description = $request->job_description;
        $job->job_requirement = $request->job_requirement;
        $job->total_positions = $request->total_positions;
        $job->location_id = $request->location_id;
        $job->category_id = $request->category_id;
        $job->start_date = $request->start_date;
        $job->end_date = $request->end_date;
        $job->status =  $request->status ? 'Active' : 'Inactive';
        $job->required_info_json = json_encode($request->required_info);
        $job->required_docs_json = json_encode($request->required_docs);
        $job->job_roles_json = json_encode($request->job_roles);
        $job->save();

        if (!is_null($request->skill_id)) {
            JobSkill::where('job_id', $job->id)->delete();

            foreach ($request->skill_id as $skill) {
                $jobSkill = new JobSkill();
                $jobSkill->skill_id = $skill;
                $jobSkill->job_id = $job->id;
                $jobSkill->save();
            }
        }
        // Update Question for job
        if (!is_null($request->question)) {
            JobQuestion::where('job_id', $job->id)->delete();

            foreach ($request->question as $question) {
                $jobQuestion = new JobQuestion();
                $jobQuestion->question_id = $question;
                $jobQuestion->job_id = $job->id;
                $jobQuestion->save();
            }
        }
        return Reply::redirect(route('admin.jobs.index'), __('menu.jobs') . ' ' . __('messages.updatedSuccessfully'));
    }

    public function storeSettings(Request $request, $id )
    {
        abort_if(!$this->user->can('add_jobs'), 403);
        $job = Job::find($id);
        $job->filter_settings_json = json_encode($request->data);
        $job->save();
        return Reply::dataOnly(['status' => 'success', 'data' =>  json_encode($request->data), 'message' => __('messages.filterCreated')], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort_if(!$this->user->can('delete_jobs'), 403);

        Job::destroy($id);
        return Reply::success(__('messages.recordDeleted'));
    }

    public function data()
    {
        abort_if(!$this->user->can('view_jobs'), 403);

        $categories = Job::where('id', '>', '0');

        if (\request('filter_company') != "") {
            $categories->where('company_id', \request('filter_company'));
        }

        if (\request('filter_status') != "") {
            $categories->where('status', \request('filter_status'));
        }

        $categories->get();

        return DataTables::of($categories)
            ->addColumn('action', function ($row) {
                $action = '';

                if ($this->user->can('edit_jobs')) {
                    $action .= '<a href="' . route('admin.jobs.edit', [$row->id]) . '" class="btn btn-primary btn-circle"
                      data-toggle="tooltip" data-original-title="' . __('app.edit') . '"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                }

                if ($this->user->can('delete_jobs')) {
                    $action .= ' <a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                      data-toggle="tooltip" data-row-id="' . $row->id . '" data-original-title="' . __('app.delete') . '"><i class="fa fa-times" aria-hidden="true"></i></a>';
                }
                return $action;
            })
            ->editColumn('title', function ($row) {
               return '<a href="'.asset('admin/job-applications/job/'.$row->id).'" target="_blank">'.ucfirst($row->title).'</a>';
            })
            ->editColumn('company_id', function ($row) {
               
                return ucfirst($row->company->company_name);
            })
            ->editColumn('location_id', function ($row) {
                return ucfirst($row->location->location) . ' (' . $row->location->country->country_code . ')';
            })
            ->editColumn('start_date', function ($row) {
                return $row->start_date->format('d M, Y');
            })
            ->editColumn('end_date', function ($row) {
                return $row->end_date->format('d M, Y');
            })
            ->editColumn('status', function ($row) {
                if ($row->status == 'active') {
                    return '<label class="badge bg-success">' . __('app.active') . '</label>';
                }
                if ($row->status == 'inactive') {
                    return '<label class="badge bg-danger">' . __('app.inactive') . '</label>';
                }
            })
            ->rawColumns(['status', 'action', '', 'title'])
            ->addIndexColumn()
            ->make(true);
    }
}
