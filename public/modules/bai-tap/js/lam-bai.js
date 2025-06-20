function updateSelected() {
    const total = document.querySelectorAll(".question-card").length;
    let selected = 0;
    document.querySelectorAll(".question-card").forEach((q, idx) => {
        const qNum = idx + 1;
        const selectedAnswer = q.querySelector('input[type="radio"]:checked');
        const allNums = document.querySelectorAll(`[data-jump="${qNum}"]`);
        if (selectedAnswer) {
            selected++;
            allNums.forEach((n) => n.classList.add("selected"));
        } else {
            allNums.forEach((n) => n.classList.remove("selected"));
        }
    });
    document.getElementById("answeredCount").innerText = selected;
    document.getElementById("mobileCount").innerText = selected;
}

// Gán sự kiện cho input
document.addEventListener("change", (e) => {
    if (e.target.matches('input[type="radio"]')) {
        updateSelected();
    }
});

// Nhảy tới câu hỏi khi bấm số
document.querySelectorAll(".question-number").forEach((el) => {
    el.addEventListener("click", () => {
        const qNum = el.dataset.jump;
        const target = document.querySelector(
            `.question-card[data-question="${qNum}"]`
        );
        if (target) {
            target.scrollIntoView({ behavior: "smooth", block: "start" });
        }
    });
});

// Toggle mobile nav
document.getElementById("mobileNavToggle").addEventListener("click", () => {
    const nav = document.getElementById("mobileQuestionNav");
    nav.style.display = nav.style.display === "block" ? "none" : "block";
});

// Cập nhật tổng số câu
document.getElementById("totalQuestions").innerText =
    document.querySelectorAll(".question-card").length;
document.getElementById("mobileTotal").innerText =
    document.querySelectorAll(".question-card").length;
