
document.querySelectorAll(".menu__link").forEach((menuLink, index) => {
    menuLink.addEventListener("click", function (event) {
        event.preventDefault();
        const dropDownMenu = document.querySelectorAll(".dropdown-menu")[index];

        if (dropDownMenu.style.display === "block") {
            dropDownMenu.style.display = "none";
        } else {
            dropDownMenu.style.display = "block";
        }
    });
});