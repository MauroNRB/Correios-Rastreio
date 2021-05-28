<?php

function getTracking()
{
    $obj = null;
    if  (isset($_POST)) {
        $request = $_POST;

        $obj = isset($request['obj']) ? $request['obj'] : null;
        $obj = !empty($obj) ? $obj : null;
    }

    if (isset($_POST) && $obj !== null) {
        $post = array('Objetos' => $obj);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www2.correios.com.br/sistemas/rastreamento/resultado_semcontent.cfm");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 5000);
        $output = curl_exec($ch);
        curl_close($ch);
        $out = explode("table class=\"listEvent sro\">", $output);
        if (isset($out[1])) {
            $output = explode("<table class=\"listEvent sro\">", $output);
            $output = explode("</table>", $output[1]);
            $output = str_replace("</td>", "", $output[0]);
            $output = str_replace("</tr>", "", $output);
            $output = str_replace("<strong>", "", $output);
            $output = str_replace("</strong>", "", $output);
            $output = str_replace("<tbody>", "", $output);
            $output = str_replace("</tbody>", "", $output);
            $output = str_replace("<label style=\"text-transform:capitalize;\">", "", $output);
            $output = str_replace("</label>", "", $output);
            $output = str_replace("&nbsp;", "", $output);
            $output = str_replace("<td class=\"sroDtEvent\" valign=\"top\">", "", $output);
            $output = explode("<tr>", $output);
            $arrayCorreios = array();
            $arrayCorreios['obj'] = $obj;

            foreach ($output as $texto) {
                $info   = explode("<td class=\"sroLbEvent\">", $texto);
                $dados  = explode("<br />", $info[0]);
                $dia   = trim($dados[0]);
                $hora  = trim(@$dados[1]);
                $local = trim(@$dados[2]);
                $dados = explode("<br />", @$info[1]);
                $acao  = trim($dados[0]);
                $exAction   = explode($acao . "<br />", @$info[1]);
                $acrionMsg  = strip_tags(trim(preg_replace('/\s\s+/', ' ', $exAction[0])));
                if ("" != $dia) {
                    $exploDate = explode('/', $dia);
                    $dia1 = $exploDate[2] . '-' . $exploDate[1] . '-' . $exploDate[0];
                    $dia2 = date('Y-m-d');
                    $diferenca = strtotime($dia2) - strtotime($dia1);
                    $dias = floor($diferenca / (60 * 60 * 24));
                    $change = utf8_encode("há {$dias} dias");
                    $arrayCorreios[] = array("erro" => false, "date" => $dia, "hour" => $hora, "location" => $local, "action" => utf8_encode($acao), "message" => utf8_encode($acrionMsg), "change" => utf8_decode($change));
                }
            }
        } else {
            $arrayCorreios = array();
            $arrayCorreios['erro'] = true;
            $arrayCorreios['msg'] = "A consulta do demorou para retornar, por favor tente mais tarde. Caso o rastreamoento do pedido seja recente fique tranquilo que normalmente leva 72 horas depois da postagem nos correios para começar a exibir informações.";
            $arrayCorreios['obj'] = $obj;
        }
    } else {
        $arrayCorreios = array();
        $arrayCorreios['erro'] = true;
        $arrayCorreios['msg'] = "Ops! Nenhum código de rastreio foi informado.";
    }

    return $arrayCorreios;
}

function createHTML()
{
    $trackings = getTracking();
    if (isset($trackings['erro']) && $trackings['erro'] == true) {
        if (isset($trackings['obj']) && $trackings['obj'] != '') {
            echo "
                    <div class='row'>
                        Código: {$trackings['obj']}
                    </div> 
                    <div class='gap'></div>
                    <div class='row'>
                        {$trackings['msg']}
                    </div> 
                ";
        } else {
            echo "
                    <div class='row'>
                        {$trackings['msg']}
                    </div>
                ";
        }
    } else {
        if (isset($trackings['obj']) && $trackings['obj'] != '') {
            echo "<div class='row'>Código: {$trackings['obj']} </div><div class='gap'></div>";
        }

        echo '<div class="row"><table class="table table-striped"><tbody>';
        foreach ($trackings as $key => $tracking) {
            if ($key === 'obj') {
                continue;
            }
            echo "
                <tr>
                    <th>
                        {$tracking['date']}
                        <br>
                        {$tracking['hour']}
                        <br>
                        {$tracking['location']}
                    </th>
                    <td>
                        <b>{$tracking['action']}</b>
                        <br>
                        {$tracking['message']}
                    </td>
                </tr>
            ";
        }
        echo '</div>';
    }
}
?>


<html>
    <head>
        <title>Rastreio Encomenda</title>
        <meta charset="utf-8">
        <link rel="icon" type="image/x-icon" href="maps.ico">
        <meta name="description" content="Rastreamento simplificado de encomendas nacionais e internacionais. Rastreio simples, rápido e 100% grátis. Acompanhe suas entregas online e simplificado.">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
        <style>
            .gap {
                height: 40px;
                width: 100%;
                clear: both;
                display: block;
            }

            body {
                background-color: #fafafa;
                color: #555;
            }

            a {
                color: #222;
            }

            a:hover {
                color: #ec5840;
            }

            CSS
            body.dark-mode {
                background-color: #212529;
                color: #899095;
            }

            .dark-mode a {
                color: #dee2e6;
            }

            .dark-mode a:hover {
                color: #3cc368;
            }

            body.dark-mode {
                background-color: #212529;
                color: #899095;
            }

            .dark-mode a {
                color: #dee2e6;
            }

            .dark-mode a:hover {
                color: #3cc368;
            }

            .dark-mode button {
                background-color: #6c757d;
                border-color: #6c757d;
            }

            .dark-mode button:hover {
                background-color: #343a40;
                border-color: #343a40;
            }

            .switch {
                position: relative;
                display: inline-block;
                width: 60px;
                height: 34px;
            }

            .switch input {
                opacity: 0;
                width: 0;
                height: 0;
            }

            .slider {
                position: absolute;
                cursor: pointer;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: #ccc;
                -webkit-transition: 0.4s;
                transition: 0.4s;
            }

            .slider:before {
                position: absolute;
                content: "";
                height: 40px;
                width: 40px;
                left: 0px;
                bottom: 4px;
                top: 0;
                bottom: 0;
                margin: auto 0;
                -webkit-transition: 0.4s;
                transition: 0.4s;
                box-shadow: 0 0px 15px #2020203d;
                background: white url('sol.png');
                background-repeat: no-repeat;
                background-position: center;
                background-size: 24px 24px;
            }

            input:checked + .slider {
                background-color: #2196f3;
            }

            input:focus + .slider {
                box-shadow: 0 0 1px #2196f3;
            }

            input:checked + .slider:before {
                -webkit-transform: translateX(24px);
                -ms-transform: translateX(24px);
                transform: translateX(24px);
                background: white url('lua.png');
                background-repeat: no-repeat;
                background-position: center;
                background-size: 24px 24px;
            }

            .slider.round {
                border-radius: 34px;
            }

            .slider.round:before {
                border-radius: 50%;
            }
        </style>
    </head>
    <body class="">
        <div class="container">
            <div class="gap"></div>

            <div class="row">
                <div class="col-xl-6 float-left">
                    <img width="201" src="https://correiosrastrear.com/imagens/correioslogo.png">
                </div>

                <div class="col-xl-6 float-right">
                    <label id="switch" class="switch float-right">
                        <input id="dark-mode" type="checkbox">
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>

            <div class="gap"></div>

            <div class="row">
                <h2>Rastreamento de Objetos</h2>
            </div>

            <div class="gap"></div>

            <div class="row">
                <form class="form-inline" method="post">
                    <label for="codeTracking">Código de Rastreio</label>
                    <input type="text" id="codeTracking" name="obj" class="form-control mx-sm-3">
                    <button type="submit" name="submit" class="btn btn-primary">Confirmar</button>
                </form>
            </div>

            <div class="gap"></div>

            <?php
                try {
                    if(isset($_POST['submit'])){
                        createHTML();
                    }
                } catch (Throwable $e) {
                    echo "
                        <div class='row'>
                            Demorou demais a consulta. Tente novamente mais tarde.
                        </div> 
                        <div class='gap'></div> 
                    ";
                }

            ?>
        </div>

    </body>

    <script>
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            $('body').addClass('dark-mode');
            $('#dark-mode').attr('Checked','Checked');
        }

        $('#dark-mode').change(function(){
            if ($(this).prop('checked')) {
                $('body').addClass('dark-mode');
            } else{
                $('body').removeClass('dark-mode');
            }
        });

        var footer = $('<div class="gap"></div><div class="fixed-bottom"><footer class="container-fluid"> <div class="justify-content-center"> <p class="text-center">Developed by <a href="https://www.linkedin.com/in/mauro-ribeiro-b76500178/">Mauro Ribeiro</a>.</p> </div> </footer></div>');

        $("body").append(footer);
    </script>
</html>
