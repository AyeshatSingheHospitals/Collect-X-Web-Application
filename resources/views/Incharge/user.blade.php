@extends('incharge.navbar')

@section('content')

<div class="container mt-10">
    <div class="row">
        <div class="col-md-12 text-end">
            <!-- Button to trigger modal for registering new users -->
            <button type="button" class="btn btn-success btn-lg btn-gradient" data-bs-toggle="modal"
                data-bs-target="#registerUserModal">
                <i class="fas fa-plus"></i>
            </button>
        </div>

        <div class="col-md-12 mt-3">
            <!-- Display Success Message -->
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- Display Error Message -->
            @if(Session::has('error'))
            <div class="alert alert-danger">{{ Session::get('error') }}</div>
            @endif

            <!-- Validation Errors -->
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Registered Users Section -->
            <br>
            <h1 class="gradient-heading">
                <b>Registered Users</b>
            </h1><br>

            <!-- Table or content for registered users would go here -->

        </div>
    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Styles for Cards and Buttons -->
<style>
    .gradient-heading {
        background: linear-gradient( #6e8efb, #a77bff);
        -webkit-background-clip: text;
        color: transparent;
    }

    .card {
        border-radius: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transition: transform 0.2s;
        position: relative;
        overflow: hidden;
    }

    .card:hover {
        transform: translateY(-2px);
    }

    .overlay {
        height: 100%;
        width: 100%;
        background: linear-gradient(transparent, rgba(28, 28, 28, 0.58));
        border-radius: 15px;
        position: absolute;
        left: 0;
        bottom: 0;
        display: none;
    }

    .card:hover .overlay {
        display: block;
    }

    /* Button Gradient */
    .btn-gradient {
        background: linear-gradient(to bottom right, #8A3FFC, #00BFFF);
        border: none;
        color: white;
    }

    .btn-gradient:hover {
        background: linear-gradient(to bottom right, #FFB6C1, #FF69B4);
    }
</style>

@endsection
