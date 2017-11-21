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
                <td><a href="{{ $item->original }}">{{ $item->original }}</a></td>
                <td><a href="{{url('/')}}/{{ $item->uri }}">{{url('/')}}/{{ $item->uri }}</a></td>
                <td>{{ $item->created_at }}</td>
                <td></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="pull-right">
        {!! $urls->render() !!}
    </div>
@endif