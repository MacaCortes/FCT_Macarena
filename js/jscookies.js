/** SALIR DE POLITICA DE COOKIES**/

function irAceptar(){
    window.location.href ="index.php";
}



/* ésto comprueba la localStorage si ya tiene la variable guardada */
function compruebaAceptaCookies() {
  if(localStorage.aceptaCookies == 'true'){
    cajacookies.style.display = 'none';
  }
}

/* aquí guardamos la variable de que se ha
aceptado el uso de cookies así no mostraremos
el mensaje de nuevo */
function aceptarCookies() {
  localStorage.aceptaCookies = 'true';
  cajacookies.style.display = 'none';
}

/* ésto se ejecuta cuando la web está cargada */
$(document).ready(function () {
  compruebaAceptaCookies();
});




/*

function aceptarCookies() {   
    var nombre = "cookieComeBienSano";
    var valor = "cookie";
    var expires = "1";
    setCookie(nombre, valor, expires);
    console.log(nombre, valor, expires);
   // darcoookie(nombre);
    sessionStorage.setItem(nombre,valor);
}

if(sessionStorage){
   var cook= document.getElementById('mostrarCookies');
   cook.innerHTML="";
}else{
     var cook= document.getElementById('mostrarCookies');
   cook.innerHTML="<div id='cajacookies' class='flotante' > <div class='cc_grey'></div><div class='row aviso'>"
                +"<div class='col-6'>"
                 +"<h3>Su privacidad es importante para nosotros</h3>"
                 +"<p>Por la ley llamada LGPD, y ahora también hecho reglamento RGPD, los desarrolladores nos vemos obligados a mostrar un mensaje de si el usuario acepta que usemos cookies. Todo esto se debe en parte por el miedo por las cookies, quizá por eldesconocimiento de lo que son o para qué sirven. Las cookies son simples ficheros de texto en plano que no contienen absolutamente ningún programa. No se ejecutan ni pueden infiltrarse en un ordenador, pero ya puestos a hacer leyes nonos queda otra que obedecer.<a href='politica_Cookies.html'>política de privacidad</a>.</p>"
               +" </div><div class='col-6'>"
               +" <button onclick='aceptarCookies()' class='btncookies'> Aceptar Cookies </button></div> </div> </div> ";
}

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
    sessionStorage.setItem(cname,cvalue);
    document.getElementById("cajacookies").style.display = "none";
}

function getCookie(nombre) {
    var name = nombre + "=";
    var array = document.cookie.split(';');
    console.log(array);
    for (var i = 0; i < array.length; i++) {
        var c = array[i];
        console.log(c);
        while (c.chartAt(0) == " ") {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}
*/