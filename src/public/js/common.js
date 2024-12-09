function toggleMenu(event) {
    event.preventDefault();
    const dropdown = document.querySelector('.dropdown-menu');
    dropdown.classList.toggle('active_');

    if (dropdown.classList.contains('active_')) {
        document.addEventListener('click', closeMenuOnClickOutside);
    }
}

function closeMenuOnClickOutside(event) {
    const dropdown = document.querySelector('.dropdown-menu');
    const menuLink = document.querySelector('.menu__link');

    if (!dropdown.contains(event.target) && !menuLink.contains(event.target)) {
        dropdown.classList.remove('active_');
        document.removeEventListener('click', closeMenuOnClickOutside);
    }
}

