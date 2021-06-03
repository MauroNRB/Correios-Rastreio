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
            $arrayCorreios['msg'] = "A consulta do correios demorou para retornar, por favor tente mais tarde. Caso o rastreamoento do pedido seja recente fique tranquilo que normalmente leva 72 horas depois da postagem nos correios para começar a exibir informações.";
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