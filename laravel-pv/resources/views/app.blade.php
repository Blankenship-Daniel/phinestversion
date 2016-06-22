<?php use Illuminate\Support\Facades\Session; ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>phinest version</title>
        <meta name="csrf-token" id="_token" content="{{ csrf_token() }}" />
        <link href='https://fonts.googleapis.com/css?family=Quicksand' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=EB+Garamond' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link rel="stylesheet" href="/css/main.css">
        <script rel="text/javascript" src="/js/jquery-2.1.4.min.js"></script>
        <script rel="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <script rel="text/javascript" src="/js/script.js"></script>


        @if (Request::is('songs'))

            <link rel="stylesheet" href="/css/songs.css">

        @elseif (Request::is('shows'))

            <link rel="stylesheet" href="/css/shows.css">

        @elseif (Request::is('years'))

            <link rel="stylesheet" href="/css/years.css">

        @elseif (Request::is('login'))

            <link rel="stylesheet" href="/css/login.css">

        @endif
    
        <?php

                // these are the possible uids that could come
                //  through paid search.     
                $search_uids = [
                                    'ppc',
                                    'seo',
                                    'agg',
                                    'prt',
                                    'web',
                                    'ref'
                                            ];

                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }

                // set the referring site if not already set.
                if (!isset($_SESSION['ref_site'])) {
                    $_SESSION['ref_site'] = (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');
                }

                // grab the uid to establish the type of paid search.
                if (isset($_REQUEST['uid']) && $_REQUEST['uid'] != '') {

                    // grabs the type. Such as [ppc, seo, agg...] etc.       
                    $uidShort = substr($_REQUEST['uid'], 0, 3); 

                    if (in_array($uidShort, $search_uids)) {

                    }
                }
        ?>

    </head>
    <body>

        <!-- Google Analytics -->

        <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-71040730-1', 'auto');
        ga('send', 'pageview');
        </script> 

        <!-- End Google Analytics -->

         <header>
            <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <!--<a href="/"><img id="phish_logo" src="/img/phish.png" width="60"></a>-->
                        <span id="logo">phinest version</span>
                    </div>

                    <div id="navbar" class="navbar-collapse collapse">
                        <ul class="nav navbar-nav">
                            <li class="home"><a href="/"><h4>Home</h4></a></li>
                            <li class="songs"><a href="/songs"><h4>Songs</h4></a></li>
                            <li class="shows"><a href="/shows"><h4>Shows</h4></a></li>
                            <li class="years"><a href="/years"><h4>Years</h4></a></li>

                            @if (Session::has('username'))

                                <li class="logout"><a href="/logout"><h4>Logout</h4></a></li>

                            @else

                                <li class="logout"><a href="/login"><h4>Login</h4></a></li>

                            @endif

                            <li class="search">
                                {!! Form::open(array('url' => 'song-search', 'class' => 'form-bootstrap search-form')) !!}
                                {!! Form::text('search', null, array('placeholder' => 'Search for a song...', 'class' => 'form-control search')) !!}
                                {!! Form::submit('Go', array('class' => 'search-submit')) !!}
                                {!! Form::close() !!}
                            </li>
                        </ul>
                        @if (Session::has('username'))
                            <div id="user-image">
                                <img src="{{ Auth::user()->image }}" width="75">
                            </div>
                        @endif
                    </div>

                </div>


            </nav>
        </header>
        <div class="content">

            @yield('content')
            
        </div>
    </body>
</html>
