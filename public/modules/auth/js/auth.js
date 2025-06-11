$('.toggle-password').on('click', function () {
  const passwordField = $(this).prev('input');
  const toggleIcon = $(this).children('.toggle-icon');
  // Kiểm tra loại hiện tại của input
  const type = passwordField.attr('type') === 'password' ? 'text' : 'password';

  // Đặt loại mới cho input
  passwordField.attr('type', type);

  // Thay đổi icon dựa trên loại mới
  if (type === 'text') {
    toggleIcon.removeClass('fa-eye-slash').addClass('fa-eye');
  } else {
    toggleIcon.removeClass('fa-eye').addClass('fa-eye-slash');
  }
});