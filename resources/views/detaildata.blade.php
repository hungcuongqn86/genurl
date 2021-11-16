<div class="panel-heading">
    <h1><a class="btn-back"><i class="glyphicon glyphicon-circle-arrow-left"></i></a> Update data for <a
                style="word-wrap: break-word;" href="{{ $urldata->original }}">{{ $urldata->source }}</a></h1>
    <div class="row">
        <div class="col-md-12">
            <p>Created {{$urldata->created}}</p>
            <p>Original URL: <a style="word-wrap: break-word;"
                                href="{{ $urldata->original }}">{{ $urldata->original }}</a></p>
        </div>
    </div>
</div>
<div class="panel-body">
    <div class="form-group">
        <label for="original_url">Original URL</label>
        <input id="original_url" type="url" class="form-control" value="{{ $urldata->original }}"
               placeholder="Your original URL here">
        <div id="original_url_alert" class="val-alert" style="display: none;">
                            <span class="help-block">
                                Not original URL
                            </span>
        </div>
    </div>
    <div class="form-group">
        <label for="title">Title</label>
        <input id="title" type="text" class="form-control" value="{{ $urldata->title }}"
               placeholder="Your Title of links here">
    </div>
    <div class="form-group">
        <label for="description">Description</label>
        <input id="description" type="text" class="form-control" value="{{ $urldata->description }}"
               placeholder="Your Description of links here">
    </div>
    <div class="form-group">
        <label for="image">Image</label>
        <input id="image" type="file" accept="image/*">
    </div>

    <div class="form-group">
        <button type="button" id="shorten" class="btn btn-primary">Update</button>
    </div>

    <table class="table table-hover">
        <thead>
        <tr>
            <th class="col-md-1 hidden-xs col-sm-2">Id</th>
            <th class="col-md-8 col-sm-8 col-xs-9">Short URL</th>
            <th class="col-md-1 col-sm-1 hidden-xs">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($urldata->ShortLinks as $item)
            <tr id="{{$item->id}}" short-url="{{url('/')}}/{{ $item->uri }}"
                class="data-row">
                <td class="col-md-1 hidden-xs col-sm-2">{{ $item->id }}</td>
                <td class="col-md-1 hidden-xs col-sm-2"><a href="{{url('/')}}/{{ $item->uri }}">{{ $item->uri }}</a>
                    <div short-url="{{url('/')}}/{{ $item->uri }}" class="open-action copy-short-url" title="Copy short URL"><a class="action"><i class="glyphicon glyphicon-duplicate"></i></a></div>
                </td>
                <td class="col-md-1 col-sm-1 hidden-xs">
                    <div class="open-action dropdown">
                        <a class="action" data-toggle="dropdown"><i class="glyphicon glyphicon-option-vertical"></i></a>
                        <ul class="dropdown-menu dropdown-menu-right" role="menu">
                            <li class="analytics-data"><a class="a-analytics" href="{{url('/')}}/analytics/{{ $item->uri }}/all_time">Analytics Data</a></li>
                            <li class="delete-url"><a href="#">Delete Link</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="form-group">
        <label for="uri">More short links</label>
        <div class="input-group">
            <span class="input-group-addon">Number of links</span>
            <input placeholder="Your short URL here" type="number" min="1" max="50" class="form-control" id="count">
            <span class="input-group-btn">
                                <button id="automatically" class="btn btn-default" title="Automatically create a short URL" type="button">Add</button>
                            </span>
        </div>
    </div>
</div>