<?php
session_start();//Inicia o reanuda la sesion actual
require('conexion.php');//Contiene el archivo que contiene la conexion a la BD

$message = ""; // Variable para almacenar el mensaje de la alerta

if ($_SERVER['REQUEST_METHOD'] == 'POST') {//Verifica si el formulario se ha enviado mediante el método POST
    $correo = $_POST['correo'];//Obtiene el valor del correo
    $password = $_POST['password'];//Obtiene el valor de la contraseña

    $sql = "SELECT * FROM personas WHERE correo='$correo' AND password='$password'";//Se verifica si hay una coincidencia con los datos anteriores en BD
    $result = $conn->query($sql);//Ejecuta la consulta

    if ($result->num_rows > 0) {//Se verifica si encuentra al menos una fila en el resultado de la consulta, si es asi, se considera exitoso
        $_SESSION['loggedin'] = true;//Indica que el usuario ha iniciado sesion
        // Mensaje de éxito
        $message = "Inicio de sesión exitoso";
        // Redirigir después de 2 segundos
        header("refresh:2;url=crud.php");//Redirige a la página crud.php
    } else {
        $message = "Usuario o contraseña incorrectos";//Mensaje de error
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            max-width: 400px;
            margin-top: 50px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 4px;
            box-shadow: none;
        }

        .btn-primary {
            background-color: #28a745;
            border-color: #28a745;
            width: 100%;
            padding: 10px;
            font-size: 16px;
        }

        .btn-primary:hover {
            background-color: #218838;
            border-color: #218838;
        }

        .alert {
            border-radius: 8px;
        }
        h2 {
            color: #28a745;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Alerta de error o éxito -->
    <?php if (!empty($message)): ?>
        <div class="alert <?php echo ($result->num_rows > 0) ? 'alert-success' : 'alert-danger'; ?> alert-dismissible fade show" role="alert">
            <?php echo $message; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>
    <h2 class="mb-3">Inicie sesión</h2>
    <form method="post" action="login.php">
        <div class="form-group">
            <label for="correo">Correo:</label>
            <input type="text" class="form-control" id="correo" name="correo" required>
        </div>
        <div class="form-group">
            <label for="password">Contraseña:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Iniciar sesión</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
