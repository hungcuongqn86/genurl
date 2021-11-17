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
    <form method="POST" id="updateurlFrm" name="updateurlFrm" enctype="multipart/form-data">
        <input type="hidden" id="id" name="id" value="{{ $urldata->id }}">

        <div class="form-group">
            <label for="original_url">Original URL</label>
            <input id="original_url" name="original_url" type="url" class="form-control" value="{{ $urldata->original }}"
                   placeholder="Your original URL here">
            <div id="original_url_alert" class="val-alert" style="display: none;">
                                <span class="help-block">
                                    Not original URL
                                </span>
            </div>
        </div>
        <div class="form-group">
            <label for="title">Title</label>
            <input id="title" name="title" type="text" class="form-control" value="{{ $urldata->title }}"
                   placeholder="Your Title of links here">
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <input id="description" name="description" type="text" class="form-control" value="{{ $urldata->description }}"
                   placeholder="Your Description of links here">
        </div>
        <div class="form-group">
            <label for="image">Image</label>
            <img style="max-width: 80px; max-height: 80px; margin: 5px 0;" src="{{ URL::asset('images/') }}/{{ $urldata->image }}" class="img-responsive" alt="{{ $urldata->image }}">
            <input id="image" name="image" type="file" accept="image/*">
        </div>

        <div class="form-group">
            <button type="button" id="updateUrlBtn" class="btn btn-primary">Update</button>
        </div>
    </form>
    <table id="LinkList" class="table table-hover">
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
                <td class="col-md-1 hidden-xs col-sm-2">
                    <input id="uri" name="uri" type="text" style="width: 120px; display: inherit;" class="form-control sort-link-uri" value="{{ $item->uri }}" placeholder="Your URI here">
                    <div short-url="{{url('/')}}/{{ $item->uri }}" class="open-action copy-short-url" title="Copy short URL"><a class="action"><i class="glyphicon glyphicon-duplicate"></i></a></div>
                </td>
                <td class="col-md-1 col-sm-1 hidden-xs">
                    <div class="open-action dropdown">
                        <a class="action" data-toggle="dropdown"><i class="glyphicon glyphicon-option-vertical"></i></a>
                        <ul class="dropdown-menu dropdown-menu-right" role="menu">
                            <li class="delete-link"><a href="#">Delete Link</a></li>
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
            <input placeholder="Your Number short URL here" type="number" min="1" max="50" class="form-control" id="count" name="count">
            <span class="input-group-btn">
                                <button id="addLink" class="btn btn-default" title="Create short URL" type="button">Add</button>
                            </span>
        </div>
    </div>
</div>
