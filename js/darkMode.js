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

  html.darkmode img {
      filter: invert(1) hue-rotate(180deg);
  }
  
  html.darkmode h1, html.darkmode h3{
    filter: invert(1) hue-rotate(180deg);
    color: rgb(249, 249, 234); 
  }

  html.darkmode button, html.darkmode .btn, html.darkmode .select-wrapper select{
    filter: invert(1) hue-rotate(180deg);
  }

  html.darkmode img.invert-keep {
    filter: none;
  }
  

  html.darkmode .btn, html.darkmode button, html.darkmode .select-wrapper select {
    background-color: rgb(249, 249, 234);  
    color: black;   
  }

  html.darkmode .VersandButton{
    background-color: rgb(207, 47, 47); 
    color: rgb(249, 249, 234); 
  }

  html.darkmode .select-wrapper select{

    background-image: url('data:image/svg+xml;utf8,<svg fill="black" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M3.793 8.293a1 1 0 0 1 1.414 0L12 15.086l6.793-6.793a1 1 0 1 1 1.414 1.414l-7.5 7.5a1 1 0 0 1-1.414 0l-7.5-7.5a1 1 0 0 1 0-1.414Z"/></svg>');
  }

  html.darkmode .FavoritenButton svg{
    stroke: black; 
  }

  html.darkmode .accordion::after {
    content: '';
    position: absolute;
    right: 0.5rem;
    top: 50%;
    transform: translateY(-50%) rotate(0deg);
    width: 1rem;
    height: 1rem;
    background-image: url('data:image/svg+xml;utf8,<svg fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M3.793 8.293a1 1 0 0 1 1.414 0L12 15.086l6.793-6.793a1 1 0 1 1 1.414 1.414l-7.5 7.5a1 1 0 0 1-1.414 0l-7.5-7.5a1 1 0 0 1 0-1.414Z"/></svg>');
    background-size: contain;
    background-repeat: no-repeat;
    transition: transform 0.8s linear;
  }

  .accordion.active::after {
    transform: translateY(-50%) rotate(180deg);
  }
  
  .accordion:hover {
    background-color: inherit;
  }

   html.darkmode .accordion {
    background-color: black;
    color:  rgb(249, 249, 234);
    background-image
   }

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