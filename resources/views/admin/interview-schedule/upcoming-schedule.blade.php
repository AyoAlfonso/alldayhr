@forelse($upComingSchedules as $key => $upComingSchedule)
    <div>
        @php
            $date = \Carbon\Carbon::createFromFormat('Y-m-d', $key);
        @endphp
        <h4>{{ $date->format('M d, Y') }}</h4>



        <ul class="scheduleul">
            @forelse($upComingSchedule as $key => $dtData)

                <li class="deco"  onclick="getScheduleDetail(event, {{$dtData->id}}) " style="list-style: none;">
                    <h5 class="text-muted"  style="float: left">{{ ucfirst($dtData->title) }} </h5>
                    <div class="pull-right">
                        @if($user->can('edit_schedule'))
                            <span style="margin-right: 15px;" >
                                                        <button onclick="editUpcomingSchedule(event, '{{ $dtData->id }}')" class="btn btn-sm btn-info notify-button editSchedule" title="Edit"> <i class="fa fa-pencil"></i></button>
                                                    </span>
                            <span  style="margin-right: 15px;" >
                                                        <button data-schedule-id="{{ $dtData->id }}" class="btn btn-sm btn-danger notify-button deleteSchedule" title="Delete"> <i class="fa fa-trash"></i></button>
                                                    </span>
                        @endif
                    </div>
                    <div class="clearfix"></div>
                    <div class="direct-chat-name" style="font-size: 13px">{{ ucfirst($dtData->full_name) }}</div>
                    <span class="direct-chat-timestamp" style="font-size: 13px">{{ $dtData->schedule_date->format('h:i a') }}</span>

                    @if(in_array($user->id, $dtData->employee->pluck('user_id')->toArray()))
                        @php
                            $empData = $dtData->employeeData($user->id);
                        @endphp

                        @if($empData->user_accept_status == 'accept')
                            <label class="badge badge-success float-right" >@lang('app.accepted')</label>
                        @elseif($empData->user_accept_status == 'refuse')
                            <label class="badge badge-danger float-right">@lang('app.refused')</label>
                        @else
                            <span class="float-right">
                                                        <button onclick="employeeResponse({{$empData->id}}, 'accept')" class="btn btn-sm btn-success notify-button">@lang('app.accept')</button>
                                                        <button  onclick="employeeResponse({{$empData->id}}, 'refuse')" class="btn btn-sm btn-danger notify-button">@lang('app.refuse')</button>
                                                    </span>
                        @endif
                    @endif
                </li>
                @if($key != (count($upComingSchedule)-1))<hr>@endif
            @empty

            @endforelse
        </ul>

    </div>
    <hr>
@empty
    <div>
        <p>@lang('messages.noUpcomingScheduleFund')</p>
    </div>
@endforelse