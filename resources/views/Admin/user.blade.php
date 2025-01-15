@extends('admin.sidebar')

@section('content')

<!-- Registration Form -->
<main>
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
    <div class="container1">


        <!-- Left Section (Cards) -->
        <div class="left-section">
            <!-- Search Bar -->
            <input type="text" id="searchInput" class="form-control" placeholder="Search by name or role..."
                onkeyup="filterCards()">

            @foreach($users as $user)
            <div class="card">
                <div class="card-content">
                    <h3 class="text"><i class="bx bxs-user-circle"></i> {{ $user->fname }} {{ $user->lname }}</h3>
                    <p><strong>Role:</strong> {{ $user->role }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($user->status) }}</p>
                    <p><strong>EPF:</strong> {{ $user->epf }}</p>
                </div>
                <div class="edit-icon" title="Edit" onclick="editUser(this)" data-id="{{ $user->uid }}">
                    <i class="bx bx-edit"></i>
                </div>
            </div>
            @endforeach
        </div>


        <!-- Right Section (Form) -->
        <div class="right-section1">

            <form id="userForm" action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <div class="form-check">
                        <div class="row">
                            <div class="radio">
                                <input class="radio__input" type="radio" name="role" id="roleAdmin" value="Admin"
                                    required>
                                <label class="radio__label" for="roleAdmin"> Admin </label>

                                <input class="radio__input" type="radio" name="role" id="roleSupervisor"
                                    value="Supervisor" required>
                                <label class="radio__label" for="roleSupervisor"> Supervisor </label>

                                <input class="radio__input" type="radio" name="role" id="roleIncharge" value="Incharge"
                                    required>
                                <label class="radio__label" for="roleIncharge"> Incharge </label>

                                <input class="radio__input" type="radio" name="role" id="roleRO" value="RO" required>
                                <label class="radio__label" for="roleRO"> RO </label>
                            </div>


                            <div class="www">
                                <div>
                                    <input type="radio" class="checks" name="status" id="active" value="active"
                                        checked="checked" />
                                    <label for="active" class="check">Active</label>
                                </div>

                                <div>
                                    <input type="radio" class="checks" name="status" id="inactive" value="inactive" />
                                    <label for="inactive" class="check">Inactive</label>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <br><br>

                <input type="hidden" id="userId" name="userId">

                <!-- First Name (fname) -->
                <div class="form-group form-group-full-width">
                    <label for="fname">First Name</label>
                    <input type="text" id="fname" name="fname" class="form-control" required
                        oninput="generateUsername()">
                </div>

                <!-- Last Name (lname) -->
                <div class="form-group form-group-full-width">
                    <label for="lname">Last Name</label>
                    <input type="text" id="lname" name="lname" class="form-control" required>
                </div>

                <!-- Contact -->
                <div class="form-group form-group-full-width">
                    <label for="contact">Contact</label>
                    <input type="text" id="contact" name="contact" class="form-control" required>
                </div>

                <!-- EPF -->
                <div class="form-group form-group-full-width">
                    <label for="epf">EPF</label>
                    <input type="text" id="epf" name="epf" class="form-control" required required
                        oninput="generateUsername()">
                </div>

                <!-- Username -->
                <div class="form-group form-group-full-width">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control" readonly>
                </div>

                <!-- Password -->
                <div class="form-group form-group-full-width">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>

                <br>

                <!-- Image -->
                <div class="form-group form-group-full-width">
                    <label for="image">Profile Image</label>
                    <input type="file" id="image" name="image" class="form-control" accept="image/*">
                    <small id="imageLabel" style="display: none; color: gray;"></small>
                </div>
                <br><br>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary" class="new" class="form-group">Register</button>
            </form>
        </div>
    </div>
</main>

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

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

.www {
    padding-left: 100px;
    display: inline-flex;
    gap: 10px;
}

.text {
    font-weight: 600;
    font-size: 14px;
}

.danger {
    color: var(--color-danger);
}

.success {
    color: var(--color-success);
}

.container {
    display: grid;
    width: 96%;
    margin: 0 auto;
    gap: 1.8rem;
    grid-template-columns: 16rem auto 6rem;
}

/*-------------------------Form Css-------------------------------*/

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
    border-color: #9BC1FF;
    outline: none;
}

/* Button Styling */
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
    background-color: #628ECB;
}

/* Centering Button for Mobile View */
.text-center {
    text-align: center;
    margin-bottom: 20px;
}

.form-group input[type="file"] {
    width: 100%;
    max-width: 400px;
    padding: 10px;
    font-size: 14px;
    color: #555;
    border: 1px solid #ccc;
    border-radius: 25px;
    cursor: pointer;
    transition: border-color 0.3s ease;
    background-color: var(--color-background);

}

.form-group input[type="file"]:hover {
    border-color: #628ECB;
    /* Green border on hover */
}

.form-group input[type="file"]::-webkit-file-upload-button {
    padding: 8px 16px;
    color: #fff;
    background-color: #8AAEE0;
    border: none;
    border-radius: 25px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.form-group input[type="file"]::-webkit-file-upload-button:hover {
    background-color: #628ECB;
}

.row {
    display: inline-flex;
    gap: 0.8em;
}

/* ------------------------------------Active Inactive----------------------------------------- */

.checks {
    display: none;
}

.check {
    position: relative;
    color: #9BC1FF;
    font-family: "Poppins", sans-serif;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.8em;
    border: 3px solid #9BC1FF;
    padding: 0.5em 2em;
    border-radius: 0.5em;
}

.check:before {
    content: "";
    height: 0.5em;
    width: 0.5em;
    border: 3px solid #9BC1FF;
    border-radius: 50%;
}

.checks:checked+label.check:before {
    height: 0.1em;
    width: 0.2em;
    border: 0.65em solid #ffffff;
    background-color: #9BC1FF;
}

.checks:checked+label.check {
    background-color: #9BC1FF;
    color: #ffffff;
}

/* ----------------------------------- Radio Button for role -------------------------  */
.radio {
    display: inline-flex;
    overflow: hidden;
    border-radius: 8px;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.25);
}

.radio__input {
    display: none;
}

.radio__label {
    padding: 8px 14px;
    font-size: 12px;
    /* font-family:sans-serif; */
    color: #fff;
    background: #8AAEE0;
    cursor: pointer;
    transition: background 0.1s;
}

.radio__label:not(:last-of-type) {
    border-right: 3px solid #B1C9EF;
}

.radio__input:checked+.radio__label {
    background: #628ECB;
}

/* ----------------------end role--------------------------- */

/* Main Container */
.container1 {
    display: grid;
    grid-template-columns: 1fr 2fr;
    /* 1fr for cards, 2fr for form */
    gap: 2rem;
    padding: 1.8rem;
}

/* Right Section (Form) */
.right-section1 {
    background-color: var(--color-white);
    padding: var(--card-padding);
    border-radius: var(--card-border-radius);
    box-shadow: var(--box-shadow);
    display: flex;
    flex-direction: column;
    /* align-items: flex-start; */
    gap: 1rem;
}

/* Left Section (Cards) */
.left-section {
    display: flex;
    flex-direction: column;
    gap: 1.8rem;
}

.left-section {
    max-height: 667px;
    /* Set a fixed height for the left section */
    overflow-y: auto;
    /* Enable vertical scrolling */
    padding-right: 10px;
    /* Add some padding to avoid hiding content behind the scrollbar */
}

.left-section::-webkit-scrollbar {
    display: none;
    /* Hide scrollbar in Chrome, Safari, and Edge */
}


/* Fixed positioning for the search bar */
.left-section #searchInput {
    position: sticky;
    top: 0;
    z-index: 1;
    padding: 8px;
    margin-bottom: 10px;

}

/* Card Style */
.card {

    position: relative;
    background-color: var(--color-white);
    padding: var(--card-padding);
    border-radius: var(--card-border-radius);
    box-shadow: var(--box-shadow);
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: none;
}

i {
    font-size: 17px;
    color: #628ECB;
}

/* Styling for the edit icon */
.edit-icon {
    /* position: relative; */
    position: absolute;
    top: 10px;
    right: 10px;
    display: none;
    /* Hidden by default */
    font-size: 18px;
    color: #007bff;
    cursor: pointer;
    transition: transform 0.3s ease, opacity 0.3s ease;
}

.card:hover .edit-icon {
    display: block;
    /* Show when hovering over the card */
    transform: scale(1.2);
    opacity: 1;
}

.edit-icon:hover {
    color: #0056b3;
}

@media (min-width: 1200px) and (max-width: 1587px) {
    .container1 {
        display: grid;
        /* padding: 0 var(--padding-1); */
        /* width: 100%; */
        grid-template-columns: 1.5fr 2.5fr;
        /* 1fr for cards, 2fr for form */
        gap: 2rem;
        padding: 1.8rem;
    }

    main {
        /* margin-top: 8rem; */
        padding: 0 1rem;
    }

    .card {

        position: relative;
        background-color: var(--color-white);
        padding: var(--card-padding);
        border-radius: var(--card-border-radius);
        box-shadow: var(--box-shadow);
        transition: all 0.3s ease;
    }

    .www {
        padding-left: 5px;
        /* Corrected the typo: removed the extra "/" */
        display: inline-flex;
        /* Keeps the flex layout but with inline behavior */
        gap: 10px;
        /* Space between items */
        flex-wrap: wrap;
    }

    .row {
        padding-left: 5px;
        /* Corrected the typo: removed the extra "/" */
        display: inline-flex;
        /* Keeps the flex layout but with inline behavior */
        gap: 10px;
        /* Space between items */
        flex-wrap: wrap;
    }

}

@media (min-width: 1058px) and (max-width: 1200px) {
    .container1 {
        display: grid;
        /* padding: 0 var(--padding-1); */
        /* width: 100%; */
        grid-template-columns: 1.5fr 2.5fr;
        /* 1fr for cards, 2fr for form */
        gap: 2rem;
        padding: 1.8rem;
    }

}

/* @media (min-width: 1200px) and (max-width: 1587px) {
    
} */

/* New media queries */
@media screen and (max-width: 1200px)

/* @media (min-width: 1248px) and (max-width: 1587px) */
    {
    /* aside .logo {
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
    } */
    /* .container1 {
        width: 100%;
        grid-template-columns: 1fr;
        padding: 0 var(--padding-1);
    } */

    .container1 {
        /* display: grid;
        grid-template-columns: 1fr 2fr;
        /* 1fr for cards, 2fr for form */
        /* gap: 2rem;
        padding: 1.8rem; */ 
    }


    /* Right Section (Form) */
    .right-section1 {
        background-color: var(--color-white);
        padding: var(--card-padding);
        border-radius: var(--card-border-radius);
        box-shadow: var(--box-shadow);
        display: flex;
        flex-direction: column;
        /* align-items: flex-start; */
        gap: 1rem;
    }

    /* Left Section (Cards) */


    /* Card Style */
    .card {

        position: relative;
        background-color: var(--color-white);
        padding: var(--card-padding);
        border-radius: var(--card-border-radius);
        box-shadow: var(--box-shadow);
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: none;
    }

    i {
        font-size: 17px;
        color: #628ECB;
    }

    /* Styling for the edit icon */
    .edit-icon {
        /* position: relative; */
        position: absolute;
        top: 10px;
        right: 10px;
        display: none;
        /* Hidden by default */
        font-size: 18px;
        color: #007bff;
        cursor: pointer;
        transition: transform 0.3s ease, opacity 0.3s ease;
    }

    .card:hover .edit-icon {
        display: block;
        /* Show when hovering over the card */
        transform: scale(1.2);
        opacity: 1;
    }

    .edit-icon:hover {
        color: #0056b3;
    }

    /* aside {
        position: fixed;
        background-color: var(--color-white);
        width: 15rem;
        z-index: 3;
        box-shadow: 1rem 3rem 4rem var(--color-light);
        height: 100vh;
        left: -100%;
        display: none;
        animation: showMenu 0.4s ease forwards;
    } */

    @keyframes showMenu {
        to {
            left: 0;
        }
    }

    main {
        margin-top: 8rem;
        padding: 0 1rem;
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

    .www {
        padding-left: 100px;
        display: inline-flex;
        gap: 10px;
    }

}

@media screen and (max-width: 1200px) {
    /* .container1 {
        width: 95%;
        grid-template-columns: 7rem auto 23rem;
        width: 100%;
        grid-template-columns: 1fr;
        padding: 0 var(--padding-1);
    } */

    main .container1 {
        grid-template-columns: 1fr;
        gap: 25px;
        position: absolute;
        left: 5%;
        /* transform: t */
        /* width: 65%; */
        /* grid-template-columns: 7rem auto 23rem; */
    }

    main .new-users .user-list .user {
        flex-basis: 40%;
    }

    main .container1 {
        width: 94%;
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        margin: 2rem 0 0 0.8rem;
    }

    main .right-section1 form {
        width: 80vw;
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

@media screen and (max-width: 1200px) {
    /* .container1 {
        width: 100%;
        grid-template-columns: 1fr;
        padding: 0 var(--padding-1);
    } */

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

    main .container1 {
        width: 94%;
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        margin: 2rem 0 0 0.8rem;
    }

    main .right-section1 form {
        width: 69vw;
    }

    main table thead tr th:last-child,
    main table thead tr th:first-child {
        display: none;
    }

    main table tbody tr td:last-child,
    main table tbody tr td:first-child {
        display: none;
    }

    .www {
        padding-left: 5px;
        /* Corrected the typo: removed the extra "/" */
        display: inline-flex;
        /* Keeps the flex layout but with inline behavior */
        gap: 10px;
        /* Space between items */
        flex-wrap: wrap;
    }

    .row {
        padding-left: 5px;
        /* Corrected the typo: removed the extra "/" */
        display: inline-flex;
        /* Keeps the flex layout but with inline behavior */
        gap: 10px;
        /* Space between items */
        flex-wrap: wrap;
    }

}
</style>

<script>
// JavaScript function to filter cards based on the search input
function filterCards() {
    let input = document.getElementById('searchInput').value.toLowerCase();
    let cards = document.querySelectorAll('.left-section .card');

    cards.forEach(card => {
        let name = card.querySelector('h3').innerText.toLowerCase();
        let role = card.querySelector('p').innerText.toLowerCase();

        if (name.includes(input) || role.includes(input)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

//create username automatically
function generateUsername() {
    const fname = document.getElementById('fname').value.trim().toLowerCase();
    const epf = document.getElementById('epf').value.trim();
    const usernameField = document.getElementById('username');

    if (fname && epf) {
        usernameField.value = fname + "-" + epf;
    } else {
        usernameField.value = ''; // Clear username if one of the fields is empty
    }
}

function editUser(element) {
    const userId = element.getAttribute('data-id');

    if (!userId) {
        console.error('Error: userId is missing.');
        return;
    }

    fetch(`/admin/users/${userId}/edit`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(user => {
            // Populate the form fields
            document.getElementById('userId').value = user.uid;
            document.getElementById('fname').value = user.fname;
            document.getElementById('lname').value = user.lname;
            document.getElementById('contact').value = user.contact;
            document.getElementById('epf').value = user.epf;
            document.getElementById('username').value = user.username;
            document.getElementById('password').value = user.password;
            // Set the image input field
            const imageInput = document.getElementById('image');
            if (user.image) {
                // Display the file name or a message
                const imageLabel = document.getElementById('imageLabel');
                imageLabel.textContent = `Current image: ${user.image}`;
                imageLabel.style.display = 'block';
            }
            document.querySelector(`input[name="role"][value="${user.role}"]`).checked = true;
            document.querySelector(`input[name="status"][value="${user.status}"]`).checked = true;

            // Update form action
            const form = document.getElementById('userForm');
            form.action = `/admin/users/${user.uid}/update`;

            const submitButton = document.getElementById('submitButton');
            submitButton.textContent = "Update";
        })
        .catch(error => console.error('Error fetching user:', error));
}

document.getElementById('userForm').addEventListener('reset', () => {
    const form = document.getElementById('userForm');
    form.action = "{{ route('admin.users.store') }}"; // Reset to store route
    document.getElementById('submitButton').textContent = "Register";
});
</script>

@endsection