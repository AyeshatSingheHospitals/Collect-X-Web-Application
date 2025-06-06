@extends('supervisor.sidebar')

@section('content')
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
    --color-chack: #181a1e;


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
    --color-chack: #f6f6f9;
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

main .analyse svg {
    width: 7rem;
    height: 7rem;
}

main .analyse svg circle {
    fill: none;
    stroke-width: 10;
    stroke-linecap: round;
    transform: translate(5px, 5px);
}

main .analyse .sales svg circle {
    stroke: var(--color-success);
    stroke-dashoffset: -30;
    stroke-dasharray: 200;
}

main .analyse .visits svg circle {
    stroke: var(--color-danger);
    stroke-dashoffset: -30;
    stroke-dasharray: 200;
}

main .analyse .centers svg circle {
    stroke: var(--color-warning);
    stroke-dashoffset: -30;
    stroke-dasharray: 200;
}

main .analyse .searches svg circle {
    stroke: var(--color-danger);
    stroke-dashoffset: -30;
    stroke-dasharray: 200;
}

main .analyse .ros svg circle {
    stroke: var(--color-pink);
    stroke-dashoffset: -30;
    stroke-dasharray: 200;
}

main .analyse .labassigns svg circle {
    stroke: var(--color-orange);
    stroke-dashoffset: -30;
    stroke-dasharray: 200;
}

main .analyse .routeassigns svg circle {
    stroke: var(--color-purple);
    stroke-dashoffset: -30;
    stroke-dasharray: 200;
}

main .analyse .supervisors svg circle {
    stroke: var(--color-green);
    stroke-dashoffset: -30;
    stroke-dasharray: 200;
}

main .analyse .incharges svg circle {
    stroke: var(--color-primary);
    stroke-dashoffset: -30;
    stroke-dasharray: 200;
}

main .analyse .progresss .percentage {
    position: absolute;
    top: -3px;
    left: -1px;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    width: 100%;
    font-size: 30px;
    /* font-color: var(--color-danger); */
}

main .new-users {
    margin-top: 1.3rem;
}

main .new-users .user-list {
    background-color: var(--color-white);
    padding: var(--card-padding);
    border-radius: var(--card-border-radius);
    margin-top: 1rem;
    box-shadow: var(--box-shadow);
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
    gap: 1.4rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

main .new-users .user-list:hover {
    box-shadow: none;
}

main .new-users .user-list .user {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

main .new-users .user-list .user img {
    width: 5rem;
    height: 5rem;
    margin-bottom: 0.4rem;
    border-radius: 50%;
}

main .recent-orders {
    margin-top: 1.3rem;
}

main .recent-orders h2 {
    margin-bottom: 0.8rem;
}

main .recent-orders table {
    background-color: var(--color-white);
    width: 100%;
    padding: var(--card-padding);
    text-align: center;
    box-shadow: var(--box-shadow);
    border-radius: var(--card-border-radius);
    transition: all 0.3s ease;
}

main .recent-orders table:hover {
    box-shadow: none;
}

main table tbody td {
    height: 2.8rem;
    border-bottom: 1px solid var(--color-light);
    color: var(--color-dark-variant);
}

main table tbody tr:last-child td {
    border: none;
}

main .recent-orders a {
    text-align: center;
    display: block;
    margin: 1rem auto;
    color: var(--color-primary);
}

.right-section {
    margin-top: 1.4rem;
}

.right-section .nav {
    display: flex;
    justify-content: end;
    gap: 2rem;
}

.right-section .nav button {
    display: none;
}

.right-section .dark-mode {
    background-color: var(--color-light);
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 1.6rem;
    width: 4.2rem;
    cursor: pointer;
    border-radius: var(--border-radius-1);
}

.right-section .dark-mode span {
    font-size: 1.2rem;
    width: 50%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.right-section .dark-mode span.active {
    background-color: var(--color-primary);
    color: white;
    border-radius: var(--border-radius-1);
}

.right-section .nav .profile {
    display: flex;
    gap: 2rem;
    text-align: right;
}

.right-section .nav .profile .profile-photo {
    width: 2.8rem;
    height: 2.8rem;
    border-radius: 50%;
    overflow: hidden;
}

.right-section .user-profile {
    display: flex;
    justify-content: center;
    text-align: center;
    margin-top: 1rem;
    background-color: var(--color-white);
    padding: var(--card-padding);
    border-radius: var(--card-border-radius);
    box-shadow: var(--box-shadow);
    cursor: pointer;
    transition: all 0.3s ease;
}

.right-section .user-profile:hover {
    box-shadow: none;
}

.right-section .user-profile img {
    width: 11rem;
    height: auto;
    margin-bottom: 0.8rem;
    border-radius: 50%;
}

.right-section .user-profile h2 {
    margin-bottom: 0.2rem;
}

.right-section .reminders {
    margin-top: 2rem;
}

.right-section .reminders .header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0.8rem;
}

.right-section .reminders .header span {
    padding: 10px;
    box-shadow: var(--box-shadow);
    background-color: var(--color-white);
    border-radius: 50%;
}

.right-section .reminders .notification {
    background-color: var(--color-white);
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 0.7rem;
    padding: 1.4rem var(--card-padding);
    border-radius: var(--border-radius-2);
    box-shadow: var(--box-shadow);
    cursor: pointer;
    transition: all 0.3s ease;
}

.right-section .reminders .notification:hover {
    box-shadow: none;
}

.right-section .reminders .notification .content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 0;
    width: 100%;
}

.right-section .reminders .notification .icon {
    padding: 0.6rem;
    color: var(--color-white);
    background-color: var(--color-success);
    border-radius: 20%;
    display: flex;
}

.right-section .reminders .notification.deactive .icon {
    background-color: var(--color-danger);
}

.right-section .reminders .add-reminder {
    background-color: var(--color-white);
    border: 2px dashed var(--color-primary);
    color: var(--color-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}

.right-section .reminders .add-reminder:hover {
    background-color: var(--color-primary);
    color: white;
}

.right-section .reminders .add-reminder div {
    display: flex;
    align-items: center;
    gap: 0.6rem;
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

    main .analyse {
        grid-template-columns: 2fr 2fr 2fr;
        gap: 20px;
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

    main .analyse {
        grid-template-columns: 2fr 2fr 2fr;
        gap: 0;
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

    .right-section {
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

@media screen and (max-width: 548px) {
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

    main .analyse {
        grid-template-columns: 2fr;
        gap: 0;
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

    .right-section {
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

@media (min-width: 548px) and (max-width: 719px) {
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

    main .analyse {
        grid-template-columns: 2fr;
        gap: 0;
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

    .right-section {
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

/* @media screen and (max-width: 548px) */
@media (min-width:719px) and (max-width: 1040px) {
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

    main .analyse {
        grid-template-columns: 2fr 2fr;
        gap: 10px;
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

    .right-section {
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
</style>

<!-- <div class="container"> -->
<!-- Main Content -->
<main>
    <div class="top-bar">
        <h1>Dashboard</h1>
    </div>
    <br>

    <div class="row">
        <input type="hidden" name="uid" value="{{ session('uid') }}">

        <!-- Assigned Labs as Stylish Radio Buttons -->
        <div class="form-group1">
            <!-- <label style="color:#7f7f7f; font-size: 18px; font-weight: bold;">Select your Lab :</label> -->
            <div id="labOptions" class="radio-container">
                <p>Loading...</p> <!-- Placeholder while fetching data -->
            </div>
        </div>
    </div>

    <!-- Analyses Section -->
    <div class="analyse" id="analyseSection" style="display:none;">
        <div class="sales">
            <div class="status">
                <div class="info">
                    <h3>Total Sales</h3>
                    <h1 id="totalSales"></h1>
                </div>
                <div class="progresss">
                    <svg>
                        <circle cx="38" cy="38" r="36"></circle>
                    </svg>
                    <div class="percentage">
                        <i class='bx bx-money'></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="visits">
            <div class="status">
                <div class="info">
                    <h3>Total Routes</h3>
                    <h1 id="totalRoutes"></h1>
                </div>
                <div class="progresss">
                    <svg>
                        <circle cx="38" cy="38" r="36"></circle>
                    </svg>
                    <div class="percentage">
                        <i class='bx bxs-location-plus'></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="centers">
            <div class="status">
                <div class="info">
                    <h3>Total Centers</h3>
                    <h1 id="totalCenters"></h1>
                </div>
                <div class="progresss">
                    <svg>
                        <circle cx="38" cy="38" r="36"></circle>
                    </svg>
                    <div class="percentage">
                        <i class='bx bx-buildings'></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="incharges">
            <div class="status">
                <div class="info">
                    <h3>Total Relationship Officers</h3>
                    <h1 id="totalROs"></h1>
                </div>
                <div class="progresss">
                    <svg>
                        <circle cx="38" cy="38" r="36"></circle>
                    </svg>
                    <div class="percentage">
                        <i class='bx bxs-user-voice'></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading GIF -->
    <!-- <div class="col-12 mt-5 center-align-container"> -->
    <div id="loadingGif"  >
        <!-- <img src="path/to/your/loading.gif" alt="Loading..." /> -->
        <img src="{{ asset('../image/Data Analytics - Animation.gif') }}" alt="Loading..." class="col-12 mt-5 center-align-container"
            style="max-width: 500px; border-radius:50%; text-align:center; display:block;">
        <!-- </div> -->
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const labOptionsContainer = document.getElementById('labOptions');
    const analyseSection = document.getElementById('analyseSection');
    const loadingGif = document.getElementById('loadingGif');

    // Hide analysis section initially and show loading GIF
    analyseSection.style.display = 'none';
    loadingGif.style.display = 'block';

    // Fetch assigned labs
    fetch(`/lab/assigned-labs`)
        .then(response => response.json())
        .then(data => {
            labOptionsContainer.innerHTML = ''; // Clear existing options

            if (data.length === 0) {
                labOptionsContainer.innerHTML = `<p>No labs assigned</p>`;
            } else {
                data.forEach(lab => {
                    labOptionsContainer.innerHTML += `
                        <label class="lab-option">
                            <input type="radio" name="lid" value="${lab.lid}" required>
                            <span>${lab.name}</span>
                        </label>
                    `;
                });
            }
        })
        .catch(error => {
            console.error('Error fetching labs:', error);
            labOptionsContainer.innerHTML = `<p>Error loading labs</p>`;
        });

    // Listen for radio button change to fetch the selected lab details
    labOptionsContainer.addEventListener('change', function(e) {
        if (e.target.name === 'lid') {
            const labId = e.target.value;

            // Fetch details for the selected lab
            fetch(`/dashboard/lab-details`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content'),
                    },
                    body: JSON.stringify({
                        lid: labId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Update dashboard with the new data
                    document.getElementById('totalSales').textContent =
                        `Rs.${data.totalSales.toLocaleString()}`;
                    document.getElementById('totalRoutes').textContent = data.totalRoutes
                        .toLocaleString();
                    document.getElementById('totalCenters').textContent = data.totalCenters
                        .toLocaleString();
                    document.getElementById('totalROs').textContent = data.totalROs
                        .toLocaleString();

                    // Hide the loading GIF and show the analysis section
                    loadingGif.style.display = 'none';
                    analyseSection.style.display = 'block';
                })
                .catch(error => {
                    console.error('Error fetching lab details:', error);
                });
        }
    });
});
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
    padding-top: 9%;
    padding-left:28%;
}


/* Flex container for radio buttons (Single Row Layout) */
.radio-container {
    display: flex;
    flex-wrap: wrap;
    /* Allows wrapping if too many items */
    gap: 10px;
    /* Space between buttons */
    margin-top: 10px;
}

/* Beautiful Radio Button Styling */
.lab-option {
    display: flex;
    align-items: center;
    background:var(--color-pointer1);
    padding: 5px 10px;
    border-radius: 25px;
    font-size: 12px;
    font-weight: 600;
    color:var(--color-pointer2);
    cursor: pointer;
    transition: all 0.3s ease-in-out;
    border: 2px solid transparent;
}

.lab-option input[type="radio"] {
    display: none;
    /* Hide default radio button */
}

/* Style when radio button is selected */
.lab-option input[type="radio"]:checked+span {
    background:  #8e94f2;
    color: white;
    padding: 10px 20px;
    border-radius: 25px;
    transition: all 0.3s ease-in-out;
}

/* Hover Effect */
.lab-option:hover {
    border: 2px solid  #8e94f2;
    color:  #8e94f2;
}

/* Ensuring text inside buttons is centered */
.lab-option span {
    padding: 5px 10px;
}
</style>

</div>

<!-- <script src="index.js"></script> -->

@endsection