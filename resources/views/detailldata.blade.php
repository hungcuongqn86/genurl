<div class="panel-heading">
    <h1><a class="btn-back"><i class="glyphicon glyphicon-circle-arrow-left"></i></a> Analytics data for <a
                style="word-wrap: break-word;" href="{{url('/')}}/{{ $urldata->uri }}">{{url('/')}}/{{ $urldata->uri }}</a></h1>
    <div class="row">
        <div class="col-md-10">
            <p>Created {{$urldata->created}}</p>
            <p>Original URL: <a style="word-wrap: break-word;"
                                href="{{ $urldata->original }}">{{ $urldata->source }}</a></p>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="timeframe">Timeframe</label>
                <select class="form-control" id="timeframe">
                    <option id="two_hours" {{($time==='two_hours')?'selected':''}} value="{{url('/')}}/analytics/{{ $urldata->uri }}/two_hours">two hours</option>
                    <option id="day" {{($time==='day')?'selected':''}} value="{{url('/')}}/analytics/{{ $urldata->uri }}/day">day</option>
                    <option id="week" {{($time==='week')?'selected':''}} value="{{url('/')}}/analytics/{{ $urldata->uri }}/week">week</option>
                    <option id="month" {{($time==='month')?'selected':''}} value="{{url('/')}}/analytics/{{ $urldata->uri }}/month">month</option>
                    <option id="all_time" {{($time==='all_time')?'selected':''}} value="{{url('/')}}/analytics/{{ $urldata->uri }}/all_time">all time</option>
                </select>
            </div>
        </div>
    </div>
</div>
<div class="panel-body">
    <div id="anldata">
        <div class="total-clicks text-center">Total Clicks
            <div class="count">{{ sizeof($urldata->Logs) }}</div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Country</h4>
                    </div>
                    <div class="panel-body">
                        @foreach ($cl_country as $key => $item)
                            <p>{{$key?$key:'unknown'}}: {{$item}}</p>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Referer</h4>
                    </div>
                    <div class="panel-body">
                        @foreach ($cl_referer as $key => $item)
                            <p>{{$key}}: {{$item}}</p>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Device type</h4>
                    </div>
                    <div class="panel-body">
                        @foreach ($cl_device_type as $key => $item)
                            <p>{{$key==1?'Desktop':($key==2?'Phone':($key==3?'Robot':''))}}: {{$item}}</p>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Device</h4>
                    </div>
                    <div class="panel-body">
                        @foreach ($cl_device as $key => $item)
                            <p>{{$key}}: {{$item}}</p>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Platform</h4>
                    </div>
                    <div class="panel-body">
                        @foreach ($cl_platform as $key => $item)
                            <p>{{$key}}: {{$item}}</p>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Browser</h4>
                    </div>
                    <div class="panel-body">
                        @foreach ($cl_browser as $key => $item)
                            <p>{{$key}}: {{$item}}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>