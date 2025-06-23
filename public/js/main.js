$('#toggleSidebar').on('click', function () {
  $('#sidebar').toggleClass('collapsed');

  $('.menu-sidebar .submenu-sidebar:visible').stop(false, false).slideUp(100);

  $('.menu-sidebar .nav-link i.fa-angle-down.rotated').removeClass('rotated');
});

$('.offcanvas .btn-close').on('click', function () {
  $('.menu-sidebar .submenu-sidebar:visible').stop(false, false).slideUp(100);

  $('.menu-sidebar .nav-link i.fa-angle-down.rotated').removeClass('rotated');
});

// Xử lý xổ submenu
$('.menu-sidebar a.nav-link').on('click', function () {
  var navLink = $(this);
  var targetSubmenu = navLink.next('.submenu-sidebar');
  var icon = navLink.find('i.fa-angle-down');

  if (navLink.attr('href') == 'javascript:void(0)') {
    if (targetSubmenu.is(':visible')) {
      targetSubmenu.find('.submenu-sidebar').stop(false, false).slideUp(350, function () {
        $(this).prev('.nav-link').find('i.fa-angle-down').removeClass('rotated');
      });

      targetSubmenu.stop(false, false).slideUp(350);

      icon.removeClass('rotated');
    } else {
      targetSubmenu.stop(false, false).slideDown(350);

      icon.addClass('rotated');
    }
  }
});

// Layout
function adjustContentHeight() {
  const header = document.querySelector('header');
  const content = document.querySelector('.content-row');

  if (header && content) {
    const headerHeight = header.offsetHeight;
    content.style.maxHeight = `calc(100vh - ${headerHeight}px)`;
  }
}

// Gọi khi trang load xong + khi resize
window.addEventListener('load', adjustContentHeight);
window.addEventListener('resize', adjustContentHeight);



// Enable cho tooltips (bootstrap 5)
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))