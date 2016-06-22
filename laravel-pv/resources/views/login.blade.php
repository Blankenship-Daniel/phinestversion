@extends('app')
@section('content')

    <h1>Login</h1>

    @if (session('submit_fail'))
        <p>{{ session('submit_fail') }}</p>
    @endif

    @if (session('success_message'))
        <p>{{ session('success_message') }}</p>
    @endif

    @if (session('vote_attempt'))
        <p>{{ session('vote_attempt') }}</p>
    @endif

    @if (session('comment_fail'))
        <p>{{ session('comment_fail') }}</p>
    @endif

    {!! Form::open(array('url' => 'login', 'class' => 'form-bootstrap')) !!}

    <div class="form-group">
        {!! Form::label('email', 'Email Address') !!}
        {!! Form::text('email', Input::old('email'), array('placeholder' => 'example@example.com', 'class' => 'form-control')) !!}
    </div>

    <div class="form-group">
        {!! Form::label('password', 'Password') !!}
        {!! Form::password('password', array('class' => 'form-control')) !!}
    </div>

    {!! Form::submit('Login') !!}
    <span id="register_link">Don't have an account? <a href="/register">Register here</a>.</span>

    <!-- Display errors -->
    <div class="errors">
        <p class="error">
            {{ $errors->first('email') }}
        </p>
        <p class="error">
            {{ $errors->first('password') }}
        </p>

        @if (session('invalid_credentials'))
            {!! session('invalid_credentials') !!}
        @endif
    </div>

    {!! Form::close() !!}

@stop