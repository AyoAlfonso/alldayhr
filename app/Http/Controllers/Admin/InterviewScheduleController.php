<?php

namespace App\Http\Controllers\Admin;

use App\ApplicationStatus;
use App\Helper\Reply;

use App\Http\Requests\InterviewSchedule\StoreRequest;
use App\Http\Requests\InterviewSchedule\UpdateRequest;
use App\InterviewSchedule;
use App\InterviewScheduleEmployee;
use App\JobApplication;
use App\Notifications\CandidateNotify;
use App\Notifications\CandidateReminder;
use App\Notifications\CandidateScheduleInterview;
use App\Notifications\EmployeeResponse;
use App\Notifications\ScheduleInterview;
use App\Notifications\ScheduleInterviewStatus;
use App\Notifications\ScheduleStatusCandidate;
use App\ScheduleComments;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Yajra\DataTables\Facades\DataTables;


class InterviewScheduleController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('menu.interviewSchedule');
        $this->pageIcon = 'icon-calender';
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     * @throws \Throwable
     */
    public function index(Request $request)
    {
        abort_if(! $this->user->can('view_schedule'), 403);

        $currentDate = Carbon::now()->format('Y-m-d'); // Current Date

        // Get All schedules
        $this->schedules = InterviewSchedule::
            select('interview_schedules.*', 'jobs.title', 'job_applications.full_name' )
            ->with(['employee','employee.user'])
            ->join('job_applications', 'job_applications.id', 'interview_schedules.job_application_id')
            ->join('jobs', 'jobs.id', 'job_applications.job_id')
            ->where('interview_schedules.status', 'pending')
            ->get();

        // Filter upcoming schedule
        $upComingSchedules = $this->schedules->filter(function ($value, $key)use($currentDate) {
            return $value->schedule_date >= $currentDate;
        });

        $upcomingData = [];

        // Set array for upcoming schedule
        foreach($upComingSchedules as $upComingSchedule){
            $dt = $upComingSchedule->schedule_date->format('Y-m-d');
            $upcomingData[$dt][] = $upComingSchedule;
        }

        $this->upComingSchedules = $upcomingData;

        if($request->ajax()){
            $viewData = view('admin.interview-schedule.upcoming-schedule', $this->data)->render();
            return Reply::dataOnly(['data' => $viewData, 'scheduleData' => $this->schedules]);
        }

        return view('admin.interview-schedule.index', $this->data);
    }


    /**
     * @param Request $request
     * @return string
     * @throws \Throwable
     */
    public function create(Request $request){
        abort_if(! $this->user->can('add_schedule'), 403);
        $this->candidates = JobApplication::all();
        $this->users = User::all();
        $this->scheduleDate = $request->date;
        return view('admin.interview-schedule.create', $this->data)->render();
    }

    /**
     * @param Request $request
     * @return string
     * @throws \Throwable
     */
    public function table(Request $request){
        abort_if(! $this->user->can('add_schedule'), 403);
        $this->candidates = JobApplication::all();
        $this->users = User::all();
        return view('admin.interview-schedule.table', $this->data);
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function data(Request $request){
        abort_if(! $this->user->can('view_schedule'), 403);

        $shedule = InterviewSchedule::select('interview_schedules.id','job_applications.full_name','interview_schedules.status', 'interview_schedules.schedule_date')
            ->leftjoin('job_applications', 'job_applications.id', 'interview_schedules.job_application_id');
        // Filter by status
        if($request->status != 'all' && $request->status != ''){
            $shedule = $shedule->where('interview_schedules.status', $request->status);
        }

        // Filter By candidate
        if($request->applicationID != 'all' && $request->applicationID != ''){
            $shedule = $shedule->where('job_applications.id', $request->applicationID);
        }

        // Filter by StartDate
        if($request->startDate !== null && $request->startDate != 'null'){
            $shedule = $shedule->where(DB::raw('DATE(interview_schedules.`schedule_date`)'), '>=', "$request->startDate");
        }

        // Filter by EndDate
        if($request->endDate !== null && $request->endDate != 'null'){
            $shedule = $shedule->where(DB::raw('DATE(interview_schedules.`schedule_date`)'), '<=', "$request->endDate");
        }

        return DataTables::of($shedule)
            ->addColumn('action', function ($row) {
                $action = '';


                if( $this->user->can('view_schedule')){
                    $action.= '<a href="javascript:;" data-row-id="' . $row->id . '" class="btn btn-info btn-circle view-data"
                      data-toggle="tooltip" data-original-title="'.__('app.view').'"><i class="fa fa-search" aria-hidden="true"></i></a>';
                }
                if( $this->user->can('edit_schedule')){
                    $action.= '<a href="javascript:;" style="margin-left:4px" data-row-id="' . $row->id . '" class="btn btn-primary btn-circle edit-data"
                      data-toggle="tooltip" data-original-title="'.__('app.edit').'"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                }

                if( $this->user->can('delete_schedule')){
                    $action.= ' <a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                      data-toggle="tooltip" data-row-id="' . $row->id . '" data-original-title="'.__('app.delete').'"><i class="fa fa-times" aria-hidden="true"></i></a>';
                }
                return $action;
            })
            ->editColumn('full_name', function ($row) {
                return ucwords($row->full_name);
            })
            ->editColumn('schedule_date', function ($row) {
                return Carbon::parse($row->schedule_date)->format('d F, Y H:i a');
            })
            ->editColumn('status', function ($row) {
                if($row->status == 'pending'){
                    return '<label class="badge bg-warning">'.__('app.pending').'</label>';
                }
                if($row->status == 'hired'){
                    return '<label class="badge bg-success">'.__('app.hired').'</label>';
                }
                if($row->status == 'canceled'){
                    return '<label class="badge bg-danger">'.__('app.canceled').'</label>';
                }
                if($row->status == 'rejected'){
                    return '<label class="badge bg-danger">'.__('app.rejected').'</label>';
                }
            })
            ->rawColumns(['action', 'status', 'full_name'])
            ->make(true);
    }

    /**
     * @param $id
     * @return string
     * @throws \Throwable
     */
    public function edit($id){

        abort_if(! $this->user->can('edit_schedule'), 403);
        $this->candidates = JobApplication::all();
        $this->users = User::all();
        $this->schedule = InterviewSchedule::with(['jobApplication', 'user'])->find($id);
        $this->comment = ScheduleComments::where('interview_schedule_id', $this->schedule->id)
                                            ->where('user_id', $this->user->id)->first();
        $this->employeeList = json_encode($this->schedule->employee->pluck('user_id')->toArray());
        return view('admin.interview-schedule.edit', $this->data)->render();
    }

    /**
     * @param StoreRequest $request
     * @return array
     */
    public function store(StoreRequest $request){
        abort_if(! $this->user->can('add_schedule'), 403);

        $dateTime =  $request->scheduleDate.' '.$request->scheduleTime;
        $dateTime = Carbon::createFromFormat('Y-m-d H:i', $dateTime);

        // store Schedule
        $interviewSchedule = new InterviewSchedule();
        $interviewSchedule->job_application_id = $request->candidate;
        $interviewSchedule->schedule_date = $dateTime;
        $interviewSchedule->save();

        // Update Schedule Status
        $jobApplication = JobApplication::find($request->candidate);
        $jobApplication->status_id = 3;
        $jobApplication->save();

        if($request->comment){
            $scheduleComments = new ScheduleComments();
            $scheduleComments->interview_schedule_id = $interviewSchedule->id;
            $scheduleComments->user_id = $this->user->id;
            $scheduleComments->comment = $request->comment;
            $scheduleComments->save();
        }

        if(!empty($request->employee)){
            InterviewScheduleEmployee::where('interview_schedule_id', $interviewSchedule->id)->delete();
            foreach($request->employee as $employee)
            {
                $scheduleEmployee = new InterviewScheduleEmployee();
                $scheduleEmployee->user_id = $employee;
                $scheduleEmployee->interview_schedule_id = $interviewSchedule->id;
                $scheduleEmployee->save();

                $user = User::find($employee);
                // Mail to employee for inform interview schedule
                // Notification::send($user, new ScheduleInterview($jobApplication));
            }
        }

        // mail to candidate for inform interview schedule
        // Notification::send($jobApplication, new CandidateScheduleInterview($jobApplication, $interviewSchedule));

        return Reply::redirect(route('admin.interview-schedule.index'), __('menu.interviewSchedule').' '.__('messages.createdSuccessfully'));
    }

    public function changeStatus(Request $request){
        abort_if(! $this->user->can('add_schedule'), 403);

        // store Schedule
        $interviewSchedule = InterviewSchedule::findOrFail($request->id);
        $interviewSchedule->status = $request->status;
        $interviewSchedule->save();

        $application = JobApplication::findOrFail($interviewSchedule->job_application_id);

        $status = ApplicationStatus::all();

        if($request->status == 'rejected' || $request->status == 'canceled'){
            // Filter application status
            $applicationStatus = $status->filter(function ($value, $key) {
                return $value->status == 'rejected';
            });

            $applicationStatus = $applicationStatus->first();

            $application->status_id = $applicationStatus->id;
        }
        if($request->status == 'hired'){
            // Filter application status
            $applicationSattus = $status->filter(function ($value, $key) {
                return $value->status == 'hired';
            });

            $applicationSattus = $applicationSattus->first();

            $application->status_id = $applicationSattus->id;
        }

        $application->save();

        $employeeIds = InterviewScheduleEmployee::where('interview_schedule_id', $interviewSchedule->id)->pluck('user_id')->toArray();

        $users = User::whereIn('id', $employeeIds)->get();

        if($users){
            // Mail to employee for inform interview schedule
            // Notification::send($users, new ScheduleInterviewStatus($application));
        }

        $admins = User::allAdmins();

        if($admins){
            // Notification::send($users, new ScheduleInterviewStatus($application));
        }

        if($request->mailToCandidate ==  'yes'){
            // mail to candidate for inform interview schedule status
            // Notification::send($application, new ScheduleStatusCandidate($application, $interviewSchedule));
        }

        return Reply::success(__('messages.interviewScheduleStatus'));
    }

    /**
     * @param UpdateRequest $request
     * @param $id
     * @return array
     */
    public function update(UpdateRequest $request, $id){
        abort_if(! $this->user->can('add_schedule'), 403);

        $dateTime =  $request->scheduleDate.' '.$request->scheduleTime;
        $dateTime = Carbon::createFromFormat('Y-m-d H:i', $dateTime);

        // Update interview Schedule
        $interviewSchedule = InterviewSchedule::find($id);
        $interviewSchedule->schedule_date = $dateTime;
        $interviewSchedule->save();

        if($request->comment){
            $schedule = ScheduleComments::where('interview_schedule_id', $interviewSchedule->id)
                                            ->where('user_id', $this->user->id)->first();
            if($schedule){
                $schedule->comment = $request->comment;
                $schedule->save();
            }else{
                $scheduleComments = new ScheduleComments();
                $scheduleComments->interview_schedule_id = $interviewSchedule->id;
                $scheduleComments->user_id = $this->user->id;
                $scheduleComments->comment = $request->comment;
                $scheduleComments->save();
            }


        }

        $jobApplication = JobApplication::find($request->candidate_id);

        if(!empty($request->employee)){
            InterviewScheduleEmployee::where('interview_schedule_id', $interviewSchedule->id)->delete();
            foreach($request->employee as $employee)
            {
                $scheduleEmployee = new InterviewScheduleEmployee();
                $scheduleEmployee->user_id = $employee;
                $scheduleEmployee->interview_schedule_id = $interviewSchedule->id;
                $scheduleEmployee->save();

                $user = User::find($employee);
                // Mail to employee for inform interview schedule
                // Notification::send($user, new ScheduleInterview($jobApplication));
            }
        }

        return Reply::redirect(route('admin.interview-schedule.index'), __('menu.interviewSchedule').' '.__('messages.updatedSuccessfully'));
    }

    /**
     * @param $id
     * @return array
     */
    public function destroy($id)
    {
        abort_if(! $this->user->can('delete_schedule'), 403);

        InterviewSchedule::destroy($id);
        return Reply::success(__('messages.recordDeleted'));
    }

    /**
     * @param $id
     * @return string
     * @throws \Throwable
     */
    public function show(Request $request, $id){
        abort_if(! $this->user->can('view_schedule'), 403);
        $this->schedule = InterviewSchedule::with(['jobApplication', 'user'])->find($id);
        $this->currentDateTimestamp = Carbon::now()->timestamp;
        $this->tableData = null;

        if($request->has('table')){
            $this->tableData = 'yes';
        }

        return view('admin.interview-schedule.show', $this->data)->render();
    }

    // notify and reminder to candidate on interview schedule
    public function notify($id, $type){

        $jobApplication = JobApplication::find($id);

        if($type == 'notify'){
            // mail to candidate for hiring notify
            // Notification::send($jobApplication, new CandidateNotify());
            return Reply::success(__('messages.notificationForHire'));
        }
        else{
            // mail to candidate for interview reminder
            // Notification::send($jobApplication, new CandidateReminder( $jobApplication->schedule));
            return Reply::success(__('messages.notificationForReminder'));
        }

    }

    // Employee response on interview schedule
    public function employeeResponse($id, $res){

        $scheduleEmployee = InterviewScheduleEmployee::find($id);
        $users = User::allAdmins(); // Get All admins for mail
        $type = 'refused';

        if($res == 'accept'){  $type = 'accepted'; }

        $scheduleEmployee->user_accept_status = $res;

        // mail to admin for employee response on refuse or accept
        // Notification::send($users, new EmployeeResponse($scheduleEmployee->schedule, $type, $this->user));

        $scheduleEmployee->save();

        return Reply::success(__('messages.responseAppliedSuccess'));

    }

    public function changeStatusMultiple(Request $request){
        abort_if(! $this->user->can('edit_schedule'), 403);
        foreach($request->id as $ids)
        {
            // store Schedule
            $interviewSchedule = InterviewSchedule::findOrFail($ids);
            $interviewSchedule->status = $request->status;
            $interviewSchedule->save();

            $application = JobApplication::findOrFail($interviewSchedule->job_application_id);

            $status = ApplicationStatus::all();

            if($request->status == 'rejected' || $request->status == 'canceled'){
                // Filter application status
                $applicationStatus = $status->filter(function ($value, $key) {
                    return $value->status == 'rejected';
                });

                $applicationStatus = $applicationStatus->first();

                $application->status_id = $applicationStatus->id;
            }
            if($request->status == 'hired'){
                // Filter application status
                $applicationSattus = $status->filter(function ($value, $key) {
                    return $value->status == 'hired';
                });

                $applicationSattus = $applicationSattus->first();

                $application->status_id = $applicationSattus->id;
            }
            if($request->status == 'pending'){
                // Filter application status
                $applicationSattus = $status->filter(function ($value, $key) {
                    return $value->status == 'hired';
                });

                $applicationSattus = $applicationSattus->first();

                $application->status_id = $applicationSattus->id;
            }

            $application->save();

            $employeeIds = InterviewScheduleEmployee::where('interview_schedule_id', $interviewSchedule->id)->pluck('user_id')->toArray();

            $users = User::whereIn('id', $employeeIds)->get();

            if($users){
                // Mail to employee for inform interview schedule
                Notification::send($users, new ScheduleInterviewStatus($application));
            }

            $admins = User::allAdmins();

            if($admins){
                Notification::send($users, new ScheduleInterviewStatus($application));
            }

            if($request->mailToCandidate ==  'yes'){
                // mail to candidate for inform interview schedule status
                Notification::send($application, new ScheduleStatusCandidate($application, $interviewSchedule));
            }
        }

        return Reply::success(__('messages.interviewScheduleStatus'));
    }

}
