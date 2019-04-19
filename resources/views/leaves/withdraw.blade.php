@extends('layouts.app')
@push('stylesheet')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
@endpush
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title">Szabadság igény rögzítése</div>
        </div>
        <form method="POST" action="{{action('LeaveRequest\LeaveRequestController@insert')}}">
            @csrf
            <div class="card-body card-block">
                <div class="form-group row">
                    <label for="date_range" class="col-sm-3 col-form-label-lg">Szabadság típusa:</label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <select name="id_leave_type" class="custom-select custom-select-lg mb-3">
                                @foreach($leaveTypes as $leaveType)
                                    <option value="{{$leaveType->id_leave_type}}">{{$leaveType->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="date_range" class="col-sm-3 col-form-label-lg">Időtartam:</label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <input name="date_range" type="text" class="date-range-picker form-control-lg"
                                   placeholder="">
                            <div class="input-group-append">
                                <button class="btn btn-lg btn-outline-secondary date-range-picker-calendar-button"
                                        type="button"><i class="far fa-calendar-alt"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="comment" class="col-sm-3 col-form-label-lg">Megjegyzés:</label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <textarea id="comment" class=" form-control" name="comment" rows="2"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button id="submit" class="btn btn-primary" name="submit" type="submit">
                    <i class="far fa-save"></i>
                    Igénylés
                </button>
            </div>
        </form>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="card-title">Elérhető szabadságok</div>
        </div>
        <div class="card-body card-block">
            <table class="table table-sm table-striped align-middle">
                <thead>
                <tr>
                    <th scope="col">Szabadás típus</th>
                    <th class="text-info" scope="col">Hozzárendelt</th>
                    <th scope="col">Elhasznált</th>
                    <th class="text-success" scope="col">Elérhető</th>
                </tr>
                </thead>
                <tbody>
                @foreach($leaveTypes as $leaveType)
                    <tr>
                        <td scope="row">
                            {{$leaveType->name}}
                            <div class="text-info">
                                <small>{{$leaveType->start_at}} - {{$leaveType->end_at}}</small>
                            </div>
                        </td>
                        <td class="text-info align-middle">{{$leaveType->assigned}}</td>
                        <td class="align-middle">{{$leaveType->used}}</td>
                        <td class="text-success align-middle">{{$leaveType->available}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('javascript')
    <script src="{{ mix('/js/daterangepicker.js') }}"></script>
    <script>
        $(function () {
            $('.date-range-picker-calendar-button').click(function (event) {
                $(event.target).closest('.input-group').find('.date-range-picker').trigger('click');
            })

            $('.date-range-picker').daterangepicker({
                "showWeekNumbers": false,
                "locale": {
                    "format": "YYYY-MM-DD",
                    "separator": " - ",
                    "applyLabel": "Kiválaszt",
                    "cancelLabel": "Mégse",
                    "fromLabel": "tól",
                    "toLabel": "ig",
                    "customRangeLabel": "Egyéni",
                    "daysOfWeek": [
                        "V",
                        "H",
                        "K",
                        "Sze",
                        "Cs",
                        "P",
                        "Szo"
                    ],
                    "monthNames": [
                        "Január",
                        "Február",
                        "Március",
                        "Április",
                        "Május",
                        "Június",
                        "Július",
                        "Augusztus",
                        "Szeptember",
                        "Október",
                        "November",
                        "December"
                    ],
                    "firstDay": 1
                },
                "alwaysShowCalendars": true,
                "isInvalidDate": function (date) {

                    var hollidays = @json($holidays);
                    var workdays = @json($workdays);
                    var leaves = @json($leaves);

                    if (date.isoWeekday() == 6 || date.isoWeekday() == 7) {
                        for (index = 0, len = workdays.length; index < len; ++index) {
                            if (moment.range(workdays[index].start, workdays[index].end).contains(date)) {
                                return false;
                            }
                        }
                        return true;
                    }

                    for (index = 0, len = hollidays.length; index < len; ++index) {
                        if (moment.range(hollidays[index].start, hollidays[index].end).contains(date)) {
                            return true;
                        }
                    }

                    for (index = 0, len = leaves.length; index < len; ++index) {
                        if (moment.range(leaves[index].start, leaves[index].end).contains(date)) {
                            return true;
                        }
                    }

                    return false;
                }

            }, function (start, end, label) {
                console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
            });

        })
    </script>
@endpush