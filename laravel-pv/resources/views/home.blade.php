@extends('app')
@section('content')

    <h1>Welcome to phinest version!</h1>

    <p>Phish has played a lot of songs in the last 30 years. We want to find the best of the best. Login to submit your favorite version of each song, vote up or down, and comment on other submissions.</p>

    <p><strong>Update:</strong> New Years shows have been added to the database. An issue has also been fixed with not being able to delete your own submissions. Happy New Years!</p>

    @if (!Auth::check())

        <p>Please <a class="link" href="/login">login</a> or <a class="link" href="/register">register</a>.</p>

    @endif

    <div class="row">
        <div class="col-md-12">
                <h3>Top Songs</h3>
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
                            <td>
                                {{ $song->tracks_count }}
                            </td>
                            <?php $score = intval($song->score) >= 0 ? '+' . $song->score : $song->score; ?>
                            <td class="green">{{ $score }}</td>
                        </tr>

                    @endforeach
                    </tbody>
                </table>

        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
    <h3>Top Shows</h3>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Show</th>
            <th>Location</th>
            <th>Score</th>
        </tr>
        </thead>
        <tbody>
        @foreach($shows as $show)

            <tr>
                <td>
                    <a class="link" href="/show/{{ $show->date }}">{{ $show->date  }}</a>
                </td>
                <td>{{ $show->name }} {{ $show->location }}</td>
                <?php $score = intval($show->score) >= 0 ? '+' . $show->score : $show->score; ?>
                <td class="green">{{ $score }}</td>
            </tr>

        @endforeach
        </tbody>
    </table>
    </div>
    </div>

    <div class="row">
        <div class="col-md-12">
    <h3>Recent Submissions</h3>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Song</th>
            <th>Show</th>
            <th>Description</th>
        </tr>
        </thead>
        <tbody>
        @foreach($submissions as $submission)

            <tr>
                <td><a class="link" href="/song/{{ $submission->slug }}">{{ $submission->title }}</a></td>
                <td><a class="link" href="/show/{{ $submission->date }}">{{ $submission->date }}</a></td>
                <td><a class="link" href="/song/{{ $submission->slug }}/{{ $submission->submission_id }}">{{ $submission->description }}</a></td>
            </tr>

        @endforeach
        </tbody>
    </table>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">

            <h3>Recent Comments</h3>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Song</th>
                    <th>Show</th>
                    <th>Comment</th>
                </tr>
                </thead>
                <tbody>
                @foreach($comments as $comment)

                    <tr>
                        <td><a class="link" href="/song/{{ $comment->slug }}">{{ $comment->title }}</a></td>
                        <td><a class="link" href="/show/{{ $comment->date }}">{{ $comment->date }}</a></td>
                        <td><a class="link" href="/song/{{ $comment->slug }}/{{ $comment->submission_id }}">{{ $comment->comment }}</a></td>
                    </tr>

                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <p class="fine-print">Even though I love Phish, I am in no way affiliated with the band. If you would like to find out more click <a class="link" href="/about">here</a>.</p>
@stop
