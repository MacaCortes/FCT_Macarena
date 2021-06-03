

/** --------------*/

/* funcion para validar el dni de ususario*/

function validarForm() {
   // alert("Procedemos a la comprobacion de datos");
    let dni = document.getElementById('dni').value;
    console.log(dni);
    let nombre = document.form1.nombre.value;
    console.log(nombre);
    let apellido = document.form1.apellido.value;
    console.log(apellido);
    let telefono = document.form1.telefono.value;
    console.log(telefono);
    let email = document.form1.email.value;
    console.log(email);
    let direccion = document.form1.direccion.value;
    console.log(direccion);
    let cp = document.form1.codigo_postal.value;
    console.log(cp);
    let pass = document.form1.pass.value;
    console.log(pass);

    if (nif(dni) == false) {
        alert('[ERROR]\nDni erroneo, ');
        return false;
    }
    if (nombre == null || nombre.length == 0 || / ^ \s + $ /.test(nombre)) {
        alert("Fallo en Nombre, \nvuelva a escribir porfavor");
        return false;
    }
    if (apellido == null || apellido.length == 0 || / ^ \s + $ /.test(nombre)) {
        alert("Fallo en Apellido \nvuelva a escribir porfavor");
        return false;
    }
    if (direccion == null || direccion.length == 0) {
        alert("Fallo en la Direccion \nvuelva a escribir porfavor");
        return false;
    }


    if (cp == "") {
        alert("[ERROR] no ha seleccionado ningun código postal");
        return false;
    }
    if (isNaN(telefono)) {
        alert("[ERROR] en numero de telefono")
        return false;
    }
    if (!(/^\d{9}$/.test(telefono))) {
        alert("[ERROR] en numero de telefono debe tener 9 digitos")
        return false;
    }
    let expresion = /^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;

    if (email == "" && !expresion.test(email)) {
        alert("[ERROR] email mal introducido");
        return false;
    }

    if (constrasenia(pass) == false) {
        alert("[ERROR] contraseña debil \n8digitos minimo \nLetras Mayusculas \nLetra minuscula \nNumeros \nUnsimbolo como el punto");
        return false;

    }
   // alert('primera comprobacion de datos: CORRECTA ');
    return true;

}

function nif(dni) {
    var letras = ['T', 'R', 'W', 'A', 'G', 'M', 'Y', 'F', 'P', 'D', 'X', 'B', 'N', 'J', 'Z', 'S', 'Q', 'V', 'H', 'L', 'C', 'K', 'E', 'T'];
    if (!(/^\d{8}[A-Z]$/.test(dni))) {
        return false;
    }
    if (dni.charAt(8) != letras[(dni.substring(0, 8)) % 23]) {
        return false;
    } else {
        return true;
    }

}

/**Tiene ocho caracteres como mínimo.
Letras mayúsculas.
Letras minúsculas.
Números.
Símbolos del teclado (todos los caracteres del teclado que no se definen como letras o números) y espacios. */
function constrasenia(pass) {
    if (pass.length >= 8) {
        var mayuscula = false;
        var minuscula = false;
        var numero = false;
        var caracter_raro = false;

        for (var i = 0; i < pass.length; i++) {
            if (pass.charCodeAt(i) >= 65 && pass.charCodeAt(i) <= 90) {
                mayuscula = true;
            } else if (pass.charCodeAt(i) >= 97 && pass.charCodeAt(i) <= 122) {
                minuscula = true;
            } else if (pass.charCodeAt(i) >= 48 && pass.charCodeAt(i) <= 57) {
                numero = true;
            } else {
                caracter_raro = true;
            }
        }
        if (mayuscula == true && minuscula == true && caracter_raro == true && numero == true) {
            return true;
        }
    }
    return false;
}

