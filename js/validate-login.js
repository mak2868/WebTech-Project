const username = document.getElementById("username");
const password = document.getElementById("password");
const loginBtn = document.getElementById("submit");

function validateLogin() {
  const userValid = username.value.length >= 5;
  const passValid = password.value.length >= 10;

  colorize(username, userValid);
  colorize(password, passValid);

  loginBtn.disabled = !(userValid && passValid);
}

function colorize(input, valid) {
  input.style.border = valid ? "2px solid green" : "2px solid red";
}

// Events, die wirklich alles erfassen:
[username, password].forEach(input => {
  input.addEventListener("input", validateLogin);
  input.addEventListener("change", validateLogin); // ← wichtig für Autovervollständigung
});

// Auch beim Laden prüfen
window.addEventListener("DOMContentLoaded", validateLogin);
