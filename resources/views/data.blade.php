@if(count($urls) > 0)
    <table class="table table-hover">
        <thead>
        <tr>
            <th>Original URL</th>
            <th>Short URL</th>
            <th>Created</th>
            <th>All Clicks</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($urls as $item)
            <tr>
                <td><a href="{{ $item->original }}">{{ $item->source }}</a></td>
                <td><a href="{{url('/')}}/{{ $item->uri }}">{{ $item->uri }}</a></td>
                <td>{{ $item->created }}</td>
                <td></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="pull-right">
        {!! $urls->render() !!}
    </div>
@endif