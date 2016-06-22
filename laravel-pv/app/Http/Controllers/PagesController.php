<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Show;
use App\Song;
use App\Submission;
use App\User;
use App\Venue;
use App\Vote;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Request;

class PagesController extends Controller
{
    // Update scripts ...
    
    public function updateShows()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://phish.in/api/v1/shows.json?per_page=4&page=1&sort_attr=date&sort_dir=desc');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
   
        $json = json_decode($output);
       
        foreach ($json->data as $d) {
            $show = new Show;
            $show->id = $d->id;
            $show->date = $d->date;
            $show->tour_id = $d->tour_id;
            $show->venue_id = $d->venue_id;
            $show->save(); 
        }
     
        curl_close($ch);  
    }

    // Private

    /**
     *  returns true if the variable passed has a length of one
     *   and equals a letter [A-Z]
     * @param $x
     * @return bool
     */
    private function isAlphaQuery($x)
    {
        for ($i = 65; $i <= 90; $i++)
        {
            if (chr($i) == $x && strlen($x) == 1)
                return true;
        }

        return false;
    }

    // Public


    public function index()
    {
        $songs = DB::select('SELECT submissions.song_id AS song_id, title, slug, tracks_count, SUM(score) AS score FROM ' .
                                'submissions LEFT JOIN songs ON submissions.song_id = songs.id ' .
                                'GROUP BY title ORDER BY score DESC LIMIT 5');

        $shows = DB::select('SELECT date, name, location, SUM(score) AS score FROM submissions LEFT JOIN shows ON ' .
                                'submissions.show_id = shows.id LEFT JOIN venues ON venue_id = venues.id GROUP BY ' .
                                'submissions.show_id ORDER BY score DESC LIMIT 5');

        $comments = DB::select('SELECT  songs.slug as slug, songs.title as title, users.username as username, submission_id, comment, comments.created_at as created_at, shows.date as date FROM comments LEFT JOIN submissions ON comments.submission_id = submissions.id LEFT JOIN songs ON songs.id = submissions.song_id LEFT JOIN users ON users.id = submissions.user_id LEFT JOIN shows ON shows.id = submissions.show_id ORDER BY comments.created_at DESC LIMIT 5');


        $submissions = DB::select('SELECT submissions.id AS submission_id, submissions.description AS description, songs.title AS title, songs.slug AS slug, shows.date AS date FROM submissions LEFT JOIN songs ON submissions.song_id = songs.id LEFT JOIN shows ON submissions.show_id = shows.id ORDER BY submissions.id DESC LIMIT 5');

        return view('home', compact('songs', 'shows', 'comments', 'submissions'));
    }


    public function songs()
    {
        $songs = DB::select('SELECT submissions.song_id AS song_id, title, slug, tracks_count, SUM(score) AS score FROM ' .
            'submissions LEFT JOIN songs ON submissions.song_id = songs.id ' .
            'GROUP BY title ORDER BY score DESC');

        $songs_remaining = DB::select('SELECT songs.id, title, slug, tracks_count FROM songs LEFT JOIN submissions ON songs.id = submissions.song_id WHERE score IS NULL ORDER BY tracks_count DESC');

        return view('songs', compact('songs', 'songs_remaining'));
    }


    public function showSong($slug)
    {
        $song_result = Song::where('slug', '=', $slug)->first();
        if (is_null($song_result))
            App::abort('404');

        $submission_result = Submission::where('song_id', '=', $song_result->id)->orderBy('score', 'DESC')->get();

        foreach ($submission_result as $result)
        {
            $user_result = User::where('id', '=', $result->user_id)->first();
            $result['user_image'] = $user_result->image;
            $result['user_name'] = $user_result->username;

            $show_result = Show::where('id', '=', $result->show_id)->first();
            $result['show_date'] = $show_result->date;

            $venue_result = Venue::where('id', '=', $show_result->venue_id)->first();
            $result['venue_name'] = $venue_result->name;
            $result['venue_location'] = $venue_result->location;

            $comments_result = Comment::where('submission_id', '=', $result->id)->count();
            $result['num_comments'] = $comments_result;

            if (Auth::check())
            {
                $vote_result = Vote::where('submission_id', '=', $result->id)
                    ->where('user_id', '=', Auth::user()->id)->first();

                if (count($vote_result))
                {
                    $result['vote_type'] = $vote_result->vote_type;
                    $result['vote'] = 'true';
                }
                else
                {
                    $result['vote'] = 'false';
                }
            }
            else
            {
                $result['vote'] = 'false';
            }
        }

        return view('song', compact('song_result', 'submission_result'));
    }

    public function showSongFromDate($date, $slug)
    {
        $song_result = Song::where('slug', '=', $slug)->first();
        $show = Show::where('date', '=', $date)->first();
        if (is_null($song_result) || is_null($show))
            App::abort('404');

        $submission_result = Submission::where('song_id', '=', $song_result->id)
            ->where('show_id', '=', $show->id)
            ->orderBy('score', 'DESC')->get();

        foreach ($submission_result as $result)
        {
            $user_result = User::where('id', '=', $result->user_id)->first();
            $result['user_image'] = $user_result->image;
            $result['user_name'] = $user_result->username;

            $show_result = Show::where('id', '=', $result->show_id)->first();
            $result['show_date'] = $show_result->date;

            $venue_result = Venue::where('id', '=', $show_result->venue_id)->first();
            $result['venue_name'] = $venue_result->name;
            $result['venue_location'] = $venue_result->location;

            $comments_result = Comment::where('submission_id', '=', $result->id)->count();
            $result['num_comments'] = $comments_result;
        }

        return view('song', compact('song_result', 'submission_result'));
    }

    public function submit()
    {
        App::abort('404');
    }


    public function submitSong($slug)
    {
        if (!Auth::check())
            return Redirect::to('login')->with('submit_fail', 'Please login in to submit a song.');

        $name = Song::where('slug', '=', $slug)->groupBy('title')->first();
        if (is_null($name))
            App::abort('404');

        $dates = Show::orderBy('date')->lists('date', 'id');
        return view('submit', compact('name', 'dates', 'slug'));
    }

    public function show($date)
    {
        $display_result = DB::select('SELECT date, title, score, slug, submissions.id as submission_id FROM shows LEFT JOIN submissions ON ' .
                                        'shows.id = submissions.show_id LEFT JOIN songs ON ' .
                                        'submissions.song_id = songs.id WHERE date = ? ' .
                                        'GROUP BY title ORDER BY score DESC', [$date]);

        return view('show', compact('display_result', 'date'));
    }

    public function shows()
    {
        $display_result = DB::select('SELECT show_id, sum(score) as score, date, venue_id, name, location ' .
                                    'FROM submissions ' .
                                    'LEFT JOIN shows ON submissions.show_id = shows.id  ' .
                                    'LEFT JOIN venues ON venues.id = venue_id GROUP BY date ORDER BY score DESC');

        return view('shows', compact('display_result'));
    }

    public function year($year)
    {
        $year_result = DB::select("SELECT * FROM shows WHERE strftime('%Y', date) = ?", [$year]);
        if (!count($year_result))
            App::abort('404');

        $display_result = DB::select('SELECT date, name, location, SUM(score) as score FROM submissions ' .
                                        'LEFT JOIN shows ON submissions.show_id = shows.id ' .
                                        'LEFT JOIN venues ON venue_id = venues.id ' .
                                        'WHERE strftime("%Y", shows.date) = ? GROUP BY date ORDER BY score DESC', [$year]);

        return view('year', compact('year', 'display_result'));
    }

    public function years()
    {
        $year_rankings = array();
        $hiatus = [2001, 2002, 2005, 2006, 2007, 2008];

        for ($i = 1983; $i <= 2015; $i++)
        {
            if (in_array($i, $hiatus))
                continue;

            $submission_result = DB::select('SELECT SUM(score) AS SCORE FROM submissions LEFT JOIN shows ON ' .
                                                'submissions.show_id = shows.id ' .
                                                'WHERE strftime("%Y", shows.date) = ?', [$i]);

            if (!is_null($submission_result[0]->SCORE))
                $year_rankings[$i] = $submission_result[0]->SCORE;
            else
                $year_rankings[$i] = 0;
        }

        arsort($year_rankings);

        return view('years', compact('year_rankings'));
    }


    public function login()
    {
        return view('login');
    }


    public function register()
    {
        return view('register');
    }


    public function logout()
    {
        Auth::logout();
        Session::flush();
        Session::regenerate();
        return Redirect::to('/login');
    }


    public function doLogin()
    {
        // validate the info, create rules for the inputs
        $rules = array(
            'email' => 'required|email',            // make sure the email is an actual email
            'password' => 'required|alphaNum|min:8' // password can only be alphanumeric and has to be greater than 3 characters
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make(Input::all(), $rules);

        // if the validator fails, redirect back to the form
        if ($validator->fails())
        {
            return Redirect::to('login')
                ->withErrors($validator)                // send back all errors to the login form
                ->withInput(Input::except('password')); // send back the input (not the password) so that we can repopulate the form
        }
        else
        {
            // create our user data for the authentication
            $userdata = array(
                'email' => Input::get('email'),
                'password' => Input::get('password')
            );

            // attempt to do the login
            if (Auth::attempt($userdata))
            {
                Session::put('username', Auth::user()->username);
                Session::put('image', Auth::user()->image);

                // validation successful!
                return Redirect::to('/');
            }
            else
            {
                // validation not successful, send back to form
                return Redirect::to('login')->with('invalid_credentials', '<p class="error">No user exists with that email/password combo</p>');
            }
        }
    }


    public function doRegister()
    {
        // validate the info, create rules for the inputs
        $rules = array(
            'username' => 'required|alphaNum|min:3',
            'email' => 'required|email',                        // make sure the email is an actual email
            'password' => 'required|alphaNum|min:8|confirmed',  // password can only be alphanumeric and has to be greater than 3 characters
            'password_confirmation' => 'required|alphaNum|min:8',
            'image' => 'required|mimes:jpg,jpeg,png'
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make(Input::all(), $rules);

        // if the validator fails, redirect back to the form
        if ($validator->fails())
        {
            return Redirect::to('register')
                ->withErrors($validator)                // send back all errors to the register form
                ->withInput(Input::except('password')); // send back the input (not the password) so that we can repopulate the form
        }
        else
        {
            // save file to the /img directory
            $image = Request::file('image');
            $extension = $image->getClientOriginalExtension();
            $img = '/img/' . $image->getFilename() . '.' . $extension;
            $image->move('img', $img);

            $username_err = '';
            $username_result = User::where('username', '=', Request::input('username'))->first();
            if ($username_result)
                $username_err = '<p class="error">Username already taken.</p>';

            $email_err = '';
            $email_result = User::where('email', '=', Request::input('email'))->first();
            if ($email_result)
                $email_err = '<p class="error">A user with that email already exists.</p>';

            if ((isset($username_err) && $username_err) || (isset($email_err) && $email_err))
                return Redirect::to('register')->with('duplicate_error', $username_err . $email_err);



            $user = new User;
            $user->image = $img;
            $user->username = Request::input('username');
            $user->email = Request::input('email');
            $user->password = Hash::make(Request::input('password'));
            $user->save();
        }

        return Redirect::to('login')->with('success_message', 'Thank you for creating a new account. Please sign in.');
    }


    public function doSubmit()
    {
        // if no user is logged in, redirect to the log in process
        if (!Auth::check())
            return Redirect::to('login')->with('submit_fail', 'Please login in to submit a song.');

        $rules = array(
            'description' => 'required|max:300'
        );

        $validator = Validator::make(Input::all(), $rules);

        // if the validator fails, redirect back to the form
        if ($validator->fails())
        {
            return Redirect::to('/submit/' . Request::input('slug'))
                ->withErrors($validator);  // send back all errors to the submit form
        }
        else
        {
            // properly format date to match date listings in the database : 01-01-1983
            $date = Request::input('year') . '-' .
                    (Request::input('month') < 10 ? '0' . Request::input('month') : Request::input('month')) . '-' .
                    (Request::input('day') < 10 ? '0' . Request::input('day') : Request::input('day'));

            // query the database to see if the submitted date matches
            //  a date played by the band.
            $date_result = Show::where('date', '=', $date)->first();

            if (is_null($date_result))
            {
                return Redirect::to('/submit/'. Request::input('slug'))
                    ->with('invalid_date', '<p class="error">Sorry! Phish never played on this date. ' .
                                'If you are having trouble remembering the date you can look up the show ' .
                                '<a class="link" target="_blank" href="http://phish.net/setlists">here</a>.</p>');
            }
            else
            {
                // query the songs table to get the song_id for the submission
                $song_result = Song::where('slug', '=', Request::input('slug'))->first();

                // check to see if this version of this song has already been submitted
                $submission_result = Submission::where('song_id', '=', $song_result->id)
                    ->where('show_id', '=', $date_result->id)->first();

                // if this version has already been submitted,
                //  redirect back to the submit screen with an error
                if (!is_null($submission_result))
                {
                    return Redirect::to('/submit/' . Request::input('slug'))
                        ->with('submission_taken', '<p class="error">Sorry! That version has already been submitted.</p>');
                }

                // save the new submission
                $submission = new Submission;
                $submission->song_id = $song_result->id;
                $submission->show_id = $date_result->id;
                $submission->description = Request::input('description');
                $submission->user_id = Auth::user()->id;
                $submission->score = 0;
                $submission->save();
            }

        }

        // redirect back to the song page with the updated version listings
        return Redirect::to('/song/' . Request::input('slug'));
    }


    public function updateVotes()
    {
        // user must be logged in to vote
        if (!Auth::check())
        {
            // return message to user that they must log in
            //  before submitting a vote
            return response()->json('not_logged_in');
        }

        $data = Input::all(); // get all HTTP data

        // make sure the request is ajax
        if (Request::ajax())
        {
            // check if user has already voted on current submission
            $votes = Vote::where('submission_id', '=', $data['submissionId'])
                ->where('user_id', '=', Auth::user()->id)->first();

            if (count($votes))
            {
                if ($votes->vote_type == 'up')
                {
                    if ($data['type'] == 'up')
                    {
                        DB::table('votes')->where('submission_id', '=', $data['submissionId'])
                            ->where('user_id', '=', Auth::user()->id)->delete();
                    }
                    else if ($data['type'] == 'down')
                    {
                        DB::table('votes')->where('submission_id', '=', $data['submissionId'])
                            ->where('user_id', '=', Auth::user()->id)->update(array('vote_type' => 'down'));
                    }
                }
                else if ($votes->vote_type == 'down')
                {
                    if ($data['type'] == 'up')
                    {
                        DB::table('votes')->where('submission_id', '=', $data['submissionId'])
                            ->where('user_id', '=', Auth::user()->id)->update(array('vote_type' => 'up'));
                    }
                    else if ($data['type'] == 'down')
                    {
                        DB::table('votes')->where('submission_id', '=', $data['submissionId'])
                            ->where('user_id', '=', Auth::user()->id)->delete();
                    }
                }
            }
            else
            {
                // No vote was found,
                //  insert a new Vote.
                $vote = new Vote;
                $vote->submission_id = $data['submissionId'];
                $vote->user_id = Auth::user()->id;
                $vote->vote_type = $data['type'];
                $vote->save();
            }

            $up_count = Vote::where('submission_id', '=', $data['submissionId'])
                ->where('vote_type', '=', 'up')->count();

            $down_count = Vote::where('submission_id', '=', $data['submissionId'])
                ->where('vote_type', '=', 'down')->count();

            $count = $up_count - $down_count;

            // update the submission score
            $submission = Submission::where('id', '=', $data['submissionId'])->first();
            $submission->score = $count;
            $submission->save();

            // return the vote count
            return response()->json($count);
        }
    }

    public function songSearch()
    {
        $data = Input::all();

        $song_result = Song::where('title', 'LIKE', $data['search'] . '%')->orderBy('tracks_count', 'desc')->groupBy('title')->get();

        $display_result = array();
        foreach ($song_result as $result)
        {
            $submission_result = Submission::where('song_id', '=', $result['id'])->count();
            $display_result[$result['id']] = array('slug' => $result['slug'],
                                                    'title' => $result['title'],
                                                    'versions' => $submission_result);
        }

        return view('search', compact('display_result'));
    }

    public function doComment()
    {
        $data = Input::all();

        if (!Auth::check())
            return Redirect::to('login')->with('comment_fail', 'Please login in to comment on a song.');

        $comment = new Comment;
        $comment->submission_id = $data['submissionId'];
        $comment->user_id = Auth::user()->id;
        $comment->comment = $data['comment'];
        $comment->save();

        return Redirect::to($data['retVal']);
    }

    public function showComments($slug, $submissionId)
    {
        $song_result = Song::where('slug', '=', $slug)->first();
        if (is_null($song_result))
            App::abort('404');

        $submission_result = Submission::where('id', '=', $submissionId)->first();
        if (is_null($submission_result))
            App::abort('404');

        $user_result = User::where('id', '=', $submission_result->user_id)->first();
        $submission_result['user_image'] = $user_result->image;
        $submission_result['user_name'] = $user_result->username;

        $show_result = Show::where('id', '=', $submission_result->show_id)->first();
        $submission_result['show_date'] = $show_result->date;

        $venue_result = Venue::where('id', '=', $show_result->venue_id)->first();
        $submission_result['venue_name'] = $venue_result->name;
        $submission_result['venue_location'] = $venue_result->location;

        $num_comments = Comment::where('submission_id', '=', $submission_result->id)->count();
        $submission_result['num_comments'] = $num_comments;

        $comments_result = Comment::where('submission_id', '=', $submissionId)->orderBy('created_at', 'asc')->get();

        foreach ($comments_result as $result)
        {
            $comment_user_result = User::where('id', '=', $result->user_id)->first();
            $result['username'] = $comment_user_result->username;
            $result['user_image'] = $comment_user_result->image;
        }

        return view('comments', compact('song_result', 'submission_result', 'comments_result'));
    }

    public function about()
    {
        return view('about');
    }

    public function deleteSubmission($submissionId, $userId, $slug)
    {
        if (!Auth::check())
            App::abort('404');

        if (Auth::user()->id != $userId)
            App::abort('404');

        $submission_result = Submission::find($submissionId);
        if (is_null($submission_result))
            App::abort('404');

        $submission_result->delete();

        return Redirect::to('song/' . $slug);
    }
}
