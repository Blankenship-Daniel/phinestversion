@extends('app')
@section('content')

    <?php
            $i = 0;
            foreach ($year_rankings as $key => $val)
            {
                $y = substr($key, -2);

                if ($i % 3 == 0 && $i == 0)
                    echo '<div class="row">';
                else if ($i % 3 == 0 && $i != 0)
                    echo '</div><div class="row">';

                echo '<div class="col-md-4 year-icon-wrapper">' .
                        '<div class="year-icon p' . $y . '">' .
                                    '<div class="rank">' . ++$i . '</div>' .
                                    '<a href="/year/' . $key . '"><div class="year-icon-text">' . $y . '</div></a>' .
                                '</div>' .
                        '</div>';
            }
    ?>

@stop
