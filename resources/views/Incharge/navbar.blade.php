<!DOCTYPE html>
<html lang="en">

<head>
    <title>Nav bar</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

        body {
            height: 97vh;
            background: #f6f6f9;
            font-family: 'Poppins', sans-serif;
            font-size: 0.88rem;
            font-weight: 300;
            margin: 0;
        }

        nav {
            height: 40px;
            top: 10px;
            position: relative;
            background: #fff;
            padding: 20px 15px;
            border-radius: 50px;
            box-shadow: 0 2rem 3rem rgba(132, 139, 200, 0.18);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav .logo {
            margin-right: auto;
            padding-left: 15px;
        }

        nav .logo img {
            height: 40px;
            width: auto;
        }

        nav a {
            margin-right: 50px;
            position: relative;
            padding: 10px 20px;
            text-decoration: none;
            color: #333;
            border: 1px solid transparent;
            border-radius: 20px;
            transition: background-color 0.3s, color 0.3s, box-shadow 0.3s;
        }

        nav a:hover {
            background: linear-gradient(to left, #61045f, #aa076b);
            color: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        nav a.active {
            background: linear-gradient(to left, #61045f, #aa076b);
            color: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .avatar-container {
            display: flex;
            align-items: center;
            margin-left: auto;
        }

        .avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: #ccc;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-right: 10px;
        }

        .avatar img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
        }

        .user-name {
            display: flex;
            flex-direction: column;
            color: #333;
        }

        .user-name .greeting {
            font-size: 11px;
        }

        .user-name .name {
            font-size: 14px;
        }

        /* Media Query for Tablet Screens */
        @media (max-width: 768px) {
            nav {
                flex-direction: column;
                padding: 10px;
                height: auto;
            }

            nav .logo {
                margin-bottom: 10px;
            }

            nav a {
                margin-right: 0;
                margin-bottom: 10px;
                padding: 8px 16px;
                font-size: 14px;
            }

            .avatar-container {
                justify-content: center;
                margin-top: 10px;
            }

            .avatar {
                width: 50px;
                height: 50px;
            }

            .user-name .greeting {
                font-size: 10px;
            }

            .user-name .name {
                font-size: 13px;
            }
        }

        /* Media Query for Mobile Screens */
        @media (max-width: 480px) {
            nav {
                flex-direction: column;
                padding: 5px;
                height: auto;
            }

            nav a {
                font-size: 12px;
                margin-right: 0;
                margin-bottom: 8px;
                padding: 6px 12px;
            }

            .avatar-container {
                flex-direction: column;
                align-items: center;
                margin-top: 10px;
            }

            .avatar {
                width: 40px;
                height: 40px;
            }

            .user-name .greeting {
                font-size: 9px;
            }

            .user-name .name {
                font-size: 12px;
            }

            nav .logo img {
                height: 30px;
            }
        }
    </style>
</head>

<body>
    <nav>
        <!-- Logo Section -->
        <div class="logo">
            <img src="{{ asset('./image/collectxwbg.png') }}" alt="Logo">
        </div>

        <!-- Navigation Links -->
        <a href="/dash" class="{{ request()->is('dash') ? 'active' : '' }}"><b>Dashboard</b></a>
        <a href="/assign" class="{{ request()->is('assign') ? 'active' : '' }}"><b>Route Assign</b></a>
        <a href="#" class="{{ request()->is('transaction') ? 'active' : '' }}"><b>Transaction</b></a>

        <!-- Avatar Section -->
        <div class="avatar-container">
            <div class="avatar">
                <img src="https://th.bing.com/th/id/OIF.lKeokhLLPGVKRyBmXMKK8w?rs=1&pid=ImgDetMain" alt="Avatar">
            </div>
            <div class="user-name">
                <span class="greeting">Hey,</span>
                <span class="name">John Doe</span>
            </div>
        </div>
    </nav>

    <div class="content">
        @yield('content')
    </div>

</body>

</html>
