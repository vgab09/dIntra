<!-- Left Panel -->

<aside id="left-panel" class="left-panel">
    <nav class="navbar navbar-expand-sm navbar-default">

        <div class="navbar-header">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa fa-bars"></i>
            </button>
            <a class="navbar-brand" href="{{route('dashboard')}}"><span class="font-weight-bolder">Day off</span>&nbsp;Intranet</a>
            <a class="navbar-brand hidden" href="{{route('dashboard')}}"><span class="font-weight-bolder">d</span>I</a>
        </div>

        <div id="main-menu" class="main-menu collapse navbar-collapse">
            {!! $presenter->getLeftMenu() !!}
        </div><!-- /.navbar-collapse -->
    </nav>
</aside><!-- /#left-panel -->

<!-- Left Panel -->