<div class="panel-heading">
    <h1><a class="btn-back"><i class="glyphicon glyphicon-circle-arrow-left"></i></a> Url data for <a
                style="word-wrap: break-word;" href="{{url('/')}}/{{ $urldata->uri }}">{{url('/')}}/{{ $urldata->uri }}</a></h1>
    <div class="row">
        <div class="col-md-12">
            <p>Created {{$urldata->created}}</p>
            <p>Original URL: <a style="word-wrap: break-word;"
                                href="{{ $urldata->original }}">{{ $urldata->source }}</a></p>
        </div>
    </div>
</div>
<div class="panel-body">

</div>
