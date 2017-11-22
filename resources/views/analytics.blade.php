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
                            @include('anldata')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
