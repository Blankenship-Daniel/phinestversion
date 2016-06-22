@extends('app')
@section('content')

    <p>Enter your favorite version <a class="link" href="/submit/{{ $song_result->slug }}">here</a>.</p>
    <h3><a class="link" href="/song/{{ $song_result->slug }}">{{ $song_result->title }}</a></h3>
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
                    <h4 class="modal-title">Sorry!</h4>
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

    <div class="songlistings row">
        <table class="table table-striped">
            <tbody>

                <tr>
                    <td>
                        <p><a href="/show/{{ $submission_result->show_date }}/">{{ $submission_result->show_date }} - {{ $submission_result->venue_name }} {{ $submission_result->venue_location }}</a></p>
                        <p class="description">{{ $submission_result->description }}</p>
                        <div>

                                    <span class="border-right phishin-link">listen to this version at
                                        <a class="link" target="_blank" href="http://phish.in/{{ $submission_result->show_date }}/{{ $song_result->slug }}">phish.in</a>
                                    </span>

                                        <span class="border-right view-comments"><a class="link" href="{{ $submission_result->id }}">{{ $submission_result->num_comments }} comments</a></span>

                                    <span class="comment" data-submission-id="{{ $submission_result->id }}" data-display="{{ $submission_result->show_date }} - {{ $song_result->title }}">
                                        <a href="javascript:;" class="link" data-toggle="modal" data-target="#commentModal">
                                            <i class="fa fa-comment-o"></i> comment
                                        </a>
                                    </span>

                        </div>
                    </td>
                    <td>
                        <div>
                            <img src="{{ $submission_result->user_image }}" width="50">
                        </div>
                        <div class="username">{{ $submission_result->user_name }}</div>
                    </td>
                </tr>

            </tbody>
        </table>

        <table class="table">
            <tbody>
                @foreach($comments_result as $result)
                    <tr>
                        <td class="date"><?php echo date('jS F Y \a\t h:i:s A', strtotime($result->created_at)) ?></td>
                        <td>{{ $result->comment }}</td>
                        <td>
                            <div>
                                <img src="{{ $result->user_image }}" width="50">
                            </div>
                            <div class="username">{{ $result->username }}</div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@stop
