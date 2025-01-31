@extends('admin.sidebar')

@section('content')

<!-- Registration Form -->
<main>
    <div class="top-bar">
        <h1>Center Create</h1>

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

    <br><br>

    @if($centers->isEmpty())
    <div class="col-12 mt-5 center-align-container">
        <!-- Display the image -->
        <img src="{{ asset('../image/center.gif') }}" alt="No Records" class="img-fluid mb-3"
            style="max-width: 300px; height: 300px; border-radius:50%;">
        <!-- Display the "No record here" message -->
        <h4 class="text-muted">No record here</h4>
    </div>

    @else
    <div class="raw">
        @foreach($centers as $center)
        <div class="col-md-4">
            <div class="card mb-3 custom-card">
                <div class="card-body">
                    <h3 class="card-title">{{ $center->centername }}</h3>
                    <p class="card-text"><strong></strong>{{ $center->authorizedperson }}</p>
                    <p class="card-text"><strong></strong>{{ $center->authorizedcontact }}</p>
                    <p class="card-text"><strong>Lab:</strong> {{ $center->lab->name ?? 'Not Assigned' }}</p>
                    <p class="card-text"><strong>Route:</strong> {{ $center->route->routename ?? 'Not Assigned' }}</p>
                    <div class="btn-container">
                        <button class="btn edit-btn" onclick="openEditModal({{ json_encode($center) }})">
                            <i class='bx bxs-message-square-edit'></i>
                        </button>

                        <!-- Delete Button -->
                        <form action="{{ route('admin.center.destroy', $center->cid) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Are you sure you want to delete this center?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn delete-btn" type="submit"><i class='bx bx-message-square-x'></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @endif
    </div>

    <!-- The pop-up (initially hidden) -->
    <div id="popup" class="popup">
        <div class="popup-content">
            <span class="close" id="closePopup">&times;</span>

            <!-- The form inside the pop-up -->
            <form action="{{ route('admin.centers.store') }}" method="POST">
                @csrf

                <input type="hidden" id="selectedLabId" name="lid">

                <div class="form-group">
                    <label class="form-label" for="uid">
                        <i class="fas fa-user me-2"></i> UID:
                    </label>
                    <input type="text" name="uid" class="form-control rounded-pill" value="{{ session('username') }}"
                        readonly required />
                </div>

                <!-- Hidden field for uid -->
                <input type="hidden" name="uid" value="{{ session('uid') }}">

                <div class="raw">
                    <div class="mb-3">
                        <label class="form-label" for="labSearch">
                            <i class="fas fa-flask me-2"></i> Search Lab Name:
                        </label>
                        <input type="text" id="labSearch" class="form-control rounded-pill"
                            placeholder="Type to search labs...">

                        <input type="hidden" id="selectedLabId" name="selectedLabId" name="lid">

                        <!-- Dropdown list to display labs -->
                        <ul id="labList" class="list-group mt-2"
                            style="display: none; max-height: 200px; overflow-y: auto; position: absolute; z-index: 1000; width: 92%;">
                            <!-- Lab names will be populated here -->
                        </ul>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-route me-2"></i> Select Route:
                        </label>
                        <select id="routeDropdown" class="form-control rounded-pill" name="rid" class="select">
                            <option value="" class="fonts">Select a route</option>
                        </select>
                    </div>
                </div>

                <br>
                <div class="raw">
                    <div class="form-group">
                        <label for="centername">Center Name</label>
                        <input type="text" name="centername" id="centername" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="authorizedperson">Authorized Person</label>
                        <input type="text" name="authorizedperson" id="authorizedperson" class="form-control" required>
                    </div>
                </div>

                <div class="raw">
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
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-control"></textarea>
                </div>

                <div class="raw">
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

    <!-- Edit Modal (hidden by default) -->
    <div id="editModal" class="popup" style="display: none;">
        <div class="popup-content">
            <span class="close" onclick="closeEditModal()">&times;</span>

            <!-- Edit Form -->
            <form action="{{ route('admin.centers.store') }}" method="POST" id="editForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="editCid" name="cid">

                <div class="form-group">
                    <label class="form-label" for="editUid">
                        <i class="fas fa-user me-2"></i> UID:
                    </label>
                    <input type="text" name="uid" class="form-control rounded-pill" value="{{ session('username') }}"
                        readonly required />
                </div>

                <!-- Hidden field for uid -->
                <input type="hidden" name="uid" value="{{ session('uid') }}">

                <div class="raw">
                    <div class="mb-3">
                        <label class="form-label" for="editlabSearch">
                            <i class="fas fa-flask me-2"></i> Search Lab Name:
                        </label>
                        <input type="text" id="editlabSearch" class="form-control rounded-pill"
                            placeholder="Type to search labs...">

                        <input type="hidden" id="editSelectedLabId" name="lid" value="">

                        <!-- Dropdown list to display labs -->
                        <ul id="editlabList" class="list-group mt-2"
                            style="display: none; max-height: 200px; overflow-y: auto; position: absolute; z-index: 1000; width: 92%;">
                            <!-- Lab names will be populated here -->
                        </ul>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-route me-2"></i> Select Route:
                        </label>
                        <select id="editrouteDropdown" class="form-control rounded-pill" name="rid">
                        </select>
                    </div>
                </div>

                <br>
                <div class="raw">
                    <div class="form-group">
                        <label for="editCentername">Center Name</label>
                        <input type="text" name="centername" id="editCentername" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="editAuthorizedperson">Authorized Person</label>
                        <input type="text" name="authorizedperson" id="editAuthorizedperson" class="form-control"
                            required>
                    </div>
                </div>

                <div class="raw">
                    <div class="form-group">
                        <label for="editAuthorizedcontact">Authorized Contact</label>
                        <input type="text" name="authorizedcontact" id="editAuthorizedcontact" class="form-control"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="editThirdpartycontact">ThirdParty Contact</label>
                        <input type="text" name="thirdpartycontact" id="editThirdpartycontact" class="form-control"
                            required>
                    </div>
                </div>

                <!-- Searchable User Dropdown -->
                <div class="form-group">
                    <label for="editUserDropdown">Select User Name</label>
                    <select id="editUserDropdown" name="user_id" class="form-control select2">
                        <option value="" id="newuser">Select username</option>
                        @foreach($users as $user)
                        <option value="{{ $user->uid }}" data-contact="{{ $user->contact }}">
                            {{ $user->fname }} {{ $user->lname }} - {{ $user->role }}
                        </option>
                        @endforeach
                    </select>
                    <input type="text" id="editselectedcontact" name="selectedcontact" class="form-control" readonly />
                </div>

                <div class="form-group">
                    <label for="editDescription">Description</label>
                    <textarea name="description" id="editDescription" class="form-control"></textarea>
                </div>

                <div class="raw">
                    <div class="form-group">
                        <label for="editlatitude">Latitude</label>
                        <input type="text" name="latitude" id="editlatitude" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="editlongitude">Longitude</label>
                        <input type="text" name="longitude" id="editlongitude" class="form-control" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Update Center</button>
            </form>
        </div>
    </div>

</main>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Popup functionality
    const openPopup = document.getElementById("openPopup");
    const popup = document.getElementById("popup");
    const closePopup = document.getElementById("closePopup");

    // Open popup when the open button is clicked
    openPopup.onclick = function() {
        popup.style.display = "block";
    }

    // Close popup when the close button is clicked
    closePopup.onclick = function() {
        popup.style.display = "none";
    }

    // Close popup when clicking outside of it
    window.addEventListener('click', function(event) {
        if (event.target === popup) {
            popup.style.display = "none";
        }
    });


    // Side menu functionality
    const sideMenu = document.querySelector('aside');
    const menuBtn = document.getElementById('menu-btn');
    const closeBtn = document.getElementById('close-btn');

    // Open side menu when the menu button is clicked
    if (menuBtn) {
        menuBtn.addEventListener('click', () => {
            sideMenu.classList.add('is-visible');
        });
    }

    // Close side menu when the close button is clicked
    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            sideMenu.classList.remove('is-visible');
        });
    }

    // Lab search and route dropdown functionality
    const labSearch = document.getElementById('labSearch');
    const labList = document.getElementById('labList');
    const selectedLabId = document.getElementById('selectedLabId');
    const routeDropdown = document.getElementById('routeDropdown');

    const labs = @json($labs);

    let debounceTimeout;
    labSearch.addEventListener('input', function() {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(() => {
            const query = labSearch.value.toLowerCase();

            labList.innerHTML = '';
            const filteredLabs = labs.filter(lab => lab.name.toLowerCase().includes(query));

            if (filteredLabs.length > 0 && query.trim() !== '') {
                labList.style.display = 'block';
                filteredLabs.forEach(lab => {
                    const labItem = document.createElement('li');
                    labItem.textContent = lab.name;
                    labItem.classList.add('list-group-item', 'list-group-item-action');
                    labItem.style.cursor = 'pointer';

                    labItem.addEventListener('click', function() {
                        labSearch.value = lab.name;
                        selectedLabId.value = lab.lid;
                        routeDropdown.innerHTML =
                            '<option value="">Select a route</option>';
                        fetchRoutes(lab.lid);
                        labList.style.display = 'none';
                    });

                    labList.appendChild(labItem);
                });
            } else {
                labList.style.display = 'none';
            }
        }, 300);
    });

    // Close lab list when clicking outside of it
    document.addEventListener('click', function(e) {
        if (!labSearch.contains(e.target) && !labList.contains(e.target)) {
            labList.style.display = 'none';
        }
    });

    // Fetch routes for a specific lab
    function fetchRoutes(labId) {
        fetch(`/getRoutes/${labId}`)
            .then(response => response.json())
            .then(data => {
                routeDropdown.innerHTML = '<option value="">Select a route</option>';

                if (data.routes.length > 0) {
                    data.routes.forEach(route => {
                        const option = document.createElement('option');
                        option.value = route.rid;
                        option.textContent = route.routename;
                        routeDropdown.appendChild(option);
                    });
                    routeDropdown.disabled = false;
                } else {
                    const noRoutesOption = document.createElement('option');
                    noRoutesOption.textContent = 'No routes available';
                    noRoutesOption.disabled = true;
                    routeDropdown.appendChild(noRoutesOption);
                    routeDropdown.disabled = true;
                }
            })
            .catch(error => {
                console.error('Error fetching routes:', error);
                alert('An error occurred while fetching routes. Please try again.');
            });
    }

    // User dropdown functionality
    document.getElementById('userDropdown').addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        var contactNumber = selectedOption.getAttribute('data-contact');

        // Set the contact number in the "selectedcontact" field
        document.getElementById('selectedcontact').value = contactNumber;
    });
});

// Edit popup
document.addEventListener('DOMContentLoaded', function() {
    // Function to open the edit modal and populate the fields with data
    function openEditModal(centerData) {
        // Open the modal
        const editModal = document.getElementById('editModal');
        const popupContent = document.querySelector('#editModal .popup-content');

        // Set form action
        document.getElementById('editForm').action = `/admin/centers/${centerData.cid}`;

        // Pre-fill the form with the center data
        document.getElementById('editCid').value = centerData.cid || '';
        document.getElementById('editCentername').value = centerData.centername || '';
        document.getElementById('editAuthorizedperson').value = centerData.authorizedperson || '';
        document.getElementById('editAuthorizedcontact').value = centerData.authorizedcontact || '';
        document.getElementById('editThirdpartycontact').value = centerData.thirdpartycontact || '';
        document.getElementById('editDescription').value = centerData.description || '';
        document.getElementById('editlatitude').value = centerData.latitude || '';
        document.getElementById('editlongitude').value = centerData.longitude || '';
        document.getElementById('editSelectedLabId').value = centerData.lid || '';
        document.getElementById('editlabSearch').value = centerData.lab?.name || '';
        document.getElementById('editrouteDropdown').value = centerData.rid || '';

        // Set the selected user in the dropdown
        const userDropdown = document.getElementById('editUserDropdown');
        const selectedUserOption = Array.from(userDropdown.options).find(option => option.value == centerData
            .user_id);
        if (selectedUserOption) {
            userDropdown.value = centerData.user_id;
            document.getElementById('editselectedcontact').value = selectedUserOption.getAttribute(
                'data-contact');
        } else {
            document.getElementById('editselectedcontact').value = centerData.selectedcontact || ''; // Fallback
        }

        // Trigger lab search (if needed)
        if (centerData.lid) {
            fetchRoutes(centerData.lid); // Fetch routes based on the lab ID
        }

        // Open the edit modal
        editModal.style.display = 'block';
    }

    // Event listener for user dropdown changes
    document.getElementById('editUserDropdown').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const contactNumber = selectedOption.getAttribute('data-contact');

        // Update the contact number in the field
        document.getElementById('editselectedcontact').value = contactNumber;
    });

    // Close the edit modal when the close button is clicked
    function closeEditModal() {
        const editModal = document.getElementById('editModal');
        editModal.style.display = "none";
    }

    // Function to fetch routes based on the lab ID (similar to the insert model)
    function fetchRoutes(labId) {
        const routeDropdown = document.getElementById('editrouteDropdown');

        // Clear all existing options
        routeDropdown.innerHTML = '';

        // Fetch routes for the selected lab ID
        fetch(`/getRoutes/${labId}`)
            .then(response => response.json())
            .then(data => {
                if (data.routes.length > 0) {
                    // Populate the dropdown with new routes
                    data.routes.forEach(route => {
                        const option = document.createElement('option');
                        option.value = route.rid;
                        option.textContent = route.routename;
                        routeDropdown.appendChild(option);
                    });
                    routeDropdown.disabled = false; // Enable dropdown
                } else {
                    // If no routes are available, show a message
                    const noRoutesOption = document.createElement('option');
                    noRoutesOption.textContent = 'No routes available';
                    noRoutesOption.disabled = true;
                    routeDropdown.appendChild(noRoutesOption);
                    routeDropdown.disabled = true; // Disable dropdown
                }
            })
            .catch(error => {
                console.error('Error fetching routes:', error);
                alert('An error occurred while fetching routes. Please try again.');
            });
    }

    // Event listener for lab search input to populate the lab list (same as in insert model)
    const editlabSearch = document.getElementById('editlabSearch');
    const editlabList = document.getElementById('editlabList');
    const editSelectedLabId = document.getElementById('editSelectedLabId');
    const labs = @json($labs);

    let debounceTimeout;
    editlabSearch.addEventListener('input', function() {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(() => {
            const query = editlabSearch.value.toLowerCase();

            editlabList.innerHTML = '';
            const filteredLabs = labs.filter(lab => lab.name.toLowerCase().includes(query));

            if (filteredLabs.length > 0 && query.trim() !== '') {
                editlabList.style.display = 'block';
                filteredLabs.forEach(lab => {
                    const labItem = document.createElement('li');
                    labItem.textContent = lab.name;
                    labItem.classList.add('list-group-item', 'list-group-item-action');
                    labItem.style.cursor = 'pointer';

                    labItem.addEventListener('click', function() {
                        editlabSearch.value = lab.name;
                        editSelectedLabId.value = lab.lid;
                        fetchRoutes(lab.lid);
                        editlabList.style.display = 'none';
                    });

                    editlabList.appendChild(labItem);
                });
            } else {
                editlabList.style.display = 'none';
            }
        }, 300);
    });

    // Close the lab list if clicked outside of it
    document.addEventListener('click', function(e) {
        if (!editlabSearch.contains(e.target) && !editlabList.contains(e.target)) {
            editlabList.style.display = 'none';
        }
    });

    // Close modal on click outside of it
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('editModal');
        if (event.target === modal) {
            closeEditModal();
        }
    });

    // Expose openEditModal globally
    window.openEditModal = openEditModal;
    window.closeEditModal = closeEditModal;
});
</script>

<!-- me tika awe selected contact search ek create krnkota  -->
<!-- Include jQuery (if not already included) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<!-- ------------------------------------------------------------------- -->

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

/* Style the textarea */
textarea#editDescription,
#description {
    width: 100%;
    min-height: 90px;
    padding: 10px;
    font-size: 1rem;
    font-family: Arial, sans-serif;
    border: 1px solid #ccc;
    /* Light gray */
    border-radius: 20px;
    background-color: var(--color-white);
    /* Light background */
    resize: vertical;
    /* Allow vertical resizing only */
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    /* Subtle shadow */
    transition: border-color 0.3s, box-shadow 0.3s;
    /* Smooth interaction effects */
    color: var(--color-dark);
}

/* Focus state for the textarea */
textarea#editDescription:focus {
    /* border-color: #4caf50; Green border */
    /* box-shadow: 0 0 8px rgba(76, 175, 80, 0.5); Green glow */
    border-color: #628ECB;
    outline: none;
}

/* Focus state for the textarea */
textarea#description:focus {
    /* border-color: #4caf50; Green border */
    /* box-shadow: 0 0 8px rgba(76, 175, 80, 0.5); Green glow */
    border-color: #628ECB;
    outline: none;
}

#editUserDropdown,
#userDropdown {
    background-color: var(--color-white);
    color: var(--color-dark);
}

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
    height: 330px;
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
    font-size: 1.3em;
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
    background-color:rgb(32, 137, 207);
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
    max-width: 800px;
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

#routeDropdown,
#editrouteDropdown {
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
        margin-left: 5%;
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
        margin-left: 5%;
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
        flex: 0 0 18%;
        /* Fits 5 cards in a row */
        max-width: 18%;
    }

    .card-title {
        font-size: 1.3em;
        /* Slightly larger titles */
    }
}

/* For Large Screens (992px to 1199px) */
@media (min-width: 992px) and (max-width: 1199px) {
    .col-md-4 {
        flex: 0 0 23%;
        /* Fits 4 cards in a row */
        max-width: 23%;
    }

    .search-container {
        margin-left: 50%;
        /* Adjust search bar alignment */
    }

    .card-title {
        font-size: 1.3em;
        /* Slightly larger titles */
    }
}

/* For Medium Screens (768px to 991px) */
@media (min-width: 768px) and (max-width: 991px) {
    .col-md-4 {
        flex: 0 0 30%;
        /* Fits 3 cards in a row */
        max-width: 30%;
    }

    .row {
        gap: 20px;
        /* Reduce gap between cards */
    }

    .search-container {
        margin-left: 40%;
        /* Adjust search bar alignment */
    }

    #searchInput {
        width: 300px;
        /* Smaller search input */
    }

    .custom-card {
        height: 360px;
    }

    .card-title {
        font-size: 1.3em;
        /* Slightly larger titles */
    }
}

/* For Small Screens (576px to 767px) */
@media (min-width: 576px) and (max-width: 767px) {
    .col-md-4 {
        flex: 0 0 45%;
        /* Fits 2 cards in a row */
        max-width: 45%;
    }

    .row {
        gap: 15px;
        /* Reduce gap further */
    }

    .search-container {
        margin-left: 20%;
        /* Center search bar */
    }

    #searchInput {
        width: 250px;
        /* Smaller search input */
        height: 45px;
    }

    .card-title {
        font-size: 1.3em;
        /* Slightly larger titles */
    }

}

/* For Extra Small Screens (up to 575px) */
@media (max-width: 575px) {
    .col-md-4 {
        flex: 0 0 90%;
        /* Full-width cards */
        max-width: 90%;
    }

    .row {
        gap: 10px;
        /* Minimal gap */
        flex-direction: column;
        /* Stack cards vertically */
    }

    .search-container {
        margin-left: 0;
        text-align: center;
        /* Center the search bar */
    }

    #searchInput {
        width: 90%;
        /* Full-width search input */
        height: 40px;
    }

    .card-body {
        padding: 20px;
        /* Reduce padding for smaller screens */
    }

    .card-title {
        font-size: 1.3em;
        /* Smaller font size */
    }

    .card-text {
        font-size: 1em;
        /* Smaller font size */
    }

    .custom-card {
        /* border: none;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background-color: var(--card-white);
            border-radius: var(--card-border-radius);
            box-shadow: var(--box-shadow); */
        height: 250px;
    }
}

@media (min-width: 849px) and (max-width: 1301px) {

    /* Card Layout Adjustments */
    .col-md-4 {
        flex: 0 0 27%;
        /* Adjust to fit 4-5 cards per row depending on screen size */
        max-width: 27%;
    }

    .row {
        gap: 20px;
        /* Adjust the spacing between cards */
    }

    /* Search Bar Adjustments */
    .search-container {
        margin-left: 25%;
        /* Adjust alignment for this screen size */
    }

    #searchInput {
        width: 300px;
        /* Slightly smaller search input */
        height: 45px;
    }

    /* Card Content Adjustments */
    .card-body {
        padding: 25px;
        /* Adjust padding for better spacing */
    }

    .card-title {
        font-size: 1.3em;
        /* Slightly smaller font size for titles */
    }

    .card-text {
        font-size: 1em;
        /* Slightly smaller font size for text */
    }

    /* Button Adjustments */
    .custom-card .btn i {
        font-size: 18px;
        /* Adjust icon size */
    }

    .custom-card {
        height: 370px;
    }
}

@media (min-width: 1300px) and (max-width: 1588px) {

    /* Adjust card layout for the specific screen size */
    .col-md-4 {
        flex: 0 0 21%;
        /* Fits 5 cards per row */
        max-width: 21%;
    }

    .row {
        gap: 30px;
        /* Maintain consistent spacing between cards */
    }

    /* Search Bar Adjustments */
    .search-container {
        margin-left: 50%;
        /* Center the search bar for this width */
    }

    #searchInput {
        width: 350px;
        /* Standard size for search input */
        height: 50px;
    }

    /* Card Styling */
    .card-body {
        padding: 30px;
        /* Ample padding for better readability */
    }

    .card-title {
        font-size: 1.3em;
        /* Slightly larger titles */
    }

    .card-text {
        font-size: 1em;
        /* Slightly larger text for better readability */
    }

    /* Buttons and Icons */
    .custom-card .btn i {
        font-size: 20px;
        /* Larger icons to match screen size */
    }

    /* Reduce the gap between buttons inside cards */
    .card-body .btn-container {
        gap: 15px;
    }

    .custom-card {
        height: 390px;
    }
}
</style>




@endsection