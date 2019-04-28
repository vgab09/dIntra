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
                    <form action="{{ route('password.email') }}" method="post">
                        @csrf
                        <p><h2>Jelszó visszaállítása</h2></p>
                            <div class="form-group">
                                <label>E-mail cím</label>
                                <input type="email" name="email" class="form-control" placeholder="Email">
                            </div>
                            <button type="submit" class="btn btn-success btn-flat m-b-30 m-t-30">Visszaállító link küldése</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection