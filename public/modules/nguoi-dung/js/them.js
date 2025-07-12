// document.addEventListener("DOMContentLoaded", () => {

//     const form = document.getElementById("form-input"); //  chỉ form-input được kiểm tra

//     if (!form) return; // nếu không có form-input thì thoát

//     const fields = [
//         {
//             name: "ho_ten",
//             regex: /^[\p{L}\s]+$/u,
//             empty: "Họ tên không được để trống.",
//             invalid: "Họ tên chỉ được chứa chữ cái và khoảng trắng.",
//         },
//         {
//             name: "email",
//             regex: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
//             empty: "Email không được để trống.",
//             invalid: "Email không hợp lệ.",
//         },
//         {
//             name: "sdt",
//             regex: /^0\d{9,10}$/,
//             empty: null,
//             invalid: "Số điện thoại phải bắt đầu bằng 0 và dài 10–11 số.",
//             allowEmpty: true,
//         },
//         {
//             name: "vai_tro",
//             validate: (value) => value !== "",
//             empty: "Vui lòng chọn vai trò.",
//         },
//     ];

//     const validateField = (field) => {
//         const input = form.querySelector(`[name="${field.name}"]`);
//         const errorDiv = document.querySelector(`#${field.name}_error`);
//         const value = input?.value?.trim() ?? "";

//         const showError = (message) => {
//             input.classList.add("is-invalid");
//             errorDiv.textContent = message;
//             errorDiv.classList.remove("d-none");
//         };

//         const hideError = () => {
//             input.classList.remove("is-invalid");
//             errorDiv.classList.add("d-none");
//         };

//         if (!input) return true;

//         if (value === "") {
//             if (field.allowEmpty) {
//                 hideError();
//                 return true;
//             } else {
//                 showError(field.empty);
//                 return false;
//             }
//         }

//         if (field.validate && !field.validate(value)) {
//             showError(field.empty);
//             return false;
//         }

//         if (field.regex && !field.regex.test(value)) {
//             showError(field.invalid);
//             return false;
//         }

//         hideError();
//         return true;
//     };

//     // Validate realtime
//     fields.forEach((field) => {
//         const input = form.querySelector(`[name="${field.name}"]`);
//         if (!input) return;

//         input.addEventListener(
//             field.name === "vai_tro" ? "change" : "input",
//             () => validateField(field)
//         );
//     });

//     // Chặn submit nếu có lỗi
//     form.addEventListener("submit", (e) => {
//         let isValid = true;
//         fields.forEach((field) => {
//             if (!validateField(field)) isValid = false;
//         });

//         if (!isValid) {
//             e.preventDefault(); //  chỉ chặn submit của form-input
//         }
//     });
// });

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
                errorDiv.textContent = ""; // xoá thông báo Laravel cũ
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

        input.addEventListener(
            field.name === "vai_tro" ? "change" : "input",
            () => validateField(field)
        );
    });

    form.addEventListener("submit", (e) => {
        let isValid = true;
        fields.forEach((field) => {
            if (!validateField(field)) isValid = false;
        });

        if (!isValid) e.preventDefault();
    });
});

function updateFileName(input) {
    const fileName =
        input.files.length > 0 ? input.files[0].name : "Chưa chọn file";
    document.getElementById("file-name").textContent = fileName;
}
