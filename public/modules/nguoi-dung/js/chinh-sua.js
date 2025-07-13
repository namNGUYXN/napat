document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("form-input");
    if (!form) return;

    const fields = [
        {
            name: "ho_ten",
            regex: /^[\p{L}\s]+$/u,
            empty: "Họ tên không được để trống.",
            invalid: "Họ tên chỉ được chứa chữ cái và khoảng trắng.",
        },
        {
            name: "email",
            regex: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
            empty: "Email không được để trống.",
            invalid: "Email không hợp lệ.",
        },
        {
            name: "sdt",
            regex: /^0\d{9,10}$/,
            empty: null,
            invalid: "Số điện thoại phải bắt đầu bằng 0 và dài 10–11 số.",
            allowEmpty: true,
        },
        {
            name: "vai_tro",
            validate: (val) => val !== "",
            empty: "Vui lòng chọn vai trò.",
        },
    ];

    const validateField = (field) => {
        const input = form.querySelector(`[name="${field.name}"]`);
        if (!input) return true;

        // Nếu input bị readonly (như email khi is_change_pass) thì bỏ qua
        if (input.hasAttribute("readonly")) return true;

        const value = input.value.trim();
        const errorDiv = input
            .closest(".mb-3")
            ?.querySelector(".invalid-feedback");

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
            if (field.allowEmpty) {
                hideError();
                return true;
            } else {
                showError(field.empty);
                return false;
            }
        }

        if (field.validate && !field.validate(value)) {
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

        const eventType = field.name === "vai_tro" ? "change" : "input";
        input.addEventListener(eventType, () => validateField(field));
    });

    form.addEventListener("submit", (e) => {
        let isValid = true;
        fields.forEach((field) => {
            if (!validateField(field)) isValid = false;
        });

        if (!isValid) e.preventDefault();
    });
});
