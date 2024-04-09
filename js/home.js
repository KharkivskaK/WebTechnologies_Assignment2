// Activating burger menu for the navigation bar
document.addEventListener("DOMContentLoaded", function () {

    // Menu button and the burger menu elements by their new IDs
    var menuBt = document.getElementById("menuButton");
    var burgerMenu = document.getElementById("burgerMenu");

    // Click event listener to the menu button
    menuBt.addEventListener("click", function () {

        // displaying property of the burger menu between 'block' and 'none'
        if (burgerMenu.style.display === "block") {
            burgerMenu.style.display = "none";
        } else {
            burgerMenu.style.display = "block";
        }

        this.classList.toggle("menu-active");
    });
});

// Activating link for the selected category
document.addEventListener('DOMContentLoaded', (event) => {
    const currentCategory = window.location.href;
    const navLinks = document.querySelectorAll('nav#pjnav ul.categories a');

    navLinks.forEach(link => {
        if (link.href === currentCategory) {
            link.classList.add('active');
        }
    });
});