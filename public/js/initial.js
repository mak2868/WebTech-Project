/**
 * initial.js
 * zuständig für das Setzen des Fenstersymbols (dynamische Anpassung an den Darkmode)
 * @author Marvin Kunz
 */

// Funktion, die ermittelt, ob der Browser im White- oder Darkmode ist -> Entscheidungshrundlage zum Setzen des Fenstersymbols --> Aufruf der Setzen Funktion mit entsprechenden Parametern
function initializeFenstersymbol() {
    const isDarkMode = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;

    const fenstersymbol = document.getElementById('fenstersymbol');

    setFenstersymbol(fenstersymbol, isDarkMode);

    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
        if (e.matches) {
            setFenstersymbol(fenstersymbol, true);
        } else {
            setFenstersymbol(fenstersymbol, false);
        }
    });
}

// Funktion zum Setzen des Fenstersymbols (Nutzen des passenden Links)
function setFenstersymbol(fenstersymbol, isDarkMode) {
    if (isDarkMode) {
        fenstersymbol.href = fenstersymbolData[0]['fenstersymbol_white'];
    } else {
        fenstersymbol.href = fenstersymbolData[0]['fenstersymbol_black'];
    }
}