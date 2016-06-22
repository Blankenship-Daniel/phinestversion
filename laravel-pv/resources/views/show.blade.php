@extends('app')
@section('content')

    <h3>{{ $date }}</h3>
    <div class="row">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Song</th>
                <th>Score</th>
            </tr>
            </thead>
            <tbody>
            @foreach($display_result as $result)

                <tr>
                    <td>
                        <a href="/song/{{ $result->slug }}/{{ $result->submission_id }}">{{ $result->title  }}</a>
                    </td>
                    <?php $score = intval($result->score) >= 0 ? '+' . $result->score : $result->score; ?>
                    <td>{{ $score }}</td>
                </tr>

            @endforeach
            </tbody>
        </table>
    </div>

@stop