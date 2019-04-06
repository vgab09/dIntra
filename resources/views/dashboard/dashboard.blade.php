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
        <div class="card">
            <div class="card-header">
                <div class="card-title">Szabadságok</div>
            </div>
            <div class="card-body card-block">
                <div id='calendar'></div>
            </div>
        </div>
    </div>
@endsection
@push('stylesheet')
    <link href="{{ mix('/css/fullcalendar.min.css') }}" rel="stylesheet" type="text/css"/>
@endpush
@push('headJavascript')
    <script>

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                plugins: [  'dayGrid', 'list' ],
                locales: [ 'hu' ],
                locale: 'hu',
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
                },
                eventLimit: true,
                buttonIcons: true,
                weekNumbers: true,
                navLinks: true,
                eventSources: [

                    {
                        url: '{{route('getFullCalendarEvents')}}',
                        method: 'GET',
                        failure: function() {
                            alert('there was an error while fetching events!');
                        }
                    }
                ]
            });

            calendar.render();
        });
        </script>
@endpush
@push('javascript')
    <script src="{{ mix('/js/fullcalendar.js') }}"></script>
@endpush