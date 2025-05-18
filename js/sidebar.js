// Sidebar functionality
function openSidebar() {
    const navbar = document.getElementById('navbar');
    if (navbar) {
        navbar.classList.add('show');
    }
}

function closeSidebar() {
    const navbar = document.getElementById('navbar');
    if (navbar) {
        navbar.classList.remove('show');
    }
}

// Close sidebar when clicking outside
document.addEventListener('click', function(event) {
    const navbar = document.getElementById('navbar');
    const openSidebarBtn = document.getElementById('open-sidebar-btn');
    
    if (navbar && navbar.classList.contains('show') && 
        !navbar.contains(event.target) && 
        !openSidebarBtn.contains(event.target)) {
        navbar.classList.remove('show');
    }
}); 