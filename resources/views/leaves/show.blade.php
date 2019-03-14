@extends('layouts.partials.card')

@section('card-title')
Szabadság igény
@endsection

@section('card-body')
@include('leaves.partials.leave_request_information',['leaveRequest' => $leaveRequest])
@endsection