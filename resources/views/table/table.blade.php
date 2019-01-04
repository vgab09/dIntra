@extends('layouts.app')
@section('page_title')
    {{$helper->getTitle()}}
@endsection

@section('content')
    <div class="card">
        <div class="card-body card-block">
    {!! $builder->table([],false,true) !!}
        </div>
    </div>
@endsection

@push('javascript')
    <script src="{{ mix('/js/datatable.js') }}"></script>
    {!! $builder->scripts() !!}
@endpush

@push('stylesheet')
    <link href="{{ mix('/css/datatables.css') }}" rel="stylesheet" type="text/css" />
@endpush


