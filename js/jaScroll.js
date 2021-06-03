
/**Menu se queda fijo al hacer scroll */
/*
window.onscroll = function() { myFunction() };

var divsti = document.getElementById("mymenutop");
var sticky = divsti.offsetTop;
//document.getElementById("minilogo").hidden=false;
    
function myFunction() {
    if (window.pageYOffset >= sticky) {
        divsti.classList.add("sticky");
        document.getElementById("minilogoHidden").style.display="block";
       // document.getElementById("minilogoHidden").style.visibility='visible';
    } else {
        divsti.classList.remove("sticky");
       document.getElementById("minilogoHidden").style.display="hidden";
      // document.getElementById("minilogoHidden").style.visibility='hidden';
    }
}
*/
window.onscroll = function() { myFunction() };

var header = document.getElementById("mymenutop");
var sticky = header.offsetTop;
//document.getElementById("minilogo").hidden=false;
    
function myFunction() {
    if (window.pageYOffset > sticky) {
        header.classList.add("sticky");
        document.getElementById("minilogoHidden").hidden=false;
       // document.getElementById("minilogoHidden").style.visibility='visible';
    } else {
        header.classList.remove("sticky");
       document.getElementById("minilogoHidden").hidden=true;
      // document.getElementById("minilogoHidden").style.visibility='hidden';
    }
}
