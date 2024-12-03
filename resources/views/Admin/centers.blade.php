@extends('admin.sidebar')

@section('content')

<!-- Registration Form -->
<main>
    <div class="top-bar">
        <h1>Center Create</h1>

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




    <!-- The pop-up (initially hidden) -->
    <div id="popup" class="popup">
        <div class="popup-content">
            <span class="close" id="closePopup">&times;</span>

            <!-- The form inside the pop-up -->
            <form action="{{ route('admin.centers.store') }}" method="POST">
                @csrf

                <input type="hidden" id="selectedLabId" name="lid">


                <div class="form-group">
                    <label for="uid">User Name</label>
                    <input type="text" name="uid" id="uid" class="form-control" required>
                </div>

                <div class="row">
                    <div class="mb-3">
                        <label class="form-label" for="labSearch">
                            <i class="fas fa-flask me-2"></i> Search Lab Name:
                        </label>
                        <input type="text" id="labSearch" class="form-control rounded-pill"
                            placeholder="Type to search labs...">

                        <input type="hidden" id="selectedLabId" name="lid">

                        <!-- Dropdown list to display labs -->
                        <ul id="labList" class="list-group mt-2" name="lid"
                            style="display: none; max-height: 200px; overflow-y: auto; position: absolute; z-index: 1000; width: 92%;">
                            <!-- Lab names will be populated here -->
                        </ul>
                    </div>
                    <br>
                    <div class="mb-3">
                        <label class="form-label" for="routeSearch">
                            <i class="fas fa-flask me-2"></i> Search Route Name:
                        </label>
                        <input type="text" id="routeSearch" class="form-control rounded-pill"
                            placeholder="Type to search routes...">

                        <!-- Dropdown list to display routes -->
                        <ul id="routeList" class="list-group mt-2" name="rid"
                            style="display: none; max-height: 200px; overflow-y: auto; position: absolute; z-index: 1000; width: 92%;">
                        </ul>
                    </div>
                </div>


                <br>
                <div class="row">
                    <div class="form-group">
                        <label for="centername">Center Name</label>
                        <input type="text" name="centername" id="centername" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="authorizedperson">Authorized Person</label>
                        <input type="text" name="authorizedperson" id="authorizedperson" class="form-control" required>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <label for="authorizedcontact">Authorized Contact</label>
                        <input type="text" name="authorizedcontact" id="authorizedcontact" class="form-control"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="thirdpartycontact">ThirdParty Contact</label>
                        <input type="text" name="thirdpartycontact" id="thirdpartycontact" class="form-control"
                            required>
                    </div>
                </div>

                <!-- Searchable User Dropdown -->
                <div class="form-group">
                    <label for="userDropdown">Select User Name</label>
                    <select id="userDropdown" name="user_id" class="form-control select2">
                        <option value="">Select username</option>
                        @foreach($users as $user)
                        <option value="{{ $user->uid }}" data-contact="{{ $user->contact }}">
                            {{ $user->fname }} {{ $user->lname }} - {{ $user->role }}
                        </option>
                        @endforeach
                    </select>
                    <input type="text" id="selectedcontact" name="selectedcontact" class="form-control" readonly />

                </div>

                <!-- Input for Contact Number -->
                <!-- <div class="form-group">
                    <label for="selectedcontact">Contact Number</label>
                    <input type="text" id="selectedcontact" name="selectedcontact" class="form-control" readonly />
                </div> -->

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-control"></textarea>
                </div>

                <div class="row">
                    <div class="form-group">
                        <label for="latitude">Latitude</label>
                        <input type="text" name="latitude" id="latitude" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="longitude">Longitude</label>
                        <input type="text" name="longitude" id="longitude" class="form-control" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Create Center</button>
            </form>
        </div>
    </div>

</main>

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

.row {
    display: inline-flex;
}

.popup-content {
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0px 10px 40px rgba(0, 0, 0, 0.2);
    /* max-width: 500px; 
     */
    /* width: 100%; */
    width: 900px;
    position: relative;
    animation: fadeIn 0.4s ease-in-out;

    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    /* width: 80%; */
    /* max-width: 500px; */
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

.dark-mode-active .popup-content {
    background-color: #202528;
    color: #edeffd;
}

.dark-mode-active .form-control {
    background-color: #3c4146;
    color: #edeffd;
    border: 1px solid #677483;
}

.dark-mode-active .form-control::placeholder {
    color: #a3bdcc;
}

.dark-mode-active .form-check-input {
    background-color: #3c4146;
    border: 1px solid #677483;
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

#routeSearch,
#labSearch,
#centername,
#authorizedperson,
#authorizedcontact,
#thirdpartycontact,
#latitude, #longitude {
    width: 400px;
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

#labList li {
    padding: 5px;
    background-color: #F7E7FB;
}

#labList li:hover {
    background-color: #D53D70;
}

#routeList li {
    padding: 5px;
    background-color: #F7E7FB;
}

#routeList li:hover {
    background-color: #D53D70;
}

/* #routeList li {
    padding: 5px;
    background-color: #F7E7FB;


}

#routeList li:hover {
    background-color: #D53D70;
} */

/* Optional: Customize Select2 dropdown width */
.select2-container--default .select2-selection--single {
    width: 100% !important;
}

/* Optional: Styling for the contact input field */
#selectedcontact {
    border: 1px solid #ccc;
    font-size: 14px;
    padding: 8px;
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

/* ----------------------------description-------------------------------- */
/* Style for the form group */
.form-group {
    margin-bottom: 1.5rem;
    /* Add space between form groups */
}

/* Label styling */
label[for="description"] {
    /* font-size: 1rem;
    font-weight: bold; */
    color: #333;
    margin-bottom: 0.5rem;
    display: block;
}

/* Textarea styling */
textarea[name="description"] {
    width: 100%;
    /* Make the textarea fill the width of the container */
    padding: 10px;
    /* Add padding inside the textarea */
    font-size: 1rem;
    /* Set the font size for the input text */
    border: 1px solid #ccc;
    /* Set border color */
    border-radius: 4px;
    /* Rounded corners for the textarea */
    resize: vertical;
    /* Allow the user to resize vertically */
    min-height: 100px;
    /* Set a minimum height for the textarea */
    box-sizing: border-box;
    /* Ensure padding does not affect the width calculation */
    transition: border-color 0.3s;
    /* Smooth transition for border color change */
    border-radius: 35px;
}

/* Focus effect for textarea */
textarea[name="description"]:focus {
    border-color: #007bff;
    /* Change border color when focused */
    outline: none;
    /* Remove default focus outline */
}

/* Optional: Placeholder styling */
textarea[name="description"]::placeholder {
    color: #888;
    /* Light gray color for placeholder */
}

/* Optional: Responsive styling for small screens */
@media (max-width: 576px) {
    .form-group {
        margin-bottom: 1rem;
    }

    label[for="description"] {
        font-size: 0.9rem;
    }

    textarea[name="description"] {
        font-size: 0.9rem;
    }
}

/* -------------------end description ------------------- */
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
})
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const labSearch = document.getElementById('labSearch');
    const labList = document.getElementById('labList');

    const selectedLabId = document.getElementById('selectedLabId');

    const labs = @json($labs); // Get lab data from the server

    labSearch.addEventListener('input', function() {
        const query = labSearch.value.toLowerCase();

        // Clear previous results
        labList.innerHTML = '';

        // Filter labs based on the query
        const filteredLabs = labs.filter(lab => lab.name.toLowerCase().includes(query));

        // If there are results, show the dropdown
        if (filteredLabs.length > 0 && query !== '') {
            labList.style.display = 'block';

            // Populate the dropdown with filtered labs
            filteredLabs.forEach(lab => {
                const labItem = document.createElement('li');
                labItem.textContent = lab.name;
                labItem.classList.add('list-group-item', 'list-group-item-action');
                labItem.style.cursor = 'pointer';

                // Handle click event to select lab name
                labItem.addEventListener('click', function() {
                    labSearch.value = lab
                    .name; // Set selected lab name in the search bar

                    selectedLabId.value = lab.id;

                    labList.style.display = 'none'; // Hide the dropdown
                    // You can also set a hidden input to capture the selected lab ID if needed
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


<script>
document.addEventListener('DOMContentLoaded', function() {
    const routeSearch = document.getElementById('routeSearch');
    const routeList = document.getElementById('routeList');
    const routes = @json($routes); // Ensure routes are passed from the controller

    routeSearch.addEventListener('input', function() {
        const query = routeSearch.value.toLowerCase().trim();

        // Clear previous results
        routeList.innerHTML = '';

        // Filter routes based on the query
        const filteredRoutes = routes.filter(route => route.routename.toLowerCase().includes(query));

        // Show or hide the dropdown based on results
        if (filteredRoutes.length > 0 && query !== '') {
            routeList.style.display = 'block';

            // Populate the dropdown with filtered routes
            filteredRoutes.forEach(route => {
                const routeItem = document.createElement('li');
                routeItem.textContent = route.routename;
                routeItem.classList.add('list-group-item', 'list-group-item-action');
                routeItem.style.cursor = 'pointer';

                // Click event to select route
                routeItem.addEventListener('click', function() {
                    routeSearch.value = route
                        .routename; // Set selected route name in input
                    routeList.style.display = 'none'; // Hide dropdown
                });

                routeList.appendChild(routeItem);
            });
        } else {
            routeList.style.display = 'none';
        }
    });

    // Hide dropdown if clicked outside
    document.addEventListener('click', function(e) {
        if (!routeSearch.contains(e.target) && !routeList.contains(e.target)) {
            routeList.style.display = 'none';
        }
    });
});
</script>

<script>
// Add event listener to user dropdown
document.getElementById('userDropdown').addEventListener('change', function() {
    // Get the selected user's contact from the data-contact attribute
    var selectedOption = this.options[this.selectedIndex];
    var contactNumber = selectedOption.getAttribute('data-contact');

    // Set the contact number in the "selectedcontact" field
    document.getElementById('selectedcontact').value = contactNumber;
});
</script>


<!-- FontAwesome for icons -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<!-- me tika awe selected contact search ek create krnkota  -->
<!-- Include Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

<!-- Include jQuery (if not already included) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<!-- ------------------------------------------------------------------- -->

@endsection