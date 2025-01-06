@extends('admin.sidebar')

@section('content')

<!-- Registration Form -->
<main>
    <div class="top-bar">
        <h1>Laboratory Create</h1>

    </div>
    <br>
    <!-- Button to trigger the pop-up -->
    <div class="row">

        <div class="text-left mb-3">
            <button id="openPopup" class="glow-on-hover btn btn-info rounded-pill" type="button">
                <i class="fas fa-info-circle me-2"></i> Add
            </button>
        </div>
        <div class="search-container">
            <input type="text" id="searchInput" class="form-control" placeholder="Search..." onkeyup="filterLabs()" />

        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <br>
    <br>
    <!-- Display list of routes in card format -->

    @if($labs->isEmpty())
    <div class="col-12 mt-5 center-align-container">
        <!-- Display the image -->
        <img src="{{ asset('../image/Motion.gif') }}" alt="No Records" class="img-fluid mb-3"
            style="max-width: 300px; border-radius:50%;">
        <!-- Display the "No record here" message -->
        <h4 class="text-muted">No record here</h4>
    </div>

    @else
    <div class="raw">
        @foreach($labs as $lab)

        <div class="col-md-4">
            <div class="card mb-3 custom-card">
                <div class="card-body">
                    <h5 class="card-title">{{ $lab->name }}</h5>
                    <p class="card-text"> {{ $lab->address }}</p>
                    <div class="btn-container">
                        <!-- Edit Button -->
                        <button class="btn edit-btn" onclick="openEditModal({{ $lab }})">
                            <i class='bx bxs-message-square-edit'></i>
                        </button>

                        <!-- Delete Button -->
                        <form action="{{ route('admin.lab.destroy', $lab->lid) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Are you sure you want to delete this Laboratory?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn delete-btn" type="submit"><i
                                    class='bx bx-message-square-x'></i></i></button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        @endforeach
        @endif
    </div>

    <!-- Edit Modal (hidden by default) -->
    <div id="editModal" class="popup" style="display: none;">
        <div class="popup-content">
            <span class="close" onclick="closeEditModal()">&times;</span>

            <!-- Edit Form -->
            <form id="editForm" method="POST" action="{{ route('admin.labs.store') }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="lid" id="lid">

                <!-- <div class="form-group">
                    <label  class="form-label" for="editUid" class="form-label">UID: </label>
                    <input type="text" name="uid" class="form-control rounded-pill" value="{{ session('usernme') }}" readonly required>
                </div>

                <input type="hidden" name="uid" value="{{ session('uid') }}"> -->

                <div class="form-group">
                    <label class="form-label" for="editUid" class="form-label"> UID:</label>
                    <input type="text" name="uid" class="form-control rounded-pill" value="{{ session('username') }}"
                        readonly required />
                </div>

                <input type="hidden" name="uid" value="{{ session('uid') }}">


                <div class="form-group">
                    <label for="name">Laboratory Name</label>
                    <input type="text" name="name" id="name" class="form-control rounded-pill" required>
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" name="address" id="address" class="form-control rounded-pill" required>
                </div>
                <br>

                <button type="submit" class="btn btn-success rounded-pill">Update</button>
            </form>
        </div>
    </div>

    <!-- The pop-up (initially hidden) -->
    <div id="popup" class="popup">
        <div class="popup-content">
            <span class="close" id="closePopup">&times;</span>

            <!-- The form inside the pop-up -->
            <form action="{{ route('admin.labs.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="uid">UID: </label>
                    <input type="text" name="uid" id="uid" class="form-control" value="{{ session('username') }}"
                        required>
                </div>

                <!-- Hidden field for uid -->
                <input type="hidden" name="uid" value="{{ session('uid') }}">

                <div class="form-group">
                    <label for="name">Laboratory Name</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" name="address" id="address" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Create Laboratory</button>
            </form>
        </div>
    </div>

</main>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');


    .center-align-container {
        display: flex;
        justify-content: center;
        /* Horizontally center */
        align-items: center;
        /* Vertically center */
        text-align: center;
        /* Align the text in the center */
        flex-direction: column;
        /* Stack the image and text vertically */
        padding-top: 6%;
    }


    .content {
        margin-left: 90px;
    }

    .alert {
        padding: 15px;
        margin: 20px 0;
        border-radius: 5px;
        font-size: 16px;
        font-weight: 500;
        position: relative;
        opacity: 0.95;
        transition: opacity 0.3s ease-in-out;
    }

    .alert-success {
        background-color: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
    }

    .alert-danger {
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
    }

    .alert ul {
        margin: 0;
        padding-left: 20px;
    }

    .alert ul li {
        list-style-type: disc;
    }

    /* Optional close button styles */
    .alert::after {
        content: "×";
        position: absolute;
        top: 10px;
        right: 15px;
        cursor: pointer;
        font-size: 20px;
        color: inherit;
    }

    .alert:hover {
        opacity: 1;
    }

    .alert-success:hover {
        border-color: #98c68a;
        background-color: #c3e6cb;
    }

    .alert-danger:hover {
        border-color: #f1a2a5;
        background-color: #f5c6cb;
    }

    * {
        margin: 0;
        padding: 0;
        outline: 0;
        appearance: 0;
        border: 0;
        text-decoration: none;
        box-sizing: border-box;
    }


    h1 {
        font-weight: 800;
        font-size: 1.8rem;
    }

    p {
        color: var(--color-dark-variant);
    }

    b {
        color: var(--color-dark);
    }

    .text-muted {
        color: var(--color-info-dark);
    }

    .container {
        display: grid;
        width: 96%;
        margin: 0 auto;
        gap: 1.8rem;
        grid-template-columns: 16rem auto 6rem;
    }

    main {
        margin-top: 1.4rem;
    }

    main .analyse {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.6rem;
    }

    main .analyse>div {
        background-color: var(--color-white);
        padding: var(--card-padding);
        border-radius: var(--card-border-radius);
        margin-top: 1rem;
        box-shadow: var(--box-shadow);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    main .analyse>div:hover {
        box-shadow: none;
    }

    main .analyse>div .status {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    main .analyse h3 {
        margin-left: 0.6rem;
        font-size: 1rem;
    }

    main .analyse .progresss {
        position: relative;
        width: 92px;
        height: 92px;
        border-radius: 50%;
    }

    #searchInput {
        width: 350px;
        height: 50px;
        /* margin-left: 340px; */
    }

    .search-container {
        margin-left: 59%;
        flex: 1;
    }

    /* Container for the cards */
    .row {
        display: flex;
        flex-wrap: nowrap;
        /* flex-wrap: wrap; */
        /* justify-content: space-between; */
        gap: 30px;
    }

    .raw {
        display: flex;
        /* flex-wrap: nowrap;   */
        flex-wrap: wrap;
        /* justify-content: space-between; */
        gap: 25px;
    }

    /* Flex to ensure 5 cards in a row on large screens */
    .col-md-4 {
        flex: 0 0 18%;
        /* Adjust to fit 5 cards */
        max-width: 18%;
        margin-bottom: 20px;
    }

    /* Card styles to match the fluid, gradient look */
    .custom-card {
        border: none;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background-color: var(--card-white);
        border-radius: var(--card-border-radius);
        box-shadow: var(--box-shadow);
        height: 230px;
    }

    .custom-card:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    /* Card body content styling */
    .card-body {
        text-align: center;
        padding: 30px;
        color: #464646;
        /* White text for contrast */
    }

    /* Title and text styles */
    .card-title {
        font-size: 1.5em;
        font-weight: bold;
        margin-bottom: 15px;
        color: var(--color-dark);
    }

    .card-text {
        font-size: 1em;
        margin-bottom: 10px;
        color: var(--color-dark);
    }



    .custom-card .btn-container {
        position: relative;
        bottom: -15px;
        /* right: -2px; */
        display: flex;
    }

    .custom-card .btn {
        background: transparent;
        border: none;
        cursor: pointer;
        padding: 0;
        margin: 0;
    }

    .custom-card .btn i {
        font-size: 20px;
        color: #628ECB;

    }


    .custom-card .btn-container .btn.delete-btn i {
        color: #628ECB;
    }


    .custom-card .btn-container .btn.delete-btn:hover i,
    .custom-card .btn-container .btn.edit-btn:hover i {

        transform: scale(1.05);


    }

    /* Wrapper around buttons for inline display */
    .card-body .btn-container {
        display: flex;
        justify-content: center;
        gap: 10px;
    }

    /* pop upButton Styling */
    button {
        padding: 12px 25px;
        border: none;
        border-radius: 50px;
        background-color: #628ECB;
        color: white;
        font-size: 1rem;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #365485;
    }

    button:active {
        background-color: #0c5e48;
    }

    h1 {
        margin: 0;
        font-size: 24px;
    }

    .popup {
        display: none;
        /* Hidden by default */
        position: fixed;
        z-index: 1000;
        /* Sit on top */
        left: 0;
        top: 0;
        width: 100%;
        /* Full width */
        height: 100%;
        /* Full height */
        background-color: rgba(0, 0, 0, 0.5);
        /* Black w/ opacity */
    }

    .popup-content {
        background-color: #fff;
        margin: 15% auto;
        /* 15% from the top and centered */
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        /* Could be more or less, depending on screen size */
        position: relative;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    .glow-on-hover {
        background-color: #628ECB;
        color: white;
        border: none;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
        transition: background-color 0.3s, box-shadow 0.3s;
        border-radius: 30px;

    }

    .glow-on-hover:hover {
        background-color: #365485;

    }


    /* Popup styles */
    .popup {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .popup-content {
        background-color: #fefefe;
        margin: 15% auto;
        /* 15% from the top and centered */
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        /* Could be more or less, depending on screen size */
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    .dark-mode-active {
        background-color: #181a1e;
        /* Dark background */
        color: #edeffd;
        /* Light text color */
    }

    .dark-mode-active .popup-content {
        background-color: #202528;
        /* Darker background for popup */
        color: #edeffd;
        /* Light text color for popup */
    }

    .dark-mode-active .form-control {
        background-color: #3c4146;
        /* Darker input background */
        color: #edeffd;
        /* Light input text */
        border: 1px solid #677483;
        /* Optional: border for inputs */
    }

    .dark-mode-active .form-control::placeholder {
        color: #a3bdcc;
        /* Light placeholder text color */
    }

    .dark-mode-active .form-check-input {
        background-color: #3c4146;
        /* Dark checkbox background */
        border: 1px solid #677483;
        /* Optional: border for checkboxes */
    }



    /* Popup Styling */
    .popup {
        display: none;
        position: fixed;
        z-index: 999;
        left: 0;
        top: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
    }

    .popup-content {
        background-color: var(--color-white);
        color: var(--color-dark);
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0px 10px 40px rgba(0, 0, 0, 0.2);
        max-width: 500px;
        width: 100%;
        position: relative;
        animation: fadeIn 0.4s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.8);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .close {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 24px;
        color: #333;
        cursor: pointer;
    }

    form {
        display: flex;
        flex-direction: column;
    }

    /* Input Field Styling */
    input[type="text"],
    input[type="password"],
    input[type="email"] {
        border: 1px solid #ddd;
        border-radius: 50px;
        padding: 12px 20px;
        font-size: 1rem;
        margin-bottom: 15px;
        width: 100%;
        transition: border 0.3s ease;
        background-color: var(--color-white);
        color: var(--color-dark);
    }

    input:focus {
        border-color: #628ECB;
        outline: none;
    }

    /* Radio Button and Label */
    .form-check-input {
        width: 16px;
        height: 16px;
        margin-right: 8px;
    }

    .form-check-label {
        font-size: 0.9rem;
        color: #555;
    }

    /* Responsive Styling */
    @media (max-width: 768px) {
        h1 {
            font-size: 1.5rem;
        }

        .popup-content {
            padding: 20px;
            max-width: 90%;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"] {
            font-size: 0.9rem;
            padding: 10px 15px;
            background-color: var(--color-white);
        }

        button {
            padding: 10px 20px;
            font-size: 0.9rem;
        }

    }

    @media screen and (max-width: 1200px) {
        .container1 {
            width: 100%;
            grid-template-columns: 1fr;
            padding: 0 var(--padding-1);
        }

        aside {
            position: fixed;
            background-color: var(--color-white);
            width: 15rem;
            z-index: 3;
            box-shadow: 1rem 3rem 4rem var(--color-light);
            height: 100vh;
            left: -100%;
            display: none;
            animation: showMenu 0.4s ease forwards;
        }

        @keyframes showMenu {
            to {
                left: 0;
            }
        }

        aside .logo {
            margin-left: 1rem;
        }

        aside .logo h2 {
            display: inline;
        }

        aside .sidebar h3 {
            display: inline;
        }

        aside .sidebar a {
            width: 100%;
            height: 3.4rem;
        }

        aside .sidebar a:last-child {
            position: absolute;
            bottom: 5rem;
        }

        aside .toggle .close {
            display: inline-block;
            cursor: pointer;
        }

        main {
            margin-top: 8rem;
            padding: 0 1rem;
        }

        main .new-users .user-list .user {
            flex-basis: 35%;
        }

        main .recent-orders {
            position: relative;
            margin: 3rem 0 0 0;
            width: 100%;
        }

        main .recent-orders table {
            width: 100%;
            margin: 0;
        }

        .right-section1 {
            width: 94%;
            margin: 0 auto 4rem;
        }

        .right-section .nav {
            position: fixed;
            top: 0;
            left: 0;
            align-items: center;
            background-color: var(--color-white);
            padding: 0 var(--padding-1);
            height: 4.6rem;
            width: 100%;
            z-index: 2;
            box-shadow: 0 1rem 1rem var(--color-light);
            margin: 0;
        }

        .right-section .nav .dark-mode {
            width: 4.4rem;
            position: absolute;
            left: 66%;
        }

        .right-section .profile .info {
            display: none;
        }

        .right-section .nav button {
            display: inline-block;
            background-color: transparent;
            cursor: pointer;
            color: var(--color-dark);
            position: absolute;
            left: 1rem;
        }

        .right-section .nav button span {
            font-size: 2rem;
        }


    }

    @media screen and (max-width: 768px) {
        .container1 {
            width: 100%;
            grid-template-columns: 1fr;
            padding: 0 var(--padding-1);
        }

        
        .row {
            display: flex;
            flex-wrap: nowrap;
            /* flex-wrap: wrap; */
            /* justify-content: space-between; */
            gap: 10px;
        }

        #searchInput {
            width: 230px;
        }

        aside {
            position: fixed;
            background-color: var(--color-white);
            width: 15rem;
            z-index: 3;
            box-shadow: 1rem 3rem 4rem var(--color-light);
            height: 100vh;
            left: -100%;
            display: none;
            animation: showMenu 0.4s ease forwards;
        }

        @keyframes showMenu {
            to {
                left: 0;
            }
        }

        main {
            margin-top: 8rem;
            padding: 0 1rem;
        }

        main .new-users .user-list .user {
            flex-basis: 35%;
        }

        main .recent-orders {
            position: relative;
            margin: 3rem 0 0 0;
            width: 100%;
        }

        main .recent-orders table {
            width: 100%;
            margin: 0;
        }

        .right-section1 {
            width: 94%;
            margin: 0 auto 4rem;
        }

        .right-section .nav {
            position: fixed;
            top: 0;
            left: 0;
            align-items: center;
            background-color: var(--color-white);
            padding: 0 var(--padding-1);
            height: 4.6rem;
            width: 100%;
            z-index: 2;
            box-shadow: 0 1rem 1rem var(--color-light);
            margin: 0;
        }

        .right-section .nav .dark-mode {
            width: 4.4rem;
            position: absolute;
            left: 66%;
        }

        .right-section .profile .info {
            display: none;
        }

        .right-section .nav button {
            display: inline-block;
            background-color: transparent;
            cursor: pointer;
            color: var(--color-dark);
            position: absolute;
            left: 1rem;
        }

        .right-section .nav button span {
            font-size: 2rem;
        }

        .raw {
            display: flex;
            /* flex-wrap: nowrap;   */
            flex-wrap: wrap;
            /* justify-content: space-between; */
            gap: 30px;
        }

    }

    @media screen and (max-width: 480px) {
        .container1 {
            width: 100%;
            grid-template-columns: 1fr;
            padding: 0 var(--padding-1);
        }

        aside {
            position: fixed;
            background-color: var(--color-white);
            width: 15rem;
            z-index: 3;
            box-shadow: 1rem 3rem 4rem var(--color-light);
            height: 100vh;
            left: -100%;
            display: none;
            animation: showMenu 0.4s ease forwards;
        }

        @keyframes showMenu {
            to {
                left: 0;
            }
        }

        main {
            margin-top: 8rem;
            padding: 0 1rem;
        }

        main .new-users .user-list .user {
            flex-basis: 35%;
        }

        main .recent-orders {
            position: relative;
            margin: 3rem 0 0 0;
            width: 100%;
        }

        main .recent-orders table {
            width: 100%;
            margin: 0;
        }

        .right-section1 {
            width: 94%;
            margin: 0 auto 4rem;
        }

        .right-section .nav {
            position: fixed;
            top: 0;
            left: 0;
            align-items: center;
            background-color: var(--color-white);
            padding: 0 var(--padding-1);
            height: 4.6rem;
            width: 100%;
            z-index: 2;
            box-shadow: 0 1rem 1rem var(--color-light);
            margin: 0;
        }

        .right-section .nav .dark-mode {
            width: 4.4rem;
            position: absolute;
            left: 66%;
        }

        .right-section .profile .info {
            display: none;
        }

        .right-section .nav button {
            display: inline-block;
            background-color: transparent;
            cursor: pointer;
            color: var(--color-dark);
            position: absolute;
            left: 5rem;
        }

        .right-section .nav button span {
            font-size: 2rem;
        }

        .search-container {
            margin-left: 59%;
            flex: 1;
        }
    }

    @media screen and (max-width: 1200px) {
        .container {
            width: 95%;
            grid-template-columns: 7rem auto 23rem;
        }

        main .analyse {
            grid-template-columns: 1fr;
            gap: 0;
        }

        main .new-users .user-list .user {
            flex-basis: 40%;
        }

        main .recent-orders {
            width: 94%;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            margin: 2rem 0 0 0.8rem;
        }

        main .recent-orders table {
            width: 83vw;
        }

        main table thead tr th:last-child,
        main table thead tr th:first-child {
            display: none;
        }

        main table tbody tr td:last-child,
        main table tbody tr td:first-child {
            display: none;
        }

        .search-container {
            margin-left: 59%;
            flex: 1;
        }

        .row {
        display: flex;
        flex-wrap: nowrap;
        /* flex-wrap: wrap; */
        /* justify-content: space-between; */
        gap: 20px;
        }

    }

    @media screen and (max-width: 768px) {
        .container {
            width: 100%;
            grid-template-columns: 1fr;
            padding: 0 var(--padding-1);
        }

        main {
            margin-top: 8rem;
            padding: 0 1rem;
        }

        .search-container {
            margin-left:5%;
            flex: 1;
        }

        .row {
        display: flex;
        flex-wrap: nowrap;
        /* flex-wrap: wrap; */
        /* justify-content: space-between; */
        gap: 10px;
        }

        /* Centering Button for Mobile View */
        .text-center {
        text-align: center;
        margin-bottom: 20px;
        }

    }
    /* Base styles (already defined) */
    @media screen and (max-width: 1200px) {
        .container {
            width: 100%;
            grid-template-columns: 1fr;
            padding: 0 var(--padding-1);
        }

        main {
            margin-top: 8rem;
            padding: 0 1rem;
        }

        .search-container {
            margin-left:5%;
            flex: 1;
        }

        .row {
        display: flex;
        flex-wrap: nowrap;
        /* flex-wrap: wrap; */
        /* justify-content: space-between; */
        gap: 10px;
        }

        /* Centering Button for Mobile View */
        .text-center {
        text-align: center;
        margin-bottom: 20px;
        }

    }
    /* Media Queries for Responsive Design */

    /* For Extra Large Screens (1200px and above) */
    @media (min-width: 1200px) {
        .col-md-4 {
            flex: 0 0 18%; /* Fits 5 cards in a row */
            max-width: 18%;
        }
        .card-title {
            font-size: 1.3em; /* Smaller font size */
        }
    }

    /* For Large Screens (992px to 1199px) */
    @media (min-width: 992px) and (max-width: 1199px) {
        .col-md-4 {
            flex: 0 0 23%; /* Fits 4 cards in a row */
            max-width: 23%;
        }
        .search-container {
            margin-left: 50%; /* Adjust search bar alignment */
        }
        .card-title {
            font-size: 1.3em; /* Smaller font size */
        }
    }

    /* For Medium Screens (768px to 991px) */
    @media (min-width: 768px) and (max-width: 991px) {
        .col-md-4 {
            flex: 0 0 30%; /* Fits 3 cards in a row */
            max-width: 30%;
        }
        .row {
            gap: 20px; /* Reduce gap between cards */
        }
        .search-container {
            margin-left: 40%; /* Adjust search bar alignment */
        }
        #searchInput {
            width: 300px; /* Smaller search input */
        }
        .card-title {
            font-size: 1.3em; /* Smaller font size */
        }
    }

    /* For Small Screens (576px to 767px) */
    @media (min-width: 576px) and (max-width: 767px) {
        .col-md-4 {
            flex: 0 0 45%; /* Fits 2 cards in a row */
            max-width: 45%;
        }
        .row {
            gap: 15px; /* Reduce gap further */
        }
        .search-container {
            margin-left: 20%; /* Center search bar */
        }
        #searchInput {
            width: 250px; /* Smaller search input */
            height: 45px;
        }
        .card-title {
            font-size: 1.3em; /* Smaller font size */
        }
    }

    /* For Extra Small Screens (up to 575px) */
    @media (max-width: 575px) {
        .col-md-4 {
            flex: 0 0 90%; /* Full-width cards */
            max-width: 90%;
        }
        .row {
            gap: 10px; /* Minimal gap */
            flex-direction: column; /* Stack cards vertically */
        }
        .search-container {
            margin-left: 0;
            text-align: center; /* Center the search bar */
        }
        #searchInput {
            width: 90%; /* Full-width search input */
            height: 40px;
        }
        .card-body {
            padding: 20px; /* Reduce padding for smaller screens */
        }
        .card-title {
            font-size: 1.3em; /* Smaller font size */
        }
        .card-text {
            font-size: 1em; /* Smaller font size */
        }
    }

    @media (min-width: 849px) and (max-width: 1301px) {
        /* Card Layout Adjustments */
        .col-md-4 {
            flex: 0 0 22%; /* Adjust to fit 4-5 cards per row depending on screen size */
            max-width: 22%;
        }
        .row {
            gap: 20px; /* Adjust the spacing between cards */
        }

        /* Search Bar Adjustments */
        .search-container {
            margin-left: 25%; /* Adjust alignment for this screen size */
        }
        #searchInput {
            width: 300px; /* Slightly smaller search input */
            height: 45px;
        }

        /* Card Content Adjustments */
        .card-body {
            padding: 25px; /* Adjust padding for better spacing */
        }
        .card-title {
            font-size: 1.3em; /* Slightly smaller font size for titles */
        }
        .card-text {
            font-size: 1em; /* Slightly smaller font size for text */
        }

        /* Button Adjustments */
        .custom-card .btn i {
            font-size: 18px; /* Adjust icon size */
        }
        
    }

    @media (min-width: 1300px) and (max-width: 1588px) {
        /* Adjust card layout for the specific screen size */
        .col-md-4 {
            flex: 0 0 21%; /* Fits 5 cards per row */
            max-width:21%;
        }
        .row {
            gap: 30px; /* Maintain consistent spacing between cards */
        }

        /* Search Bar Adjustments */
        .search-container {
            margin-left:50%; /* Center the search bar for this width */
        }
        #searchInput {
            width: 350px; /* Standard size for search input */
            height: 50px;
        }

        /* Card Styling */
        .card-body {
            padding: 30px; /* Ample padding for better readability */
        }
        .card-title {
            font-size: 1.3em; /* Slightly larger titles */
        }
        .card-text {
            font-size: 1em; /* Slightly larger text for better readability */
        }

        /* Buttons and Icons */
        .custom-card .btn i {
            font-size: 20px; /* Larger icons to match screen size */
        }

        /* Reduce the gap between buttons inside cards */
        .card-body .btn-container {
            gap: 15px;
        }
        .custom-card {
            height: 270px;
        }
    }

</style>

<!-- JavaScript -->
<script>
const openPopup = document.getElementById("openPopup");
const popup = document.getElementById("popup");
const closePopup = document.getElementById("closePopup");

// When the user clicks the button, open the pop-up
openPopup.onclick = function() {
    popup.style.display = "block";
}

// When the user clicks on <span> (x), close the pop-up
closePopup.onclick = function() {
    popup.style.display = "none";
}

// When the user clicks anywhere outside of the pop-up, close it
window.onclick = function(event) {
    if (event.target == popup) {
        popup.style.display = "none";
    }
}

document.getElementById("openPopup").onclick = function() {
    document.getElementById("popup").style.display = "block";
};

document.getElementById("closePopup").onclick = function() {
    document.getElementById("popup").style.display = "none";
};

// Close the popup when clicking outside of the popup content
window.onclick = function(event) {
    const popup = document.getElementById("popup");
    if (event.target == popup) {
        popup.style.display = "none";
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const darkModeToggle = document.querySelector('.dark-mode');
    const body = document.body;

    darkModeToggle.addEventListener('click', function() {
        body.classList.toggle('dark-mode-active');
    });
});
</script>

<!-- <script>
const sideMenu = document.querySelector('aside');
const menuBtn = document.getElementById('menu-btn');
const closeBtn = document.getElementById('close-btn');

const darkMode = document.querySelector('.dark-mode');

menuBtn.addEventListener('click', () => {
    sideMenu.style.display = 'block';
});

closeBtn.addEventListener('click', () => {
    sideMenu.style.display = 'none';
});

darkMode.addEventListener('click', () => {
    document.body.classList.toggle('dark-mode-variables');
    darkMode.querySelector('span:nth-child(1)').classList.toggle('active');
    darkMode.querySelector('span:nth-child(2)').classList.toggle('active');
})
</script> -->

<!-- FontAwesome for icons -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>


<!-- edit modal popup -->
<script>
function openEditModal(lab) {

    document.getElementById("editForm").action = `/admin/labs/${lab.lid}`;
    document.getElementById("lid").value = lab.lid;
    // document.getElementById("uid").value = lab.uid;
    document.getElementById("name").value = lab.name;
    document.getElementById("address").value = lab.address;

    // Display the modal
    document.getElementById("editModal").style.display = "block";
}

function closeEditModal() {
    document.getElementById("editModal").style.display = "none";
}
</script>

<script>
// Function to filter and reposition laboratory cards by name
function filterLabs() {
    const searchValue = document.getElementById("searchInput").value.toLowerCase();

    const cards = document.querySelectorAll(".custom-card");
    let firstVisibleCard = null; // To track the first matching card

    cards.forEach((card) => {
        const labName = card.querySelector(".card-title").textContent.toLowerCase();

        if (labName.includes(searchValue)) {
            card.style.display = "block"; // Show the card

            if (!firstVisibleCard) {
                firstVisibleCard = card; // Capture the first matching card
            }
        } else {
            card.style.display = "none"; // Hide non-matching cards
        }
    });

    // Bring the first matching card into view
    if (firstVisibleCard) {
        firstVisibleCard.scrollIntoView({
            behavior: "smooth", // Smooth scroll effect
            block: "start", // Align to the top
        });
    }
}
</script>

@endsection