@extends('layouts.app')

@section('contents')
    <ul class="nav no-bullets">
        <li><a href="{{ url('/') }}">Back to Home</a></li>
    </ul>

    <div class="card">
        <h5 class="card-header">{{ $agent->first_name }} {{ $agent->last_name }}</h5>
        <div class="card-body">
            <p class="card-text">Email: {{ $agent->email }}</p>
            <p class="card-text">Phone: {{ $agent->phone }}</p>
            <p class="card-text">Address: {{ $agent->address }}</p>
            @if(count($properties) > 0)
                <p class="card-text">Current Properties:</p>
                <div>
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Address</th>
                            <th scope="col">Property Type</th>
                            <th scope="col">Country</th>
                            <th scope="col">Price</th>
                            <th scope="col">Type</th>
                            <th scope="col">Agent For</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($properties as $property)
                            <tr>
                                <th scope="row">{{$property->id}}</th>
                                <td>{{$property->address}}</td>
                                <td>{{$property->property_type->title}}</td>
                                <td>{{$property->country}}</td>
                                <td>{{$property->price}}</td>
                                <td>{{$property->for_sale == 1 ? "Sale" : "Rent" }}</td>
                                <td>{{$property->pivot->view == 1 ? "View" : "Sell" }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="card-text">Current Properties: None</p>
            @endif
        </div>
    </div>

    {{--Add new property--}}
    <div class="card">
        <div class="card-body">
            <p class="card-text">Add new property</p>
            <div class="input-group col-md-8">
                <div class="form-outline">
                    <input type="search" class="form-control" id="searchInput" placeholder="Search by address..">
                </div>
                <input type="submit" class="btn btn-primary" id="searchProperty" value="Search" disabled>
            </div>
            <br>
            <div class="agentExists text-danger col" style="display:none">This property already has an agent for viewing</div>
            <div id="propertyList" class="d-flex p-4"></div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('/js/view.js') }}"></script>
    <script src="{{ asset('/js/list.js') }}"></script>
@endsection
