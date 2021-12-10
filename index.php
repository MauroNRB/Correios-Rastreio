<html>
    <head>
        <title>Rastreio Tudo</title>
        <meta charset="utf-8">
        <link rel="icon" type="image/x-icon" href="img/maps.ico">
        <meta name="description" content="Rastreamento simplificado de encomendas nacionais e internacionais. Rastreio simples, rápido e 100% grátis. Acompanhe suas entregas online e simplificado.">
        <script src="js/jquery.min.js"></script>
        <link rel="stylesheet" href="css/bootstrap.min.css">

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
                background: white url('./img/sol.png');
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
                background: white url('./img/lua.png');
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

    <script async>
        let isDarkMode = localStorage.getItem('isDarkMode');
        if (isDarkMode == null) {
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                localStorage.setItem('isDarkMode', 1);
                isDarkMode = 1;
            } else {
                localStorage.setItem('isDarkMode', 0);
                isDarkMode = 0;
            }
        }

        if (isDarkMode == 1) {
            $('body').addClass('dark-mode');
            $('#dark-mode').attr('Checked','Checked');
        }

        $('#dark-mode').change(function(){
            if ($(this).prop('checked')) {
                localStorage.setItem('isDarkMode', 1);
                $('body').addClass('dark-mode');
            } else{
                localStorage.setItem('isDarkMode', 0);
                $('body').removeClass('dark-mode');
            }
        });
    </script>
</html>
