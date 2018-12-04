@include('layout-partials.htmlhead')
<body>

@include('layout-partials.leftpanel')
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

    <div class="breadcrumbs">
        <div class="col-sm-4">
            <div class="page-header float-left">
                <div class="page-title">
                    <h1>@yield('page_title')</h1>
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="page-header float-right">
                <div class="page-title">
                    <ol class="breadcrumb text-right">
                        @yield('breadcrumb','')
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content mt-3">

        <div class="col-sm-12">
            {{--
            <div class="alert  alert-success alert-dismissible fade show" role="alert">
                <span class="badge badge-pill badge-success">Success</span> You successfully read this important alert message.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            --}}

        </div>
        @yield('content')
    </div> <!-- .content -->
</div><!-- /#right-panel -->

<!-- Right Panel -->

<script src="{{ mix('/js/app.js') }}"></script>
<script src="{{ mix('/js/dashboard.js') }}"></script>
@yield('javascript')

</body>

</html>
