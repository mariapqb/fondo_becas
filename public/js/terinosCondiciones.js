document.addEventListener("DOMContentLoaded", function () {
    console.log("El DOM está completamente cargado y listo para usarse.");
    
    const checkbox = document.getElementById("terms");
    const errorText = document.getElementById("terms-error");
    const submitBtn = document.getElementById("submit-btn");

    checkbox.addEventListener("change", function () {
        if (checkbox.checked) {
            errorText.style.display = "none";
            submitBtn.disabled = false;
        } else {
            errorText.style.display = "block";
            submitBtn.disabled = true;
        }
    });
});

  // Expresión la contraseña

document.addEventListener("DOMContentLoaded", function () {
    const passwordInput = document.getElementById("password");
    const confirmPasswordInput = document.getElementById("confirm-password");
    const message = document.getElementById("password-message");
    const errorText = document.getElementById("password-error");

    function validatePassword() {
        const password = passwordInput.value;

        // Expresión regular para validar la contraseña (mínimo 8 caracteres, una mayúscula y un número)
        const strongPassword = /^(?=.*[A-Z])(?=.*\d).{8,}$/;

        if (!strongPassword.test(password)) {
            message.style.display = "block";
        } else {
            message.style.display = "none";
        }
    }

    function checkPasswordMatch() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;

        if (password !== confirmPassword) {
            errorText.style.display = "block";
        } else {
            errorText.style.display = "none";
        }
    }

    // Agregar eventos a los inputs
    passwordInput.addEventListener("input", validatePassword);
    confirmPasswordInput.addEventListener("input", checkPasswordMatch);
});
