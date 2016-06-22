@extends('app')
@section('content')

    <h3>{{ $year }}</h3>
    <div class="row">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Show</th>
                <th>Location</th>
                <th>Score</th>
            </tr>
            </thead>
            <tbody>

            @foreach($display_result as $result)

                <tr>
                    <td>
                        <a class="link" href="/show/{{ $result->date }}">{{ $result->date  }}</a>
                    </td>
                    <td>{{ $result->name }} {{ $result->location }}</td>
                    <?php $score = intval($result->score) >= 0 ? '+' . $result->score : $result->score; ?>
                    <td class="green">{{ $score }}</td>
                </tr>

            @endforeach

            </tbody>
        </table>
    </div>

@stop