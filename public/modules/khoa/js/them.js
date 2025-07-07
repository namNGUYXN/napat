document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("form-input");
    if (!form) return;

    const fields = [
        {
            name: "ten",
            regex: /^[\p{L}\s]+$/u,
            empty: "Tên khoa không được để trống.",
            invalid: "Tên khoa chỉ được chứa chữ cái và khoảng trắng.",
        },
        {
            name: "ma",
            regex: /^[A-Z0-9]{2,10}$/,
            empty: "Mã khoa không được để trống.",
            invalid: "Mã khoa phải viết hoa, không dấu và từ 2–10 ký tự.",
        },
    ];

    const validateField = (field) => {
        const input = form.querySelector(`[name="${field.name}"]`);
        if (!input) return true;

        const value = input.value.trim();
        const errorDiv = input
            .closest(".form-group")
            .querySelector(".invalid-feedback");

        const showError = (message) => {
            input.classList.add("is-invalid");
            if (errorDiv) {
                errorDiv.textContent = message;
                errorDiv.classList.remove("d-none");
            }
        };

        const hideError = () => {
            input.classList.remove("is-invalid");
            if (errorDiv) {
                errorDiv.textContent = "";
                errorDiv.classList.add("d-none");
            }
        };

        if (value === "") {
            showError(field.empty);
            return false;
        }

        if (field.regex && !field.regex.test(value)) {
            showError(field.invalid);
            return false;
        }

        hideError();
        return true;
    };

    fields.forEach((field) => {
        const input = form.querySelector(`[name="${field.name}"]`);
        if (!input) return;
        input.addEventListener("input", () => validateField(field));
    });

    form.addEventListener("submit", (e) => {
        let isValid = true;
        fields.forEach((field) => {
            if (!validateField(field)) isValid = false;
        });
        if (!isValid) e.preventDefault();
    });
});
