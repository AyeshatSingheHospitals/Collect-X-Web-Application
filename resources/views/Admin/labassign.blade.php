@extends('admin.sidebar')

@section('content')

<!-- Registration Form -->
<main>
    <div class="container1">

        <!-- Left Section (Cards) -->
        <div class="left-section">

            <div class="search">
                <input type="text" id="searchInput" class="form-control" placeholder="Search by name or role..."
                    onkeyup="filterCards()">
            </div><br><br>

            @foreach($labassigns as $labassign)
            <div class="card">
                <div class="image">
                    <img src="{{ asset('storage/' . $labassign->systemuser->image) }}" alt="Employee Image" />
                </div>

                <div class="content1">
                    <h2 class="name">{{ optional($labassign->systemuser)->full_name ?? 'User not Assigned' }}</h2>
                    <p class="job">{{ optional($labassign->systemuser)->role ?? 'Role not Defined' }}</p>

                    <div class="row1">
                        <div class="col">
                            <h3 class="{{ optional($labassign->lab)->name ? '' : 'lab-not-assigned' }}">
                                | {{ optional($labassign->lab)->name ?? 'Lab not Assigned' }} |
                            </h3>
                        </div>
                    </div>


                    <div class="row1">
                        <!-- <button class="btn2" onclick="openEditForm({{ $labassign->laid }})">Edit</button> -->
                        <button class="btn2" onclick="populateForm(
                            '{{ $labassign->laid }}', 
                            '{{ optional($labassign->systemuser)->uid ?? '' }}', 
                            '{{ optional($labassign->systemuser)->full_name ?? '' }}', 
                            '{{ optional($labassign->systemuser)->epf ?? '' }}', 
                            '{{ optional($labassign->lab)->lid ?? '' }}', 
                            '{{ optional($labassign->lab)->name ?? '' }}')">Edit</button>
                        <!-- Delete Button -->
                        <form action="{{ route('admin.labassigns.destroy', $labassign->laid) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this Lab Assign?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn1">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>


        <!-- Right Section (Form) -->
        <div class="right-section1">
            <br>
            <h1>Lab Assign</h1><br>

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

            <form id="labAssignForm" action="{{ route('admin.labassigns.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <!-- Hidden input for the method (will be dynamically set by JavaScript) -->
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <input type="hidden" name="laid" id="laid">

                <!-- Input for Username -->
                <div class="form-group form-group-full-width">
                    <label for="uid" class="form-label">Username</label>
                    <input type="text" name="uid" id="uid" class="form-control rounded-pill"
                        value="{{ session('username') }}" required readonly>
                </div>
                <br>

                <input type="hidden" name="uid" value="{{ session('uid') }}">

                <!-- Input for Name -->
                <div class="form-group form-group-full-width">
                    <label for="assign_name">Name</label>
                    <input type="text" id="assign_name" name="assign_name" class="form-control"
                        placeholder="Type to search name..." required readonly onclick="openUserModal()">
                </div>
                <br>

                <input type="hidden" name="uid_assign" id="uid_assign">

                <!-- Modal for User Selection -->
                <div id="userModal" class="popup-modal hidden">
                    <div class="popup-content">
                        <h5>Select a User</h5>
                        <!-- Search Bar -->
                        <input type="text" id="searchUserBar" class="form-control"
                            placeholder="Search by name or EPF..." onkeyup="filterUsers()" />

                        <!-- User List -->
                        <ul id="userList" class="list-group">
                            <!-- Dynamic list of users will be inserted here -->
                        </ul>
                        <button type="button" class="btn btn-secondary" onclick="closeUserModal()">Close</button>
                    </div>
                </div>

                <!-- Input for EPF -->
                <div class="form-group form-group-full-width">
                    <label for="epf">EPF</label>
                    <input type="text" id="epf" name="epf" class="form-control" required readonly>
                </div>
                <br>

                <!-- Input for Lab Name -->
                <div class="form-group form-group-full-width">
                    <label for="lab_name">Lab name</label>
                    <input type="text" id="lab_name" name="lab_name" class="form-control"
                        placeholder="Type to search lab name..." required readonly onclick="openLabModal()">
                </div>

                <input type="hidden" name="lid" id="lid">

                <!-- Modal for Lab Selection -->
                <div id="labModal" class="popup-modal hidden">
                    <div class="popup-content">
                        <h5>Select a Lab</h5>
                        <!-- Search Bar -->
                        <input type="text" id="searchLabBar" class="form-control" placeholder="Search by lab name..."
                            onkeyup="filterLabs()" />

                        <!-- Lab List -->
                        <ul id="labList" class="list-group">
                            <!-- Dynamic list of labs will be inserted here -->
                        </ul>
                        <button type="button" class="btn btn-secondary" onclick="closeLabModal()">Close</button>
                    </div>
                </div>

                <br><br><br>
                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary form-group">Register</button><br><br>
            </form>
        </div>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
// JavaScript function to filter cards based on the search input
function filterCards() {
    let input = document.getElementById('searchInput').value.toLowerCase();
    let cards = document.querySelectorAll('.left-section .card');

    cards.forEach(card => {
        let name = card.querySelector('h2').innerText.toLowerCase();
        let role = card.querySelector('p').innerText.toLowerCase();
        let lab = card.querySelector('h3').innerText.toLowerCase();

        if (name.includes(input) || role.includes(input) || lab.includes(input)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

function openUserModal() {
    const modal = document.getElementById('userModal');
    modal.classList.remove('hidden');
    modal.classList.add('visible');

    // Fetch user names, EPFs, and UIDs
    $.ajax({
        url: '{{ route("get.user.names") }}',
        method: 'GET',
        success: function(response) {
            const userList = document.getElementById('userList');
            userList.innerHTML = '';

            response.forEach(user => {
                const listItem = document.createElement('li');
                listItem.className = 'list-group-item';

                listItem.setAttribute('data-name', user.full_name.toLowerCase());
                listItem.setAttribute('data-epf', user.epf.toLowerCase());
                listItem.setAttribute('data-uid', user.uid); // Store the UID

                listItem.textContent = `${user.full_name} (EPF: ${user.epf})`;
                listItem.onclick = () => selectUser(user.full_name, user.epf, user.uid);
                userList.appendChild(listItem);
            });
        },
        error: function() {
            alert('Failed to fetch user names.');
        }
    });
}

function selectUser(fullName, epf, uid) {
    document.getElementById('assign_name').value = fullName;
    document.getElementById('epf').value = epf;
    document.getElementById('uid_assign').value = uid; // Assign the UID to the hidden input
    closeUserModal();
}

function openLabModal() {
    const modal = document.getElementById('labModal');
    modal.classList.remove('hidden');
    modal.classList.add('visible');

    // Fetch lab names
    $.ajax({
        url: '{{ route("get.lab.names") }}',
        method: 'GET',
        success: function(response) {
            const labList = document.getElementById('labList');
            labList.innerHTML = '';

            response.forEach(lab => {
                const listItem = document.createElement('li');
                listItem.className = 'list-group-item';

                listItem.setAttribute('data-name', lab.name.toLowerCase());
                listItem.setAttribute('data-lid', lab.lid);

                listItem.textContent = lab.name;
                listItem.onclick = () => selectLab(lab.name, lab.lid);
                labList.appendChild(listItem);
            });
        },
        error: function() {
            alert('Failed to fetch lab names.');
        }
    });
}

function filterUsers() {
    const filter = document.getElementById('searchUserBar').value.toLowerCase();
    const items = document.querySelectorAll('#userList .list-group-item');
    items.forEach(item => {
        const name = item.getAttribute('data-name');
        const epf = item.getAttribute('data-epf');
        item.style.display = name.includes(filter) || epf.includes(filter) ? '' : 'none';
    });
}

function filterLabs() {
    const filter = document.getElementById('searchLabBar').value.toLowerCase();
    const items = document.querySelectorAll('#labList .list-group-item');
    items.forEach(item => {
        const name = item.getAttribute('data-name');
        item.style.display = name.includes(filter) ? '' : 'none';
    });
}

function closeUserModal() {
    const modal = document.getElementById('userModal');
    modal.classList.add('hidden');
    modal.classList.remove('visible');
}

function closeLabModal() {
    const modal = document.getElementById('labModal');
    modal.classList.add('hidden');
    modal.classList.remove('visible');
}

function selectLab(name, lid) {
    document.getElementById('lab_name').value = name;
    document.getElementById('lid').value = lid;
    closeLabModal();
}

let laidValue = null; // Variable to store 'laid' value

// Populate the form for editing and switch to PUT method
function populateForm(laid, uid, fullName, epf, lid, labName) {
    // Populate other form fields
    document.getElementById('assign_name').value = fullName;
    document.getElementById('epf').value = epf;
    document.getElementById('uid_assign').value = uid;
    document.getElementById('lab_name').value = labName;
    document.getElementById('lid').value = lid;
    document.getElementById('laid').value = laid;

    // Store the 'laid' value in the JavaScript variable
    laidValue = laid;

    // Change the form action to the update route
    const form = document.getElementById('labAssignForm');
    form.action = `/admin/labassigns/${laidValue}`; // Use the 'laid' value for the route

    // Change the form method to PUT for updating
    form.method = 'POST'; // Ensure the method is POST
    document.getElementById('formMethod').value = 'PUT'; // Simulate PUT method
}

function resetForm() {
    // Reset the form for new entry
    const form = document.getElementById('labAssignForm');
    form.action = "{{ route('admin.labassigns.store') }}"; // Set to store route
    form.method = 'POST'; // Default method is POST
    document.getElementById('formMethod').value = 'POST'; // Simulate POST method

    // Clear form fields
    form.reset();

    // Reset the laid value in the JavaScript variable
    laidValue = null; // Clear the stored 'laid' value
}
</script>

<!-- Add Bootstrap JS for Modal functionality -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

.lab-not-assigned {
    color: #FF0060;
}

/* -------------------------------------------------------------Popup------------------------------------------------------------------ */
/* Modal Styles */
.right-section1 .popup-modal {
    position: fixed;
    top: 43%;
    left: 75%;
    transform: translate(-50%, -50%);
    background-color: var(--color-pop);
    border-radius: 25px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    display: none;
    padding: 0;
    width: 300px;
    max-height: 345px;
    overflow-y: auto;
}

.right-section1 .popup-modal.visible {
    display: block;
}

.right-section1 .popup-header {
    position: sticky;
    top: 0;
    background-color: var(--color-white);
    padding: 10px 15px;
    border-bottom: 1px solid #ddd;
    display: flex;
    justify-content: space-between;
    align-items: center;
    z-index: 2;
    /* Ensure it stays above the scrolling content */
}

.right-section1 .popup-header input[type="text"] {
    width: 70%;
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
    background-color: var(--color-white);
}

.right-section1 .popup-header .close-button {
    cursor: pointer;
    font-size: 18px;
    color: #333;
    background: none;
    border: none;
}

.right-section1 .popup-content {
    display: flex;
    flex-direction: column;
    gap: 10px;
    padding: 10px;
    /* Add some padding for the content */
}

.right-section1 .list-group-item {
    cursor: pointer;
    padding: 8px 12px;
    margin-bottom: 5px;
    border-radius: 4px;
}

.right-section1 .list-group-item:hover {
    background-color: var(--color-pointer);
}

.right-section1 .popup-content #searchBar {
    position: sticky;
    top: 0;
    /* Stick it to the top of the popup-content */
    /* Optional: Ensure it's visually distinct */
    z-index: 1;
    /* Optional: Ensure it stays above the content */
    background-color: var(--color-white);
}

/* -------------------------------------------------------------Popup------------------------------------------------------------------ */

.content {
    margin-left: 90px;
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

@media screen and (max-width: 1200px) {
    .container {
        width: 95%;
        grid-template-columns: 7rem auto 23rem;
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

    @keyframes showMenu {
        to {
            left: 0;
        }
    }

    main {
        margin-top: 8rem;
        padding: 0 1rem;
    }

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
    border-color: #8e94f2;
    outline: none;
}

/* Button Styling */
button {
    padding: 12px 25px;
    border: none;
    border-radius: 50px;
    background-color: #8e94f2;
    color: white;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #757bc8;
}

button:active {
    background-color: #bbadff;
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

/*--------------------------------------------------------*/
.form-group-full-width {
    width: 100%;
    display: inline-block;
    margin-right: 2%;
}

.form-group-full-width:last-child {
    margin-right: 0;
    /* Remove margin for the last div */
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
}
</style>

<style>
/* Main Container */
.container1 {
    display: grid;
    grid-template-columns: 1.4fr 1.2fr;
    /* 1fr for cards, 2fr for form */
    gap: 2rem;
    padding: 1.8rem;
}

.search {
    display: grid;
    /* grid-template-columns: 0.5fr 0.5fr; */
    gap: 7em 1.8rem;
    height: 20px;
    width: 200%;
}

/* Left Section (Cards) */
.left-section {
    display: grid;
    grid-template-columns: 0.5fr 0.5fr;
    gap: 7em 1.8rem;
    /* height: 20px; */
    max-height: 750px;
    /* Set a fixed height */
    overflow-y: auto;
    /* Add vertical scrollbar */
    padding-right: 10px;
    /* Space for scrollbar */
}

/* Optional: Styling the Scrollbar */
.left-section::-webkit-scrollbar {
    width: 8px;
    /* Width of the scrollbar */
    display: none;
}

.left-section::-webkit-scrollbar-thumb {
    background-color: #888;
    /* Color of the scroll thumb */
    border-radius: 4px;
    /* Rounded edges for the thumb */
}

.left-section::-webkit-scrollbar-thumb:hover {
    background-color: #555;
    /* Darker on hover */
}

.left-section::-webkit-scrollbar-track {
    background: #f1f1f1;
    /* Background of the scrollbar track */

}

.search {
    position: sticky;
    top: 0;
    z-index: 1;
    padding: 8px;
    margin-bottom: 10px;
}

/* Card Style */
.card {
    background-color: var(--color-white);
    padding: var(--card-padding);
    border-radius: var(--card-border-radius);
    box-shadow: var(--box-shadow);
}

.card:hover {
    /* box-shadow: none; */
    /* === */
    /* transform:scale(1.1); */
}

/* ================================= */

.image {
    width: 70px;
    height: 70px;
    /* background:red; */
    border-radius: 50%;
    margin-top: -80px;
    margin-inline: auto;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
    overflow: hidden;
    transition: 0.5s;
}

.image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.card:hover .image {
    /* box-shadow: none; */
    /* === */
    transform: scale(1.1);
}

.content1 {
    margin-top: 13px;
    /* height:250px; */
    height: 40px;
    text-align: center;
    overflow: hidden;
    transition: 0.5s;
}

.card:hover .content1 {
    height: 190px;
}

.name {
    font-size: 16px;
    /* font-weight:700; */
}

.job {
    font-size: 14px;
    font-weight: 500;
    color: #999;
}

.row1 {
    display: flex;
    align-items: center;
    justify-content: space-around;
    margin-top: 35px;
    gap: 15px;
}

.col h3 {
    /* font-size:20px; */
    font-weight: 600;
}

.col p {
    font-size: 13px;
    font-weight: 500;
    color: #999;
}

.btn1 {
    width: 100%;
    height: 45px;
    outline: none;
    border: 2px solid #8e94f2;
    /* background: #8e94f2; */
    color: #8e94f2;
    cursor: pointer;
    font-size: 18px;
    font-weight: 500;
    border-radius: 10px;
    transition: 0.5s;
    background: var(--color-white);
}

.btn1:hover {
    box-shadow: 0 0 15px #8e94f2;
    background: var(--color-background);
}

.btn1:nth-child(2) {
    background: #fff;
    color: #8e94f2;
}

.btn2 {
    width: 100%;
    height: 45px;
    outline: none;
    border: 2px solid #8e94f2;
    background: #8e94f2;
    color: #fff;
    cursor: pointer;
    font-size: 18px;
    font-weight: 500;
    border-radius: 10px;
    transition: 0.5s;
}

.btn2:hover {
    box-shadow: 0 0 15px #8e94f2;
    background: #8e94f2;
}

.btn2:nth-child(2) {
    background: none;
    color: #8e94f2;
}

/* ======================================== */

/* Right Section (Form) */
.right-section1 {
    background-color: var(--color-white);
    padding: var(--card-padding);
    border-radius: var(--card-border-radius);
    box-shadow: var(--box-shadow);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    /* padding: 20px; */
    margin: 0 auto;
    width: 100%;
    max-width: 500px;

}

.alert {
    padding: 15px;
    margin: 20px 0;
    border-radius: 5px;
    font-size: 14px;
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

/* Right Section (Form) */
.right-section1 {
    background-color: var(--color-white);
    padding: var(--card-padding);
    border-radius: var(--card-border-radius);
    box-shadow: var(--box-shadow);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    /* padding: 20px; */
    margin: 0 auto;
    width: 100%;
    max-width: 500px;

}

@media screen and (max-width: 1200px) {
    .container1 {
        width: 95%;
        grid-template-columns: 7rem auto 23rem;
        width: 100%;
        grid-template-columns: 1fr;
        padding: 0 var(--padding-1);
    }

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
}

@media (min-width: 1200px) and (max-width: 1600px) {

    /* Left Section (Cards) */
    .left-section {
        display: grid;
        grid-template-columns: 1fr;
        gap: 7em 1.8rem;
        /* height: 20px; */
        max-height: 750px;
        /* Set a fixed height */
        overflow-y: auto;
        /* Add vertical scrollbar */
        padding-right: 10px;
        /* Space for scrollbar */
    }

    /* Optional: Styling the Scrollbar */
    .left-section::-webkit-scrollbar {
        width: 8px;
        /* Width of the scrollbar */
        display: none;
    }

    .left-section::-webkit-scrollbar-thumb {
        background-color: #888;
        /* Color of the scroll thumb */
        border-radius: 4px;
        /* Rounded edges for the thumb */
    }

    .left-section::-webkit-scrollbar-thumb:hover {
        background-color: #555;
        /* Darker on hover */
    }

    .left-section::-webkit-scrollbar-track {
        background: #f1f1f1;
        /* Background of the scrollbar track */

    }

    .search {
        display: grid;
        /* grid-template-columns: 0.5fr 0.5fr; */
        gap: 7em 1.8rem;
        height: 20px;
        width: 100%;
    }

    .container1 {
        display: grid;
        grid-template-columns: 1.2fr 1.8fr;
        /* 1fr for cards, 2fr for form */
        gap: 2rem;
        padding: 0.5rem;
    }
}

@media screen and (max-width: 604px) {
    .left-section {
        display: grid;
        grid-template-columns: 1fr;
        gap: 7em 1.8rem;
        /* height: 20px; */
        max-height: 750px;
        /* Set a fixed height */
        overflow-y: auto;
        /* Add vertical scrollbar */
        padding-right: 10px;
        /* Space for scrollbar */
        width: 90%;
    }

    .search {
        display: grid;
        /* grid-template-columns: 0.5fr 0.5fr; */
        gap: 7em 1.8rem;
        height: 20px;
        width: 100%;
    }
}
</style>

@endsection