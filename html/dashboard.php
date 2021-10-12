<!DOCTYPE html>
<?php
$displayList = 'style="display:none;"';
$displayError = 'style="display:show;"';
if (isset($_GET['auth'])) {
    $val = $_GET['auth'];
    $valSystem = (date('d') * 4);
    if ($val == $valSystem) {
        $displayList = 'style="display:show;"';
        $displayError = 'style="display:none;"';
    }
}
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<!--    <meta http-equiv="refresh" content="120">-->
    <link rel="stylesheet" type="text/css" href="css/theme.min.css">
    <link rel="stylesheet" type="text/css" href="css/recharge.css">
    <title>Dashboard > Recargas &bull; SportSiete</title>
</head>
<body>
<main class="page-wrapper d-flex flex-column">
    <div class="container" <?php echo $displayList; ?>>
        <div class="d-flex align-items-center p-3 my-3 text-white-50 bg-info rounded box-shadow">
            <div class="lh-100">
                <h2 class="mb-0 text-white lh-100">Listado de últimos pagos realizados </h2>
                <small class="text-white">SportSiete.com</small>
            </div>
        </div>
        <hr>
        <div class="table-responsive my-3 p-3 bg-white rounded box-shadow">
            <table class="table table-striped table-sm">
                <thead>
                <tr>
                    <th>Usuario</th>
                    <th># Referencia de pago</th>
                    <th>Correo electrónico</th>
                    <th>Valor</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody id="contentTable"></tbody>
            </table>
        </div>
    </div>

    <div class="constainer" <?php echo $displayError; ?>>
        <div class="d-flex flex-column justify-content-center pt-5 mt-n6"
             style="flex: 1 0 auto;">
            <div class="pt-7 pb-5">
                <div class="text-center mb-2 pb-4">
                    <h1 class="mb-grid-gutter"><img class="d-inline-block" src="imagenes/500-illustration.svg"
                                                    alt="Error 500"><span class="visually-hidden">Error</span><span
                                class="d-block pt-3 fs-sm fw-semibold text-danger">Error code: 500</span></h1>
                    <h2>Usted no tiene permitido la visualización de este modulo</h2>
                    <a class="btn btn-translucent-primary me-3" href="index.php">
                        Ir a la página principal
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

<div class="alert alert-danger alert-fixed" id="alert" role="alert" style="visibility: hidden;"></div>
<div class="alert alert-success alert-fixed" id="alertSuccess" role="alert" style="visibility: hidden;"></div>
<script src="js/dashboard.js"></script>
</body>
</html>
