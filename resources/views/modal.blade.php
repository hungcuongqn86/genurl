<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div id="myModal-modal-content" class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="uri">Short URL</label>
                    <div class="input-group">
                        <span class="input-group-addon">{{url('/')}}/</span>
                        <input placeholder="Your short URL here" type="text" class="form-control" id="uri">
                        <span class="input-group-btn">
                                <button id="automatically" class="btn btn-default" title="Automatically create a short URL" type="button">Auto</button>
                            </span>
                    </div>
                    <div id="uri_alert" class="val-alert" style="display: none;">
                            <span class="help-block">
                                Not short URL
                            </span>
                    </div>
                </div>
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
            </div>
            <div class="modal-footer">
                <button type="button" id="shorten" class="btn btn-primary">Create</button>
                <button type="button" id="update-url" class="btn btn-primary">Update</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>