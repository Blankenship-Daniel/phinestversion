function doVote(elem, submissionId, type)
{
    var $_token = $('#_token').val();

    $.ajax({
        url: '/ajax/vote',
        type: 'POST',
        data: { submissionId: submissionId, type: type },
        headers: { 'X-XSRF-TOKEN' : $_token },
        dataType: 'json',
        success: function(data) {

            var modal = $('#voteModal');
            var msg = $('#voteErrorMessage');

            if (data == 'not_logged_in')
            {
                modal.modal('show');
                msg.html('You must be logged in to vote.');
            }
            else if (data == 'already_submitted')
            {
                modal.modal('show');
                msg.html('You can only vote once.');
            }
            else
            {
                var votes = parseInt(data) >= 0 ? '+' + data : data;

                if (type == 'up')
                {
                    elem.next().html(votes);
                }
                else
                {
                    elem.prev().html(votes);
                }
            }
        },
        error: function(xhr, textStatus, error) {
            //alert(xhr + ' ' + textStatus + ' ' + error);
        }
    });
}

$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

    $('body').on('click', '.vote-up-link', function() {
       var submissionId = $(this).attr('data-submission-id');
       doVote($(this), submissionId, 'up');
    });

    $('body').on('click', '.vote-down-link', function() {
        var submissionId = $(this).attr('data-submission-id');
        doVote($(this), submissionId, 'down')
    });

    $('body').on('click', '.comment', function() {
        $('#submissionId').val($(this).attr('data-submission-id'));
        $('#comment').val('');
        $('#commentModal .modal-title').text('Leave a comment for ' + $(this).attr('data-display'));
    });
});
