<?php /** @var \App\Http\Components\Presenter $presenter */ ?>
<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en">
<!--<![endif]-->
@include('layouts.partials.htmlhead')
<body>

@include('layouts.partials.leftpanel')
<!-- Right Panel -->
<div id="right-panel" class="right-panel">

    <!-- Header-->
    <header id="header" class="header">
        <div class="header-menu">
            <div class="col-sm-5">
                <div class="header-left">
                    <a id="menuToggle" class="menutoggle"><i class="fas fa-bars"></i></a>
                </div>
            </div>

            <div class="col-sm-7">
                <div class="header-right">
                    @can('withdraw_leave_request')
                    <a href="{{route('withdrawLeaveRequest')}}" class="btn btn-outline-primary" role="button" aria-pressed="true">Szabadság igénylés</a>
                    @endcan
                    <div class="dropdown for-notification">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="notification" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-bell"></i>
                            <span class="count bg-danger">5</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="notification">
                            <p class="red">You have 3 Notification</p>
                            <div class="dropdown-item media alert alert-primary" role="alert">
                                This is a primary alert—check it out!
                            </div>
                        </div>
                    </div>
                    {!! $presenter->getUserMenu() !!}
            </div>
        </div>
    </div>

    </header><!-- /header -->
    <!-- Header-->
    <div class="content mt-3">
        <div class="row">
            <div class="col-sm-12">
                {!! $presenter->renderAlerts() !!}
            </div>
        </div>
        @yield('content')
    </div> <!-- .content -->
</div><!-- /#right-panel -->

<!-- Right Panel -->

<script src="{{ mix('/js/vendor.js') }}"></script>
<script src="{{ mix('/js/app.js') }}"></script>
@stack('javascript')

</body>

</html>
