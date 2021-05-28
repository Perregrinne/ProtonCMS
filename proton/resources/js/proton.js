//Set the footer date inside Proton admin pages:
let currYear = new Date().getYear();
let footer = (currYear > 2021) ? "Copyright © 2021 - " + currYear + " Proton CMS" : "Copyright © 2021 Proton CMS";
document.getElementById("footer").innerHTML = footer;