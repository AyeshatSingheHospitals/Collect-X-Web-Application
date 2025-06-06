@extends('admin.sidebar')

@section('content')

<main>
    <div class="top-bar">
        <h1>Transaction Records</h1>
    </div>
    <br><br>

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

    <div class="form-group">
        <label for="searchInput" style="color:#7f7f7f">Search Records:</label>
        <input type="text" id="searchInput" class="form-control"
            placeholder="Search by Transaction ID, Name, Center Name, or Date">
    </div>

    <div class="table-container">
        <div class="scrollable-wrapper">
            <table class="transactions-table">
                <thead>
                    <tr>
                        <th>TID</th>
                        <th>Date</th>
                        <th>Full Name</th>
                        <th>Center Name</th>
                        <th>Bill_Amount</th>
                        <th>Collected_Amount</th>
                        <th>Difference_Amount</th>
                        <th>Remark</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="transactionTableBody">
                    @forelse ($transactions as $transaction)
                    <tr class="main-row" data-id="{{ $transaction->tid }}">
                        <td>{{ $transaction->tid }}</td>
                        <td>{{ $transaction->created_at }}</td>
                        <td>{{ $transaction->systemuser->fname }} {{ $transaction->systemuser->lname }}</td>
                        <td>{{ $transaction->center->centername }}</td>
                        <td class="amount-cell">LRK {{ number_format($transaction->bill_amount, 2) }}</td>
                        <td class="amount-cell">LRK {{ number_format($transaction->amount, 2) }}</td>
                        <td class="amount-cell">LRK {{ number_format($transaction->difference_amount, 2) }}</td>
                        <td>{{ $transaction->remark }}</td>
                        <td>
                            <button class="edit-btn" data-id="{{ $transaction->tid }}"
                                data-amount="{{ $transaction->amount }}"
                                data-bill_amount="{{ $transaction->bill_amount }}">
                                <i class='bx bxs-pen'></i>
                            </button>
                        </td>
                    </tr>
                    <tr class="sms-details-row" style="display: none;" data-id="{{ $transaction->tid }}">
                        <td colspan="9">
                            <div class="sms-container">
                                <h4>SMS Messages (TID: {{ $transaction->tid }})</h4>
                                @forelse ($transaction->sms as $sms)
                                <div class="sms-message">
                                    <p>{{ $sms->description ?? 'No description' }}</p>
                                    <small>Sent to:
                                        @isset($sms->phonenumber1) {{ $sms->phonenumber1 }} @endisset
                                        @isset($sms->phonenumber2) , {{ $sms->phonenumber2 }} @endisset
                                        @isset($sms->phonenumber3) , {{ $sms->phonenumber3 }} @endisset
                                    </small>
                                </div>
                                @empty
                                <p>No SMS messages found for this transaction</p>
                                @endforelse
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">No transactions available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Amount Modal -->
    <div id="edit-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <h2>Edit Amount</h2>
            <form method="POST" id="edit-form">
                @csrf
                @method('PUT')
                <input type="hidden" name="uid" value="{{ session('uid') }}">
                <input type="hidden" name="tid" id="edit-tid">

                <label for="bill_amount">New Bill Amount:</label>
                <input type="number" name="bill_amount" id="edit-bill_amount" step="0.01" required>

                <label for="amount">Collected Amount:</label>
                <input type="number" name="amount" id="edit-amount" step="0.01" required>
                <hr>
                <p><label for="difference_amount">Difference Amount:</label>
                    <input type="number" id="edit-difference_amount" name="difference_amount" readonly
                        class="difference-amount">
                </p>
                <hr>
                <button type="submit">Update</button>
                <button type="button" id="cancel-btn">Cancel</button>
            </form>
        </div>
    </div>
</main>

<!-- Script for calculations -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const differenceAmountInput = document.getElementById('edit-difference_amount');
    const billAmountInput = document.getElementById('edit-bill_amount');
    const amountInput = document.getElementById('edit-amount');

    // Function to calculate and update difference amount with color
    function updateDifferenceAmount() {
        const billAmount = parseFloat(billAmountInput.value) || 0;
        const amount = parseFloat(amountInput.value) || 0;
        const difference = amount - billAmount;

        differenceAmountInput.value = difference.toFixed(2);


        updateDifferenceAmountColor();
    }

    // Function to update the color based on value
    function updateDifferenceAmountColor() {
        const value = parseFloat(differenceAmountInput.value);

        // Remove all color classes first
        differenceAmountInput.classList.remove('negative', 'positive', 'zero');

        if (value < 0) {
            differenceAmountInput.classList.add('negative');
        } else if (value > 0) {
            differenceAmountInput.classList.add('positive');
        } else {
            differenceAmountInput.classList.add('zero');
        }
    }

    // Event listeners for automatic calculation
    billAmountInput.addEventListener('input', updateDifferenceAmount);
    amountInput.addEventListener('input', updateDifferenceAmount);

    // Initialize when modal opens (if needed)
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            // ... your existing modal open code ...
            updateDifferenceAmount(); // Calculate initial difference
        });
    });
});
</script>
<!-- Script for calculations -->

<!-- Script for message loading -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle row clicks to show/hide SMS messages
    document.querySelectorAll('.main-row').forEach(row => {
        row.addEventListener('click', function(e) {
            // Don't trigger if clicking on the edit button
            if (e.target.closest('.edit-btn')) {
                return;
            }

            const tid = this.getAttribute('data-id');
            const smsRow = document.querySelector(`.sms-details-row[data-id="${tid}"]`);

            // Toggle display
            smsRow.style.display = smsRow.style.display === 'none' ? 'table-row' : 'none';

            // Scroll to the expanded row if showing
            if (smsRow.style.display === 'table-row') {
                smsRow.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest'
                });
            }
        });
    });

    // Handle edit button clicks (prevent row click event)
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            // Your existing edit button logic here
            const tid = this.getAttribute('data-id');
            const amount = this.getAttribute('data-amount');
            console.log('Edit transaction:', tid, amount);
            // Add your edit modal or form handling here
        });
    });
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".clickable-row").forEach(row => {
        row.addEventListener("click", function(event) {
            // Prevent clicking on the Edit button inside the row
            if (!event.target.closest('.edit-btn')) {
                window.location.href = this.dataset.href;
            }
        });
    });
});
</script>
<!-- Script for message loading -->

<!-- Script for edit popup -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('edit-modal');
    const cancelBtn = document.getElementById('cancel-btn');
    const billAmountInput = document.getElementById('edit-bill_amount');
    const amountInput = document.getElementById('edit-amount');
    const differenceAmountInput = document.getElementById('edit-difference_amount');

    // Open modal and populate fields
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            const tid = this.dataset.id;
            const bill_amount = parseFloat(this.dataset.bill_amount) || 0;
            const amount = parseFloat(this.dataset.amount) || 0;
            const difference_amount = amount - bill_amount; // Calculate difference

            document.getElementById('edit-tid').value = tid;
            billAmountInput.value = bill_amount;
            amountInput.value = amount;
            differenceAmountInput.value = difference_amount.toFixed(2); // Display formatted value
            

            // Set the form's action URL dynamically
            document.getElementById('edit-form').action = `/admin/transaction/${tid}`;

            modal.style.display = 'block';
        });
    });

    // Function to calculate the difference
    function calculateDifference() {
        const billAmount = parseFloat(billAmountInput.value) || 0;
        const amount = parseFloat(amountInput.value) || 0;
        const difference = amount - billAmount;
        differenceAmountInput.value = difference.toFixed(2); // Display formatted value


    }

    // Listen for input changes and recalculate the difference
    billAmountInput.addEventListener('input', calculateDifference);
    amountInput.addEventListener('input', calculateDifference);

    // Close modal
    cancelBtn.addEventListener('click', function() {
        modal.style.display = 'none';
    });
});
</script>
<!-- Script for edit popup -->

<!-- Script for searching table -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById("searchInput");
    const tableBody = document.getElementById("transactionTableBody");
    const originalRows = Array.from(tableBody.querySelectorAll("tr.main-row, tr.sms-details-row"));
    let noDataRow = null;

    // Create a no data row if it doesn't exist
    function createNoDataRow() {
        if (!noDataRow) {
            noDataRow = document.createElement("tr");
            noDataRow.innerHTML = `<td colspan="9" class="text-center">No data found</td>`;
        }
        return noDataRow;
    }

    // Function to clean and normalize text for comparison
    function normalizeText(text) {
        return text.toLowerCase().trim();
    }

    // Function to clean amount by removing currency symbols and formatting
    function cleanAmount(amountText) {
        return amountText.replace(/[^0-9.]/g, '');
    }

    searchInput.addEventListener("input", function() {
        const query = normalizeText(searchInput.value);
        let hasVisibleRows = false;

        // Clear the table body
        tableBody.innerHTML = '';

        if (query === '') {
            // If search is empty, restore all original rows
            originalRows.forEach(row => {
                tableBody.appendChild(row);
            });
            return;
        }

        // Filter and display matching rows
        originalRows.forEach(row => {
            if (row.classList.contains('main-row')) {
                const columns = row.querySelectorAll("td");
                if (columns.length > 0) {
                    // Extract and normalize data from each relevant column
                    const tid = normalizeText(columns[0].textContent);
                    const date = normalizeText(columns[1].textContent);
                    const fullName = normalizeText(columns[2].textContent);
                    const centerName = normalizeText(columns[3].textContent);
                    const billAmount = cleanAmount(columns[4].textContent);
                    const collectedAmount = cleanAmount(columns[5].textContent);
                    const differenceAmount = cleanAmount(columns[6].textContent);
                    const remark = normalizeText(columns[7].textContent);

                    // Check if any column matches the search query
                    if (tid.includes(query) || 
                        date.includes(query) || 
                        fullName.includes(query) || 
                        centerName.includes(query) || 
                        billAmount.includes(query) || 
                        collectedAmount.includes(query) || 
                        differenceAmount.includes(query) ||
                        remark.includes(query)) {
                        
                        // Add the main row
                        tableBody.appendChild(row);
                        hasVisibleRows = true;
                        
                        // Find and add the corresponding sms-details-row if it exists
                        const rowId = row.getAttribute('data-id');
                        const smsRow = originalRows.find(r => 
                            r.classList.contains('sms-details-row') && 
                            r.getAttribute('data-id') === rowId
                        );
                        
                        if (smsRow) {
                            tableBody.appendChild(smsRow);
                        }
                    }
                }
            }
        });

        // If no rows matched the search, show "No data found" message
        if (!hasVisibleRows) {
            tableBody.appendChild(createNoDataRow());
        }
    });

    // Initialize the table with all rows
    originalRows.forEach(row => {
        tableBody.appendChild(row);
    });
});
</script>
<!-- Script for searching table -->

<!-- CSS -->
<style>
.difference-amount {
    font-weight: bold;
    /* Default color for zero */
    color: #333;
}

.difference-amount.negative {
    color: #FF0060;
    /* Red for negative */
}

.difference-amount.positive {
    color: #ADC865;
    /* Green for positive */
}

.difference-amount.zero {
    color: #333;
    /* Default for zero */
}
</style>

<!-- CSS for message showing-->
<style>
hr {
    border: 0;
    height: 1px;
    background: #ddd;
    /* Light gray color */
    margin: 20px 0;
    /* Add margin above and below the line */
}

.sms-container {
    padding: 15px;
    background-color: var(--color-background);
    border-radius: 5px;
    margin: 5px 0;
}

.sms-message {
    padding: 10px;
    margin-bottom: 10px;
    background-color: white;
    border-left: 4px solid #4e73df;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    background-color: var(--color-white);

}

.sms-message small {
    /* color: #6c757d; */
    font-size: 0.85rem;
    /* color: var(--color-dark); */

}
.sms-container p {
    /* color: #6c757d; */
    /* font-size: 0.85rem; */
    color: var(--color-dark);

}

.main-row {
    cursor: pointer;
    transition: background-color 0.2s;
}

.main-row:hover {
    background-color: #f5f5f5;
}


</style>
<!-- CSS for message showing-->

<style>
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

.table-container {
    /* padding: 20px; */
}

.scrollable-wrapper {
    max-height: 600px;
    /* Adjust this height as needed */
    overflow-y: auto;
    scrollbar-width: thin;
    /* For Firefox */
    scrollbar-color: transparent transparent;
    /* For Firefox */
}

.scrollable-wrapper::-webkit-scrollbar {
    width: 8px;
    /* Adjust width of the scrollbar */
}

.scrollable-wrapper::-webkit-scrollbar-thumb {
    background-color: transparent;
    /* Invisible scrollbar thumb */
}

.scrollable-wrapper::-webkit-scrollbar-track {
    background: transparent;
    /* Invisible track */
}

.transactions-table {
    width: 100%;
    border-collapse: collapse;
}

.transactions-table th,
.transactions-table td {
    padding: 8px;
    text-align: left;
    /* border: 1px solid #ddd; */
}

.transactions-table th {
    background-color: #f4f4f4;
    position: sticky;
    top: 0;
    /* This keeps the header at the top of the table */
    z-index: 1;
    /* Ensures the header stays on top of the table body */
}

/* Show scrollbar when scrolling */
.scrollable-wrapper:hover::-webkit-scrollbar-thumb {
    background-color: #888;
    /* Visible scrollbar thumb */
}

.scrollable-wrapper:hover::-webkit-scrollbar {
    opacity: 1;
}

.modal {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    padding: 60px;
    border-radius: 20px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
}

.modal-content {
    background-color: var(--color-white);
    color: var(--color-dark);
    display: flex;
    flex-direction: column;
    line-height: 2.2;
}

.modal-content button {
    margin-top: 10px;
    font-size: 14px;
}

.modal {
    background-color: var(--color-white);
    color: var(--color-dark);
    z-index: 1050 !important;
    /* Ensure modal is above other elements */
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
input[type="number"]{
    background-color: var(--color-white);
    color: var(--color-dark);
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