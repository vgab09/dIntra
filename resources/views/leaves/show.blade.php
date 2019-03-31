@extends('layouts.app')
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title">Szabadság igény</div>
            <div class="card-actions">{!! $toolbar->render() !!}</div>
        </div>
        <div class="card-body card-block">
            @include('leaves.partials.leave_request_information',['leaveRequest' => $leaveRequest])
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title">Esemény napló</div>
        </div>
        <div class="card-body card-block">
            @include('leaves.partials.leave_request_event_history',['history' => $history])
        </div>
    </div>
@endsection