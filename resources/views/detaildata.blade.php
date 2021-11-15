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
        <input id="original_url" type="url" class="form-control"
               placeholder="Your original URL here">
        <div id="original_url_alert" class="val-alert" style="display: none;">
                            <span class="help-block">
                                Not original URL
                            </span>
        </div>
    </div>
    <div class="form-group">
        <label for="title">Title</label>
        <input id="title" type="text" class="form-control"
               placeholder="Your Title of links here">
    </div>
    <div class="form-group">
        <label for="description">Description</label>
        <input id="description" type="text" class="form-control"
               placeholder="Your Description of links here">
    </div>
    <div class="form-group">
        <label for="image">Image</label>
        <input id="image" type="file" accept="image/*">
    </div>
</div>
