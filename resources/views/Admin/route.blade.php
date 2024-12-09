@extends('admin.sidebar')

@section('content')

<!-- Registration Form -->
<main>
    <div class="top-bar">
        <h1>Route Create</h1>

    </div>
    <br>
    <!-- Button to trigger the pop-up -->
    <div class="text-left mb-3">
        <button id="openPopup" class="glow-on-hover btn btn-info rounded-pill" type="button">
            <i class="fas fa-info-circle me-2"></i> Add
        </button>
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
    <div class="row">
        @foreach($routes as $route)
        <div class="col-md-4">
            <div class="card mb-3 custom-card">
                <div class="card-body">
                    <h5 class="card-title">{{ $route->routename }}</h5>
                    <!-- <p class="card-text"><strong>UID:</strong> {{ $route->uid }}</p> -->
                    <p class="card-text"><strong>Lab Name:</strong> {{ $route->lab->name ?? 'Not Assigned' }}</p>

                    <p class="card-text"><strong>Description:</strong>{{ $route->description }}</p>

                    <div class="btn-container">
                        <!-- Edit Button -->
                        <button class="btn edit-btn" onclick="openEditModal({{ $route }})">
                            <i class='bx bxs-message-square-edit'></i>
                        </button>

                        <!-- Delete Button -->
                        <form action="{{ route('admin.route.destroy', $route->rid) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Are you sure you want to delete this route?');">
                            @csrf
                            @method('DELETE')

                            <!-- Use session('uid') for the logged-in user ID -->
                            <!-- <input type="hidden" name="uid" value="{{ session('uid') }}" required> -->

                            <button class="btn delete-btn" type="submit"><i class='bx bx-message-square-x'></i></button>
                        </form>
                    </div>

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
            <form id="editForm" method="POST" action="{{ route('admin.routes.store') }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="rid" id="editRid">
                <!-- <input type="hidden" name="uid" id="editUid" class="form-control rounded-pill" required> -->

                <div class="mb-3">
                    <label class="form-label" for="editUid" class="form-label"> UID:</label>
                    <input type="text" name="uid" class="form-control rounded-pill" value="{{ session('username') }}"
                        readonly required />
                </div>

                <input type="hidden" name="uid" value="{{ session('uid') }}">

                <div class="mb-3">
                    <label class="form-label" for="editlabSearch">
                        <i class="fas fa-flask me-2"></i> Search Lab Name:
                    </label>
                    <input type="text" id="editlabSearch" class="form-control rounded-pill"
                        placeholder="Type to search labs...">

                    <!-- Hidden input to store the selected lab ID -->
                    <input type="hidden" id="editLid" name="lid">

                    <!-- Dropdown list to display labs -->
                    <ul id="editlabList" class="list-group mt-2"
                        style="display: none; max-height: 200px; overflow-y: auto; position: absolute; z-index: 1000; width: 90%;">
                        <!-- Lab names will be populated here -->
                    </ul>
                </div>

                <div class="mb-3">
                    <label for="editRoutename" class="form-label">Routename:</label>
                    <input type="text" name="routename" id="editRoutename" class="form-control rounded-pill" required>
                </div>

                <div class="mb-3">
                    <label for="editDescription" class="form-label">Description:</label>
                    <input type="text" name="description" id="editDescription" class="form-control rounded-pill"
                        required>
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
            <form action="{{ route('admin.routes.store') }}" method="POST">
                @csrf


                <div class="mb-3">
                    <label class="form-label" for="uid">
                        <i class="fas fa-user me-2"></i> UID:
                    </label>
                    <input type="text" name="uid" class="form-control rounded-pill" value="{{ session('username') }}"
                        readonly required />
                </div>

                <!-- Hidden field for uid -->
                <input type="hidden" name="uid" value="{{ session('uid') }}">


                <div class="mb-3">
                    <label class="form-label" for="labSearch">
                        <i class="fas fa-flask me-2"></i> Search Lab Name:
                    </label>
                    <input type="text" id="labSearch" class="form-control rounded-pill"
                        placeholder="Type to search labs...">

                    <!-- Hidden input to store the selected lab ID -->
                    <input type="hidden" id="selectedLabId" name="lid">

                    <!-- Dropdown list to display labs -->
                    <ul id="labList" class="list-group mt-2"
                        style="display: none; max-height: 200px; overflow-y: auto; position: absolute; z-index: 1000; width: 90%;">
                        <!-- Lab names will be populated here -->
                    </ul>
                </div>

                <!-- 
                <div class="mb-3">
                    <label class="form-label" for="lid">
                        <i class="fas fa-user me-2"></i> Lab Name:
                    </label>
                    <input type="text" name="lid" class="form-control rounded-pill" placeholder="Enter your last name"
                        required />
                </div> -->

                <div class="mb-3">
                    <label class="form-label" for="routename">
                        <i class="fas fa-user-circle me-2"></i> Routename:
                    </label>
                    <input type="text" name="routename" class="form-control rounded-pill"
                        placeholder="Enter your routename" required />
                </div>

                <!-- description -->
                <div class="mb-3">
                    <label class="form-label" for="description">
                        <i class="fas fa-user-circle me-2"></i> Description:
                    </label>
                    <input type="text" name="description" class="form-control rounded-pill"
                        placeholder="Enter your description" required />
                </div>

                <br><br>
                <!-- Submit button -->
                <div class="text-center">
                    <button type="submit" class="btn btn-success btn-lg rounded-pill">
                        <i class="fas fa-user-plus me-2"></i> Register
                    </button>
                </div>
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

* {
    margin: 0;
    padding: 0;
    outline: 0;
    appearance: 0;
    border: 0;
    text-decoration: none;
    box-sizing: border-box;
}

.container {
    display: grid;
    width: 96%;
    margin: 0 auto;
    gap: 1.8rem;
    grid-template-columns: 16rem auto 6rem;
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
    color: #464646;
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
    /* Remove padding on icon */
    margin: 0;
}

.custom-card .btn i {
    font-size: 20px;
    color: #628ECB;

}

/* Style for delete icon in the cards view */
.custom-card .btn-container .btn.delete-btn i {
    color: #628ECB;
    /* Set your desired color here, like a red tone */
}

/* Hover effect for delete icon */
.custom-card .btn-container .btn.delete-btn:hover i,
.custom-card .btn-container .btn.edit-btn:hover i {
    /* transition:  transform 0.3s ease; */
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
    /* Button color - green */
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
    background-color: #365485;
    /* Hover color - darker green */
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

#labList li {
    padding: 5px;
    background-color: #F7E7FB;


}

#labList li:hover {
    background-color: #D53D70;
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

<script>
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
});

function openEditModal(route) {
    // Set the form action to the update route with the user's ID
    document.getElementById("editForm").action = `/admin/routes/${route.rid}`;

    // Populate the modal fields with user data
    document.getElementById("editRid").value = route.routename;
    //document.getElementById("editUid").value = route.uid;
    document.getElementById("editlabSearch").value = route.systemuser?.username || "Not Assigned";
    document.getElementById("editLid").value = route.lid;
    // Set the visible input for lab name
    document.getElementById("editlabSearch").value = route.lab?.name || "Not Assigned";
    document.getElementById("editRoutename").value = route.routename;
    document.getElementById("editDescription").value = route.description;

    // Display the modal
    document.getElementById("editModal").style.display = "block";
}

function closeEditModal() {
    document.getElementById("editModal").style.display = "none";
}

document.addEventListener('DOMContentLoaded', function() {
    const labSearch = document.getElementById('labSearch');
    const labList = document.getElementById('labList');
    const selectedLabId = document.getElementById('selectedLabId'); // Hidden input to store lid
    const labs = @json($labs); // Get lab data from the server

    labSearch.addEventListener('input', function() {
        const query = labSearch.value.toLowerCase().trim();

        // Clear previous results
        labList.innerHTML = '';

        // Filter labs based on the query
        const filteredLabs = labs.filter(lab => lab.name.toLowerCase().includes(query));

        // Show the dropdown if there are results and query is not empty
        if (filteredLabs.length > 0 && query !== '') {
            labList.style.display = 'block';

            // Populate the dropdown with filtered labs
            filteredLabs.forEach(lab => {
                const labItem = document.createElement('li');
                labItem.textContent = lab.name;
                labItem.classList.add('list-group-item', 'list-group-item-action');
                labItem.style.cursor = 'pointer';

                // Handle click event to select lab
                labItem.addEventListener('click', function() {
                    labSearch.value = lab.name; // Set lab name in the search bar
                    selectedLabId.value = lab
                        .lid; // Store the lab ID in the hidden input
                    labList.style.display = 'none'; // Hide the dropdown
                });

                labList.appendChild(labItem);
            });
        } else {
            labList.style.display = 'none'; // Hide the dropdown if no matches
        }
    });

    // Hide dropdown if clicked outside
    document.addEventListener('click', function(e) {
        if (!labSearch.contains(e.target) && !labList.contains(e.target)) {
            labList.style.display = 'none';
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const labSearch = document.getElementById('editlabSearch');
    const labList = document.getElementById('editlabList');
    const selectedLabId = document.getElementById('editLid'); // Hidden input to store lid
    const labs = @json($labs); // Get lab data from the server

    labSearch.addEventListener('input', function() {
        const query = labSearch.value.toLowerCase().trim();

        // Clear previous results
        labList.innerHTML = '';

        // Filter labs based on the query
        const filteredLabs = labs.filter(lab => lab.name.toLowerCase().includes(query));

        // Show the dropdown if there are results and query is not empty
        if (filteredLabs.length > 0 && query !== '') {
            labList.style.display = 'block';

            // Populate the dropdown with filtered labs
            filteredLabs.forEach(lab => {
                const labItem = document.createElement('li');
                labItem.textContent = lab.name;
                labItem.classList.add('list-group-item', 'list-group-item-action');
                labItem.style.cursor = 'pointer';

                // Handle click event to select lab
                labItem.addEventListener('click', function() {
                    labSearch.value = lab.name; // Set lab name in the search bar
                    editLid.value = lab
                        .lid; // Store the lab ID in the hidden input
                    labList.style.display = 'none'; // Hide the dropdown
                });

                labList.appendChild(labItem);
            });
        } else {
            labList.style.display = 'none'; // Hide the dropdown if no matches
        }
    });

    // Hide dropdown if clicked outside
    document.addEventListener('click', function(e) {
        if (!labSearch.contains(e.target) && !labList.contains(e.target)) {
            labList.style.display = 'none';
        }
    });
});
</script>


<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<!-- FontAwesome for icons -->
<!-- <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script> -->


@endsection