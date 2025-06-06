// Author: Merzan
// das meiste Controller
// Datenbankzugriffe in Model


// Zugriff auf die Formularfelder und den Absende-Button
const usernameInput = document.getElementById("name");
const passwordInput = document.getElementById("password");
const confirmInput = document.getElementById("confirm");
const submitBtn = document.getElementById("submit");
const usernameRule = document.getElementById("username-rule");
const confirmRule = document.getElementById("confirm-rule");

// Funktion für visuelles Blinken bei Fehler
function flashRule(element) {
  element.classList.add("flash");
  setTimeout(() => element.classList.remove("flash"), 600);
}

// Hauptfunktion zur Validierung aller Eingaben
function validateForm() {
  let valid = true;

  // === BENUTZERNAME ===
  const username = usernameInput.value;
  const usernameValid = username.length >= 5 &&
                        /[A-Z]/.test(username) &&
                        /[a-z]/.test(username);
  colorize(usernameInput, usernameValid);
  if (!usernameValid) {
    usernameRule.classList.remove("hint-neutral");
    usernameRule.classList.add("hint-error");
    flashRule(usernameRule);
    valid = false;
  } else {
    usernameRule.classList.remove("hint-error");
    usernameRule.classList.add("hint-neutral");
  }

  // === PASSWORT ===
  const pw = passwordInput.value;
  const pwValid = pw.length >= 10;
  colorize(passwordInput, pwValid);
  if (!pwValid) valid = false;

  // === PASSWORT-WIEDERHOLUNG ===
  const pwRepeatValid = pw === confirmInput.value;
  colorize(confirmInput, pwRepeatValid);
  if (!pwRepeatValid) {
    confirmRule.classList.remove("hint-neutral");
    confirmRule.classList.add("hint-error");
    flashRule(confirmRule);
    valid = false;
  } else {
    confirmRule.classList.remove("hint-error");
    confirmRule.classList.add("hint-neutral");
  }

  // === BUTTON-AKTIVIERUNG ===
  submitBtn.disabled = !valid;
}

// Funktion für farbliche Eingabefeld-Markierung
function colorize(input, isValid) {
  input.style.border = "2px solid " + (isValid ? "green" : "red");
}

// Eingaben beobachten
[usernameInput, passwordInput, confirmInput].forEach(input =>
  input.addEventListener("input", validateForm)
);

// Zugriff aufs Formular
const form = document.querySelector("form");

form.addEventListener("submit", function(e) {
  validateForm(); // Nochmal checken
  if (submitBtn.disabled) {
    e.preventDefault(); // Wenn Fehler, abbrechen
    return;
  }
  e.preventDefault(); // Kein echtes Submit

  // Registrierung erfolgreich → wie eingeloggt behandeln:
  localStorage.setItem('userLoggedIn', 'true');
  localStorage.setItem('username', usernameInput.value);
  // Hier kannst du noch weitere Felder speichern (E-Mail, etc.), falls du das später brauchst

  // Weiterleitung, z.B. auf die Startseite:
  window.location.href = 'index.php';
});