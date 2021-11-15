<div id="genUrlModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div id="myModal-modal-content" class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
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

                <div class="form-group">
                    <label for="count">Number of links</label>
                    <input id="count" type="number" class="form-control" value="5" max="50" min="1"
                           placeholder="Your Number of links here">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="shorten" class="btn btn-primary">Create</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
