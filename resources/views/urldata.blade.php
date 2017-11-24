@if(count($urls) > 0)
    <table class="table table-hover">
        <thead>
        <tr>
            <th class="col-md-8 col-sm-8 col-xs-9">Original URL</th>
            <th class="col-md-2 hidden-xs hidden-sm">Created</th>
            <th class="col-md-1 hidden-xs col-sm-2">Short URL</th>
            <th class="col-md-1 col-sm-2 col-xs-3">All Clicks</th>
            <th class="col-md-1 col-sm-1 hidden-xs"></th>
        </tr>
        </thead>
        <tbody>
        @foreach ($urls as $item)
            <tr id="{{$item->id}}" short-url="{{url('/')}}/{{ $item->uri }}" original-url="{{ $item->original }}"
                class="data-row">
                <td class="col-md-8 col-sm-8 col-xs-9 long-url"><a href="{{ $item->original }}">{{ $item->source }}</a>
                </td>
                <td class="col-md-2 hidden-xs hidden-sm">{{ $item->created }}</td>
                <td class="col-md-1 hidden-xs col-sm-2"><a href="{{url('/')}}/{{ $item->uri }}">{{ $item->uri }}</a>
                </td>
                <td class="col-md-1 col-sm-2 col-xs-3"><a class="a-analytics"
                                                          href="{{url('/')}}/analytics/{{ $item->uri }}/all_time">{{ sizeof($item->Logs) }}</a>
                </td>
                <td class="col-md-1 col-sm-1 hidden-xs">
                    <div class="open-action dropdown">
                        <a class="action" data-toggle="dropdown"><i class="glyphicon glyphicon-option-vertical"></i></a>
                        <ul class="dropdown-menu dropdown-menu-right" role="menu">
                            <li class="analytics-data"><a class="a-analytics" href="{{url('/')}}/analytics/{{ $item->uri }}/all_time">Analytics Data</a></li>
                            <li class="edit-url"><a href="#">Edit URL</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="pull-right">
        {!! $urls->render() !!}
    </div>
@endif