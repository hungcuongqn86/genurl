@if(count($urls) > 0)
    <table class="table table-hover">
        <thead>
        <tr>
            <th class="col-md-8 col-sm-8 col-xs-9">Original URL</th>
            <th class="col-md-2 hidden-xs hidden-sm">Created</th>
            <th class="col-md-1 hidden-xs col-sm-2">Short URL</th>
            <th class="col-md-1 col-sm-2 col-xs-3">All Clicks</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($urls as $item)
            <tr id="{{$item->id}}" short-url="{{url('/')}}/{{ $item->uri }}" original-url="{{ $item->original }}" class="data-row">
                <td class="col-md-8 col-sm-8 col-xs-9"><a href="{{ $item->original }}">{{ $item->source }}</a></td>
                <td class="col-md-2 hidden-xs hidden-sm">{{ $item->created }}</td>
                <td class="col-md-1 hidden-xs col-sm-2"><a href="{{url('/')}}/{{ $item->uri }}">{{ $item->uri }}</a></td>
                <td class="col-md-1 col-sm-2 col-xs-3"><a class="a-analytics" href="{{url('/')}}/analytics/{{ $item->uri }}/all_time">{{ sizeof($item->Logs) }}</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="pull-right">
        {!! $urls->render() !!}
    </div>
@endif