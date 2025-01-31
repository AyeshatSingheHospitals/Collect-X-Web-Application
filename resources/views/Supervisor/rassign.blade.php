@extends('supervisor.sidebar')

@section('content')

<main>
    <div class="container1">
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
        <div>
            <input type="hidden" name="uid" value="{{ session('uid') }}">

            <div class="form-group">
                <!-- Dropdown for selecting a lab -->
                <label for="labDropdown">Select Lab:</label>
                <select id="labDropdown" class="form-control">
                    <option value="" disabled selected>Select a lab</option>
                    @foreach ($labs as $lab)
                    <option value="{{ $lab->lid }}">{{ $lab->name }}</option>
                    @endforeach
                </select>
            </div>

            <form action="{{ route('route-assign.store') }}" method="POST">
                @csrf
                <input type="hidden" name="uid" value="{{ session('uid') }}">

                <div class="table-container">
                    <table class="route-assign-table">
                        <thead>
                            <tr id="routeHeaders">
                                <th>User</th>
                            </tr>
                        </thead>
                        <tbody id="userList" class="userList">
                            <tr>
                                <td colspan="100%">Select a lab to see the data</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Submit Button Section -->
                <div class="submit-button-container" id="submitButtonContainer" hidden>
                    <p>Hey {{ session('fname', 'Guest') }}! You can update now</p>
                    <button type="submit" id="submitAssignments">Submit</button>
                </div>
            </form>
        </div>
</main>

<script>
document.getElementById('labDropdown').addEventListener('change', async function() {
    const labId = this.value;

    if (!labId) return;

    // Fetch lab data
    const response = await fetch(`/route-assign/search?lid=${labId}`);
    const data = await response.json();

    if (response.ok) {
        renderTable(data.users, data.routes, data.assignments);
    } else {
        alert(data.message || 'Failed to fetch lab data');
    }
});

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

    // Add route headers dynamically
    routes.forEach(route => {
        const th = document.createElement('th');
        th.textContent = route.name;
        routeHeaders.appendChild(th);
    });

    // Add default "Inactive" user row
    const defaultUser = {
        id: '0',
        name: 'Inactive', // Default user
    };
    const trDefault = document.createElement('tr');
    trDefault.classList.add('user-row');

    const nameCellDefault = document.createElement('td');
    nameCellDefault.textContent = defaultUser.name;
    trDefault.appendChild(nameCellDefault);

    routes.forEach((route, columnIndex) => {
        const checkboxCellDefault = document.createElement('td');
        const checkboxDefault = document.createElement('input');
        checkboxDefault.type = 'checkbox';
        checkboxDefault.name = `assign[${defaultUser.id}][${route.id}]`;
        checkboxDefault.value = 1;
        checkboxDefault.dataset.columnIndex = columnIndex;

        // Add event listener to enforce one checkbox per column
        checkboxDefault.addEventListener('change', (e) => {
            if (e.target.checked) {
                const allCheckboxes = document.querySelectorAll(
                    `input[type="checkbox"][data-column-index="${columnIndex}"]`
                );
                allCheckboxes.forEach(cb => {
                    if (cb !== e.target) {
                        cb.checked = false;
                    }
                });
            }
        });

        checkboxCellDefault.appendChild(checkboxDefault);
        trDefault.appendChild(checkboxCellDefault);
    });
    userList.appendChild(trDefault);

    // Add user rows with checkboxes for each route
    users.forEach(user => {
        const tr = document.createElement('tr');
        tr.classList.add('user-row'); // Add class for row management

        // User name column
        const nameCell = document.createElement('td');
        nameCell.textContent = user.name;
        tr.appendChild(nameCell);

        // Checkbox columns for each route
        routes.forEach((route, columnIndex) => {
            const checkboxCell = document.createElement('td');
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.name = `assign[${user.id}][${route.id}]`;
            checkbox.value = 1;
            checkbox.dataset.columnIndex = columnIndex;

            // Check if this user is already assigned to this route
            if (assignments[user.id] && assignments[user.id].includes(route.id)) {
                checkbox.checked = true;
            }

            // Add event listener to enforce one checkbox per column
            checkbox.addEventListener('change', (e) => {
                if (e.target.checked) {
                    const allCheckboxes = document.querySelectorAll(
                        `input[type="checkbox"][data-column-index="${columnIndex}"]`
                    );

                    // Uncheck all checkboxes in the same column except the current one
                    allCheckboxes.forEach(cb => {
                        if (cb !== e.target) {
                            cb.checked = false;
                        }
                    });
                }
            });

            checkboxCell.appendChild(checkbox);
            tr.appendChild(checkboxCell);
        });

        userList.appendChild(tr);
    });
}
</script>





<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

.form-group {
    border: 1px solid #ddd;
    border-radius: 50px;
    padding: 12px 20px;
    font-size: 1rem;
    margin-bottom: 15px;
    width: 40%;
    height: 43px;

    transition: border 0.3s ease;
    background-color: var(--color-white);
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
    font-size: 14px;
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
    width: 6px;
    height: 18px;
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

/* @media (min-width: 1549px) and (max-width: 1625px) */
@media screen and (min-width: 1625px) {
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
        font-size: 13px;
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
        font-size: 13px;
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
        width: 18px;
        height: 18px;
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
}

@media (min-width: 1549px) and (max-width: 1625px) {
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
        font-size: 13px;
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
        font-size: 13px;
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
        width: 18px;
        height: 18px;
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
}

@media (min-width: 1447px) and (max-width: 1549px) {
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
        font-size: 11px;
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
        font-size: 11px;
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
        width: 15px;
        height: 15px;
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
}

@media (min-width: 1200px) and (max-width: 1447px) {
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
        /* padding: 15px; */
        /* text-transform: uppercase; */
        font-size: 10px;
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
        font-size: 10px;
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
        width: 10px;
        height: 10px;
        cursor: pointer;
        /* accent-color: #8e94f2; */
        accent-color: rgb(186, 189, 236);
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
}

@media (min-width: 1398px) and (max-width: 1447px) {
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
        /* padding: 15px; */
        /* text-transform: uppercase; */
        font-size: 10px;
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
        font-size: 10px;
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
        width: 10px;
        height: 10px;
        cursor: pointer;
        /* accent-color: #8e94f2; */
        accent-color: rgb(186, 189, 236);
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
}

@media (min-width: 1200px) and (max-width: 1398px) {
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
        /* padding: 15px; */
        /* text-transform: uppercase; */
        font-size: 10px;
        writing-mode: vertical-rl;
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
        font-size: 10px;
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
        width: 10px;
        height: 10px;
        cursor: pointer;
        /* accent-color: #8e94f2; */
        accent-color: rgb(186, 189, 236);
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
}

@media screen and (max-width: 1200px) {
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
        /* padding: 15px; */
        /* text-transform: uppercase; */
        font-size: 10px;
        writing-mode: vertical-rl;
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
        font-size: 10px;
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
        width: 10px;
        height: 10px;
        cursor: pointer;
        /* accent-color: #8e94f2; */
        accent-color: rgb(186, 189, 236);
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
}

@media screen and (max-width: 1200px) {
    .content {
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

    main .container1 {
        width: 94%;
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        margin: 2rem 0 0 0.8rem;
    }

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