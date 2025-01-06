<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- <link rel="stylesheet" href="style.css"> -->
    <link rel="stylesheet" href="../css/sidebar.css">

    <!-- me tika awe selected contact search ek create krnkota  -->
    <!-- Include Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />


    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Sharp:opsz,wght,FILL,GRAD@48,400,0,0" />


    <title>CollectX</title>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');
    </style>


</head>

<body>

    <div class="container">
        <!-- Sidebar Section -->
        <aside>
            <div class="toggle">
                <div class="logo">
                    <h2>Collect<span class="danger">X</span></h2>
                </div>
                <div class="close" id="close-btn">
                    <span class="material-icons-sharp">close</span>
                </div>
            </div>

            <div class="sidebar">
                <a href="/admin/dashboard" class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <span class="material-symbols-sharp">grid_view</span>
                    <h3>Dashboard</h3>
                </a>
                <a href="/admin/labs" class="{{ request()->is('admin/labs') ? 'active' : '' }}">
                    <span class="material-symbols-sharp">science</span>
                    <h3>Lab Creation</h3>
                </a>
                <a href="/admin/routes" class="{{ request()->is('admin/routes') ? 'active' : '' }}">
                    <span class="material-symbols-sharp">add_road</span>
                    <h3>Route Creation</h3>
                </a>
                <a href="/admin/users" class="{{ request()->is('admin/users') ? 'active' : '' }}">
                    <span class="material-symbols-sharp">person_add</span>
                    <h3>User Creation</h3>
                </a>

                <a href="/admin/centers" class="{{ request()->is('admin/centers') ? 'active' : '' }}">
                    <span class="material-symbols-sharp">location_city</span>
                    <h3>Center Creation</h3>
                    <!-- <span class="msg_count">14</span> -->
                </a>
                <a href="/admin/labassigns" class="{{ request()->is('admin/labassigns') ? 'active' : '' }}">
                    <span class="material-symbols-sharp">medical_services</span>
                    <h3>Lab Assign</h3>
                </a>
                <a href="/admin/routeassigns" class="{{ request()->is('admin/routeassigns') ? 'active' : '' }}">
                    <span class="material-symbols-sharp">check_circle</span>
                    <h3>Route Assign</h3>
                </a>
                <a href="/transaction" class="{{ request()->is('transaction') ? 'active' : '' }}">
                    <span class="material-symbols-sharp">attach_money</span>
                    <h3>Transactions</h3>
                </a>
                <a href="/">
                    <span class="material-symbols-sharp">logout</span>
                    <h3>Logout</h3>
                </a>
            </div>
        </aside>

        <!-- End of Sidebar Section -->


        <div class="content">
            <div class="top-bar">
                <h1></h1>
                <div class="right-section">
                    <div class="nav">
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
                                <p>Hey, <b>{{ session('fname', 'Guest') }}</b></p>
                                <small class="text-muted">{{ session('role', 'Unknown Role') }}</small>
                            </div>
                            <div class="profile-photo">
                                <img src="{{ asset('storage/' . Session::get('image')) }}" alt="Profile"
                                    onerror="this.style.display='none'; this.parentNode.innerHTML += '<i class=\'bx bxs-user-circle\' style=\'font-size: 40px; color: rgb(156,161,221);\'></i>';">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @yield('content')
        </div>

    </div>


    <!-- <script>
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
    </script> -->

    <script>
    const sideMenu = document.querySelector('aside');
    const menuBtn = document.getElementById('menu-btn');
    const closeBtn = document.getElementById('close-btn');
    const darkMode = document.querySelector('.dark-mode');

    // Toggle dark mode and save preference to local storage
    darkMode.addEventListener('click', () => {
        document.body.classList.toggle('dark-mode-variables');
        const isDarkMode = document.body.classList.contains('dark-mode-variables');

        // Save the current mode in local storage
        localStorage.setItem('darkMode', isDarkMode ? 'enabled' : 'disabled');

        // Toggle active states on the dark mode icons
        darkMode.querySelector('span:nth-child(1)').classList.toggle('active');
        darkMode.querySelector('span:nth-child(2)').classList.toggle('active');
    });

    // Function to apply dark mode based on saved preference
    function applyDarkModePreference() {
        const darkModePreference = localStorage.getItem('darkMode');
        if (darkModePreference === 'enabled') {
            document.body.classList.add('dark-mode-variables');
            darkMode.querySelector('span:nth-child(1)').classList.remove('active');
            darkMode.querySelector('span:nth-child(2)').classList.add('active');
        } else {
            document.body.classList.remove('dark-mode-variables');
            darkMode.querySelector('span:nth-child(1)').classList.add('active');
            darkMode.querySelector('span:nth-child(2)').classList.remove('active');
        }
    }

    // Apply dark mode preference on page load
    window.addEventListener('load', applyDarkModePreference);

    menuBtn.addEventListener('click', () => {
        sideMenu.style.display = 'block';
    });

    closeBtn.addEventListener('click', () => {
        sideMenu.style.display = 'none';
    });
    </script>


</body>

</html>