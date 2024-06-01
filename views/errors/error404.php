<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <title>Recurso no disponible</title>
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background-color: #f8f9fa;
        }
        .error-container {
            text-align: center;
        }
        .buttons-container {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container error-container">
        <div class="jumbotron">
            <h1 class="display-4">Recurso no disponible</h1>
            <p class="lead">Lo sentimos, el recurso que estás buscando no está disponible en este momento.</p>
            <hr class="my-4">
            <p>Puedes intentar nuevamente más tarde.</p>
            <div class="buttons-container" id="buttons-container" style="display: none;">
                <button class="btn btn-primary" onclick="history.back()">Retroceder</button>
                <button class="btn btn-danger" onclick="logout()">Desloguearme</button>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script>
        function logout() {
            window.location.href = 'index.php?c=LoginController&a=salir';
        }

        $(document).ready(function() {
            const currentUrl = window.location.search;
            const urlPattern = /^(|index(\.php)?(\?c=LoginController)?)$/;
            if (urlPattern.test(currentUrl)) {
                $('#buttons-container').show();
            }
        });
    </script>
</body>
</html>
