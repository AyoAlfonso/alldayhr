<?php

namespace App\Http\Controllers\Admin;

use App\Country;
use App\Helper\Reply;
use App\Http\Requests\StoreLocation;
use App\JobLocation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class AdminLocationsController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('menu.locations');
        $this->pageIcon = 'icon-location-pin';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(! $this->user->can('view_locations'), 403);

        return view('admin.locations.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(! $this->user->can('add_locations'), 403);

        $this->countries = Country::all();
        return view('admin.locations.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLocation $request)
    {
        abort_if(! $this->user->can('add_locations'), 403);

        $location = new JobLocation();
        $location->location = $request->location;
        $location->country_id = $request->country_id;
        $location->save();

        return Reply::redirect(route('admin.locations.index'), __('menu.locations').' '.__('messages.createdSuccessfully'));
    }

    public function data() {
        abort_if(! $this->user->can('view_locations'), 403);

        $categories = JobLocation::all();

        return DataTables::of($categories)
            ->addColumn('action', function ($row) {
                $action = '';

                if( $this->user->can('edit_locations')){
                    $action.= '<a href="' . route('admin.locations.edit', [$row->id]) . '" class="btn btn-primary btn-circle"
                      data-toggle="tooltip" data-original-title="'.__('app.edit').'"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                }

                if( $this->user->can('delete_locations')){
                    $action.= ' <a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                      data-toggle="tooltip" data-row-id="' . $row->id . '" data-original-title="'.__('app.delete').'"><i class="fa fa-times" aria-hidden="true"></i></a>';
                }
                return $action;
            })
            ->editColumn('location', function ($row) {
                return ucwords($row->location);
            })
            ->editColumn('country_id', function ($row) {
                return ucwords($row->country->country_name);
            })
            ->addIndexColumn()
            ->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort_if(! $this->user->can('edit_locations'), 403);

        $this->countries = Country::all();
        $this->location = JobLocation::find($id);
        return view('admin.locations.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreLocation $request, $id)
    {
        abort_if(! $this->user->can('edit_locations'), 403);

        $location = JobLocation::find($id);
        $location->location = $request->location;
        $location->country_id = $request->country_id;
        $location->save();

        return Reply::redirect(route('admin.locations.index'), __('menu.locations').' '.__('messages.updatedSuccessfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort_if(! $this->user->can('delete_locations'), 403);

        JobLocation::destroy($id);
        return Reply::success(__('messages.recordDeleted'));
    }

}
