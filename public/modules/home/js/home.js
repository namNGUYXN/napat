$('.department-list .department-toggle').on('click', function (e) {
  var departmentLink = $(this);

  if (departmentLink.attr('href') == 'javascript:void(0)') {
    departmentLink.next('.department-submenu-sidebar').stop(false, false).slideToggle(500);

    departmentLink.find('i.fa-angle-down').toggleClass('rotated');
  }
});