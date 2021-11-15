@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div id="list-conten" class="panel panel-default" style="display: none;">
                    <div class="panel-heading">
                        <button type="button" id="create-new" class="btn btn-primary">Create new</button>
                    </div>
                    <div class="panel-body">
                        <div id="item-lists">
                        </div>
                    </div>
                </div>

                <div style="display: none;" id="detail-conten" class="panel panel-default">
                    @include('detailldata')
                </div>

                <div id="analytics-conten" class="panel panel-default">
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    @include('modal')
@endsection
