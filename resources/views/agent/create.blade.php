@extends('layouts.app')

@section('contents')
    <ul class="nav no-bullets">
        <li><a href="{{ url('/') }}">Back to Home</a></li>
    </ul>
    <div class="container">
        <div class="content">
            <p class="heading">Create a new agent</p>

            <div class="invalidAlert alert-danger">Invalid data! please try again</div>
            <div class="errorAlert alert-danger">Something went wrong! please try again</div>
            <div class="successAlert alert-success">Agent added!</div>
        </div>
            <form id = "agentForm" onsubmit="return false">
                @csrf

                <div class="form-group row">
                    <label for="firstName" class="col-sm-2 col-form-label">FirstName:</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="firstName" id="firstName" minlength="3" required>
                        <div class="firstNameValidation text-danger">
                            Please choose a valid first name.
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="lastName" class="col-sm-2 col-form-label">LastName:</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="lastName" id="lastName" minlength="3" required>
                        <div class="lastNameValidation text-danger">
                            Please choose a valid last name.
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="email" class="col-sm-2 col-form-label">Email address:</label>
                    <div class="col-sm-6">
                        <input type="email" class="form-control" name="email" id="email" required>
                        <div class="emailValidation text-danger">
                            Please choose a valid email.
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="phone" class="col-sm-2 col-form-label">Phone:</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="phone" id="phone" required>
                        <div class="phoneValidation text-danger">
                            Please choose a valid phone number.
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="address" class="col-sm-2 col-form-label">Address:</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="address" id="address" required>
                        <div class="addressValidation text-danger">
                            Please choose a valid address.
                        </div>
                    </div>
                </div>

                <input class="btn btn-primary" id="createAgent" type="submit" value="Create Agent">
            </form>
        </div>

@endsection

@section('script')
    <script src="{{ asset('/js/create.js') }}"></script>
@endsection
