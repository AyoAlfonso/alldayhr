<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\Http\Requests\StoreSkill;
use App\JobCategory;
use App\Skill;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class AdminSkillsController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('menu.skills');
        $this->pageIcon = 'icon-grid';
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(! $this->user->can('view_skills'), 403);

        return view('admin.skills.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(! $this->user->can('add_skills'), 403);

        $this->categories = JobCategory::all();
        return view('admin.skills.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSkill $request)
    {
        abort_if(! $this->user->can('add_skills'), 403);

        $names = $request->name;
        $categoryId = $request->category_id;

        if (trim($names[0]) == '') {
            return Reply::error(__('errors.addSkills'));
        }

        foreach ($names as $name) {
            if (is_null($name)) {
                return Reply::error(__('errors.addSkills'));
            }
        }

        foreach ($names as $key => $name):
            if(!is_null($name)){
                Skill::create(['name' => $name, 'category_id' => $categoryId]);
            }
        endforeach;

        return Reply::redirect(route('admin.skills.index'), __('menu.skills').' '.__('messages.createdSuccessfully'));
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
        abort_if(! $this->user->can('edit_skills'), 403);

        $this->categories = JobCategory::all();
        $this->skill = Skill::find($id);
        return view('admin.skills.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreSkill $request, $id)
    {
        abort_if(! $this->user->can('edit_skills'), 403);

        $skill = Skill::find($id);
        $skill->name = $request->name;
        $skill->category_id = $request->category_id;
        $skill->save();

        return Reply::redirect(route('admin.skills.index'), __('menu.skills').' '.__('messages.updatedSuccessfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort_if(! $this->user->can('delete_skills'), 403);

        Skill::destroy($id);
        return Reply::success(__('messages.recordDeleted'));
    }

    public function data() {
        abort_if(! $this->user->can('view_skills'), 403);

        $categories = Skill::all();

        return DataTables::of($categories)
            ->addColumn('action', function ($row) {
                $action = '';

                if( $this->user->can('edit_skills')){
                    $action.= '<a href="' . route('admin.skills.edit', [$row->id]) . '" class="btn btn-primary btn-circle"
                      data-toggle="tooltip" data-original-title="'.__('app.edit').'"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                }

                if( $this->user->can('delete_skills')){
                    $action.= ' <a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                      data-toggle="tooltip" data-row-id="' . $row->id . '" data-original-title="'.__('app.delete').'"><i class="fa fa-times" aria-hidden="true"></i></a>';
                }
                return $action;
            })
            ->editColumn('name', function ($row) {
                return ucfirst($row->name);
            })
            ->editColumn('category_id', function ($row) {
                return ucfirst($row->category->name);
            })
            ->addIndexColumn()
            ->make(true);
    }

}
