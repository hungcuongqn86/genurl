@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h1>Analytics data for <a href="{{url('/')}}/{{ $urldata->uri }}">{{url('/')}}/{{ $urldata->uri }}</a></h1>
                        <div class="row">
                            <div class="col-md-10">
                                <p>Created {{$urldata->created}}</p>
                                <p>Original URL: <a href="{{ $urldata->original }}">{{ $urldata->source }}</a></p>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="timeframe">Timeframe</label>
                                    <select class="form-control" id="timeframe">
                                        <option id="two_hours" value="two_hours">two hours</option>
                                        <option id="day" value="day">day</option>
                                        <option id="week" value="week">week</option>
                                        <option id="month" value="month">month</option>
                                        <option id="all_time" value="all_time">all time</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div id="anldata">
                            @include('anldata')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
