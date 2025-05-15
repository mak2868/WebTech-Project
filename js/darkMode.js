const btn = document.getElementById('darkmodeBtn');

btn?.addEventListener('click', () => {
  document.body.classList.toggle('dark-mode');

  // Zustand im localStorage speichern
  if (document.body.classList.contains('dark-mode')) {
    localStorage.setItem('darkmode', 'true');
  } else {
    localStorage.setItem('darkmode', 'false');
  }
});

// Darkmode beim Laden der Seite anwenden (wenn vorher aktiviert)
if (localStorage.getItem('darkmode') === 'true') {
  document.body.classList.add('dark-mode');
}
