const content = document.getElementById('listTable');

/**
 * @param String name
 * @return String
 */
function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

(function () {
    const idUser = getParameterByName('idUser');
    fetch(`rechargeList.php?state=listRecharge&idUser=${idUser}`, {
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
        <tr>
        <td scope="row">${res.referencepay}</td>
        <td scope="row">${res.email}</td>
        <td scope="row">$ ${dollarUSLocale.format(res.value)}</td>
        <td>${res.status}</td>
        </tr>
        `;
    }
}