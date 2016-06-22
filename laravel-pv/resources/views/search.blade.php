@extends('app')
@section('content')

    <div class="row">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Song</th>
                <th>Versions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($display_result as $key => $val)

                <tr>
                    <td>
                        <a class="link" href="/song/{{ $val['slug'] }}">{{ $val['title']  }}</a>
                    </td>
                    <td>{{ $val['versions'] }}</td>
                </tr>

            @endforeach
            </tbody>
        </table>
    </div>

@stop