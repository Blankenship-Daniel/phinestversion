@extends('app')
@section('content')

    <p>Enter your favorite version <a class="link" href="/submit/{{ $song_result->slug }}">here</a>.</p>
    <h3>{{ $song_result->title }}</h3>

    <!-- Modal -->
    <div class="modal fade" id="commentModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <form id="commentForm" method="post" action="/comment">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                        <input type="hidden" id="submissionId" name="submissionId" value="">
                        <input type="hidden" id="retVal" name="retVal" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
                        <div class="form-group">
                            <textarea name="comment" maxlength="255" class="form-control" rows="5" id="comment"></textarea>
                        </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="modal-submit-btn btn" value="Submit">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
                </form>
            </div>

        </div>
    </div>

    <div class="modal fade" id="voteModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Error</h4>
                </div>
                <div class="modal-body">
                    <div id="voteErrorMessage"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="deleteModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Delete</h4>
                </div>
                <div class="modal-body">
                    <div id="deleteMessage"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-submission-id="" class="submitDelete btn btn-default" data-dismiss="modal">Yes</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                </div>
            </div>

        </div>
    </div>

    <div class="songlistings row">
        <table class="table table-striped">
            <tbody>

            @foreach($submission_result as $result)

                <?php
                    $status_up = '';
                    $status_down = '';

                    if ($result['vote'] == 'true')
                    {
                        if ($result['vote_type'] == 'up')
                        {
                            $status_up = 'green';
                            $status_down = 'purple';
                        }
                        else
                        {
                            $status_down = 'green';
                            $status_up = 'purple';
                        }
                    }
                    else
                    {
                        $status_up = 'purple';
                        $status_down = 'purple';
                    }
                ?>

            <tr>
                <td>
                    <div data-submission-id="{{ $result['id'] }}" class="vote-up-link">
                        <a href="javascript:;"><i class="{{ $status_up }} fa fa-thumbs-up"></i></a>
                    </div>
                    <div class="score green">
                        <?php $score = intval($result['score']) >= 0 ? '+' . $result['score'] : $result['score']; ?>
                        {{ $score }}
                    </div>
                    <div data-submission-id="{{ $result['id'] }}" class="vote-down-link">
                        <a href="javascript:;"><i class="{{ $status_down }} fa fa-thumbs-o-down"></i></a>
                    </div>
                </td>
                <td>
                    <p><a href="/show/{{ $result['show_date'] }}/">{{ $result['show_date'] }} - {{ $result['venue_name'] }} {{ $result['venue_location'] }}</a></p>
                    <p class="description">{{ $result['description'] }}</p>
                    <div>
                                    <span class="border-right phishin-link">listen to this version at
                                        <a class="link" target="_blank" href="http://phish.in/{{ $result['show_date'] }}/{{ $song_result->slug }}">phish.in</a>
                                    </span>
                                    <span class="border-right view-comments"><a class="link" href="/song/{{ $song_result->slug }}/{{ $result['id'] }}">{{ $result['num_comments'] }} comments</a></span>

                                    <span class="comment" data-submission-id="{{ $result['id'] }}" data-display="{{ $result['show_date'] }} - {{ $song_result->title }}">
                                        <a href="javascript:;" class="link" data-toggle="modal" data-target="#commentModal">
                                            <i class="fa fa-comment-o"></i> comment
                                        </a>
                                    </span>

                                    @if (Auth::check())

                                        @if (Auth::user()->id == $result['user_id'])

                                            <span data-submission-id="{{ $result['id'] }}" data-display="{{ $song_result->title }} from {{ $result['show_date'] }}" class="delete"><a class="link" href="javascript:;">delete</a></span>
                                            <form id="deleteForm{{ $result['id'] }}" action="/delete/{{ $result['id'] }}/{{ $result['user_id'] }}/{{ $song_result->slug }}" method="post"></form>

                                        @endif

                                    @endif
                    </div>
                </td>
                <td>
                    <div>
                        <img src="{{ $result['user_image'] }}" width="50">
                    </div>
                     <div class="username">{{ $result['user_name'] }}</div>
                </td>
            </tr>

            @endforeach

            </tbody>
        </table>
    </div>

@stop
