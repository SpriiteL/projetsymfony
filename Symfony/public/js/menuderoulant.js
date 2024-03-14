document.addEventListener("DOMContentLoaded", function() {
    const dropdownToggle = document.querySelectorAll('.dropdown-toggle');

    dropdownToggle.forEach(function(toggle) {
        toggle.addEventListener('click', function() {
            const dropdownMenu = this.nextElementSibling;
            if (dropdownMenu.style.display === 'block') {
                dropdownMenu.style.display = 'none';
            } else {
                dropdownMenu.style.display = 'block';
            }
        });
    });
});
