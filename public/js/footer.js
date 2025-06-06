// Optional: Feedback handling for form submission (without backend)
document.querySelector("form").addEventListener("submit", function (e) {
  e.preventDefault();
  const success = document.querySelector(".form-success");
  const error = document.querySelector(".form-error");

  // Fake success message display
  success.style.display = "block";
  error.style.display = "none";

  setTimeout(() => {
    success.style.display = "none";
  }, 3000);
});
