/************* Funciones propias del modulo de recargas del Sitio Web SportSiete ***************************************/
const recharge = () => {
    document.getElementById("rechargeCard").style.display = 'block';
    document.getElementById("confirmPayCard").style.display = 'none';
};

const confirmPay = () => {
    document.getElementById("rechargeCard").style.display = 'none';
    document.getElementById("confirmPayCard").style.display = 'block';
};

const reg = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;

/**
 * @rechargeForm
 * Funciones propias del formulario de recarga
 * */
(function () {
    const formRecharge = document.getElementById('formRecharge');
    formRecharge.addEventListener('submit', function (e) {
            e.preventDefault();
            const email = document.getElementById('email').value;
            const valRecharge = document.getElementById('valProduct').value;
            const numReference = document.getElementById('referencePay').value;
            const idUser = document.getElementById('idUser').value;
            if (valFormRecharge(email, valRecharge, numReference)) {
                const data = new FormData(formRecharge);
                const buttonSave = document.getElementById("btnSaveRecharge");
                buttonSave.disabled = true
                data.append('idUser', idUser);
                data.append("function", "insert");
                fetch('recharge.php', {
                    method: 'POST', // *GET, POST, PUT, DELETE, etc.
                    body: data
                }).then(res => res.json())
                    .then(response => {
                        if (response === 'referencePay') {
                            document.getElementById('alert').style.visibility = "visible";
                            document.getElementById('alert').innerHTML = `La referencia de pago ya esta registrada`;
                            buttonSave.disabled = true
                        } else if (response === 'success') {
                            document.getElementById('alertSuccess').style.visibility = "visible";
                            document.getElementById('alertSuccess').innerHTML = `Se inserto correctamente la información`;
                            formRecharge.reset();
                            setTimeout(function () {
                                document.getElementById('alertSuccess').style.visibility = "hidden";
                                buttonSave.disabled = false
                            }, 2000);
                        } else {
                            document.getElementById('alert').style.visibility = "visible";
                            document.getElementById('alert').innerHTML = `No fue posible insertar la información`;
                            buttonSave.disabled = false
                        }
                    })
                    .catch(error => console.error(error));
            }

        }
    );
})();

const valFormRecharge = (email, valRecharge, numReference) => {
    let msgEmail = document.getElementById('alert');
    /*Validaciones del campo correo*/
    if (email === '') {
        msgEmail.style.visibility = "visible";
        msgEmail.innerHTML = `El campo <strong>correo electrónico</strong> es requerido`;
        return false;
    } else if (email.trim() !== '' && !reg.test(email)) {
        msgEmail.style.visibility = "visible";
        msgEmail.innerHTML = `El campo <strong>correo electrónico</strong> no cumple con un formato valido <br> <strong>Ejemplo:</strong> ejemplo@email.com`;
        return false;
    }
    /*Validaciones del campo valor recarga*/
    if (valRecharge === '') {
        msgEmail.style.visibility = "visible";
        msgEmail.innerHTML = `El campo <strong>valor recarga</strong> es requerido`;
        return false;
    }
    /*Validaciones del número de referencia*/
    if (numReference === '') {
        msgEmail.style.visibility = "visible";
        msgEmail.innerHTML = `El campo <strong>referencia de pago</strong> es requerido`;
        return false;
    }

    return true;
}

function valFormEmail(msgEmail, email) {
    /*Validaciones del campo correo*/
    if (email === '') {
        msgEmail.style.visibility = "visible";
        msgEmail.innerHTML = `El campo <strong>correo electrónico</strong> es requerido`;
        return false;
    } else if (email.trim() !== '' && !reg.test(email)) {
        msgEmail.style.visibility = "visible";
        msgEmail.innerHTML = `El campo <strong>correo electrónico</strong> no cumple con un formato valido <br> <strong>Ejemplo:</strong> ejemplo@email.com`;
        return false;
    }
}

function valEmail() {
    const formRecharge = document.getElementById('formRecharge');
    const msgEmail = document.getElementById('alert');
    const email = document.getElementById('email').value;
    valFormEmail(msgEmail, email);
    if (valFormEmail && email !== '') {
        const data = new FormData(formRecharge);
        data.append("function", "valEmail");
        fetch('recharge.php', {
            method: 'POST', // *GET, POST, PUT, DELETE, etc.
            body: data
        })
            .then(res => res.json())
            .then(response => {
                if (response) {
                    document.getElementById('idUser').value = response.codigo;
                    msgEmail.style.visibility = "hidden";
                } else {
                    document.getElementById('email').value = ' ';
                    msgEmail.style.visibility = "visible";
                    msgEmail.innerHTML = `El usuario no esta registrado`;
                }
            })
            .catch(error => console.error(error));
    }
}

function valReferencePay(numReference) {
    const formRecharge = document.getElementById('formRecharge');
    const msgEmail = document.getElementById('alert');
    const referencePay = numReference ? numReference : document.getElementById('referencePay').value;
    const btnSaveRecharge = document.getElementById('btnSaveRecharge');
    if (referencePay !== '') {
        const data = new FormData(formRecharge);
        data.append("function", "valReferencePay");
        fetch('recharge.php', {
            method: 'POST', // *GET, POST, PUT, DELETE, etc.
            body: data
        })
            .then(res => res.json())
            .then(response => {
                if (response) {
                    document.getElementById('referencePay').value = ' ';
                    btnSaveRecharge.disabled = true;
                    msgEmail.style.visibility = "visible";
                    msgEmail.innerHTML = `La referencia de pago ya esta registrada`;
                } else {
                    msgEmail.style.visibility = "hidden";
                    btnSaveRecharge.disabled = false;
                }
            })
            .catch(error => console.error(error));
    }
}