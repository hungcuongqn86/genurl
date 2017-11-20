@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>Simplify your links</strong></div>

                    <div class="panel-body">
                        <form>
                            <div class="row">
                                <div class="col-md-8 col-sm-10 col-xs-12" style="margin-bottom: 15px">
                                    <div class="input-group">
                                        <input id="original_url" type="url" class="form-control" placeholder="Your original URL here">
                                        <span class="input-group-btn">
                                            <button type="button" id="shorten" class="btn btn-primary">Shorten URL</button>
                                        </span>
                                    </div>
                                </div>
                                <div id="div-alert" style="display: none;" class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="alert alert-danger">Not original URL</div>
                                </div>
                            </div>
                        </form>

                        <h2>Items Data</h2>
                        <div id="item-lists">
                            @include('data')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
