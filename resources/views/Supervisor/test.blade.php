<!-- Edit Amount Modal -->
<div id="edit-modal" style="display:none;" class="modal">
    <div class="modal-content">
        <h2>Edit Amount</h2><br>
        <form id="edit-form">
            @csrf
            <input type="hidden" id="tid" name="tid">
            <input type="hidden" name="uid" value="{{ session('uid') }}">
            <input type="text" name="sms" id="sms" readonly>

            <div class="form-group1">
                <label for="amount">Amount : LKR</label><br>
                <input type="number" id="amount" name="amount" class="form-control" required min="0" oninput="generateSmsText()">
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancel</button>
        </form>
    </div>
</div>

<script>
    // Open Edit Modal and Populate Fields
    function openEditModal(transaction) {
        document.getElementById('tid').value = transaction.id;
        document.getElementById('amount').value = transaction.amount; // Existing amount
        document.getElementById('sms').value = ''; // Clear sms initially

        // Generate the SMS text
        generateSmsText(transaction);
        
        document.getElementById('edit-modal').style.display = 'block';
    }

    // Close Edit Modal
    function closeEditModal() {
        document.getElementById('edit-modal').style.display = 'none';
    }

    // Generate SMS text dynamically
    function generateSmsText(transaction = null) {
        const amountInput = document.getElementById('amount');
        const smsInput = document.getElementById('sms');

        // Ensure transaction data is available
        if (!transaction) {
            transaction = {
                center_name: document.getElementById('tid').getAttribute('data-center-name'),
                full_name: document.getElementById('tid').getAttribute('data-full-name'),
                remark: document.getElementById('tid').getAttribute('data-remark'),
            };
        }

        const centerName = transaction.center_name || 'N/A';
        const fullName = transaction.full_name || 'N/A';
        const remark = transaction.remark || 'No remark';
        const updatedBy = "{{ session('uid') }}";
        const newAmount = amountInput.value || 0;

        smsInput.value = `Transaction updated: Center - ${centerName}, User - ${fullName} (RO), Updated by: ${updatedBy}, Amount - LKR ${newAmount}. Remark: ${remark}.`;
    }
</script>


<main>
    <div class="top-bar">
        <h1>Transaction Records</h1>
    </div>
    <br><br>

    <!-- Assigned Labs Dropdown -->
    <div class="form-group">
        <input type="hidden" name="uid" value="{{ session('uid') }}">
        <label for="labDropdown" style="color:#7f7f7f">Select your Lab:</label>
        <select name="lid" id="labDropdown" class="form-control" required>
            <option value="" disabled selected>Loading...</option>
        </select>
    </div>

    <div class="table-container">
        <table class="transactions-table">
            <thead>
                <tr>
                    <th>TID</th>
                    <th>Date</th>
                    <th>Full Name</th>
                    <th>Center Name</th>
                    <th>Amount</th>
                    <th>Remark</th>
                    <th>SMS Description</th>
                    <th>Actions</th> <!-- New column for actions -->
                </tr>
            </thead>
            <tbody id="transactionTableBody">
                <tr>
                    <td colspan="8" class="text-center">No data available</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Edit Amount Modal -->
    <div id="edit-modal" style="display:none;" class="modal">
        <div class="modal-content">
            <h2>Edit Amount</h2><br>
            <form id="edit-form">
                @csrf
                <input type="text" id="tid" name="tid">
                <input type="text" name="uid" value="{{ session('uid') }}">
                <input type="text" name="sms" id="sms">

                <div class="form-group1">
                    <label for="amount">Amount : LRK</label><br>
                    <input type="number" id="amount" name="amount" class="form-control" required min="0">
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
                <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancel</button>
            </form>
        </div>
    </div>

</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const uid = document.querySelector('input[name="uid"]').value;
    const labDropdown = document.getElementById('labDropdown');
    const transactionTableBody = document.getElementById('transactionTableBody');

    // Fetch assigned labs
    fetch(`/incharge/assigned-labs`)
        .then(response => response.json())
        .then(data => {
            labDropdown.innerHTML = ''; // Clear existing options

            if (data.length === 0) {
                labDropdown.innerHTML = `<option value="" disabled selected>No labs assigned</option>`;
            } else {
                labDropdown.innerHTML = `<option value="" disabled selected>Select a Lab</option>`;
                data.forEach(lab => {
                    labDropdown.innerHTML += `<option value="${lab.lid}">${lab.name}</option>`;
                });
            }
        })
        .catch(error => {
            console.error('Error fetching labs:', error);
            labDropdown.innerHTML = `<option value="" disabled selected>Error loading labs</option>`;
        });

    // Fetch transactions on lab selection
    labDropdown.addEventListener('change', function() {
        const lid = labDropdown.value;

        // Fetch transactions for the selected lab
        fetch(`/supervisor/transactions?lid=${lid}`)
            .then(response => response.json())
            .then(data => {
                transactionTableBody.innerHTML = ''; // Clear the table

                if (data.length === 0) {
                    transactionTableBody.innerHTML = `<tr>
                                <td colspan="8" class="text-center">No transactions found</td>
                            </tr>`;
                } else {
                    data.forEach(transaction => {
                        transactionTableBody.innerHTML += `
                                    <tr>
                                        <td>${transaction.tid}</td>
                                        <td>${transaction.date}</td>
                                        <td>${transaction.full_name}</td>
                                        <td>${transaction.center_name}</td>
                                        <td>LRK ${transaction.amount}</td>
                                        <td>${transaction.remark}</td>
                                        <td>${transaction.sms_description || 'N/A'}</td>
                                        <td>
                                            <button class="edit-btn" data-id="${transaction.tid}" data-amount="${transaction.amount}" style="font-size:1.2rem;">
                                    <i class='bx bxs-pen'></i>
                                </button>
                                        </td>
                                        
                                    </tr>`;
                    });

                    // Attach event listeners to edit buttons
                    document.querySelectorAll('.edit-btn').forEach(button => {
                        button.addEventListener('click', function() {
                            const tid = this.getAttribute('data-id');
                            const amount = this.getAttribute('data-amount');
                            openEditModal(tid, amount);
                        });
                    });
                }
            })
            .catch(error => {
                console.error('Error fetching transactions:', error);
                transactionTableBody.innerHTML = `<tr>
                            <td colspan="8" class="text-center">Error loading transactions</td>
                        </tr>`;
            });
    });

 // Open edit modal
function openEditModal(tid, amount) {
    const modal = document.getElementById('edit-modal');
    document.getElementById('tid').value = tid;
    document.getElementById('amount').value = amount;
    modal.style.display = 'block';
}

// Close edit modal
function closeEditModal() {
    const modal = document.getElementById('edit-modal');
    modal.style.display = 'none';
}

// Attach event listener to the cancel button (optional enhancement)
document.querySelector('.btn-secondary').addEventListener('click', closeEditModal);


    // Submit updated amount
    document.getElementById('edit-form').addEventListener('submit', function(event) {
        event.preventDefault();

        const tid = document.getElementById('tid').value;
        const amount = document.getElementById('amount').value;

        fetch(`/admin/transaction/${tid}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    amount
                }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);

                    // Update the amount directly in the table
                    const row = document.querySelector(`button[data-id="${tid}"]`).closest('tr');
                    row.querySelector('td:nth-child(5)').textContent = amount;

                    // Close the modal
                    closeEditModal();
                } else {
                    alert('Failed to update amount.');
                }
            })
            .catch(error => {
                console.error('Error updating amount:', error);
                alert('An unexpected error occurred.');
            });
    });
});
</script>

<!-- JavaScript -->
<script>
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


<!-- FontAwesome for icons -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<!-- refer this code properly and remember. this is my "resources\views\Supervisor\transaction.blade.php" -->