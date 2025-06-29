// Author: Nick Zetzmann

document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById('contactForm');
  const submitBtn = document.getElementById('submitBtn');
  const textarea = document.getElementById('message');

/* Funktion zum automatischen anpassen der Hoehe des Textfeldes */
  const autoResize = () => {
    textarea.style.height = "auto"; /* Hoehe zurücksetzen */
    textarea.style.height = textarea.scrollHeight + "px"; /* Hohe an Inhalt anpassen  */
  };

/* Immer wenn der Benutzer etwas im Textfeld eingibt, wird die Hoehe angepasst  */
  autoResize();
  textarea.addEventListener("input", autoResize);

/* Ueberprueft bei jeder Eingabe im Formular, ob alle Felder korrekt gefuellt sind */  
  form.addEventListener('input', () => {
    const name = form.name.value.trim();
    const mail = form.mail.value.trim();
    const subject = form.subject.value.trim();
    const message = form.message.value.trim();

/* Code zur Validierung der E-mail */    
    const emailValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(mail);

/* Button wird nur aktiviert, wenn alle Felder und E-mail korrekt ausgefuellt sind */
    submitBtn.disabled = !(name && emailValid && subject && message);
  });
});