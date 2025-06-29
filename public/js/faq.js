// Author: Nick Zetzmann

/* es werden alle Elemente der 'faq-quetion' ausgewählt und jedem Button ein Klick-Eventlistener zugeordnet  */
document.querySelectorAll('.faq-question').forEach(button => {
    button.addEventListener('click', () => {

/* Liest den aktuellen Zustand des Button und setzt das Gegenteil */        
        const expanded = button.getAttribute('aria-expanded') === 'true';
        button.setAttribute('aria-expanded', !expanded);

/* Zugehörige Antwort zur Frage wird ueber Aria-controls geholt */
        const answer = document.getElementById(button.getAttribute('aria-controls'));

/* zeigt versteckte oder verbrigt angezeigte Antworten, je nachdem welcher Zustand vorher war */        
        if (!expanded) {
            answer.hidden = false;
        } else {
            answer.hidden = true;
        }
    });
});