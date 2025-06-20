$(document).ready(function () {
    const $toggleMenu = $("#toggleMenu");
    const $slideMenu = $("#slideMenu");

    // Toggle menu khi click nút
    $toggleMenu.on("click", function (e) {
        e.stopPropagation(); // Ngăn sự kiện lan ra window
        $slideMenu.toggleClass("show");
    });

    // Tự ẩn menu khi click bên ngoài
    $(window).on("click", function (e) {
        if (
            !$slideMenu.is(e.target) &&
            $slideMenu.has(e.target).length === 0 &&
            !$toggleMenu.is(e.target) &&
            $toggleMenu.has(e.target).length === 0
        ) {
            $slideMenu.removeClass("show");
        }
    });
});
