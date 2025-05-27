// Referenzen auf die relevanten DOM-Elemente holen
const username = document.getElementById("username");  // Eingabefeld für Benutzername
const password = document.getElementById("password");  // Eingabefeld für Passwort
const loginBtn = document.getElementById("submit");    // Der Anmelde-Button
const form = document.querySelector("form");           // Das Login-Formular

// Funktion zur Validierung der Eingaben
function validateLogin() {
  // Benutzername ist gültig, wenn er mind. 5 Zeichen hat UND Groß- UND Kleinbuchstaben enthält
  const userValid = username.value.length >= 5 &&
                    /[A-Z]/.test(username.value) &&  // prüft auf Großbuchstaben
                    /[a-z]/.test(username.value);    // prüft auf Kleinbuchstaben

  // Passwort ist gültig, wenn es mind. 10 Zeichen lang ist
  const passValid = password.value.length >= 10;

  // Visuelles Feedback für Benutzername und Passwort
  colorize(username, userValid);
  colorize(password, passValid);

  // Anmelde-Button aktivieren oder deaktivieren
  loginBtn.disabled = !(userValid && passValid);  // Nur aktivieren, wenn beide gültig sind
}

// Funktion zur farblichen Darstellung der Gültigkeit
function colorize(input, valid) {
  // Grüner Rand bei gültiger Eingabe, roter Rand bei ungültiger
  input.style.border = valid ? "2px solid green" : "2px solid red";
}

// Event Listener für Eingaben: Sobald Nutzer etwas eingibt oder ändert → neu validieren
[username, password].forEach(input => {
  input.addEventListener("input", validateLogin);   // bei jedem Tastendruck
  input.addEventListener("change", validateLogin);  // beim Verlassen des Felds
});

// Formular-Submit abfangen
form.addEventListener("submit", function (e) {
  validateLogin(); // Sicherheitshalber nochmal prüfen
  if (loginBtn.disabled) {
    e.preventDefault(); // Wenn Eingaben ungültig → Formular wird NICHT abgeschickt
    return;

  }

 // ==== Hier LocalStorage & Weiterleitung erst bei erfolgreichem Login: ====
  e.preventDefault(); // Standard-Formular-Submit verhindern (wenn du KEIN echtes Backend hast)

  localStorage.setItem('userLoggedIn', 'true');
  localStorage.setItem('username', username.value); // Das Feld heißt bei dir oben "username"
  window.location.href = 'index.php'; // Weiterleitung zur Startseite
});