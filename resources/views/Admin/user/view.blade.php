@extends('Admin.sidebar')
@section('content')
<!-- Registration Form -->
<main>
    <div class="top-bar">
        <h1>Register User</h1>
    </div>

    <br>
    <!-- Button to trigger the pop-up -->
    <div class="text-left mb-3">
        <a href ="/reg" class="glow-on-hover btn btn-info rounded-pill" type="button">
            <i class="fas fa-info-circle me-2"></i> Register
        </a>

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
    </div>

    <br><br>
    <div class="row">
        @foreach($users as $user)
        <div class="col-md-4">
            <div class="card mb-3 custom-card">
                <div class="card-body">
                    <h5 class="card-title">{{ $user->fname }} {{ $user->lname }}</h5>
                    <p class="card-text"><strong>EPF:</strong> {{ $user->epf }}</p>
                    <p class="card-text"><strong>Username:</strong> {{ $user->username }}</p>
                    <p class="card-text"><strong>Role:</strong> {{ $user->role }}</p>
                    <p class="card-text"><strong>Status:</strong> {{ $user->status }}</p>

                    <!-- Edit Button -->
                    <button class="btn btn-warning" onclick="openEditModal({{ $user }})">
                        <i class="fas fa-edit me-2"></i> Edit
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Edit Modal (hidden by default) -->
    <div id="editModal" class="popup" style="display: none;">
        <div class="popup-content">
            <span class="close" onclick="closeEditModal()">&times;</span>

            <!-- Edit Form -->
            <form id="editForm" method="POST" action="">
                @csrf
                @method('PUT')
                <input type="hidden" name="uid" id="editUid">

                <div class="mb-3">
                    <label for="editFirstName" class="form-label">First Name:</label>
                    <input type="text" name="fname" id="editFirstName" class="form-control rounded-pill" required>
                </div>

                <div class="mb-3">
                    <label for="editLastName" class="form-label">Last Name:</label>
                    <input type="text" name="lname" id="editLastName" class="form-control rounded-pill" required>
                </div>

                <div class="mb-3">
                    <label for="editEpf" class="form-label">EPF Number:</label>
                    <input type="text" name="epf" id="editEpf" class="form-control rounded-pill" required>
                </div>

                <!-- Username input (read-only) -->
                <div class="mb-3">
                    <label for="editUserName" class="form-label">User Name:</label>
                    <input type="text" name="username" id="editUserName" class="form-control rounded-pill" required>
                </div>

                <!-- Password input -->
                <div class="mb-3">
                    <label for="editPassword" class="form-label">Password:</label>
                    <input type="text" name="password" id="editPassword" class="form-control rounded-pill" required>
                </div>

                <div class="mb-3">
                    <label for="editRole" class="form-label">Role:</label>
                    <select name="role" id="editRole" class="form-select rounded-pill" required>
                        <option value="Admin">Admin</option>
                        <option value="Supervisor">Supervisor</option>
                        <option value="RO">Relationship Officer</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status:</label>
                    <div class="form-check">
                        <input type="radio" name="status" value="active" class="form-check-input" id="editStatusActive">
                        <label class="form-check-label" for="editStatusActive">Active</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" name="status" value="inactive" class="form-check-input"
                            id="editStatusInactive">
                        <label class="form-check-label" for="editStatusInactive">Inactive</label>
                    </div>
                </div>

                <br>

                <button type="submit" class="btn btn-success rounded-pill">Update</button>
            </form>
        </div>
    </div>


    <!-- -----------------Registration Form-------------------------- -->
    <!-- The pop-up (initially hidden) -->
    <div id="popup" class="popup">
        <div class="popup-content">
            <span class="close" id="closePopup">&times;</span>
            <!-- The form inside the pop-up -->
            <form action="{{ route('admin.user.store') }}" method="POST">
                @csrf

                <!-- First Name input -->
                <div class="mb-3">
                    <label for="first_name" class="form-label">
                        <i class="fas fa-user me-2"></i> First Name:
                    </label>
                    <input type="text" name="fname" id="first_name" class="form-control rounded-pill"
                        placeholder="Enter your first name" required oninput="updateUsername()" />
                </div>

                <!-- Last Name input -->
                <div class="mb-3">
                    <label for="last_name" class="form-label">
                        <i class="fas fa-user me-2"></i> Last Name:
                    </label>
                    <input type="text" name="lname" class="form-control rounded-pill" placeholder="Enter your last name"
                        required />
                </div>

                <!-- EPF Number input -->
                <div class="mb-3">
                    <label for="epf" class="form-label">
                        <i class="fas fa-id-card me-2"></i> EPF Number:
                    </label>
                    <input type="text" name="epf" id="epf" class="form-control rounded-pill"
                        placeholder="Enter your EPF number" required oninput="updateUsername()" />
                </div>

                <!-- Username input (read-only) -->
                <div class="mb-3">
                    <label for="username" class="form-label">
                        <i class="fas fa-user-circle me-2"></i> Username:
                    </label>
                    <input type="text" name="username" id="username" class="form-control rounded-pill"
                        placeholder="Username will auto-generate" readonly />
                </div>

                <!-- Password input -->
                <div class="mb-3">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock me-2"></i> Password:
                    </label>
                    <input type="password" name="password" class="form-control rounded-pill"
                        placeholder="Enter password" required />
                </div>

                <!-- Role selection -->
                <div class="mb-3">
                    <label for="role" class="form-label">
                        <i class="fas fa-user-tag me-2"></i> Role:
                    </label>
                    <select name="role" class="form-select rounded-pill" required>
                        <option value="" disabled selected>Select Role</option>
                        <option value="Admin">Admin</option>
                        <option value="Supervisor">Supervisor</option>
                        <option value="RO">Relationship Officer</option>
                    </select>
                </div>

                <!-- Status radio buttons -->
                <div class="mb-3">
                    <label class="form-label">
                        <i class="fas fa-building me-2"></i> Status:
                    </label>
                    <div class="form-check">
                        <input type="radio" name="status" value="active" class="form-check-input" id="statusActive"
                            checked>
                        <label class="form-check-label" for="statusActive">Active</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" name="status" value="inactive" class="form-check-input" id="statusInactive">
                        <label class="form-check-label" for="statusInactive">Inactive</label>
                    </div>
                </div>

                <br>

                <!-- Submit button -->
                <div class="text-center">
                    <button type="submit" class="btn btn-success btn-lg rounded-pill">
                        <i class="fas fa-user-plus me-2"></i> Register
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function updateUsername() {
        const firstName = document.getElementById('first_name').value;
        const epf = document.getElementById('epf').value;

        // Generate username by combining first name and EPF number
        const username = firstName.toLowerCase() + epf;
        document.getElementById('username').value = username;
    }
    </script>
</main>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

    .content {
        margin-left: 90px;
    }

    :root {
        --color-primary: #6C9BCF;
        --color-danger: #FF0060;
        --color-success: #1B9C85;
        --color-warning: #F7D060;
        --color-white: #fff;
        --color-info-dark: #7d8da1;
        --color-dark: #363949;
        --color-light: rgba(132, 139, 200, 0.18);
        --color-dark-variant: #677483;
        --color-background: #f6f6f9;

        --card-border-radius: 2rem;
        --border-radius-1: 0.4rem;
        --border-radius-2: 1.2rem;

        --card-padding: 1.8rem;
        --padding-1: 1.2rem;

        --box-shadow: 0 2rem 3rem var(--color-light);
    }

    .dark-mode-variables {
        --color-background: #181a1e;
        --color-white: #202528;
        --color-dark: #edeffd;
        --color-dark-variant: #a3bdcc;
        --color-light: rgba(0, 0, 0, 0.4);
        --box-shadow: 0 2rem 3rem var(--color-light);
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

    html {
        font-size: 14px;
    }

    body {
        width: 100vw;
        height: 100vh;
        font-family: 'Poppins', sans-serif;
        font-size: 0.88rem;
        user-select: none;
        overflow-x: hidden;
        color: var(--color-dark);
        background-color: var(--color-background);
    }

    a {
        color: var(--color-dark);
    }

    img {
        display: block;
        width: 100%;
        object-fit: cover;
    }

    h1 {
        font-weight: 800;
        font-size: 1.8rem;
    }

    h2 {
        font-weight: 600;
        font-size: 1.4rem;
    }

    h3 {
        font-weight: 500;
        font-size: 0.87rem;
    }

    small {
        font-size: 0.76rem;
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

    .primary {
        color: var(--color-primary);
    }

    .danger {
        color: var(--color-danger);
    }

    .success {
        color: var(--color-success);
    }

    .warning {
        color: var(--color-warning);
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
    }

    .top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    h1 {
        margin: 0;
        font-size: 24px;
    }

    .right-section {
        display: flex;
        align-items: center;
    }

    .nav {
        display: flex;
        align-items: center;
        gap: 20px;
        /* Adjust the gap between items */
    }

    .dark-mode {
        display: flex;
        align-items: center;
        gap: 5px;
        /* Space between light and dark mode icons */
    }

    .profile {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .profile-photo img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
    }

    #menu-btn {
        background: none;
        border: none;
        cursor: pointer;
    }

    .material-icons-sharp {
        font-size: 24px;
        cursor: pointer;
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
        background-color: #9dd2d8;
        /* Button color */
        color: white;
        /* Text color */
        border: none;
        /* Remove border */
        padding: 15px 32px;
        /* Padding */
        text-align: center;
        /* Center text */
        text-decoration: none;
        /* Remove underline */
        display: inline-block;
        /* Inline-block display */
        font-size: 16px;
        /* Font size */
        margin: 4px 2px;
        /* Margins */
        cursor: pointer;
        /* Pointer cursor */
        transition: background-color 0.3s, box-shadow 0.3s;
        /* Smooth transitions */
        border-radius: 30px;
        /* Rounded corners */
    }

    .glow-on-hover:hover {
        background-color: #9dd2d8;
        /* Darker shade on hover */
        box-shadow: 0 0 20px rgba(23, 162, 184, 1),
            0 0 30px rgba(23, 162, 184, 1),
            0 0 40px rgba(23, 162, 184, 1);
        /* Glowing effect */
    }

    /* Popup styles */
.popup {
    display: none;
    /* Hidden by default */
    position: fixed;
    /* Stay in place */
    z-index: 1;
    /* Sit on top */
    left: 0;
    top: 0;
    width: 100%;
    /* Full width */
    height: 100%;
    /* Full height */
    overflow: auto;
    /* Enable scroll if needed */
    background-color: rgba(0, 0, 0, 0.5);
    /* Black background with opacity */
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

/* General Styles */
/* body {
    font-family: 'Poppins', sans-serif;
    color: #333;
    background-color: #f6f6f9;
    padding: 20px;
    margin: 0;
    box-sizing: border-box;
} */

/* h1 {
    text-align: center;
    font-size: 2rem;
    margin-bottom: 20px;
} */

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
    background: #fff;
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
}

input:focus {
    border-color: #1B9C85;
    outline: none;
}

    /* Button Styling */
    button {
        padding: 12px 25px;
        border: none;
        border-radius: 50px;
        background-color: #1B9C85;
        color: white;
        font-size: 1rem;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #0f775e;
    }

    button:active {
        background-color: #0c5e48;
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
        }

        button {
            padding: 10px 20px;
            font-size: 0.9rem;
        }
    }

    @media (max-width: 480px) {
        .popup-content {
            padding: 15px;
            max-width: 95%;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"] {
            font-size: 0.85rem;
            padding: 8px 12px;
        }

        button {
            padding: 8px 15px;
            font-size: 0.85rem;
        }
    }

    /* Centering Button for Mobile View */
    .text-center {
        text-align: center;
        margin-bottom: 20px;
    }

    /* Dropdown styling */
    .form-select {
        background-color: var(--color-white);
        color: var(--color-dark);
        border: 1px solid var(--color-info-dark);
        border-radius: 50px;
        /* for rounded-pill effect */
        padding: 10px 20px;
        font-size: 0.88rem;
        box-shadow: var(--box-shadow);
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .form-select:focus {
        outline: none;
        border-color: var(--color-primary);
        box-shadow: 0 0 5px var(--color-primary);
    }

    /* Dropdown options styling */
    .form-select option {
        background-color: var(--color-background);
        color: var(--color-dark);
        font-size: 0.88rem;
    }

    /* Hover effect for dropdown */
    .form-select:hover {
        border-color: var(--color-primary);
    }

    /* Style for disabled and selected options */
    .form-select option:disabled {
        color: var(--color-info-dark);
    }

    .form-select option[selected] {
        color: var(--color-primary);
    }

    .form-check-inline {
        display: flex;
        /* Use flexbox for alignment */
        gap: 50px;
        /* Space between radio buttons */
        align-items: center;
        /* Align items vertically */
    }

    .form-select {
        border: 1px solid #ddd;
        border-radius: 50px;
        padding: 12px 20px;
        font-size: 1rem;
        margin-bottom: 15px;
        width: 100%;
        transition: border 0.3s ease;
    }

    /* Container for the cards */
    .row {
        display: flex;
        flex-wrap: wrap;
        /* justify-content: space-between; */
        gap: 30px;
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
        /* background: linear-gradient(135deg, #ff9a9e, #fad0c4); */
        /* background: linear-gradient(135deg, #EDE1F8, #F3CDE2);   purple*/
        /* background: linear-gradient(135deg, #B9D3FF, #E3ECFF);  BLUE */
        /* background: linear-gradient(135deg, #ABDAE2, #CEEDED); blue */

        /* Gradient color */
        /* background:#ff9a9e; */
        /* background:#fad0c4; */
        /* background:#E47990; */
        background:#F1D5CA;
        border: none;
        border-radius: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
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
    }

    .card-text {
        font-size: 1em;
        margin-bottom: 10px;
        color: #464646;
    }

    /* Responsive adjustments for various screen sizes */
    @media (max-width: 1200px) {
        .col-md-4 {
            flex: 0 0 22%;
            /* Adjust to 4 cards per row */
            max-width: 22%;
        }
    }

    @media (max-width: 992px) {
        .col-md-4 {
            flex: 0 0 32%;
            /* Adjust to 3 cards per row */
            max-width: 32%;
        }
    }

    @media (max-width: 768px) {
        .col-md-4 {
            flex: 0 0 48%;
            /* Adjust to 2 cards per row */
            max-width: 48%;
        }
    }

    @media (max-width: 576px) {
        .col-md-4 {
            flex: 0 0 100%;
            /* Full width for mobile */
            max-width: 100%;
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
    Orders.forEach(order => {
        const tr = document.createElement('tr');
        const trContent = `
                        <td>${order.productName}</td>
                        <td>${order.productNumber}</td>
                        <td>${order.paymentStatus}</td>
                        <td class="${order.status === 'Declined' ? 'danger' : order.status === 'Pending' ? 'warning' : 'primary'}">${order.status}</td>
                        <td class="primary">Details</td>
                    `;
        tr.innerHTML = trContent;
        document.querySelector('table tbody').appendChild(tr);
    });
    const Orders = [{
            productName: 'JavaScript Tutorial',
            productNumber: '85743',
            paymentStatus: 'Due',
            status: 'Pending'
        },
        {
            productName: 'CSS Full Course',
            productNumber: '97245',
            paymentStatus: 'Refunded',
            status: 'Declined'
        },
        {
            productName: 'Flex-Box Tutorial',
            productNumber: '36452',
            paymentStatus: 'Paid',
            status: 'Active'
        },
    ]
</script> -->

<script>
const sideMenu = document.querySelector('aside');
const menuBtn = document.getElementById('menu-btn');
const closeBtn = document.getElementById('close-btn');
const darkMode = document.querySelector('.dark-mode');

// Toggle dark mode and save preference to local storage
darkMode.addEventListener('click', () => {
    document.body.classList.toggle('dark-mode-variables');
    const isDarkMode = document.body.classList.contains('dark-mode-variables');

    // Save the current mode in local storage
    localStorage.setItem('darkMode', isDarkMode ? 'enabled' : 'disabled');

    // Toggle active states on the dark mode icons
    darkMode.querySelector('span:nth-child(1)').classList.toggle('active');
    darkMode.querySelector('span:nth-child(2)').classList.toggle('active');
});

// Function to apply dark mode based on saved preference
function applyDarkModePreference() {
    const darkModePreference = localStorage.getItem('darkMode');
    if (darkModePreference === 'enabled') {
        document.body.classList.add('dark-mode-variables');
        darkMode.querySelector('span:nth-child(1)').classList.remove('active');
        darkMode.querySelector('span:nth-child(2)').classList.add('active');
    } else {
        document.body.classList.remove('dark-mode-variables');
        darkMode.querySelector('span:nth-child(1)').classList.add('active');
        darkMode.querySelector('span:nth-child(2)').classList.remove('active');
    }
}

// Apply dark mode preference on page load
window.addEventListener('load', applyDarkModePreference);

menuBtn.addEventListener('click', () => {
    sideMenu.style.display = 'block';
});

closeBtn.addEventListener('click', () => {
    sideMenu.style.display = 'none';
});
</script>

<script>
function openEditModal(user) {
    // Set the form action to the update route with the user's ID
    document.getElementById("editForm").action = `/admin/user/${user.uid}`;

    // Populate the modal fields with user data
    document.getElementById("editUid").value = user.uid;
    document.getElementById("editFirstName").value = user.fname;
    document.getElementById("editLastName").value = user.lname;
    document.getElementById("editEpf").value = user.epf;
    document.getElementById("editUserName").value = user.username;
    document.getElementById("editPassword").value = user.password;
    document.getElementById("editRole").value = user.role;
    document.getElementById("editStatusActive").checked = user.status === 'active';
    document.getElementById("editStatusInactive").checked = user.status === 'inactive';

    // Display the modal
    document.getElementById("editModal").style.display = "block";
}

function closeEditModal() {
    document.getElementById("editModal").style.display = "none";
}
</script>



<!-- FontAwesome for icons -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

@endsection