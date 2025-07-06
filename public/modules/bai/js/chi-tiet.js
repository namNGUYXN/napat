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



const currentUser = 'userA';
const MAX_NESTING_LEVEL = 4;

function createCommentHtml(comment, currentLevel = 0) {
    const isOwner = comment.ownerId === currentUser;
    const commentId = comment.id;
    const commentAuthor = comment.author;
    const commentContent = comment.content;
    const timeAgo = comment.timeAgo;

    const dropdownHtml = isOwner ? `
      <div class="dropdown comment-actions-dropdown">
          <button class="btn btn-transparent dropdown-toggle hide-arrow-down" type="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fas fa-ellipsis-v"></i>
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item edit-comment-btn" href="#" data-comment-id="${commentId}">Chỉnh sửa</a></li>
              <li><a class="dropdown-item delete-comment-btn" href="#" data-comment-id="${commentId}">Xóa</a></li>
          </ul>
      </div>
  ` : '';

    const showReplyButton = (currentLevel < MAX_NESTING_LEVEL - 1);
    const replyButtonHtml = showReplyButton ? `
      <a href="#" class="text-primary me-2 reply-btn" data-comment-id="${commentId}" data-comment-author="${commentAuthor}" data-comment-level="${currentLevel}">Phản hồi</a>
  ` : '';

    const nestedRepliesHtml = comment.replies && comment.replies.length > 0
        ? comment.replies.map(reply => createCommentHtml(reply, currentLevel + 1)).join('')
        : '';

    const totalReplies = comment.replies ? comment.replies.length : 0;

    let toggleRepliesButtonHtml = '';
    if (totalReplies > 0) {
        toggleRepliesButtonHtml = `
        <small>
            <a href="#" class="toggle-replies-btn text-muted" data-comment-id="${commentId}" data-has-replies="true" data-toggle-state="hidden">
                Có ${totalReplies} phản hồi <i class="fas fa-caret-down"></i>
            </a>
        </small>
    `;
    }

    return `
      <div class="list-group-item comment-item" data-comment-id="${commentId}" data-comment-owner-id="${comment.ownerId}" data-comment-level="${currentLevel}">
          <div class="d-flex w-100 justify-content-between align-items-center">
              <h6 class="mb-1 me-auto">${commentAuthor}</h6>
              <small class="text-muted me-2">${timeAgo}</small>
              ${dropdownHtml}
          </div>
          <p class="mb-1 comment-content-text">${commentContent}</p>
          
          <div class="edit-form-container mt-2" style="display: none;">
              <form class="edit-comment-form d-flex align-items-end">
                  <div class="flex-grow-1 me-2">
                      <textarea class="form-control form-control-sm" rows="2" required>${commentContent}</textarea>
                  </div>
                  <button type="submit" class="btn btn-sm btn-success me-1">Lưu</button>
                  <button type="button" class="btn btn-sm btn-secondary cancel-edit-btn">Hủy</button>
              </form>
          </div>
          
          <small class="comment-action-links">
              ${replyButtonHtml}
          </small>
          
          ${toggleRepliesButtonHtml}

          <div class="reply-form-container mt-2" style="display: none;">
              <form class="reply-form d-flex align-items-end">
                  <div class="flex-grow-1 me-2">
                      <textarea class="form-control form-control-sm" rows="2" placeholder="Phản hồi lại ${commentAuthor}..." required></textarea>
                  </div>
                  <button type="submit" class="btn btn-sm btn-outline-primary">Gửi</button>
              </form>
          </div>
          
          <div class="replies-container mt-2 hidden-replies">
              ${nestedRepliesHtml}
          </div>
      </div>
  `;
}

const mockCommentsData = [
    {
        id: 'cmt1', author: 'Người dùng A', ownerId: 'userA', timeAgo: '2 giờ trước', content: 'Đây là nội dung của bình luận đầu tiên.', parentId: null, replies: [
            {
                id: 'cmt1.1', author: 'Người dùng B', ownerId: 'userB', timeAgo: '1 giờ trước', content: '@Người dùng A: Tôi hoàn toàn đồng ý!', parentId: 'cmt1', replies: [
                    {
                        id: 'cmt1.1.1', author: 'Người dùng C', ownerId: 'userC', timeAgo: '30 phút trước', content: '@Người dùng B: Cảm ơn bạn đã xác nhận!', parentId: 'cmt1.1', replies: [
                            { id: 'cmt1.1.1.1', author: 'Người dùng D', ownerId: 'userD', timeAgo: '15 phút trước', content: '@Người dùng C: Không có gì, rất vui được thảo luận.', parentId: 'cmt1.1.1', replies: [] }
                        ]
                    },
                    { id: 'cmt1.1.2', author: 'Người dùng E', ownerId: 'userE', timeAgo: '20 phút trước', content: '@Người dùng B: Bình luận của bạn rất có giá trị.', parentId: 'cmt1.1', replies: [] }
                ]
            }
        ]
    },
    { id: 'cmt2', author: 'Admin', ownerId: 'userA', timeAgo: 'Hôm qua', content: 'Chào mừng các bạn đến với phần bình luận!', parentId: null, replies: [] }
];

function loadInitialComments() {
    if ($('#loadingCommentsMessage').length) {
        $('#loadingCommentsMessage').hide();
    }
    const commentsHtml = mockCommentsData.map(comment => createCommentHtml(comment, 0)).join('');
    $('#commentsList').html(commentsHtml);
}
loadInitialComments();

$('#commentForm').on('submit', function (e) {
    e.preventDefault();
    const commentContent = $('#newCommentContent').val().trim();
    if (commentContent === '') {
        alert('Vui lòng nhập nội dung bình luận.');
        return;
    }
    const newComment = {
        id: 'cmt' + Date.now(),
        author: "Bạn",
        ownerId: currentUser,
        timeAgo: "Vừa xong",
        content: commentContent,
        parentId: null,
        replies: []
    };
    const newCommentHtml = createCommentHtml(newComment, 0);
    $('#commentsList').prepend(newCommentHtml);
    $('#newCommentContent').val('');
    console.log("Gửi bình luận mới:", newComment);
});

$(document).on('click', '.reply-btn', function (e) {
    e.preventDefault();
    const $replyBtn = $(this);
    const $commentItem = $replyBtn.closest('.comment-item');
    const $replyFormContainer = $commentItem.find('.reply-form-container:first');
    const commentAuthor = $replyBtn.data('comment-author');

    $replyFormContainer.find('textarea').attr('placeholder', `Phản hồi lại ${commentAuthor}...`);

    $('.reply-form-container').not($replyFormContainer).slideUp(200);
    $('.edit-form-container').slideUp(200);

    // Đóng các replies-container đang mở, TRỪ container cha của bình luận hiện tại và TRỪ chính nó
    // Tìm tất cả các replies-container đang hiển thị
    $('.replies-container:not(.hidden-replies)').each(function () {
        const $openContainer = $(this);
        // Kiểm tra xem container đang mở có phải là tổ tiên của bình luận hiện tại không
        const isAncestor = $.contains($openContainer[0], $commentItem[0]);
        // Nếu không phải tổ tiên (và không phải chính $repliesContainer của $commentItem), thì đóng nó
        if (!isAncestor && !$openContainer.is($commentItem.find('.replies-container:first'))) {
            $openContainer.slideUp(200).addClass('hidden-replies')
                .prevAll('.toggle-replies-btn:first').find('i').removeClass('fa-caret-up').addClass('fa-caret-down');
            const currentText = $openContainer.prevAll('.toggle-replies-btn:first').text();
            $openContainer.prevAll('.toggle-replies-btn:first').html(currentText.replace('Ẩn ', 'Có ') + ' <i class="fas fa-caret-down"></i>');
        }
    });


    $replyFormContainer.slideToggle(200, function () {
        if ($replyFormContainer.is(':visible')) {
            $replyFormContainer.find('textarea').focus();
        }
    });
});

$(document).on('submit', '.reply-form', function (e) {
    e.preventDefault();
    const $form = $(this);
    const replyContent = $form.find('textarea').val().trim();
    if (replyContent === '') {
        alert('Vui lòng nhập nội dung phản hồi.');
        return;
    }
    const $parentCommentItem = $form.closest('.comment-item');
    const parentCommentId = $parentCommentItem.data('comment-id');
    const parentCommentAuthor = $parentCommentItem.find('h6').text().trim();
    const parentCommentLevel = parseInt($parentCommentItem.data('comment-level'));

    const newReplyLevel = parentCommentLevel + 1;

    const newReply = {
        id: parentCommentId + '.rep' + Date.now(),
        author: "Bạn",
        ownerId: currentUser,
        timeAgo: "Vừa xong",
        content: `@${parentCommentAuthor}: ${replyContent}`,
        parentId: parentCommentId,
        replies: []
    };

    const newReplyHtml = createCommentHtml(newReply, newReplyLevel);
    const $repliesContainer = $parentCommentItem.find('.replies-container:first');

    // Thêm phản hồi mới vào container
    $repliesContainer.append(newReplyHtml);

    // Cập nhật số lượng và trạng thái nút toggle
    let $toggleBtn = $parentCommentItem.find('.toggle-replies-btn:first');
    if (!$toggleBtn.length) { // Nếu chưa có nút toggle (trước đó không có phản hồi)
        const count = $repliesContainer.children('.comment-item').length; // Đếm lại số con
        const newToggleHtml = `
                <small>
                    <a href="#" class="toggle-replies-btn text-muted" data-comment-id="${parentCommentId}" data-has-replies="true" data-toggle-state="shown">
                        Có ${count} phản hồi <i class="fas fa-caret-up"></i>
                    </a>
                </small>`;
        // Thêm nút toggle vào sau comment-action-links
        $parentCommentItem.find('.comment-action-links').after(newToggleHtml);
        $toggleBtn = $parentCommentItem.find('.toggle-replies-btn:first'); // Lấy lại tham chiếu đến nút mới
    } else {
        const currentCount = parseInt($toggleBtn.text().match(/\d+/)) || 0;
        $toggleBtn.html(`Có ${currentCount + 1} phản hồi <i class="fas fa-caret-up"></i>`).data('toggle-state', 'shown');
    }

    // Đảm bảo container hiển thị khi có phản hồi mới
    if ($repliesContainer.hasClass('hidden-replies')) {
        $repliesContainer.slideDown(200).removeClass('hidden-replies');
        $toggleBtn.find('i').removeClass('fa-caret-down').addClass('fa-caret-up');
        const currentText = $toggleBtn.text();
        $toggleBtn.html(currentText.replace('Có ', 'Ẩn ') + ' <i class="fas fa-caret-up"></i>');
    }

    $form.find('textarea').val('');
    $form.closest('.reply-form-container').slideUp(200);

    console.log("Gửi phản hồi:", newReply);
});

$(document).on('click', '.edit-comment-btn', function (e) {
    e.preventDefault();
    const $editBtn = $(this);
    const $commentItem = $editBtn.closest('.comment-item');
    const commentId = $commentItem.data('comment-id');
    const ownerId = $commentItem.data('comment-owner-id');

    if (ownerId !== currentUser) {
        alert('Bạn không có quyền chỉnh sửa bình luận này.');
        return;
    }

    const $commentContentText = $commentItem.find('.comment-content-text:first');
    const $editFormContainer = $commentItem.find('.edit-form-container:first');
    const $editTextArea = $editFormContainer.find('textarea');

    $('.reply-form-container').slideUp(200);
    $('.edit-form-container').not($editFormContainer).slideUp(200);

    // Đóng các replies-container đang mở, TRỪ container cha của bình luận hiện tại và TRỪ chính nó
    $('.replies-container:not(.hidden-replies)').each(function () {
        const $openContainer = $(this);
        const isAncestor = $.contains($openContainer[0], $commentItem[0]);
        if (!isAncestor && !$openContainer.is($commentItem.find('.replies-container:first'))) {
            $openContainer.slideUp(200).addClass('hidden-replies')
                .prevAll('.toggle-replies-btn:first').find('i').removeClass('fa-caret-up').addClass('fa-caret-down');
            const currentText = $openContainer.prevAll('.toggle-replies-btn:first').text();
            $openContainer.prevAll('.toggle-replies-btn:first').html(currentText.replace('Ẩn ', 'Có ') + ' <i class="fas fa-caret-down"></i>');
        }
    });


    if ($editFormContainer.is(':visible')) {
        $editFormContainer.slideUp(200);
        $commentContentText.show();
    } else {
        $editTextArea.val($commentContentText.text().trim());
        $commentContentText.hide();
        $editFormContainer.slideDown(200, function () {
            $editTextArea.focus();
        });
    }
});

$(document).on('click', '.cancel-edit-btn', function () {
    const $cancelBtn = $(this);
    const $editFormContainer = $cancelBtn.closest('.edit-form-container');
    const $commentItem = $cancelBtn.closest('.comment-item');
    const $commentContentText = $commentItem.find('.comment-content-text:first');
    $editFormContainer.slideUp(200, function () {
        $commentContentText.show();
    });
});

$(document).on('submit', '.edit-comment-form', function (e) {
    e.preventDefault();
    const $form = $(this);
    const newContent = $form.find('textarea').val().trim();
    const $commentItem = $form.closest('.comment-item');
    const commentId = $commentItem.data('comment-id');
    const ownerId = $commentItem.data('comment-owner-id');

    if (ownerId !== currentUser) {
        alert('Bạn không có quyền chỉnh sửa bình luận này.');
        return;
    }
    if (newContent === '') {
        alert('Nội dung bình luận không được để trống.');
        return;
    }
    console.log(`Đang lưu chỉnh sửa bình luận ID: ${commentId}, Nội dung mới: "${newContent}"`);
    $commentItem.find('.comment-content-text:first').text(newContent).show();
    $form.closest('.edit-form-container').slideUp(200);
    alert('Bình luận đã được cập nhật thành công!');
});

$(document).on('click', '.delete-comment-btn', function (e) {
    e.preventDefault();
    const $deleteBtn = $(this);
    const $commentItem = $deleteBtn.closest('.comment-item');
    const commentId = $commentItem.data('comment-id');
    const ownerId = $commentItem.data('comment-owner-id');

    if (ownerId !== currentUser) {
        alert('Bạn không có quyền xóa bình luận này.');
        return;
    }

    if (confirm('Bạn có chắc chắn muốn xóa bình luận này không?')) {
        console.log(`Đang xóa bình luận ID: ${commentId}`);
        const $parentRepliesContainer = $commentItem.parent('.replies-container');

        $commentItem.slideUp(300, function () {
            $(this).remove();
            alert('Bình luận đã được xóa thành công!');

            if ($parentRepliesContainer.length) {
                const $parentCommentItem = $parentRepliesContainer.closest('.comment-item');
                const $toggleBtn = $parentCommentItem.find('.toggle-replies-btn:first');
                if ($toggleBtn.length) {
                    const newCount = $parentRepliesContainer.children('.comment-item').length; // Đếm lại số con
                    if (newCount === 0) {
                        $toggleBtn.remove();
                    } else {
                        $toggleBtn.html(`Có ${newCount} phản hồi <i class="fas fa-caret-up"></i>`);
                    }
                }
            }
        });
    }
});

// --- SỬA LỖI: Xử lý sự kiện click nút "Hiện/Ẩn phản hồi" ---
$(document).on('click', '.toggle-replies-btn', function (e) {
    e.preventDefault();
    const $toggleBtn = $(this);
    const $commentItem = $toggleBtn.closest('.comment-item');
    const $repliesContainer = $commentItem.find('.replies-container:first');
    const $icon = $toggleBtn.find('i');

    // Đóng tất cả các form phản hồi và chỉnh sửa đang mở
    $('.reply-form-container').slideUp(200);
    $('.edit-form-container').slideUp(200);

    // Logic chính để đóng các container khác:
    // Đóng TẤT CẢ các replies-container đang mở (không có class hidden-replies)
    // ngoại trừ container của chính bình luận cha mà bạn đang click vào,
    // và ngoại trừ các container là tổ tiên của nó.
    $('.replies-container:not(.hidden-replies)').each(function () {
        const $openContainer = $(this);
        // Kiểm tra xem $openContainer có phải là container của $commentItem hay không
        const isThisCommentContainer = $openContainer.is($repliesContainer);
        // Kiểm tra xem $openContainer có phải là tổ tiên của $commentItem hay không
        const isAncestorOfThisComment = $.contains($openContainer[0], $commentItem[0]);

        if (!isThisCommentContainer && !isAncestorOfThisComment) {
            $openContainer.slideUp(200).addClass('hidden-replies');
            const $associatedToggleBtn = $openContainer.prevAll('.toggle-replies-btn:first');
            if ($associatedToggleBtn.length) {
                $associatedToggleBtn.find('i').removeClass('fa-caret-up').addClass('fa-caret-down');
                const currentText = $associatedToggleBtn.text();
                $associatedToggleBtn.html(currentText.replace('Ẩn ', 'Có ') + ' <i class="fas fa-caret-down"></i>');
            }
        }
    });

    // Bây giờ, xử lý hiển thị/ẩn container của bình luận hiện tại
    if ($repliesContainer.hasClass('hidden-replies')) {
        $repliesContainer.slideDown(200).removeClass('hidden-replies');
        $icon.removeClass('fa-caret-down').addClass('fa-caret-up');
        const currentText = $toggleBtn.text();
        $toggleBtn.html(currentText.replace('Có ', 'Ẩn ') + ' <i class="fas fa-caret-up"></i>');
    } else {
        $repliesContainer.slideUp(200).addClass('hidden-replies');
        $icon.removeClass('fa-caret-up').addClass('fa-caret-down');
        const currentText = $toggleBtn.text();
        $toggleBtn.html(currentText.replace('Ẩn ', 'Có ') + ' <i class="fas fa-caret-down"></i>');
    }
});


$(document).on('submit', '#form-search-bai', function (e) {
    e.preventDefault();

    const urlSearch = $(this).attr('action');
    let queryString = $(this).serialize();

    if (queryString == 'search=') queryString = 'search=all';

    $.ajax({
        url: urlSearch,
        type: 'GET',
        data: queryString,
        dataType: 'json',
        success: function (response) {
            $('#list-bai').html(response.html);
        },
        error: function (xhr) {
            alert('Đã xảy ra lỗi: ' + xhr.status + ' ' + xhr.statusText);
        }
    });
});
