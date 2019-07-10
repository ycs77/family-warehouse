require('./bootstrap');

// Dashboard sidebar
const sidebarToggler = document.querySelector('.dashboard-sidebar-toggler')
if (sidebarToggler) {
  sidebarToggler.addEventListener('click', () => {
    document.body.classList.add('show-sidebar')
    document.querySelector('.dashboard-sidebar').classList.add('show')
  })
}

const sidebarOverlay = document.querySelector('.dashboard-sidebar-overlay')
if (sidebarOverlay) {
  sidebarOverlay.addEventListener('click', () => {
    document.body.classList.remove('show-sidebar')
    document.querySelector('.dashboard-sidebar').classList.remove('show')
  })
}
