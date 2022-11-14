@if(count($agents) > 0)
    <div>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">First Name</th>
                <th scope="col">Last Name</th>
                <th scope="col">Email</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($agents as $agent)
                <tr>
                    <th id="agentId" scope="row"><a id="agentItem" href="{{ url('/api/agents', ['id' => $agent->id]) }}">{{$agent->id}}</a></th>
                    <td>{{$agent->first_name}}</td>
                    <td>{{$agent->last_name}}</td>
                    <td>{{$agent->email}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@else
    No Results
@endif
