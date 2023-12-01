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
        }

        .form-group {
            margin-bottom: 20px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
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

