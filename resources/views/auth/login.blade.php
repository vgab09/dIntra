@extends('layouts.auth')

@section('content')
<div class="sufee-login d-flex align-content-center flex-wrap">
    <div class="container">
        <div class="login-content">
            <div class="login-logo">
                <a href="{{url('/')}}">
                    uIntra
                </a>
            </div>
            <div class="login-form">
                @foreach ($presenter->getAlerts() as $alert)
                    {!! $alert->render() !!}
                @endforeach
                <form action="{{ url(route('login')) }}" method="post">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label>E-mail cím</label>
                        <input type="email" name="email" class="form-control" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <label>Jelszó</label>
                        <input type="password" name="password" class="form-control" placeholder="Password">
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="remember"> Bejelentkezve maradok
                        </label>
                        <label class="float-right">
                            <a href="{{route('password.request')}}">Elfelejtetted a jelszavad?</a>
                        </label>
                    </div>
                    <button type="submit" class="btn btn-success btn-flat m-b-30 m-t-30">Bejelentkezés</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection