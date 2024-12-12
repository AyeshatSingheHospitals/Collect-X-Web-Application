@extends('admin.sidebar')

@section('content')

<main>
    <div class="top-bar">
        <h1>Route Assign</h1>
    </div>

    <br><br>

    <div class="mb-3">
        <label class="form-label" for="labSearch">
            Search Lab Name:
        </label>
        <br>
        <input type="text" id="labSearch" class="form-control rounded-pill" placeholder="Type to search labs..."
            onkeyup="searchLabs()">
    </div>

    <br><br>

    <!-- Dynamic Table for Routes and Users -->
    <div class="table-container">
        <table class="route-assign-table">
            <thead>
                <tr id="routeHeaders">
                    <th>User</th>
                    <!-- Routes will be dynamically added here -->
                </tr>
            </thead>
            <tbody id="userList">
                <!-- Assigned users will be dynamically added here -->
            </tbody>
        </table>
    </div>
</main>

<script>
    async function searchLabs() {
        const searchQuery = document.getElementById('labSearch').value;

        if (!searchQuery) {
            return; // Do nothing if the input is empty
        }

        try {
            const response = await fetch(`/route-assign/search?name=${searchQuery}`);
            const data = await response.json();

            if (response.ok) {
                renderTable(data.users, data.routes);
            } else {
                alert(data.message || 'Lab not found');
            }
        } catch (error) {
            console.error('Error fetching data:', error);
        }
    }

    function renderTable(users, routes) {
        const routeHeaders = document.getElementById('routeHeaders');
        const userList = document.getElementById('userList');

        // Clear existing headers and rows
        routeHeaders.innerHTML = '<th>User</th>';
        userList.innerHTML = '';

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
                checkboxCell.appendChild(checkbox);
                tr.appendChild(checkboxCell);
            });

            userList.appendChild(tr);
        });
    }
</script>

<style>
.table-container {
    overflow-x: auto;
    margin-top: 20px;
}

.route-assign-table {
    width: 100%;
    border-collapse: collapse;
    background-color: #ffffff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.route-assign-table thead th {
    background-color: none;
    color: #d9006c;
    /* color:#8e94f2; */
    font-weight: bold;
    padding: 15px;
    text-transform: uppercase;
    font-size: 14px;
}

.route-assign-table tbody tr {
    transition: background-color 0.3s ease;
}

.route-assign-table tbody tr:hover {
    background-color: #f1f1f1;
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
    background-color: #fafafa;
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
</style>




<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

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



<!-- FontAwesome for icons -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

@endsection