@extends('layouts.auth')

@section('content')
    <div class="sufee-login d-flex align-content-center flex-wrap">
        <div class="container">
            <div class="login-content">
                <div class="login-logo">
                    <a href="{{url('/')}}">
                        <span class="font-weight-bolder">Day off</span>&nbsp;Intranet
                    </a>
                </div>
                <div class="login-form">
                    @foreach ($presenter->getAlerts() as $alert)
                        {!! $alert->render() !!}
                    @endforeach
                    <form action="{{ url(route('login')) }}" method="post">
                        {!! csrf_field() !!}
                        <input type="hidden" name="token" value="{{ $token }}">
                        <div class="form-group">
                            <label>E-mail cím</label>
                            <input type="email" name="email" class="form-control" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <label>Jelszó</label>
                            <input type="password" name="password" class="form-control" placeholder="Password">
                        </div>
                        <div class="form-group">
                            <label>Újból a jelszó</label>
                            <input type="password" name="password" class="form-control" placeholder="Password">
                        </div>

                        <button type="submit" class="btn btn-success btn-flat m-b-30 m-t-30">Bejelentkezés</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection