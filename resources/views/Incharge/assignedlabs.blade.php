@extends('incharge.sidebar')

@section('content')

<!-- Registration Form -->
<main>
    <div class="container1">
        <!-- Left Section (Cards) -->
        <br><br><br>
        <div class="row">
            <input type="hidden" name="uid" value="{{ session('uid') }}">

            <!-- Assigned Labs Dropdown -->
            <div class="form-group">
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


    <br><br><br><br><br>
    <div class="left-section">

        <div id="cardContainer" class="card-container">

            <!-- Cards will be dynamically added here -->
        </div>
    </div>

</main>

<script>
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


//get ro's related to selected lab
document.getElementById('labDropdown').addEventListener('change', function() {
    const labId = this.value; // Get the selected lab ID
    const cardContainer = document.getElementById('cardContainer'); // Container for the cards

    // Clear existing cards
    cardContainer.innerHTML = '';

    if (labId) {
        // Make an AJAX request to fetch the lab assignments
        fetch(`/lab/assignments?lid=${labId}`)
            .then((response) => response.json())
            .then((assignments) => {
                assignments.forEach((assignment) => {
                    const officer = assignment.systemuser;

                    // Determine the status color
                    const statusColor = officer.status.toLowerCase() === 'inactive' ? '#FF0060' :
                        '#628ECB';

                    // Create a new card for each officer
                    const card = document.createElement('div');
                    card.classList.add('card');

                    card.innerHTML = `
                        <div class="image">
                         <img src="/storage/${officer.image || 'images/default.jpg'}" alt="${officer.fname}" />

                        </div>
                        <div class="content1">
                            <h2 class="name">${officer.fname + " " + officer.lname}</h2>
                            <p class="job">${officer.role}</p>
                            <p class="job">${officer.contact}</p>

                            <p class="job" style="color: ${statusColor};">${officer.status.toUpperCase()}</p>

                            
                        </div>
                    `;

                    cardContainer.appendChild(card);
                });
            })
            .catch((error) => console.error('Error fetching assignments:', error));
    }
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
</script>




<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Add Bootstrap JS for Modal functionality -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

/* -------------------------------------------------------------Popup------------------------------------------------------------------ */
.search-container {
    display: grid;
    /* grid-template-columns: 0.5fr 0.5fr; */
    gap: 7em 1.8rem;
    height: 35px;
    width: 350px;
    /* padding-right:20px; */

}

.container1 {
    display: grid;
    grid-template-columns: 1fr;
    /* 1fr for cards, 2fr for form */
    gap: 0.1rem;
    padding: 1rem;
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

.form-control {
    background-color: var(--color-white);
    color: var(--color-dark);
    padding-left: 50px;
}

/* Container for the cards */
.row {
    display: flex;
    /* flex-wrap: wrap; */
    /* justify-content: space-between; */
    gap: 300px;
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

/* Centering Button for Mobile View */
.text-center {
    text-align: center;
    margin-bottom: 20px;
}

/*--------------------------------------------------------*/


.row {
    display: inline-flex;
}
</style>

<style>
/* Left Section (Cards) */
.card-container {
    display: grid;
    grid-template-columns: 0.5fr 0.5fr 0.5fr;
    gap: 7rem 1.8rem;
    height: 20px;
    padding-top: 1px;
    /* overflow-y:auto;
    max-height:750px;
    */
}

/* Card Style */
.card {
    background-color: var(--color-white);
    padding: var(--card-padding);
    border-radius: var(--card-border-radius);
    box-shadow: var(--box-shadow);
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
    height: 90px;
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
    gap: 5px;
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



/* ======================================== */

/* Right Section (Form) */

/* @media (min-width: 1814px) { */
@media (min-width: 1100px) and (max-width: 1350px){
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

.form-group1 {
    border: 1px solid #ddd;
    border-radius: 50px;
    padding: 12px 20px;
    font-size: 1rem;
    margin-bottom: 15px;
    width: 65%;
    height: 43px;
    transition: border 0.3s ease;
    background-color: var(--color-white);
}

.form-control {
    background-color: var(--color-white);
    color: var(--color-dark);
    padding-left: 50px;
}

.form-controler {
    background-color: var(--color-white);
    color: var(--color-dark);
    padding-left: 3px;
}


/* Container for the cards */
.row {
    display: flex;
    /* flex-wrap: wrap; */
    /* justify-content: space-between; */
    gap: 100px;

}}

@media (min-width: 1338px) and (max-width: 1590px){
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

.form-control {
    background-color: var(--color-white);
    color: var(--color-dark);
    padding-left: 50px;
}

.form-controler {
    background-color: var(--color-white);
    color: var(--color-dark);
    padding-left: 3px;
}


/* Container for the cards */
.row {
    display: flex;
    /* flex-wrap: wrap; */
    /* justify-content: space-between; */
    gap: 150px;

}}

</style>

@endsection