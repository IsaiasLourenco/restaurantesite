<?php

require_once('../../conexao.php');
require_once('verificar.php');


$hoje = date('Y-m-d');
$mes_atual = Date('m');
$ano_atual = Date('Y');
$dataInicioMes = $ano_atual . "-" . $mes_atual . "-01";

$entradas = 0;
$saidas = 0;
$saldo = 0;
$entradasF = 0;
$saidasF = 0;
$saldoF = 0;
$query = $pdo->query("SELECT * from movimentacoes where data_mov = curDate() order by id desc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if ($total_reg > 0) {

    for ($i = 0; $i < $total_reg; $i++) {
        foreach ($res[$i] as $key => $value) {
        }

        if ($res[$i]['tipo'] == 'Entrada') {

            $entradas += $res[$i]['valor'];
        } else {

            $saidas += $res[$i]['valor'];
        }

        $saldo = $entradas - $saidas;

        $entradasF = number_format($entradas, 2, ',', '.');
        $saidasF = number_format($saidas, 2, ',', '.');
        $saldoF = number_format($saldo, 2, ',', '.');

        if ($saldo < 0) {
            $classeSaldo = 'text-danger';
        } else {
            $classeSaldo = 'text-success';
        }
    }
}

$query = $pdo->query("SELECT * from movimentacoes order by id desc limit 1");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$valorMov = $res[0]['valor'];
$descricaoMov = $res[0]['descricao'];
$tipoMov = $res[0]['tipo'];
$valorMov = number_format($valorMov, 2, ',', '.');
if ($tipoMov == 'Entrada') {
    $classeMov = 'text-success';
} else {
    $classeMov = 'text-danger';
}

$query = $pdo->query("SELECT * from contas_receber where data_vencimento < curDate() and pago != 'Sim'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$contas_receber_vencidas = @count($res);

$query = $pdo->query("SELECT * from contas_receber where data_vencimento = curDate() and pago != 'Sim'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$contas_receber_hoje = @count($res);

$query = $pdo->query("SELECT * from contas_pagar where data_vencimento < curDate() and pago != 'Sim'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$contas_pagar_vencidas = @count($res);

$query = $pdo->query("SELECT * from contas_pagar where data_vencimento = curDate() and pago != 'Sim'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$contas_pagar_hoje = @count($res);

$entradasM = 0;
$saidasM = 0;
$saldoM = 0;
$saldoMesF = 0;
$query = $pdo->query("SELECT * from movimentacoes where data_mov >= '$dataInicioMes' and data_mov <= curDate() ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if ($total_reg > 0) {

    for ($i = 0; $i < $total_reg; $i++) {
        foreach ($res[$i] as $key => $value) {
        }

        if ($res[$i]['tipo'] == 'Entrada') {

            $entradasM += $res[$i]['valor'];
        } else {

            $saidasM += $res[$i]['valor'];
        }

        $saldoMes = $entradasM - $saidasM;

        $entradasMF = number_format($entradasM, 2, ',', '.');
        $saidasMF = number_format($saidasM, 2, ',', '.');
        $saldoMesF = number_format($saldoMes, 2, ',', '.');

        if ($saldoMesF < 0) {
            $classeSaldoM = 'text-danger';
        } else {
            $classeSaldoM = 'text-success';
        }
    }
}

$totalPagarM = 0;
$pagarMesF = 0;
$query = $pdo->query("SELECT * from contas_pagar where data_vencimento >= '$dataInicioMes' and data_vencimento <= curDate()");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$pagarMes = @count($res);
$total_reg = @count($res);
if ($total_reg > 0) {

    for ($i = 0; $i < $total_reg; $i++) {
        foreach ($res[$i] as $key => $value) {
        }

        $totalPagarM += $res[$i]['valor'];
        $pagarMesF = number_format($totalPagarM, 2, ',', '.');
    }
}

$totalReceberM = 0;
$receberMesF = 0;
$query = $pdo->query("SELECT * from contas_receber where data_conta >= '$dataInicioMes' and data_conta <= curDate()");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$receberMes = @count($res);
$total_reg = @count($res);
if ($total_reg > 0) {

    for ($i = 0; $i < $total_reg; $i++) {
        foreach ($res[$i] as $key => $value) {
        }

        $totalReceberM += $res[$i]['valor'];
        $receberMesF = number_format($totalReceberM, 2, ',', '.');
    }
}

$query = $pdo->query("SELECT * from reservas where data_reser = curDate()");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reser = @count($res);


?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../vendor/css/h2.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
</head>

<body>
    <div class="container-fluid">
        <section id="minimal-statistics">
            <div class="row mb-2">
                <div class="col-12 mt-3 mb-1">
                    <h2 class="text-uppercase">Estatísticas Financeiras</h2>

                </div>
            </div>

            <div class="row mb-4">

                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <div class="align-self-center col-3">
                                        <i class="bi bi-cash-stack text-success fs-1 float-start"></i>
                                    </div>
                                    <div class="col-9 text-end">
                                        <h3> <span class="text-success">R$ <?php echo @$entradasF ?></span></h3>
                                        <span>Entradas do Dia</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <div class="align-self-center col-3">
                                        <i class="bi bi-cash-stack text-danger fs-1 float-start"></i>
                                    </div>
                                    <div class="col-9 text-end">
                                        <h3> <span class="">R$ <?php echo @$saidasF ?></span></h3>
                                        <span>Saídas do Dia</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <div class="align-self-center col-3">
                                        <i class="bi bi-cash <?php echo $classeSaldo ?> fs-1 float-start"></i>
                                    </div>
                                    <div class="col-9 text-end">
                                        <h3> <span class="<?php echo $classeSaldo ?>">R$ <?php echo @$saldoF ?></span></h3>
                                        <span>Saldo do Dia</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <div class="align-self-center col-3">
                                        <i class="bi bi-info-circle-fill <?php echo $classeMov ?> fs-1 float-start"></i>
                                    </div>
                                    <div class="col-9 text-end">
                                        <h3>R$ <?php echo @$valorMov ?></h3>
                                        <span><?php echo $descricaoMov ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row mb-4">

                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <div class="align-self-center col-3">
                                        <i class="bi bi-receipt text-warning fs-1 float-start"></i>
                                    </div>
                                    <div class="col-9 text-end">
                                        <h3> <span class=""><?php echo @$contas_pagar_hoje ?></span></h3>
                                        <span>Contas à Pagar (Hoje)</span>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <div class="align-self-center col-3">
                                        <i class="bi bi-receipt-cutoff text-danger fs-1 float-start"></i>
                                    </div>
                                    <div class="col-9 text-end">
                                        <h3> <span class="">
                                                <?php echo @$contas_pagar_vencidas ?></span></h3>
                                        <span>Contas à Pagar Vencidas</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <div class="align-self-center col-3">
                                        <i class="bi bi-wallet-fill text-warning fs-1 float-start"></i>
                                    </div>
                                    <div class="col-9 text-end">
                                        <h3> <span class=""><?php echo @$contas_receber_hoje ?></span></h3>
                                        <span>Contas Receber (Hoje)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <div class="align-self-center col-3">
                                        <i class="bi bi-wallet2 text-danger fs-1 float-start"></i>
                                    </div>
                                    <div class="col-9 text-end">
                                        <h3><?php echo @$contas_receber_vencidas ?></h3>
                                        <span>Contas à Receber Vencidas</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </section>

        <section id="stats-subtitle">
            <div class="row mb-2">
                <div class="col-12 mt-3 mb-1">
                    <h2 class="text-uppercase">Estatísticas Mensais</h2>

                </div>
            </div>

            <div class="row mb-4">

                <div class="col-xl-6 col-md-12">
                    <div class="card overflow-hidden">
                        <div class="card-content">
                            <div class="card-body cleartfix">
                                <div class="row media align-items-stretch">
                                    <div class="align-self-center col-1">
                                        <i class="bi-wallet-fill text-primary fs-1 mr-2"></i>
                                    </div>
                                    <div class="media-body col-6">
                                        <h4>Saldo Total</h4>
                                        <span>Total Arrecado este Mês</span>
                                    </div>
                                    <div class="text-end col-5">
                                        <h2><span class="<?php echo $classeSaldoM ?>">R$ <?php echo @$saldoMesF ?></h2></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 col-md-12">
                    <div class="card overflow-hidden">
                        <div class="card-content">
                            <div class="card-body cleartfix">
                                <div class="row media align-items-stretch">
                                    <div class="align-self-center col-1">
                                        <i class="bi bi-wallet text-danger fs-1 mr-2"></i>
                                    </div>
                                    <div class="media-body col-6">
                                        <h4>Contas à Pagar</h4>
                                        <span>Total de <?php echo $pagarMes ?> Contas no Mês</span>
                                    </div>
                                    <div class="text-end col-5">
                                        <h2>R$ <?php echo @$pagarMesF ?></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row mb-4">

                <div class="col-xl-6 col-md-12">
                    <div class="card overflow-hidden">
                        <div class="card-content">
                            <div class="card-body cleartfix">
                                <div class="row media align-items-stretch">
                                    <div class="align-self-center col-1">
                                        <i class="bi bi-cash-stack text-success fs-1 mr-2"></i>
                                    </div>
                                    <div class="media-body col-6">
                                        <h4>Contas à Receber</h4>
                                        <span>Total de <?php echo $receberMes ?> Contas no Mês</span>
                                    </div>
                                    <div class="text-end col-5">
                                        <h2>R$ <?php echo @$receberMesF ?></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 col-md-12">
                    <div class="card overflow-hidden">
                        <div class="card-content">
                            <div class="card-body cleartfix">
                                <div class="row media align-items-stretch">
                                    <div class="align-self-center col-1">
                                        <i class="bi bi-bag-check-fill text-success fs-1 mr-2"></i>
                                    </div>
                                    <div class="media-body col-6">
                                        <h4>Reservas</h4>
                                        <span>Reservas feitas Hoje</span>
                                    </div>
                                    <div class="text-end col-5">
                                        <h2><?php echo @$total_reser ?></h2>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </section>

        <section id="stats-subtitle">
            <div class="row mb-2">
                <div class="col-12 mt-3 mb-1">
                    <h2 class="text-uppercase">Modelo de Gráficos</h2>

                </div>
            </div>

            <style type="text/css">
                #principal {
                    width: 100%;
                    height: 100%;
                    margin-left: 10px;
                    font-family: Verdana, Helvetica, sans-serif;
                    font-size: 14px;

                }

                #barra {
                    margin: 0 2px;
                    vertical-align: bottom;
                    display: inline-block;
                    padding: 5px;
                    text-align: center;

                }

                .cor1,
                .cor2,
                .cor3,
                .cor4,
                .cor5,
                .cor6,
                .cor7,
                .cor8,
                .cor9,
                .cor10,
                .cor11,
                .cor12 {
                    color: #FFF;
                    padding: 5px;
                }

                .cor1 {
                    background-color: #FF0000;
                }

                .cor2 {
                    background-color: #0000FF;
                }

                .cor3 {
                    background-color: #FF6600;
                }

                .cor4 {
                    background-color: #009933;
                }

                .cor5 {
                    background-color: #FF0000;
                }

                .cor6 {
                    background-color: #0000FF;
                }

                .cor7 {
                    background-color: #FF6600;
                }

                .cor8 {
                    background-color: #009933;
                }

                .cor9 {
                    background-color: #FF0000;
                }

                .cor10 {
                    background-color: #0000FF;
                }

                .cor11 {
                    background-color: #FF6600;
                }

                .cor12 {
                    background-color: #009933;
                }
            </style>

            <div id="principal">
                <h2>Reservas de Mesas feitas no Ano de <?php echo $ano_atual ?></h2>
                <?php
                // definindo porcentagem
                //BUSCAR O TOTAL DE VENDAS POR MES NO ANO
                $total  = 12; // total de barras
                for ($i = 1; $i < 13; $i++) {

                    $dataMesInicio = $ano_atual . "-" . $i . "-01";
                    $dataMesFinal = $ano_atual . "-" . $i . "-31";
                    $totalVenM = 0;
                    $query = $pdo->query("SELECT * from reservas where data_reser >= '$dataMesInicio' AND data_reser <= '$dataMesFinal'");
                    $res = $query->fetchAll(PDO::FETCH_ASSOC);
                    $total_vendas_mes = @count($res);
                    if ($total_vendas_mes > 0) {
                        $altura_barra = $total_vendas_mes + 10;
                    } else {
                        $altura_barra = $total_vendas_mes;
                    }

                    if ($i < 10) {
                        $texto = '0' . $i . '/' . $ano_atual;
                    } else {
                        $texto = $i . '/' . $ano_atual;
                    }

                ?>

                    <div id="barra">
                        <div class="cor<?php echo $i ?>" style="height:<?php echo $altura_barra + 25 ?>px"> <?php echo $total_vendas_mes ?> </div>
                        <div><?php echo $texto ?></div>
                    </div>

                <?php } ?>

            </div>

        </section>

    </div>
</body>

</html>