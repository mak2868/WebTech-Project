document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector("#newsletterForm");
  const success = document.querySelector(".form-success");
  const error = document.querySelector(".form-error");

  if (!form) return;

  form.addEventListener("submit", async function (e) {
    e.preventDefault(); // verhindert klassisches Absenden

    const formData = new FormData(form);
    const email = formData.get("email");

    const submitBtn = form.querySelector("input[type='submit']");
    submitBtn.disabled = true; // Verhindert Doppelklick

    try {
      const response = await fetch("/WebTech-Project/public/?page=newsletterSignup", {
        method: "POST",
        body: JSON.stringify({ email }),
        headers: {
          "Content-Type": "application/json"
        }
      });

      const result = await response.json();
      console.log("Newsletter-Antwort:", result); // Debug

      if (result.success) {
        success.textContent = result.message || "Danke für deine Anmeldung!";
        success.style.display = "block";
        error.style.display = "none";
        form.reset();
      } else {
        error.textContent = result.message || "Es ist ein Fehler aufgetreten.";
        error.style.display = "block";
        success.style.display = "none";
      }
    } catch (err) {
      error.textContent = "Verbindungsfehler – bitte später nochmal versuchen.";
      error.style.display = "block";
      success.style.display = "none";
    }

    setTimeout(() => {
      success.style.display = "none";
      error.style.display = "none";
      submitBtn.disabled = false;
    }, 4000);
  });
});
