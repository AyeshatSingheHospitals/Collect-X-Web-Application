@extends('admin.sidebar')

@section('content')

<!-- Registration Form -->
<main>
    <div class="top-bar">
        <h1>Center Create</h1>
        <div class="right-section">
            <!-- <div class="nav">
                <button id="menu-btn">
                    <span class="material-icons-sharp">
                        menu
                    </span>
                </button>
                <div class="dark-mode">
                    <span class="material-icons-sharp active">
                        light_mode
                    </span>
                    <span class="material-icons-sharp">
                        dark_mode
                    </span>
                </div>

                <div class="profile">
                    <div class="info">
                        <p>Hey, <b>Reza</b></p>
                        <small class="text-muted">Admin</small>
                    </div>
                    <div class="profile-photo">
                        <img src="image/avatar.jpg" alt="Profile">
                    </div>
                </div>
            </div> -->
        </div>
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
                <div class="form-group">
                    <label for="uid">User ID</label>
                    <input type="text" name="uid" id="uid" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="rid">Route ID</label>
                    <input type="text" name="rid" id="rid" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="centername">Center Name</label>
                    <input type="text" name="centername" id="centername" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="authorizedperson">Authorized Person</label>
                    <input type="text" name="authorizedperson" id="authorizedperson" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="phonenumber">Phone Number</label>
                    <input type="text" name="phonenumber" id="phonenumber" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-control"></textarea>
                </div>

                <div class="form-group">
                    <label for="latitude">Latitude</label>
                    <input type="text" name="latitude" id="latitude" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="longitude">Longitude</label>
                    <input type="text" name="longitude" id="longitude" class="form-control" required>
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
        padding: 15px 32px;
    margin: 4px 2px;
    border: none;
    font-size: 16px;
    border-radius: 50px;
    background-color: #628ECB;
    color: white;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
    }

    .glow-on-hover:hover {
        background-color: #365485;
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

    /* General Styles */
    /* body {
        font-family: 'Poppins', sans-serif;
        color: #333;
        background-color: #f6f6f9;
        padding: 20px;
        margin: 0;
        box-sizing: border-box;
    } */

    /* h1 {
        text-align: center;
        font-size: 2rem;
        margin-bottom: 20px;
    } */

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
        background-color: #628ECB;
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


Orders.forEach(order => {
    const tr = document.createElement('tr');
    const trContent = `
            <td>${order.productName}</td>
            <td>${order.productNumber}</td>
            <td>${order.paymentStatus}</td>
            <td class="${order.status === 'Declined' ? 'danger' : order.status === 'Pending' ? 'warning' : 'primary'}">${order.status}</td>
            <td class="primary">Details</td>
        `;
    tr.innerHTML = trContent;
    document.querySelector('table tbody').appendChild(tr);
});



const Orders = [{
        productName: 'JavaScript Tutorial',
        productNumber: '85743',
        paymentStatus: 'Due',
        status: 'Pending'
    },
    {
        productName: 'CSS Full Course',
        productNumber: '97245',
        paymentStatus: 'Refunded',
        status: 'Declined'
    },
    {
        productName: 'Flex-Box Tutorial',
        productNumber: '36452',
        paymentStatus: 'Paid',
        status: 'Active'
    },
]
</script>

<!-- FontAwesome for icons -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

@endsection