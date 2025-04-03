@extends('supervisor.sidebar')

@section('content')

<!-- Registration Form -->
<main>
    <div class="container1">
        <div class="top-bar">
            <h1>Shortage & Excess</h1>
        </div>
        <br><br>

        <!-- Left Section (Cards) -->
        <div class="row">
            <input type="hidden" name="uid" value="{{ session('uid') }}">

            <!-- Assigned Labs Dropdown -->
            <div class="form-group1">
                <label for="labDropdown" style="color:#7f7f7f">Select your Lab :</label>
                <select name="lid" id="labDropdown" class="form-control" required>
                    <option value="" disabled selected>Loading...</option>
                </select>
            </div>

            <div class="search-container">
                <input type="text" id="searchInput" class="form-control" placeholder="Search..."
                    onkeyup="filterCards()" />
            </div>
        </div>
    </div>

    <!-- Display Loading GIF -->
    <div id="loadingGif">
        <!-- <img src="path/to/your/loading.gif" alt="Loading..." /> -->
        <img src="{{ asset('../image/Money.gif') }}" alt="Loading..."
            style="max-width: 500px; border-radius:50%; text-align:center; display:block;">
        <!-- </div> -->
    </div>

    <!-- Cards (Initially Hidden) -->
    <div class="raw" id="cardContainer">
        @foreach($centerBalances as $center)
        <div class="card" data-cid="{{ $center->cid }}" style="display: none;">
            <div class="card-details">
                <p class="text-title">{{ $center->centername }}</p>
                <p class="text-body">
                    @if($center->total_difference < 0)
                        <span style="color: #FF0060;">Shortage: </span><br>
                        <span style="color: #FF0060; font-weight:bold; font-size:13px;"> {{ number_format($center->total_difference, 2) }} LKR</span>
                    @else
                        <span style="color:rgb(141, 187, 16);">Excess:  </span><br>
                        <span style="color: rgb(141, 187, 16); font-weight:bold; font-size:13px;"> +{{ number_format($center->total_difference, 2) }} LKR</span>
                    @endif
                </p>
            </div>
            <button class="card-button"
                onclick="window.open('https://your-metabase-url.com/dashboard/your-dashboard-id?cid={{ $center->cid }}', '_blank')">More
                Info</button>
        </div>
        @endforeach
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const labDropdown = document.getElementById('labDropdown');
    const cardContainer = document.getElementById('cardContainer');
    const loadingGif = document.getElementById('loadingGif');

    // Fetch assigned labs
    fetch(`/lab/assigned-labs`)
        .then(response => response.json())
        .then(data => {
            labDropdown.innerHTML = ''; // Clear existing options

            if (data.length === 0) {
                labDropdown.innerHTML = `<option value="" disabled selected>No labs assigned</option>`;
            } else {
                labDropdown.innerHTML = `<option value="" disabled selected>Lab Names</option>`;
                data.forEach(lab => {
                    labDropdown.innerHTML += `<option value="${lab.lid}">${lab.name}</option>`;
                });
            }
        })
        .catch(error => {
            console.error('Error fetching labs:', error);
            labDropdown.innerHTML = `<option value="" disabled selected>Error loading labs</option>`;
        });

    // Listen for changes in the dropdown and filter cards
    labDropdown.addEventListener('change', function() {
        filterCardsByLab();
    });
});

// Function to filter cards based on selected lab
function filterCardsByLab() {
    const selectedLabId = document.getElementById('labDropdown').value;
    const cards = document.querySelectorAll('.raw .card');
    const loadingGif = document.getElementById('loadingGif');

    // Hide loading GIF and show cards
    loadingGif.style.display = 'none'; // Hide loading gif
    cards.forEach(card => {
        const centerId = card.getAttribute('data-cid'); // Get the data-cid of the card

        // Show cards that match the selected lab
        if (!selectedLabId || centerId === selectedLabId) {
            card.style.display = ''; // Show card
        } else {
            card.style.display = 'none'; // Hide card
        }
    });
}

// Function to filter cards by search input
function filterCards() {
    const searchInput = document.getElementById('searchInput').value.toLowerCase();
    const cards = document.querySelectorAll('.raw .card');

    cards.forEach(card => {
        const name = card.querySelector('.text-title').textContent.toLowerCase();

        if (name.includes(searchInput)) {
            card.style.display = ''; // Show card
        } else {
            card.style.display = 'none'; // Hide card
        }
    });
}
</script>

<style>
#loadingGif {
    display: flex;
    justify-content: center;
    /* Horizontally center */
    align-items: center;
    /* Vertically center */
    text-align: center;
    /* Align the text in the center */
    flex-direction: column;
    /* Stack the image and text vertically */
    /* padding-top: 9%; */
    padding-left: 30%;
    margin-top: 115px;
}

#loadingGif {
    text-align: center;
    display: block;
    /* Ensure it's visible while loading */
}

/* You can add more CSS to style the cards and loading GIF */
</style>


<!-- <script>
document.addEventListener('DOMContentLoaded', function() {
    const uid = document.querySelector('input[name="uid"]').value;
    const labDropdown = document.getElementById('labDropdown');
   
    // Fetch assigned labs
    fetch(`/lab/assigned-labs`)
        .then(response => response.json())
        .then(data => {
            labDropdown.innerHTML = ''; // Clear existing options

            if (data.length === 0) {
                labDropdown.innerHTML = `<option value="" disabled selected>No labs assigned</option>`;
            } else {
                labDropdown.innerHTML = `<option value="" disabled selected>Lab Names</option>`;
                data.forEach(lab => {
                    labDropdown.innerHTML += `<option value="${lab.lid}">${lab.name}</option>`;
                });
            }
        })
        .catch(error => {
            console.error('Error fetching labs:', error);
            labDropdown.innerHTML = `<option value="" disabled selected>Error loading labs</option>`;
        });
});



//search function
function filterCards() {
    const searchInput = document.getElementById('searchInput').value.toLowerCase();
    const cards = document.querySelectorAll('#cardContainer .card');

    cards.forEach(card => {
        const name = card.querySelector('.name').textContent.toLowerCase();

        if (name.includes(searchInput)) {
            card.style.display = ''; // Show card
        } else {
            card.style.display = 'none'; // Hide card
        }
    });
}
</script> -->
<!-- <script>
document.addEventListener('DOMContentLoaded', function() {
    const uid = document.querySelector('input[name="uid"]').value;
    const labDropdown = document.getElementById('labDropdown');

    // Fetch assigned labs
    fetch(`/lab/assigned-labs`)
        .then(response => response.json())
        .then(data => {
            labDropdown.innerHTML = ''; // Clear existing options

            if (data.length === 0) {
                labDropdown.innerHTML = `<option value="" disabled selected>No labs assigned</option>`;
            } else {
                labDropdown.innerHTML = `<option value="" disabled selected>Lab Names</option>`;
                data.forEach(lab => {
                    labDropdown.innerHTML += `<option value="${lab.lid}">${lab.name}</option>`;
                });
            }
        })
        .catch(error => {
            console.error('Error fetching labs:', error);
            labDropdown.innerHTML = `<option value="" disabled selected>Error loading labs</option>`;
        });

    // Listen for changes in the dropdown and filter cards
    labDropdown.addEventListener('change', function() {
        filterCardsByLab();
    });
});

// Function to filter cards based on selected lab
function filterCardsByLab() {
    const selectedLabId = document.getElementById('labDropdown').value;
    const cards = document.querySelectorAll('.raw .card');

    cards.forEach(card => {
        // Assuming the card contains a data attribute like `data-cid` matching the lab's `cid`
        const centerId = card.getAttribute('data-cid');

        if (!selectedLabId || centerId === selectedLabId) {
            card.style.display = ''; // Show card
        } else {
            card.style.display = 'none'; // Hide card
        }
    });
}

// Function to filter cards by search input
function filterCards() {
    const searchInput = document.getElementById('searchInput').value.toLowerCase();
    const cards = document.querySelectorAll('.raw .card');

    cards.forEach(card => {
        const name = card.querySelector('.text-title').textContent.toLowerCase();

        if (name.includes(searchInput)) {
            card.style.display = ''; // Show card
        } else {
            card.style.display = 'none'; // Hide card
        }
    });
}
</script> -->

<!-- <script>
document.addEventListener('DOMContentLoaded', function() {
    const labDropdown = document.getElementById('labDropdown');
   
    // Fetch assigned labs
    fetch(`/lab/assigned-labs`)
        .then(response => response.json())
        .then(data => {
            labDropdown.innerHTML = ''; // Clear existing options

            if (data.length === 0) {
                labDropdown.innerHTML = `<option value="" disabled selected>No labs assigned</option>`;
            } else {
                labDropdown.innerHTML = `<option value="" disabled selected>Lab Names</option>`;
                data.forEach(lab => {
                    labDropdown.innerHTML += `<option value="${lab.lid}">${lab.name}</option>`;
                });
            }
        })
        .catch(error => {
            console.error('Error fetching labs:', error);
            labDropdown.innerHTML = `<option value="" disabled selected>Error loading labs</option>`;
        });

    // Listen for changes in the dropdown and filter cards
    labDropdown.addEventListener('change', function() {
        filterCardsByLab();
    });
});

// Function to filter cards based on selected lab
function filterCardsByLab() {
    const selectedLabId = document.getElementById('labDropdown').value;
    const cards = document.querySelectorAll('.raw .card');

    // Loop through all cards and show/hide based on lab selection
    cards.forEach(card => {
        const centerId = card.getAttribute('data-cid'); // Get the data-cid of the card

        if (!selectedLabId || centerId === selectedLabId) {
            card.style.display = ''; // Show card
        } else {
            card.style.display = 'none'; // Hide card
        }
    });
}

// Function to filter cards by search input
function filterCards() {
    const searchInput = document.getElementById('searchInput').value.toLowerCase();
    const cards = document.querySelectorAll('.raw .card');

    cards.forEach(card => {
        const name = card.querySelector('.text-title').textContent.toLowerCase();

        if (name.includes(searchInput)) {
            card.style.display = ''; // Show card
        } else {
            card.style.display = 'none'; // Hide card
        }
    });
}
</script> -->



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Add Bootstrap JS for Modal functionality -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<style>
.row {
    display: flex;
    flex-wrap: nowrap;
    /* flex-wrap: wrap; */
    /* justify-content: space-between; */
    gap: 340px;
}

.dropdown {
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

.raw {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-top: 20px;
}

.card {
    width: 300px;
    height: 180px;
    border-radius: 20px;
    background: #f5f5f5;
    position: relative;
    padding: 1.8rem;
    border: 2px solid #c3c6ce;
    transition: 0.5s ease-out;
    overflow: visible;
}

.card-details {
    color: black;
    height: 100%;
    gap: .5em;
    display: grid;
    place-content: center;
}

.text-title {
    font-size: 9px;
    font-weight: bold;
    text-align: center;
}

.text-body {
    color: rgb(131, 131, 131);
    font-size: 12px;
    text-align: center;
}

.card-button {
    position: absolute;
    bottom: 20px;
    right: 20px;
    transform: translate(-10%, -5%);
    width: 100px;
    height: 35px;
    border-radius: 10px;
    border: none;
    background-color: #008bf8;
    color: white;
    font-size: 12px;
    padding: 5px 10px;
    cursor: pointer;
}

.card-button:hover {
    background-color: #0066cc;
}
</style>

<!-- CSS -->
<style>
.form-group1 {
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
.search-container {
    width: 35%;
}

.form-control {
    background-color: var(--color-white);
    color: var(--color-dark);
    padding-left: 50px;
}
.card {
    width: 190px;
    height: 230px;
    border-radius: 20px;
    /* background: #f5f5f5; */
    position: relative;
    /* padding:1.8rem; */
    padding: 20px;

    /* border:2px solid #c3c6ce; */
    transition: 0.5s ease-out;
    overflow: visible;
    border: none;
    box-shadow: var(--box-shadow);
    background-color: var(--card-white);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    /* border-radius: var(--card-border-radius); */

}

.card-details {
    color: black;
    height: 100%;
    gap: .5rem;
    display: grid;
    place-content: center;
}

.card-button {
    transform: translate(-50%, 125%);
    width: 60%;
    border-radius: 1rem;
    border: none;
    background-color: #8e94f2;
    color: #fff;
    font-size: 1rem;
    font-weight: bold;

    padding: .5rem 1rem;
    position: absolute;
    left: 50%;
    bottom: 0;
    opacity: 0;
    transition: 0.3s ease-out;
}

.text-body {
    color: rgb(134, 134, 134);

}

.text-title {
    font-size: 1.5em;
    font-weight: bold;

}

.card:hover {
    border-color: rgb(128, 134, 218);
    box-shadow: 0 4px 18px 0 rgba(148, 106, 206, 0.25);

}

.card:hover .card-button {
    transform: translate(-50%, 50%);
    opacity: 1;
}

.card-button:hover {
    background-color: rgb(108, 112, 179);
}

/* Container for the cards */
.raw {
    display: flex;
    flex-wrap: nowrap;
    /* flex-wrap: wrap; */
    /* justify-content: space-between; */
    gap: 30px;

}



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
    /* font-size: 14px; */
    margin-bottom: 15px;
    width: 100%;
    font-family: 'Poppins', sans-serif;
    transition: border 0.3s ease;
}

input:focus {
    border-color: rgb(65, 143, 207);
    outline: none;
}

/* Button Styling */
button {
    padding: 6px 10px;
    border: none;
    border-radius: 80px;
    background-color: rgb(100, 150, 226);
    color: white;
    font-size: 1.2rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: rgb(64, 147, 241);
}

button:active {
    background-color: rgb(87, 152, 250);
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

#searchInput {
    /* background-color:var(--color-white: #202528); */
    background-color: var(--color-white);
    color: var(--color-dark);
}
</style>

@endsection