const usernameInput = document.getElementById("name");
const passwordInput = document.getElementById("password");
const confirmInput = document.getElementById("confirm");
const submitBtn = document.getElementById("submit");

function validateForm() {
  let valid = true;

  // Benutzername: mind. 5 Zeichen, Groß- und Kleinbuchstabe
  const username = usernameInput.value;
  const usernameValid = username.length >= 5 &&
                        /[A-Z]/.test(username) &&
                        /[a-z]/.test(username);
  colorize(usernameInput, usernameValid);
  if (!usernameValid) valid = false;

  // Passwort: mind. 10 Zeichen
  const pw = passwordInput.value;
  const pwValid = pw.length >= 10;
  colorize(passwordInput, pwValid);
  if (!pwValid) valid = false;

  // Wiederholung: muss gleich sein
  const pwRepeatValid = pw === confirmInput.value;
  colorize(confirmInput, pwRepeatValid);
  if (!pwRepeatValid) valid = false;

  submitBtn.disabled = !valid;
}

function colorize(input, isValid) {
  input.style.border = "2px solid " + (isValid ? "green" : "red");
}

// Events binden
[usernameInput, passwordInput, confirmInput].forEach(input =>
  input.addEventListener("input", validateForm)
);
