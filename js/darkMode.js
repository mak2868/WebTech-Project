import { scrollBorder } from './items.js';

window.ensureDarkmodeStyles = ensureDarkmodeStyles;
window.updateDarkmodeState = updateDarkmodeState;

let darkmodeWanted = false;

function ensureDarkmodeStyles() {
  console.log("ensureDarkmodeStyles");

  if (!document.getElementById("darkmode-style")) {
    const style = document.createElement("style");
    style.id = "darkmode-style";
    style.textContent = `
        html.darkmode {
          filter: invert(1) hue-rotate(180deg);
          background-color: #111;
      }
  
      html.darkmode img, html.darkmode #Bewertungsskala {
          filter: invert(1) hue-rotate(180deg);
      }
  
      html.darkmode img.invert-keep {
          filter: none;
      }
  
      // html.darkmode #Bewertungsskala {
      //   background-color: green;
      // }
  
      body {
          transition: filter 0.3s ease, background-color 0.3s ease;
      }
        `;
    document.head.appendChild(style);
  }
}

export function updateDarkmodeState() {

  console.log("updateDarkmodeState");

  if (darkmodeWanted && window.scrollY >= scrollBorder) {
    document.documentElement.classList.add("darkmode");
  } else {
    document.documentElement.classList.remove("darkmode");
  }

  if (scrollY >= scrollBorder && darkmodeWanted) {
    document.getElementById("navbarLogo").style.filter = "invert(0)";
    document.getElementById("userBtn").style.filter = "invert(1)";
    document.getElementById("cartBtn").style.filter = "invert(1)";
    document.getElementById("darkmodeBtn").style.filter = "invert(1)";
  } else {
    document.getElementById("userBtn").style.filter = "invert(0)";
    document.getElementById("cartBtn").style.filter = "invert(0)";
    document.getElementById("darkmodeBtn").style.filter = "invert(0)";
  }

}

document.getElementById("darkmodeBtn").addEventListener("click", function (event) {
  const img = this.querySelector("img");

  if (img.src.includes("Sonne.png")) {
    img.src = "../images/Mond.png";
  } else {
    img.src = "../images/Sonne.png";
  }

  // console.log(img.src); 
  darkmodeWanted = !darkmodeWanted;
  ensureDarkmodeStyles();
  updateDarkmodeState();
});