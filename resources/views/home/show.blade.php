@extends('application.base')

@section('content')
    @component ('application.components.view-heading')
        @slot ('currentPage')
            v3
        @endslot
        @slot ('icon')
            <i class="pe page-header-icon pe-7s-shield"></i>
        @endslot
        @slot ('heading')
            AOD Tracker
        @endslot
        @slot ('subheading')
            Manage divisions and members within the AOD organization
        @endslot
    @endcomponent

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="my-division">
                    @include('home.partials.my-division')
                </div>
            </div>
        </div>

        <div class="row m-t-xl">
            <div class="col-lg-12">
                <h4 class="m-b-xs text-uppercase">Navigate
                    <small>All Divisions</small>
                </h4>
                <hr>
            </div>
        </div>

        <div class="row divisions">
            @include('home.partials.divisions')
        </div>
    </div>
@stop
