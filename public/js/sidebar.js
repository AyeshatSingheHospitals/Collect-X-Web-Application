
    document.addEventListener("DOMContentLoaded", function() {
        const profileDropdown = document.getElementById("profileDropdown");
        const dropdownMenu = profileDropdown.querySelector(".dropdown-menu");

        profileDropdown.addEventListener("click", function(event) {
            event.stopPropagation();
            profileDropdown.classList.toggle("active");
        });

        document.addEventListener("click", function(event) {
            if (!profileDropdown.contains(event.target)) {
                profileDropdown.classList.remove("active");
            }
        });
    });