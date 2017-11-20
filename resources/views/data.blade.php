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
            <td>{{ $item->original }}</td>
            <td>{{url('/')}}/{{ $item->uri }}</td>
            <td>{{ $item->created_at }}</td>
            <td>{{ $item->status }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

{!! $urls->render() !!}