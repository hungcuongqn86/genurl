@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <button type="button" id="create-new" class="btn btn-primary">Create new</button>
                    </div>
                    <div class="panel-body">
                        <div id="item-lists">
                            @include('data')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Create shorten URL</h4>
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
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
