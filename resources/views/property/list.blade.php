@if(count($properties) > 0)
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
                    <td>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="agentFor" id="selling{{$property->id}}" value="sell">
                            <label class="form-check-label" for="selling{{$property->id}}">Selling</label>
                        </div>
                    </td>
                    <td>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="agentFor" id="viewing{{$property->id}}" value="view">
                            <label class="form-check-label" for="{{$property->id}}">Viewing</label>
                        </div>
                    </td>
                    <td><input class="btn btn-primary" id="addproperty{{$property->id}}" name="addProperty" type="submit" value="Add" disabled></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@else
    <p class="card-text">No matching results</p>
@endif
