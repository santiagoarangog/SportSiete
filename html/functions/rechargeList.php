<?php
if (@$_GET) {
    if ($_GET['state'] === 'listRecharge') {
        listRecharge($_GET['idUser']);
    }
}
function listRecharge($idUser)
{
    /** @var TYPE_NAME $connect */
    $connect = pg_connect("dbname=sportsiete user=root") or die("No fue posible establecer conexión con el servidor");
    $sql = "SELECT id,email,value,referencePay,status,idUser,
            case status when '1' then 'Aprobado' when '0' then 'Rechazado' else 'Pendiente Confirmación' end as status
            FROM recharge 
            WHERE idUser = '$idUser'
            ORDER BY id DESC";
    $result = pg_query($connect, $sql) or die(pg_last_error($connect));
    if (!$result) {
        echo "Ocurrió un error.\n";
        exit;
    }

    echo json_encode(pg_fetch_all($result));
    pg_close($connect);
}