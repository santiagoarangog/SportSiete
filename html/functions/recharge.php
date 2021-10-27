<?php
/**
 * @valEmail
 * @data POST['function'] where is equal to input valEmail
 */
if (@$_POST['function'] === 'valEmail') {
    /**@isVar TYPE_NAME $connect -> Data is connect to database */
    $connect = pg_connect("dbname=sportsiete user=root") or die('No fue posible establecer conexión con el servidor');
    /**@isVar $email -> Data validation */
    $email = rtrim(ltrim($_POST['email']));

    $sql = "SELECT codigo FROM usuario WHERE correo = '$email'";
    $result = pg_query($connect, $sql) or die(pg_last_error($connect));

    $data = pg_fetch_object($result, NULL);

    /**@response Response to fetch */
    if (isset($data)) {
        echo json_encode($data);
    }
    pg_close($connect);
}

/**
 * @valReferencePay
 * @data POST['function'] where is equal to input valReferencePay
 */
if (@$_POST['function'] === 'valReferencePay') {
    /**@isVar TYPE_NAME $connect -> Data is connect to database */
    $connect = pg_connect("dbname=sportsiete user=root") or die('No fue posible establecer conexión con el servidor');
    /**@isVar $email -> Data validation */
    $referencePay = rtrim(ltrim($_POST['referencePay']));

    $sql = "SELECT id FROM recharge WHERE referencePay = '$referencePay'";
    $result = pg_query($connect, $sql) or die(pg_last_error($connect));

    $data = pg_fetch_object($result, NULL);

    /**@response Response to fetch */
    if (isset($data)) {
        echo json_encode($data);
    }
    pg_close($connect);
}

/**
 * @insertRecharge
 * Method for inserte recharge of user
 */
if (@$_POST['function'] === 'insert') {
    /** @var TYPE_NAME $connect */
    $connect = pg_connect("dbname=sportsiete user=root");
    $email = rtrim(ltrim($_POST['email']));
    $value = rtrim(ltrim($_POST['product']));
    $referencePay = rtrim(ltrim($_POST['referencePay']));
    $idUser = rtrim(ltrim($_POST['idUser']));

    $sqlVal = "SELECT * FROM recharge WHERE referencePay = '$referencePay'";
    $resultVal = pg_query($connect, $sqlVal) or die(pg_last_error($connect));
    $dataVal = pg_fetch_object($resultVal, NULL);
    if (!$dataVal) {
        $sql = "INSERT INTO recharge (email,value,referencePay,status,idUser) VALUES('$email','$value','$referencePay',null,'$idUser')";
        $result = pg_query($connect, $sql) or die(pg_last_error($connect));
        $data = pg_fetch_object($result, NULL);
        /**@response Response to fetch */
        if (isset($result)) {
            echo json_encode('success');
        } else {
            echo json_encode('error');
        }
    } else {
        echo json_encode('referencePay');
    }


    pg_close($connect);
}