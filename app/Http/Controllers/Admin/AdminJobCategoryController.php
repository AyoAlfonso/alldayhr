<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\Http\Requests\StoreJobCategory;
use App\JobCategory;
use App\Skill;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class AdminJobCategoryController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('menu.jobCategories');
        $this->pageIcon = 'icon-grid';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(! $this->user->can('view_category'), 403);

        return view('admin.job-category.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(! $this->user->can('add_category'), 403);

        return view('admin.job-category.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_if(! $this->user->can('add_category'), 403);

        $names = $request->name;

        if (trim($names[0]) == '') {
            return Reply::error(__('errors.addCategory'));
        }

        foreach ($names as $name) {
            if (is_null($name)) {
                return Reply::error(__('errors.addCategory'));
            }
        }

        foreach ($names as $key => $name):
            if(!is_null($name)){
                JobCategory::create(['name' => $name]);
            }
        endforeach;

        return Reply::redirect(route('admin.job-categories.index'), __('menu.jobCategories').' '.__('messages.createdSuccessfully'));
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
        abort_if(! $this->user->can('edit_category'), 403);

        $this->category = JobCategory::find($id);
        return view('admin.job-category.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreJobCategory $request, $id)
    {
        abort_if(! $this->user->can('edit_category'), 403);

        $category = JobCategory::find($id);
        $category->name = $request->name;
        $category->save();

        return Reply::redirect(route('admin.job-categories.index'), __('menu.jobCategories').' '.__('messages.updatedSuccessfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort_if(! $this->user->can('delete_category'), 403);

        JobCategory::destroy($id);
        return Reply::success(__('messages.recordDeleted'));
    }

    public function data() {
        abort_if(! $this->user->can('view_category'), 403);

        $categories = JobCategory::all();

        return DataTables::of($categories)
            ->addColumn('action', function ($row) {
                $action = '';

                if( $this->user->can('edit_category')){
                    $action.= '<a href="' . route('admin.job-categories.edit', [$row->id]) . '" class="btn btn-primary btn-circle"
                      data-toggle="tooltip" data-original-title="'.__('app.edit').'"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                }

                if( $this->user->can('delete_category')){
                    $action.= ' <a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                      data-toggle="tooltip" data-row-id="' . $row->id . '" data-original-title="'.__('app.delete').'"><i class="fa fa-times" aria-hidden="true"></i></a>';
                }
                return $action;
            })
            ->editColumn('name', function ($row) {
                return ucfirst($row->name);
            })
            ->addIndexColumn()
            ->make(true);
    }

    public function getSkills($categoryId){
        $jobSkills = '';

        $skills = Skill::where('category_id', $categoryId)->get();

        foreach($skills as $skill){
            $jobSkills.= '<option selected value="'.$skill->id.'">'.ucwords($skill->name).'</option>';
        }

        return Reply::dataOnly(['status' => 'success', 'data' => $jobSkills]);
    }

}
