function initializeFenstersymbol() {
    console.log("in");
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

function setFenstersymbol(fenstersymbol, isDarkMode) {
    console.log("fs0");
    console.log(fenstersymbolData);
    if (isDarkMode) {
        fenstersymbol.href = fenstersymbolData[0]['fenstersymbol_white'];
    } else {
        fenstersymbol.href = fenstersymbolData[0]['fenstersymbol_black'];
    }
}