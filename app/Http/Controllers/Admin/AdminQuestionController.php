<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\Http\Requests\Question\StoreRequest;
use App\Http\Requests\Question\UpdateRequest;
use App\Question;
use Yajra\DataTables\Facades\DataTables;

class AdminQuestionController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('menu.question');
        $this->pageIcon = 'icon-grid';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(! $this->user->can('view_question'), 403);

        return view('admin.question.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(! $this->user->can('add_question'), 403);

        return view('admin.question.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        abort_if(! $this->user->can('add_question'), 403);
        $question = new Question();
        $question->question = $request->question;
        $question->required = $request->required;
        $question->save();

        return Reply::redirect(route('admin.questions.index'), __('menu.question').' '.__('messages.createdSuccessfully'));
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
        abort_if(! $this->user->can('edit_question'), 403);

        $this->question = Question::find($id);
        return view('admin.question.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        abort_if(! $this->user->can('edit_question'), 403);
        $question = Question::find($id);
        $question->question = $request->question;
        $question->required = $request->required;
        $question->save();

        return Reply::redirect(route('admin.questions.index'), __('menu.question').' '.__('messages.updatedSuccessfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort_if(! $this->user->can('delete_question'), 403);

        Question::destroy($id);
        return Reply::success(__('messages.questionDeleted'));
    }

    public function data() {
        abort_if(! $this->user->can('view_question'), 403);

        $questions = Question::all();

        return DataTables::of($questions)
            ->addColumn('action', function ($row) {
                $action = '';

                if( $this->user->can('edit_question')){
                    $action.= '<a href="' . route('admin.questions.edit', [$row->id]) . '" class="btn btn-primary btn-circle"
                      data-toggle="tooltip" data-original-title="'.__('app.edit').'"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                }

                if( $this->user->can('delete_question')){
                    $action.= ' <a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                      data-toggle="tooltip" data-row-id="' . $row->id . '" data-original-title="'.__('app.delete').'"><i class="fa fa-times" aria-hidden="true"></i></a>';
                }
                return $action;
            })
            ->editColumn('required', function ($row) {
                return ucfirst($row->required);
            })
            ->editColumn('requ', function ($row) {
                return ucfirst($row->question);
            })
            ->addIndexColumn()
            ->make(true);
    }

}
