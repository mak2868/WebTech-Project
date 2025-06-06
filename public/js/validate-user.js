// Author: Merzan
// Meiste Controller
// Datenbankzugriffe in Model




// Zugriff auf die relevanten DOM-Elemente (Eingabefelder & Button)
const usernameInput = document.getElementById("username");  // Eingabefeld für Benutzername
const passwordInput = document.getElementById("password");  // Eingabefeld für Passwort
const saveBtn = document.getElementById("submit");          // "Registrieren"- oder "Speichern"-Button
const form = document.querySelector("form");                // Das gesamte Formular

// Funktion zur Validierung der Eingaben
function validateUserForm() {
  // Benutzername gültig: mindestens 5 Zeichen, mindestens ein Groß- und ein Kleinbuchstabe
  const usernameValid = usernameInput.value.length >= 5 &&
                        /[A-Z]/.test(usernameInput.value) &&
                        /[a-z]/.test(usernameInput.value);

  // Passwort gültig: mindestens 10 Zeichen lang
  const pwValid = passwordInput.value.length >= 10;

  // Visuelles Feedback (grüner oder roter Rahmen)
  colorize(usernameInput, usernameValid);
  colorize(passwordInput, pwValid);

  // Button aktivieren, wenn beide Felder gültig sind
  saveBtn.disabled = !(usernameValid && pwValid);
}

// Funktion zum Ändern der Rahmenfarbe abhängig von Gültigkeit
function colorize(input, valid) {
  if (input.value === "") {
    // Neutraler Stil bei leeren Feldern
    input.style.border = "1px solid #ccc";
  } else {
    // Grün bei gültiger Eingabe, Rot bei ungültiger
    input.style.border = valid ? "2px solid green" : "2px solid red";
  }
}

// Überwachung der Eingabefelder: bei jeder Eingabe oder Änderung → Validierung auslösen
[usernameInput, passwordInput].forEach(input => {
  input.addEventListener("input", validateUserForm);   // direktes Feedback beim Tippen
  input.addEventListener("change", validateUserForm);  // bei Feldwechsel (z. B. mit Tab)
});

// Vor dem Absenden des Formulars: nochmal prüfen und ggf. blockieren
form.addEventListener("submit", (e) => {
  validateUserForm(); // Sicherheitsprüfung
  if (saveBtn.disabled) {
    e.preventDefault(); // Verhindert das Absenden bei ungültiger Eingabe
  }
});

// Beim Laden der Seite: Button zunächst deaktivieren
window.addEventListener("DOMContentLoaded", () => {
  saveBtn.disabled = true;
});