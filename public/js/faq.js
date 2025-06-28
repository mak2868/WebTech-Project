// Author: Nick Zetzmann

document.querySelectorAll('.faq-question').forEach(button => {
    button.addEventListener('click', () => {
        const expanded = button.getAttribute('aria-expanded') === 'true';
        button.setAttribute('aria-expanded', !expanded);
        const answer = document.getElementById(button.getAttribute('aria-controls'));
        if (!expanded) {
            answer.hidden = false;
        } else {
            answer.hidden = true;
        }
    });
});