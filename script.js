// Check if user is logged in based on the URL parameter
const urlParams = new URLSearchParams(window.location.search);
const isLoggedIn = urlParams.get('logged_in') === 'true';

// Elements for login and user-specific options
const desktopMenu = document.getElementById("desktopMenu");
const mobileMenu = document.getElementById("mobileMenu");
const loginLinkMain = document.getElementById("loginLink"); 
const mobileLoginLinkMain = document.getElementById("mobileLoginLink"); 
const cartLink = document.getElementById("cartLink");
const mobileCartLink = document.getElementById("mobileCartLink");
const getStartedBtn = document.getElementById("getStartedBtn");


function redirectToDogs() {
    window.location.href = "available-dogs.html"; 
}


let cartCount = 0;

// Function to update the cart count
function updateCartCount() {
    cartLink.textContent = `Cart (${cartCount})`;
    mobileCartLink.textContent = `Cart (${cartCount})`;
}

function addToCart() {
    cartCount += 1;
    updateCartCount();
}

// Check if logged in and adjust menu
if (isLoggedIn) {
    // Show options for logged-in users
    loginLinkMain.style.display = "none";
    mobileLoginLinkMain.style.display = "none";
    getStartedBtn.style.display = "none";

    const loggedInOptions = `
        <a href="logout.php" class="menu-item">Logout</a>
    `;

    desktopMenu.insertAdjacentHTML("beforeend", loggedInOptions);
    mobileMenu.insertAdjacentHTML("beforeend", loggedInOptions);
}

updateCartCount();

window.addEventListener('load', () => {
    const heading = document.querySelector('.main-heading');
    heading.classList.add('bounce');
});

document.querySelectorAll('.grid-image').forEach(image => {
    image.addEventListener('mouseover', () => {
        image.style.transform = 'scale(1.2)';
    });
    image.addEventListener('mouseleave', () => {
        image.style.transform = 'scale(1)';
    });
});

function toggleMenu() {
    document.querySelector('.mobile-menu').classList.toggle('show');
}

const loginModal = document.getElementById('loginModal');
const loginLinkModal = document.getElementById('login-link'); // Renamed for clarity
const mobileLoginLinkModal = document.getElementById('mobile-login-link'); // Renamed for clarity

loginLinkModal.addEventListener('click', openLoginModal);
mobileLoginLinkModal.addEventListener('click', openLoginModal);

function openLoginModal(event) {
    event.preventDefault();
    loginModal.style.display = 'flex';
}

function closeLoginModal() {
    loginModal.style.display = 'none';
}

window.onclick = function(event) {
    if (event.target == loginModal) {
        loginModal.style.display = 'none';
    }
};



// Function to open a blog page and increment views
function openBlogWithViews(blogPage, viewsId) {
    // Get the views count element
    const viewsCount = document.getElementById(viewsId);

    // Increment the views count
    viewsCount.textContent = parseInt(viewsCount.textContent) + 1;

    // Open the blog page
    window.location.href = blogPage;
}


// Function to handle like toggle
function toggleLike(likesId, iconElement) {
    // Get the likes count element
    const likesCount = document.getElementById(likesId);

    // Toggle the 'liked' class to change the color
    iconElement.classList.toggle('liked');

    // If the icon is liked, increment; otherwise, decrement
    if (iconElement.classList.contains('liked')) {
        likesCount.textContent = parseInt(likesCount.textContent) + 1;
    } else {
        likesCount.textContent = parseInt(likesCount.textContent) - 1;
    }
}



document.querySelector('.menu-item').addEventListener('click', function(event) {
    event.preventDefault();
    document.querySelector('#footers').scrollIntoView({ behavior: 'smooth' });
});




