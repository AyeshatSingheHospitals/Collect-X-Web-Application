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
        height: 325px;
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
    #latitude,
    #longitude {
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

    #labList li,
    #editLabSearch li {
        padding: 5px;
        background-color: #F7E7FB;
    }

    #labList li:hover {
        background-color: #D53D70;
    }

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
