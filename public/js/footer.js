// // Optional: Feedback handling for form submission (without backend)
// document.querySelector("newsletterForm").addEventListener("submit", function (e) {
//   e.preventDefault();
//   const success = document.querySelector(".form-success");
//   const error = document.querySelector(".form-error");

//   // Fake success message display
//   success.style.display = "block";
//   error.style.display = "none";

//   setTimeout(() => {
//     success.style.display = "none";
//   }, 3000);
// });

document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector("#newsletterForm");
  const success = document.querySelector(".form-success");
  const error = document.querySelector(".form-error");

  if (!form) {
    console.warn("Newsletter-Formular wurde nicht gefunden.");
    return;
  }

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    // Fake success message display
    success.style.display = "block";
    error.style.display = "none";

    setTimeout(() => {
      success.style.display = "none";
    }, 3000);
  });
});

