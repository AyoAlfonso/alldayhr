<?php

namespace App\Http\Controllers\Admin;

use App\InterviewSchedule;
use App\Job;
use App\JobApplication;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Company;

class AdminDashboardController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageIcon = 'icon-speedometer';
        $this->pageTitle = __('menu.dashboard');
    }

    public function index(){

        // $client = new Client();
        // $res = $client->request('GET', config('laraupdater.update_baseurl').'/laraupdater.json');
        // $lastVersion = $res->getBody();
        // $lastVersion = json_decode($lastVersion, true);
 
        $this->totalOpenings = Job::activeJobsCount();
        $this->totalCompanies = Company::count();
        $this->totalApplications = JobApplication::count();
        $this->totalHired = JobApplication::join('application_status', 'application_status.id', '=', 'job_applications.status_id')
            ->where('application_status.status', 'hired')
            ->count();
        $this->totalRejected = JobApplication::join('application_status', 'application_status.id', '=', 'job_applications.status_id')
            ->where('application_status.status', 'rejected')
            ->count();
        $this->newApplications = JobApplication::join('application_status', 'application_status.id', '=', 'job_applications.status_id')
            ->where('application_status.status', 'applied')
            ->count();
        $this->shortlisted = JobApplication::join('application_status', 'application_status.id', '=', 'job_applications.status_id')
            ->where('application_status.status', 'phone screen')
            ->orWhere('application_status.status', 'interview')
            ->count();

        $currentDate = Carbon::now()->format('Y-m-d');

        $this->totalTodayInterview = InterviewSchedule::where(DB::raw('DATE(`schedule_date`)'),  "$currentDate")
                                        ->count();

        return view('admin.dashboard.index', $this->data);
    }
}
