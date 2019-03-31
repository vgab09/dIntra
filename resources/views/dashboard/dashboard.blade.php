@extends('layouts.app')
@section('content')
    <div class="animated fadeIn">
        <div class="row">

            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="stat-widget-one">
                            <div class="stat-icon dib"><i class="fas fa-gavel text-warning border-warning"></i></div>
                            <div class="stat-content dib">
                                <div class="stat-text">Függő szabadságok</div>
                                <div class="stat-digit">{{$pendingCount}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="stat-widget-one">
                            <div class="stat-icon dib"><i class="fas fa-users text-primary border-primary"></i></div>
                            <div class="stat-content dib">
                                <div class="stat-text">Munkatársak</div>
                                <div class="stat-digit">{{$employeCount}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="stat-widget-one">
                            <div class="stat-icon dib"><i class="ti-user text-primary border-primary"></i></div>
                            <div class="stat-content dib">
                                <div class="stat-text">Elérhető szabadságok</div>
                                <div class="stat-digit">961</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- .row -->
    </div>
@endsection