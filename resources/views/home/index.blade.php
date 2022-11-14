@extends('layouts.app')

@section('contents')
    <div class="container">
        <div class="d-flex p-2">
            <h1 class="col-md-8 text-center">Property - Admin</h1>
        </div>
        <div class="col-md-8 d-flex p-4">
            <a href="{{ url('/create-agent') }}" class="btn btn-primary text-center" id="agentCreate">Create Agent</a>
        </div>

        <div class="input-group col-md-8">
            <div class="form-outline">
                <input type="search" class="form-control" id="searchInput">
            </div>
            <input type="submit" class="btn btn-primary" id="searchAgent" value="Search" disabled>
        </div>
        <div id="agentList" class="d-flex p-4"></div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('/js/home.js') }}"></script>
@endsection
