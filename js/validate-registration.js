// Zugriff auf die Formularfelder und den Absende-Button
const usernameInput = document.getElementById("name");       // Eingabefeld für Benutzername
const passwordInput = document.getElementById("password");   // Eingabefeld für Passwort
const confirmInput = document.getElementById("confirm");     // Eingabefeld zur Passwort-Wiederholung
const submitBtn = document.getElementById("submit");         // Button zum Absenden des Formulars

// Hauptfunktion zur Validierung aller Eingaben
function validateForm() {
  let valid = true; // Status-Flag, das angibt, ob alle Felder gültig sind

  // === BENUTZERNAME ===
  // Mindestanforderungen: mind. 5 Zeichen, mindestens ein Groß- und ein Kleinbuchstabe
  const username = usernameInput.value;
  const usernameValid = username.length >= 5 &&
                        /[A-Z]/.test(username) && // enthält Großbuchstaben?
                        /[a-z]/.test(username);   // enthält Kleinbuchstaben?
  colorize(usernameInput, usernameValid);
  if (!usernameValid) valid = false;

  // === PASSWORT ===
  // Passwort muss mindestens 10 Zeichen lang sein
  const pw = passwordInput.value;
  const pwValid = pw.length >= 10;
  colorize(passwordInput, pwValid);
  if (!pwValid) valid = false;

  // === PASSWORT-WIEDERHOLUNG ===
  // Passwort-Wiederholung muss exakt gleich sein wie das ursprüngliche Passwort
  const pwRepeatValid = pw === confirmInput.value;
  colorize(confirmInput, pwRepeatValid);
  if (!pwRepeatValid) valid = false;

  // === ABSENDEN-BUTTON AKTIVIEREN ODER SPERREN ===
  submitBtn.disabled = !valid; // nur aktivieren, wenn alle Felder gültig sind
}

// Funktion für farbliche Hervorhebung (grün bei gültig, rot bei ungültig)
function colorize(input, isValid) {
  input.style.border = "2px solid " + (isValid ? "green" : "red");
}

// === EVENTS AN FORMULARELEMENTE BINDEN ===
// Bei jeder Eingabe wird das Formular neu validiert
[usernameInput, passwordInput, confirmInput].forEach(input =>
  input.addEventListener("input", validateForm)
);
