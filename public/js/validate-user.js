// Author: Merzan
// Meiste Controller
// Datenbankzugriffe in Model

document.addEventListener("DOMContentLoaded", () => {
  // Zugriff auf die relevanten DOM-Elemente (Eingabefelder & Button)
  const usernameInput = document.getElementById("username");
  const passwordInput = document.getElementById("password");
  const saveBtn = document.getElementById("submit");
  const form = document.querySelector("form");

  // Wenn die Felder nicht existieren (z. B. auf user.php), Skript beenden
  if (!usernameInput || !passwordInput || !saveBtn || !form) return;

  // Funktion zur Validierung der Eingaben
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
    if (input.value === "") {
      input.style.border = "1px solid #ccc";
    } else {
      input.style.border = valid ? "2px solid green" : "2px solid red";
    }
  }

  // Event-Listener für die Eingabefelder
  [usernameInput, passwordInput].forEach(input => {
    input.addEventListener("input", validateUserForm);
    input.addEventListener("change", validateUserForm);
  });

  // Formular absenden verhindern, wenn Eingaben ungültig
  form.addEventListener("submit", (e) => {
    validateUserForm();
    if (saveBtn.disabled) {
      e.preventDefault();
    }
  });

  // Button initial deaktivieren
  saveBtn.disabled = true;
});
