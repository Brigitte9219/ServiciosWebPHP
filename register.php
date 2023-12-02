<?php
session_start(); // Inicia o reanuda la sesión actual
require('conexion.php'); // Contiene el archivo que contiene la conexión a la BD

$message = ""; // Variable para almacenar el mensaje de la alerta

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Verifica si el formulario se ha enviado mediante el método POST
    // Validación y obtención de datos
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $password = $_POST['password'];

    // Insertar nueva persona en la base de datos
    $sql = "INSERT INTO personas (nombre, apellido, telefono, correo, password) VALUES ('$nombre', '$apellido', '$telefono', '$correo', '$password')";
    $result = $conn->query($sql);

    if ($result) {
        $message = "Registro exitoso";
    } else {
        $message = "Error al registrar";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
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
    <!-- Alerta de éxito o error -->
    <?php if (!empty($message)): ?>
        <div class="alert <?php echo ($result) ? 'alert-success' : 'alert-danger'; ?> alert-dismissible fade show" role="alert">
            <?php echo $message; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>
    
    <h2 class="mb-3">Registro</h2>
    <form method="post" action="register.php">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div class="form-group">
            <label for="apellido">Apellido:</label>
            <input type="text" class="form-control" id="apellido" name="apellido" required>
        </div>
        <div class="form-group">
            <label for="telefono">Teléfono:</label>
            <input type="number" class="form-control" id="telefono" name="telefono" required>
        </div>
        <div class="form-group">
            <label for="correo">Correo:</label>
            <input type="email" class="form-control" id="correo" name="correo" required>
        </div>
        <div class="form-group">
            <label for="password">Contraseña:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Registrarse</button>
    </form>
    <!-- Agrega el enlace a la página de inicio de sesión aquí -->
    <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a></p>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
