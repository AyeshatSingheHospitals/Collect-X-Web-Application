@extends('admin.sidebar')

@section('content')

<main>
    <div class="top-bar">
        <h1>Route Assign</h1>
    </div>

    <br><br>

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

    <div class="mb-3">
        <label class="form-label" for="labSearch">
            Search Lab Name:
        </label>
        <br>
        <input type="text" id="labSearch" class="form-control rounded-pill" placeholder="Type to search labs..."
            onkeyup="searchLabs()">
    </div>



    <div id="loadingIndicator" style="display: none;">Loading...</div>
    <form id="assignForm" method="POST" action="{{ route('route.assign.store') }}">
        @csrf
        <!-- Dynamic Table for Routes and Users -->
        <div class="table-container">
            <table class="route-assign-table">
                <thead>
                    <tr id="routeHeaders">
                        <th>User</th>
                        @foreach ($routes as $route)
                        <th>{{ $route->routename }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody id="userList">
                    <!-- Assigned users will be dynamically added here -->
                    @foreach ($groupedAssignments as $assignment)
                    <tr>
                        <!-- User Name -->
                        <td>{{ $assignment['user']->full_name }}</td>


                        <!-- @foreach ($routes as $route)
                        <td>
                            <input type="checkbox" name="assign[{{ $assignment['user']->uid }}][{{ $route->rid }}]"
                                value="1" {{ in_array($route->rid, $assignment['routes']) ? 'checked' : '' }}>
                        </td>
                        @endforeach -->

                        @foreach ($routes as $route)
                        <td>
                            <input type="checkbox" name="assign[{{ $assignment['user']->uid }}][{{ $route->rid }}]"
                                value="1" data-route-id="{{ $route->rid }}"
                                {{ in_array($route->rid, $assignment['routes']) ? 'checked' : '' }}
                                onclick="handleColumnSelection(event)">
                        </td>
                        @endforeach


                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Submit Button -->
        <div class="submit-button-container" id="submitButtonContainer">
            <p>Hey {{ session('fname', 'Guest') }}! You can update now</p>
            <input type="hidden" name="uid" value="{{ session('uid') }}">
            <button type="submit" id="submitAssignments">Submit</button>
        </div>
    </form>
</main>

<script>
async function searchLabs() {
    const searchQuery = document.getElementById('labSearch').value;
    const loadingIndicator = document.getElementById('loadingIndicator');

    if (!searchQuery) {
        return; // Do nothing if the input is empty
    }

    loadingIndicator.style.display = 'block'; // Show loading indicator

    try {
        const response = await fetch(`/route-assign/search?name=${searchQuery}`);
        const data = await response.json();

        if (response.ok) {
            renderTable(data.users, data.routes, data.assignments);
        } else {
            alert(data.message || 'Lab not found');
        }
    } catch (error) {
        console.error('Error fetching data:', error);
    } finally {
        loadingIndicator.style.display = 'none'; // Hide loading indicator
    }
}

function renderTable(users, routes, assignments = {}) {
    const routeHeaders = document.getElementById('routeHeaders');
    const userList = document.getElementById('userList');
    const submitButtonContainer = document.getElementById('submitButtonContainer');

    // Clear existing headers and rows
    routeHeaders.innerHTML = '<th>User</th>';
    userList.innerHTML = '';

    // Check if there are users and routes to display
    if (users && users.length > 0 && routes && routes.length > 0) {
        submitButtonContainer.hidden = false; // Show submit button container
    } else {
        submitButtonContainer.hidden = true; // Hide submit button container

        // Add placeholder message
        userList.innerHTML = '<tr><td colspan="100%">No data found. Try a different search.</td></tr>';
        return; // Exit the function
    }

    // Add route headers
    routes.forEach(route => {
        const th = document.createElement('th');
        th.textContent = route.name;
        routeHeaders.appendChild(th);
    });

    // Add user rows with checkboxes for each route
    users.forEach(user => {
        const tr = document.createElement('tr');

        // User name column
        const nameCell = document.createElement('td');
        nameCell.textContent = user.name;
        tr.appendChild(nameCell);

        // Checkbox columns for each route
        routes.forEach(route => {
            const checkboxCell = document.createElement('td');
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.name = `assign[${user.id}][${route.id}]`;
            checkbox.value = 1;

            // Check if this user is already assigned to this route
            if (assignments[user.id] && assignments[user.id].includes(route.id)) {
                checkbox.checked = true;
            }

            checkboxCell.appendChild(checkbox);
            tr.appendChild(checkboxCell);
        });

        userList.appendChild(tr);
    });
}

// <script>
function handleColumnSelection(event) {
    const clickedCheckbox = event.target;
    const routeId = clickedCheckbox.getAttribute('data-route-id');

    // Get all checkboxes for the same route column
    const columnCheckboxes = document.querySelectorAll(`input[data-route-id="${routeId}"]`);

    // Uncheck all other checkboxes in the column
    columnCheckboxes.forEach(checkbox => {
        if (checkbox !== clickedCheckbox) {
            checkbox.checked = false;
        }
    });
}


</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');


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


.submit-button-container {
    display: flex;
    /* Enables Flexbox */
    justify-content: space-between;
    /* Align items with space between them */
    align-items: center;
    /* Vertically align items */
    margin-top: 1rem;
    /* Add spacing above */
    padding: 0.5rem;
    /* Add some padding */
    border-radius: 5px;
    /* Rounded corners */
    gap: 40px;
}

.updated-by-text {
    margin: 0;
    /* Remove default margin */
    font-size: 16px;
    /* Adjust text size */
    color: #555;
    /* Subtle text color */
}

/* Responsive Design */
@media (max-width: 600px) {
    .submit-button-container {
        flex-direction: column;
        /* Stack items vertically on small screens */
        align-items: flex-start;
        /* Align items to the start */
    }

    #submitAssignments {
        margin-top: 0.5rem;
        /* Add spacing above the button in stacked view */
        width: 100%;
        /* Make the button full-width on small screens */
    }
}

td {
    text-align: center;
    /* Horizontally center content */
    vertical-align: middle;
    /* Vertically center content */
}

.table-container {
    position: relative;
    /* Ensure the submit button is placed relative to the table */
    margin-bottom: 2rem;
    /* Add spacing below the table */
}

.submit-button-container {
    display: flex;
    justify-content: flex-end;
    /* Align the button to the right */
    margin-top: 1rem;
}

/* .table-container {
        overflow-x: auto;
        margin-top: 20px;
    } */

.route-assign-table {
    width: 100%;
    border-collapse: collapse;
    /* background-color: #ffffff; */
    background-color: var(--color-white);
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.route-assign-table thead th {
    background-color: var(--color-white);
    color: #d9006c;
    /* color:#8e94f2; */
    font-weight: bold;
    /* padding: 15px; */
    /* text-transform: uppercase; */
    /* font-size: 14px; */
}

.route-assign-table tbody tr {
    transition: background-color 0.3s ease;
    
}

.route-assign-table tbody tr:hover {
    background-color: var(--color-table);
}

.route-assign-table tbody td {
    padding: 12px;
    /* border-bottom: 1px solid #e0e0e0; */
    font-size: 14px;
    color: #333;
    justify-content: center;
    align-items: center;
}

.route-assign-table tbody tr:nth-child(even) {
    background-color: var(--color-thover);
}

.route-assign-table tbody tr:last-child td {
    border-bottom: none;
}

/* Styled checkboxes */
.styled-checkbox {
    position: relative;
    width: 20px;
    height: 20px;
    cursor: pointer;
    /* accent-color: #8e94f2; */
    accent-color: #fff;
    transition: transform 0.2s ease;

}

.styled-checkbox:checked {
    transform: scale(1.1);
}

.route-assign-table thead th:first-child,
.route-assign-table tbody td:first-child {
    font-weight: bold;
    color: #9a4fff;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .route-assign-table thead th {
        font-size: 12px;
        padding: 10px;
    }

    .route-assign-table tbody td {
        padding: 10px;
        font-size: 13px;
    }
}

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

.form-label {
    font-size: 13px;
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
    background-color: rgb(113, 119, 204);
}

button:active {
    background-color: rgb(160, 166, 250);

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
}
</style>

<style>
.table-container {
    overflow-x: auto;
    margin-top: 20px;
}

.route-assign-table {
    width: 90%;
    border-collapse: collapse;
    text-align: center;
}

.route-assign-table thead th {
    background-color: #f3f3f3;
    padding: 10px;
    font-weight: bold;
}

.route-assign-table tbody td {
    padding: 10px;
    /* border: 1px solid #ddd; */
}

.route-assign-table tbody tr:nth-child(even) {
    background-color: #f9f9f9;
}

.route-assign-table input[type="checkbox"] {
    width: 18px;
    height: 18px;
}
</style>

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

.content {
    margin-left: 90px;
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

aside {
    height: 100vh;
}

aside .toggle {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 1.4rem;
}

aside .toggle .logo {
    display: flex;
    gap: 0.5rem;
}

aside .toggle .logo img {
    width: 2rem;
    height: 2rem;
}

aside .toggle .close {
    padding-right: 1rem;
    display: none;
}

aside .sidebar {
    display: flex;
    flex-direction: column;
    background-color: var(--color-white);
    box-shadow: var(--box-shadow);
    border-radius: 15px;
    height: 88vh;
    position: relative;
    top: 1.5rem;
    transition: all 0.3s ease;
}

aside .sidebar:hover {
    box-shadow: none;
}

aside .sidebar a {
    display: flex;
    align-items: center;
    color: var(--color-info-dark);
    height: 3.7rem;
    gap: 1rem;
    position: relative;
    margin-left: 2rem;
    transition: all 0.3s ease;
}

aside .sidebar a span {
    font-size: 1.6rem;
    transition: all 0.3s ease;
}

aside .sidebar a:last-child {
    position: absolute;
    bottom: 2rem;
    width: 100%;
}

aside .sidebar a.active {
    width: 100%;
    color: var(--color-primary);
    background-color: var(--color-light);
    margin-left: 0;
}

aside .sidebar a.active::before {
    content: '';
    width: 6px;
    height: 18px;
    background-color: var(--color-primary);
}

aside .sidebar a.active span {
    color: var(--color-primary);
    margin-left: calc(1rem - 3px);
}

aside .sidebar a:hover {
    color: var(--color-primary);
}

aside .sidebar a:hover span {
    margin-left: 0.6rem;
}

aside .sidebar .message-count {
    background-color: var(--color-danger);
    padding: 2px 6px;
    color: var(--color-white);
    font-size: 11px;
    border-radius: var(--border-radius-1);
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

    aside .logo h2 {
        display: none;
    }

    aside .sidebar h3 {
        display: none;
    }

    aside .sidebar a {
        width: 5.6rem;
    }

    aside .sidebar a:last-child {
        position: relative;
        margin-top: 1.8rem;
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
    background-color: #C9FFA0;
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
    background-color: #C9FFA0;
    /* Hover color - darker green */
    box-shadow: 0 0 20px rgba(40, 167, 69, 1),
        /* Glowing effect - green */
        0 0 30px rgba(40, 167, 69, 1),
        0 0 40px rgba(40, 167, 69, 1);
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
    /* border-color: #1B9C85; */
    outline: none;
}

/* Button Styling */
button {
    padding: 12px 25px;
    border: none;
    border-radius: 50px;
    /* background-color: #1B9C85; */
    color: white;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    /* background-color: #0f775e; */
}

button:active {
    /* background-color: #0c5e48; */
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

.table-container {
    margin-top: 2rem;
    background-color: var(--color-white);
    padding: var(--card-padding);
    border-radius: var(--card-border-radius);
    box-shadow: var(--box-shadow);
}

.transactions-table {
    width: 100%;
    border-collapse: collapse;
}

.transactions-table th,
.transactions-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid var(--color-light);
}

.transactions-table th {
    background-color: var(--color-primary);
    color: var(--color-white);
}

.transactions-table tr:hover {
    background-color: var(--color-light);
}

.success {
    color: var(--color-success);
}

.danger {
    color: var(--color-danger);
}

.warning {
    color: var(--color-warning);
}

/* Extra Small Devices (Phones in Portrait Mode, up to 480px) */
@media (max-width: 480px) {
    .container {
        width: 100%;
        grid-template-columns: 1fr;
        /* Stack the grid items vertically */
        padding: 0 var(--padding-1);
        /* Adjust padding */
    }

    .route-assign-table {
        width: 100%;
        /* Ensure the table takes up full width */
        font-size: 12px;
        /* Adjust font size */
    }

    .route-assign-table th,
    .route-assign-table td {
        padding: 8px;
        /* Reduce padding for better mobile layout */
    }

    .submit-button-container {
        flex-direction: column;
        /* Stack buttons vertically on small screens */
        align-items: flex-start;
        width: 100%;
    }

    .submit-button-container #submitAssignments {
        margin-top: 1rem;
        width: 100%;
        /* Ensure button takes full width */
    }

    h1 {
        font-size: 1.2rem;
        /* Adjust heading size */
    }

    .popup-content {
        padding: 15px;
        /* Reduce padding */
        max-width: 95%;
        /* Make popup responsive */
    }

    input[type="text"],
    input[type="password"],
    input[type="email"] {
        font-size: 0.85rem;
        /* Adjust font size for small screens */
        padding: 8px 12px;
        /* Adjust padding */
    }

    button {
        padding: 8px 15px;
        /* Adjust button size */
        font-size: 0.85rem;
    }

    .route-assign-table input[type="checkbox"] {
        width: 16px;
        /* Smaller checkboxes for small screens */
        height: 16px;
    }
}

/* Small Devices (Tablets in Portrait Mode, up to 768px) */
@media (max-width: 768px) {
    .container {
        width: 100%;
        grid-template-columns: 16rem auto 6rem;
        /* Stack grid items for tablets */
        /* padding: 0 var(--padding-1); */
    }

    .route-assign-table {
        width: 100%;
        font-size: 13px;
        /* Adjust font size */
    }

    .route-assign-table th,
    .route-assign-table td {
        padding: 10px;
    }

    .route-assign-table tbody td {
        font-size: 10px;
    }

    .submit-button-container {
        flex-direction: column;
        /* Stack items vertically */
        align-items: flex-start;
    }

    h1 {
        font-size: 1.5rem;
        /* Adjust heading size */
    }

    .route-assign-table input[type="checkbox"] {
        width: 18px;
        /* Adjust checkbox size */
        height: 18px;
    }
}

/* Medium Devices (Laptops or Desktops with max-width 1200px) */
@media (max-width: 1200px) {
    .container {
        width: 95%;
        grid-template-columns: 16rem auto 6rem;
        /* Stack grid items */
    }

    .route-assign-table {
        font-size: 11px;
        /* Adjust font size */
    }

    .route-assign-table th,
    .route-assign-table td {
        padding: 12px;
    }

    .submit-button-container {
        flex-direction: column;
        /* Stack items vertically */
        align-items: flex-start;
    }

    h1 {
        font-size: 1.7rem;
        /* Adjust heading size */
    }
}

/* Large Devices (Larger Screens/Desktops) */
@media (min-width: 1201px) {
    .container {
        width: 96%;
        grid-template-columns: 16rem auto 6rem;
        /* Default grid layout */
    }

    .route-assign-table {
        font-size: 11px;
        /* Standard font size for large screens */
    }

    .route-assign-table th,
    .route-assign-table td {
        padding: 15px;
    }
}

/* For very large screens (Extra Large Devices) */
@media (min-width: 1600px) {
    .container {
        width: 95%;
    }

    .route-assign-table {
        font-size: 13px;
        /* Increase font size for large screens */
    }

    .route-assign-table th,
    .route-assign-table td {
        padding: 18px;
    }

    h1 {
        font-size: 2rem;
        /* Adjust heading size for larger screens */
    }
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
</style>

<!-- <style>
    .table-container {
        overflow-x: auto;
        margin-top: 20px;
    }

    .route-assign-table {
        width: 100%;
        border-collapse: collapse;
        text-align: center;
    }

    .route-assign-table thead th {
        background-color: #f3f3f3;
        padding: 10px;
        font-weight: bold;
    }

    .route-assign-table tbody td {
        padding: 10px;
        border: 1px solid #ddd;
    }

    .route-assign-table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .route-assign-table input[type="checkbox"] {
        width: 18px;
        height: 18px;
    }
</style> -->

<!-- JavaScript -->
@endsection