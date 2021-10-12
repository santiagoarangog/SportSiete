const content = document.getElementById('contentTable');

(function () {
    fetch('funDashboard.php?state=listRecharge', {
        method: 'GET', // *GET, POST, PUT, DELETE, etc.
    }).then(res => res.json())
        .then(response => {
            listRecharge(response);
        })
        .catch(error => console.error(error));
})();

function listRecharge(data) {
    content.innerHTML = '';
    const dollarUSLocale = Intl.NumberFormat('en-US');
    let i = 0;
    for (let res of data) {
        i = i + 1;
        content.innerHTML += `
        <tr id="trColumn${i}">
        <input type="hidden" value="${res.id}" id="column${i}">
        <td>${res.iduser}</td>
        <td scope="row">${res.referencepay}</td>
        <td scope="row">${res.email}</td>
        <td scope="row">$ ${dollarUSLocale.format(res.value)}</td>
        <td>
            <button class="btn btn-success btn-sm" onclick="acceptPayment(${i});"><i class="ai-user-check"></i></button>
            <button class="btn btn-danger btn-sm" onclick="declinePayment(${i});"><i class="ai-user-minus"></i></button>
        </td>
        </tr>
        `;
    }
}

function acceptPayment(index) {
    const msgEmail = document.getElementById('alertSuccess');
    const idPayment = document.getElementById('column' + index).value;
    if (idPayment !== '') {
        fetch(`funDashboard.php?function=insertApprovedRecharge&idPayment=${idPayment}`, {
            method: 'GET', // *GET, POST, PUT, DELETE, etc.
        })
            .then(res => res.json())
            .then(response => {
                if (response) {
                    document.getElementById('trColumn' + index).style.visibility = 'hidden';
                    msgEmail.style.visibility = "visible";
                    msgEmail.innerHTML = `Se aprobo correctamente el pago`;
                } else {
                    msgEmail.style.visibility = "hidden";
                }
            })
            .catch(error => console.error(error));
    }
}

function declinePayment(index) {
    const msgEmail = document.getElementById('alertSuccess');
    const idPayment = document.getElementById('column' + index).value;
    if (idPayment !== '') {
        fetch(`funDashboard.php?function=insertDeclinePayment&idPayment=${idPayment}`, {
            method: 'GET', // *GET, POST, PUT, DELETE, etc.
        })
            .then(res => res.json())
            .then(response => {
                if (response) {
                    document.getElementById('trColumn' + index).style.visibility = 'hidden';
                    msgEmail.style.visibility = "visible";
                    msgEmail.innerHTML = `Se aprobo correctamente el pago`;
                } else {
                    msgEmail.style.visibility = "hidden";
                }
            })
            .catch(error => console.error(error));
    }
}