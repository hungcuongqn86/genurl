<table class="table table-bordered">
    <thead>
    <tr>
        <th>Original URL</th>
        <th>Short URL</th>
        <th>Created</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($urls as $item)
        <tr>
            <td><a href="{{ $item->original }}">{{ $item->original }}</a></td>
            <td><a href="{{url('/')}}/{{ $item->uri }}">{{url('/')}}/{{ $item->uri }}</a></td>
            <td>{{ $item->created_at }}</td>
            <td>{{ $item->status }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
<div class="pull-right">
    {!! $urls->render() !!}
</div>