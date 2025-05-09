const usernameInput = document.getElementById("username");
const passwordInput = document.getElementById("password");
const saveBtn = document.getElementById("submit");

function validateUserForm() {
  const usernameValid = usernameInput.value.length >= 5 &&
                        /[A-Z]/.test(usernameInput.value) &&
                        /[a-z]/.test(usernameInput.value);
  const pwValid = passwordInput.value.length >= 10;

  colorize(usernameInput, usernameValid);
  colorize(passwordInput, pwValid);

  saveBtn.disabled = !(usernameValid && pwValid);
}

function colorize(input, valid) {
  input.style.border = valid ? "2px solid green" : "2px solid red";
}

[usernameInput, passwordInput].forEach(input => {
  input.addEventListener("input", validateUserForm);
  input.addEventListener("change", validateUserForm);
});

window.addEventListener("DOMContentLoaded", validateUserForm);
