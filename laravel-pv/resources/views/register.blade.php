@extends('app')
@section('content')

    <h1>Register</h1>

    {!! Form::open(array('url' => 'register', 'class' => 'form-bootstrap', 'files' => true)) !!}

    <div class="form-group">
        {!! Form::label('username', 'Username') !!}
        {!! Form::text('username', Input::old('username'), array('class' => 'form-control', 'maxlength' => 30)) !!}
    </div>

    <div class="form-group">
        {!! Form::label('email', 'Email Address') !!}
        {!! Form::text('email', Input::old('email'), array('placeholder' => 'example@example.com', 'class' => 'form-control', 'maxlength' => 100)) !!}
    </div>

    <div class="form-group">
        {!! Form::label('password', 'Password') !!}
        {!! Form::password('password', array('class' => 'form-control', 'maxlength' => 50)) !!}
    </div>

    <div class="form-group">
        {!! Form::label('password_confirmation', 'Password confirmation') !!}
        {!! Form::password('password_confirmation', array('class' => 'form-control', 'maxlength' => 50)) !!}
    </div>

    <div class="form-group">
        {!! Form::label('image', 'Profile picture') !!}
        {!! Form::file('image', null) !!}
    </div>

    {!! Form::submit('Register') !!}

    <!-- Display errors -->
    <div class="errors">

        <p class="error">
            {{ $errors->first('username') }}
        </p>
        <p class="error">
            {{ $errors->first('email') }}
        </p>
        <p class="error">
            {{ $errors->first('password') }}
        </p>
        <p class="error">
            {{ $errors->first('password_confirmation') }}
        </p>
        <p class="error">
            {{ $errors->first('image') }}
        </p>

        @if (session('error_message'))
            <p class="error">
                {{ session('error_message') }}
            </p>
        @endif

        @if (session('duplicate_error'))
                {!!  session('duplicate_error') !!}
        @endif
    </div>

    {!! Form::close() !!}

@stop