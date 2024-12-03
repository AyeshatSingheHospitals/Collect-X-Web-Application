@extends('incharge.navbar')

@section('content')

<!-- Registration Form -->
<main>
    <div class="container1">
        <!-- Left Section (Cards) -->
        <div class="left-section">

            <div class="card">
                <div class="image">
                    <img src="../image/44.jpg" alt="" />
                </div>

                <div class="content1">
                    <h2 class="name">Emplyee Name 1</h2>
                    <p class="job">Role Name</p>

                    <div class="row1">
                        <div class="col">
                            <h3>Lab Name 1 |</h3>
                            <!-- <p> | </p> -->
                        </div>
                        <div class="col">
                            <h3>Lab Name 2 |</h3>
                            <!-- <p> | </p> -->
                        </div>
                        <div class="col">
                            <h3>Lab Name 3 </h3>
                            <!-- <p> | </p> -->
                        </div>
                    </div>

                    <div class="row1">
                        <button class="btn1"> Edit </button>
                        <button class="btn1"> Delete </button>
                    </div>

                </div>
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


        <!-- Right Section (Form) -->
        <div class="right-section1">
            <br>
            <h1>Lab Assign</h1>
            <br>

            <form action="{{ route('Incharge.labassigns.store')}}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Input for Username -->
                <div class="form-group form-group-full-width">
                    <label for="uid" class="form-label"> Username</label>
                    <input type="text" name="uid" id="uid" class="form-control rounded-pill" required>
                </div>
                <br>

                <!-- Input for Name -->
                <div class="form-group form-group-full-width">
                    <label for="uid_assign">Name</label>
                    <input type="text" id="uid_assign" name="uid_assign" class="form-control"
                        placeholder="Type to search name..." required readonly onclick="openUserModal()">
                </div>

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

                <br>
                <!-- Input for EPF -->
                <div class="form-group form-group-full-width">
                    <label for="epf">EPF</label>
                    <input type="text" id="epf" name="epf" class="form-control" required readonly>
                </div>

                <br>
                <!-- Input for Lab -->
                <div class="form-group form-group-full-width">
                    <label for="lid">Lab name</label>
                    <input type="text" id="lid" name="lid" class="form-control" placeholder="Type to search lab name..."
                        required readonly onclick="openLabModal()">
                </div>

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

                <br><br>
                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary form-group">Register</button><br><br>
            </form>
        </div>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
function openUserModal() {
    const modal = document.getElementById('userModal');
    modal.classList.remove('hidden');
    modal.classList.add('visible');

    // Fetch user names and EPFs
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
                listItem.textContent = `${user.full_name} (EPF: ${user.epf})`;
                listItem.onclick = () => selectUser(user.full_name, user.epf);
                userList.appendChild(listItem);
            });
        },
        error: function() {
            alert('Failed to fetch user names.');
        }
    });
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
                listItem.textContent = lab.name;
                listItem.onclick = () => selectLab(lab.name);
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

function selectUser(fullName, epf) {
    document.getElementById('uid_assign').value = fullName;
    document.getElementById('epf').value = epf;
    document.getElementById('uid').value = uid;
    closeUserModal();
}

function selectLab(name) {
    document.getElementById('lid').value = name;
    document.getElementById('lab_id').value = lid;
    closeLabModal();
}
</script>

<!-- Add Bootstrap JS for Modal functionality -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

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
    /* Initially hidden */
    padding: 0;
    /* Remove inner padding from the modal */
    width: 300px;
    max-height: 345px;
    /* Set the maximum height */
    overflow-y: auto;
    /* Enable vertical scrolling for the content */
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

/* Left Section (Cards) */
.left-section {
    display: grid;
    grid-template-columns: 0.5fr 0.5fr;
    gap: 7em 1.8rem;
    height: 20px;
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
    background: #8e94f2;
    color: #fff;
    cursor: pointer;
    font-size: 18px;
    font-weight: 500;
    border-radius: 10px;
    transition: 0.5s;
}

.btn1:hover {
    box-shadow: 0 0 15px #8e94f2;
    background: #8e94f2;
}

.btn1:nth-child(2) {
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
</style>

@endsection