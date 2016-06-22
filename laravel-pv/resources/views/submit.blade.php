@extends('app')
@section('content')

    <p>Submit a version of <b>{{ $name[0]['title'] }}</b></p>
    <?php
            $years = array();
            for ($i = 1983; $i <= 2004; $i++)
                $years[$i] = $i;

            for ($i = 2009; $i <= date("Y"); $i++)
                $years[$i] = $i;
    ?>

    {!! Form::open(array('url' => 'submit', 'class' => 'form-bootstrap')) !!}
    {!! Form::hidden('slug', $slug) !!}
    <div class="form-group">
        {!! Form::label('year', 'Year') !!}
        {!! Form::select('year', $years, null, array('class' => 'form-control')) !!}
    </div>
    <div class="form-group">
        {!! Form::label('month', 'Month') !!}
        {!! Form::selectMonth('month', null, array('class' => 'form-control')) !!}
    </div>
    <div class="form-group">
        {!! Form::label('day', 'Day') !!}
        {!! Form::selectRange('day', 1, 31, null, array('class' => 'form-control')) !!}
    </div>
    <div class="form-group">
        {!! Form::label('description', 'Description') !!}
        {!! Form::textArea('description', null, array('class' => 'form-control', 'maxlength' => 300)) !!}
    </div>
    {!! Form::submit('Submit') !!}
    {!! Form::close() !!}

    <div class="errors">
        <div class="error">
            {{ $errors->first('description') }}
        </div>

        @if (session('submission_taken'))
            {!! session('submission_taken') !!}
        @endif

        @if (session('invalid_date'))
            {!! session('invalid_date') !!}
        @endif

    </div>
@stop