<table class="table table-bordered">
    <thead>
    <tr>
        <th>original</th>
        <th>uri</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($urls as $item)
        <tr>
            <td>{{ $item->original }}</td>
            <td>{{ $item->uri }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

{!! $urls->render() !!}