@extends('app')
@section('content')

    <div class="row">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Song</th>
                <th>Times Played</th>
                <th>Score</th>
            </tr>
            </thead>
            <tbody>
            @foreach($songs as $song)

                <tr>
                    <td>
                        <a class="link" href="/song/{{ $song->slug }}">{{ $song->title  }}</a>
                    </td>
                    <td>{{ $song->tracks_count }}</td>
                    <?php $score = intval($song->score) >= 0 ? '+' . $song->score : $song->score; ?>
                    <td class="green">{{ $score }}</td>
                </tr>

            @endforeach

            @foreach($songs_remaining as $song)

                <tr>
                    <td>
                        <a class="link" href="/song/{{ $song->slug }}">{{ $song->title  }}</a>
                    </td>
                    <td>{{ $song->tracks_count }}</td>
                    <td class="green"></td>
                </tr>

            @endforeach
            </tbody>
        </table>
    </div>

@stop