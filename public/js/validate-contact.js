// Author: Nick Zetzmann

document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById('contactForm');
  const submitBtn = document.getElementById('submitBtn');
  const textarea = document.getElementById('message');

  const autoResize = () => {
    textarea.style.height = "auto"; 
    textarea.style.height = textarea.scrollHeight + "px";
  };

  autoResize();
  textarea.addEventListener("input", autoResize);

  form.addEventListener('input', () => {
    const name = form.name.value.trim();
    const mail = form.mail.value.trim();
    const subject = form.subject.value.trim();
    const message = form.message.value.trim();
    const emailValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(mail);

    submitBtn.disabled = !(name && emailValid && subject && message);
  });
});