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
                    <p>{{$key}}: {{$item}}</p>
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
                    <p>{{$key}}: {{$item}}</p>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-md-2"><div class="panel panel-default">
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