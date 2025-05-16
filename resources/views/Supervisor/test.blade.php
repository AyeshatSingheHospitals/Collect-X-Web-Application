<script>
document.addEventListener('DOMContentLoaded', function() {
    const labDropdown = document.getElementById('labDropdown');
    const loadingGif = document.getElementById('loadingGif');
    const cardContainer = document.getElementById('cardContainer');

    // Initially hide all individual cards but show the container
    cardContainer.style.display = 'block'; // Show the container
    document.querySelectorAll('.card').forEach(card => {
        card.style.display = 'none'; // Hide individual cards
    });
    
    // Show loading GIF
    loadingGif.style.display = 'flex';

    // Fetch assigned labs
    fetch(`/lab/assigned-labs`)
        .then(response => response.json())
        .then(data => {
            labDropdown.innerHTML = '<option value="" disabled selected>Select Lab</option>';

            data.forEach(lab => {
                labDropdown.innerHTML += `<option value="${lab.lid}">${lab.name}</option>`;
            });
        })
        .catch(error => {
            console.error('Error fetching labs:', error);
            labDropdown.innerHTML = '<option value="" disabled selected>Error loading labs</option>';
            loadingGif.style.display = 'none';
        });

    // Filter cards when lab is selected
    labDropdown.addEventListener('change', function() {
        const selectedLabId = this.value;
        const cards = document.querySelectorAll('.card');

        if (!selectedLabId) return;

        // Hide loading GIF when lab is selected
        loadingGif.style.display = 'none';

        // Show matching cards and hide others
        let hasVisibleCards = false;
        cards.forEach(card => {
            const centerLabId = card.getAttribute('data-lid');
            if (centerLabId === selectedLabId) {
                card.style.display = 'block';
                hasVisibleCards = true;
            } else {
                card.style.display = 'none';
            }
        });

        // If no cards matched, show a message
        if (!hasVisibleCards) {
            // You could add a "no results" message here if needed
            console.log("No centers found for selected lab");
        }
    });
});