
/**
 * DSGVO-konformer CookieBanner
 * @author Felix Bartel
 */




/**
 * lädt den Google-Tag-Manager (vorgefertigtes Script von GTM was eingefügt werden muss)
 */
function loadGTM() {
    (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-WSDV8P5R');
  }


//dataLayer initialisieren falls noch nicht vorhanden (JavaScript Array in welchen die Daten an Google gesendet werden)
window.dataLayer = window.dataLayer || [];



/**
 * Schreibt den consens in den dataLayer
 */
function updateConsent(allowAnalytics, allowAds) {
  window.dataLayer.push({
    event: 'cookie_consent_update',
    ad_storage: allowAds ? 'granted' : 'denied',
    analytics_storage: allowAnalytics ? 'granted' : 'denied',
  });

  // Ist GoogleAnalytics eingebunden, wird nun hier die Zustimmung aktualisiert
  if (typeof gtag === 'function') {
    gtag('consent', 'update', {
      ad_storage:          allowAds ? 'granted' : 'denied',
      analytics_storage:   allowAnalytics ? 'granted' : 'denied',
      ad_user_data:        allowAds ? 'granted' : 'denied',
      ad_personalization:  allowAds ? 'granted' : 'denied'
    });
  }
  
  if (allowAnalytics || allowAds) {
    loadGTM(); // GTM laden, wenn erlaubt
  }
}


/**
 * Es wird ein Cookie gesetzt. 
 * In expires wird die Ablaufinfo gespeichert. 
 * Expires = heutiges Datum + 365 Tage.
 */
function setCookie(name, value, days) {
  let expires = "";
  if (days) {
    const date = new Date();
    date.setTime(date.getTime() + (days*24*60*60*1000));
    expires = "; expires=" + date.toUTCString();
  }
  document.cookie = name + "=" + (value || "") + expires + "; path=/";
}


/**
 * Funktion ließt Wert des Cookies aus Browser und erstellt darauß einen String des entscheidenen Teils,
 * aus welchem anschließend ein Objekt erstellt werden könnte (mit JSON.parse)
 * @param cookie_consent {"analytics": true, "ads": false}
 * @return '{"analytics":true, "ads": false}' als String
 */
function getCookie(name) {
  const nameEQ = name + "=";
  const ca = document.cookie.split(';');
  for(let i=0; i < ca.length; i++) {
    let c = ca[i].trim();
    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
  }
  return null;
}



/**
 * Funktionen um den Banner zu zeigen und zu verstecken
 */
function showBanner() {
  document.getElementById('cookie-banner')?.classList.remove('hidden');
}

function hideBanner() {
  document.getElementById('cookie-banner')?.classList.add('hidden');
}

function showSettings() {
  document.getElementById('cookie-settings')?.classList.remove('hidden');
}

function hideSettings() {
  document.getElementById('cookie-settings')?.classList.add('hidden');
}

function initConsent() {
  const consent = getCookie('cookie_consent');
  if (!consent) {
    showBanner();
  } else {
    try {
      const consentObj = JSON.parse(consent);
      updateConsent(consentObj.analytics, consentObj.ads);
      hideBanner(); // Banner verstecken, wenn schon Consent da
    } catch (e) {
      console.warn('Ungültiger Consent-Cookie:', consent);
      showBanner();
    }
  }
}

document.addEventListener('DOMContentLoaded', () => {
  initConsent();



/**
 * EventListener für die im CookieBanner anzuklickenden Button (accept, decline, settings (safe-settings))
 */
  

  document.getElementById('btn-accept')?.addEventListener('click', () => {
    const consentObj = {analytics: true, ads: true};
    setCookie('cookie_consent', JSON.stringify(consentObj), 365);
    updateConsent(true, true);
    hideBanner();
  });

  document.getElementById('btn-decline')?.addEventListener('click', () => {
    const consentObj = {analytics: false, ads: false};
    setCookie('cookie_consent', JSON.stringify(consentObj), 365);
    updateConsent(false, false);
    hideBanner();
  });

  document.getElementById('btn-settings')?.addEventListener('click', showSettings);

  document.getElementById('btn-save-settings')?.addEventListener('click', () => {
    const analytics = document.getElementById('consent-analytics')?.checked ?? false;
    const ads = document.getElementById('consent-ads')?.checked ?? false;
    const consentObj = {analytics, ads};
    setCookie('cookie_consent', JSON.stringify(consentObj), 365);
    updateConsent(analytics, ads);
    hideSettings();
    hideBanner();
  });
});




