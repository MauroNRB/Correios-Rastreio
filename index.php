<html>
    <head>
        <title>Rastreio Encomenda</title>
        <meta charset="utf-8">
        <link rel="icon" type="image/x-icon" href="img/maps.ico">
        <meta name="description" content="Rastreamento simplificado de encomendas nacionais e internacionais. Rastreio simples, rápido e 100% grátis. Acompanhe suas entregas online e simplificado.">
        <script src="js/jquery.min.js"></script>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <script src="js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body class="">
        <div class="container">
            <div class="gap"></div>

            <div class="row">
                <div class="col-xl-6 float-left">
                    <img src="img/correioslogo.png">
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
                require_once 'request.php';

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

        <div class="fixed-bottom">
            <footer class="container-fluid">
                <div class="justify-content-center">
                    <p class="text-center">Developed by <a href="https://www.linkedin.com/in/mauro-ribeiro-b76500178/">Mauro Ribeiro</a>.</p>
                </div>
            </footer>
        </div>

    </body>

    <script src="js/script.js"></script>
</html>
