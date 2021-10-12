<?php
if (@$_GET) {
    if ($_GET['state'] === 'listRecharge') {
        listRecharge();
    }
}
function listRecharge()
{
    /** @var TYPE_NAME $connect
     * @status 1 = Aprobado 0 = Rechazado null = Pendiente aprobación
     */
    $connect = pg_connect("dbname=sportsiete user=root") or die("No fue posible establecer conexión con el servidor");
    $sql = "SELECT id,email,value,referencePay,status,idUser FROM recharge 
            WHERE (status <> 1 OR status is null)
            ORDER BY id DESC";
    $result = pg_query($connect, $sql) or die(pg_last_error($connect));
    if (!$result) {
        echo "Ocurrió un error.\n";
        exit;
    }

    echo json_encode(pg_fetch_all($result));
    pg_close($connect);
}

/**
 * @insertApprovedRecharge
 * Method for approved recharge of user
 */
if (@$_GET['function'] === 'insertApprovedRecharge') {
    /** @var TYPE_NAME $connect */
    $connect = pg_connect("dbname=sportsiete user=root");
    $getPayment = $_GET['idPayment'];

    $sql = "UPDATE recharge 
            SET status = 1
            WHERE id = '$getPayment'";
    $result = pg_query($connect, $sql) or die(pg_last_error($connect));

    $data = pg_fetch_object($result, NULL);
    if ($result) {
        insertRechargeTemporal($getPayment);
    } else {
        echo json_encode(false);
    }
    pg_close($connect);
}

function insertRechargeTemporal($idPayment)
{
    $connect = pg_connect("dbname=sportsiete user=root");

    date_default_timezone_set('America/Bogota');
    $date = date('Y-m-d h:i:s', time());

    $sqlData = "SELECT * FROM recharge WHERE id = '$idPayment'";
    $resultData = pg_query($connect, $sqlData) or die(pg_last_error($connect));
    $row = pg_fetch_array($resultData);

    $idUser = $row['iduser'];
    $valueRecharge = $row['value'];
    $sql = "INSERT INTO usuario_carga (usuario,instante,monto_original,monto_restante)values($idUser,'$date',$valueRecharge,$valueRecharge)";
    $result = pg_query($connect, $sql) or die(pg_last_error($connect));
    /**@response Response to fetch */
    if ($result) {
        echo json_encode(true);
    } else {
        echo json_encode(false);
    }
    pg_close($connect);
}

/**
 * @insertDeclineRecharge
 * Method for decline recharge of user
 */
if (@$_GET['function'] === 'insertDeclinePayment') {
    /** @var TYPE_NAME $connect */
    $connect = pg_connect("dbname=sportsiete user=root");
    $getPayment = $_GET['idPayment'];

    $sql = "UPDATE recharge 
            SET status = 0
            WHERE id = '$getPayment'";
    $result = pg_query($connect, $sql) or die(pg_last_error($connect));

    $data = pg_fetch_object($result, NULL);

    /**@response Response to fetch */
    if ($result) {
        echo json_encode(true);
    } else {
        echo json_encode(false);
    }
    pg_close($connect);
}