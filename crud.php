<?php
session_start(); // Inicia o reanuda la sesión actual
require('conexion.php');

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// FUNCIONES PARA LAS OPERACIONES EN LA BASE DE DATOS

function obtenerPersonas($conn)
{
    $sql = "SELECT * FROM personas";
    $result = $conn->query($sql);
    $personas = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $personas[] = $row;
        }
    }
    return $personas;
}

function agregarPersona($conn, $nombre, $apellido, $telefono, $correo, $password)
{
    $sql = "INSERT INTO personas (nombre, apellido, telefono, correo, password) VALUES ('$nombre', '$apellido', '$telefono', '$correo', '$password')";
    $conn->query($sql);
}

function actualizarPersona($conn, $id, $nombre, $apellido, $telefono, $correo, $password)
{
    $sql = "UPDATE personas SET nombre=?, apellido=?, telefono=?, correo=?, password=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $nombre, $apellido, $telefono, $correo, $password, $id);
    $stmt->execute();
    $stmt->close();
}

function eliminarPersona($conn, $id)
{
    $sql = "DELETE FROM personas WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['agregar'])) {
        agregarPersona($conn, $_POST['nombre'], $_POST['apellido'], $_POST['telefono'], $_POST['correo'], $_POST['password']);
    } elseif (isset($_POST['actualizar'])) {
        actualizarPersona($conn, $_POST['id'], $_POST['nombre'], $_POST['apellido'], $_POST['telefono'], $_POST['correo'], $_POST['password']);
    } elseif (isset($_POST['eliminar'])) {
        eliminarPersona($conn, $_POST['id']);
    } elseif (isset($_POST['logout'])) {
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit;
    }
}

$personas = obtenerPersonas($conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CRUD de Personas</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }

        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #007bff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: #ffffff;
        }

        .btn {
            cursor: pointer;
        }

        .btn-warning, .btn-danger {
            color: #fff;
        }

        .modal-content {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>

    <h2 class="mt-5">Lista de Personas</h2>
    <table class="table">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Teléfono</th>
            <th>Correo</th>
            <th>Contraseña</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($personas as $persona) : ?>
            <tr>
                <td><?= $persona['id'] ?></td>
                <td><?= $persona['nombre'] ?></td>
                <td><?= $persona['apellido'] ?></td>
                <td><?= $persona['telefono'] ?></td>
                <td><?= $persona['correo'] ?></td>
                <td class="password"><?= $persona['password'] ?></td>
                <td>
                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#actualizarModal<?= $persona['id'] ?>">
                        Actualizar
                    </button>
                    <form method="post" class="d-inline">
                        <input type="hidden" name="id" value="<?= $persona['id'] ?>">
                        <input type="submit" name="eliminar" value="Eliminar" class="btn btn-danger btn-sm">
                    </form>
                </td>
            </tr>

            <div class="modal fade" id="actualizarModal<?= $persona['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="actualizarModalLabel<?= $persona['id'] ?>" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="actualizarModalLabel<?= $persona['id'] ?>">Actualizar Persona</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="post" action="">
                                <label>Nombre: </label><input type="text" name="nombre" value="<?= $persona['nombre'] ?>" required><br>
                                <label>Apellido: </label><input type="text" name="apellido" value="<?= $persona['apellido'] ?>" required><br>
                                <label>Teléfono: </label><input type="text" name="telefono" value="<?= $persona['telefono'] ?>" required><br>
                                <label>Correo: </label><input type="email" name="correo" value="<?= $persona['correo'] ?>" required><br>
                                <label>Contraseña: </label><input type="password" name="password" value="<?= $persona['password'] ?>" required><br>
                                <input type="hidden" name="id" value="<?= $persona['id'] ?>">
                                <input type="submit" name="actualizar" value="Actualizar" class="btn btn-warning">
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) : ?>
            <tr>
                <td colspan="6">
                    <form method="post">
                        <input type="submit" name="logout" value="Cerrar sesión" class="btn btn-danger">
                    </form>
                </td>
            </tr>
        <?php endif; ?>
    </table>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var passwordTds = document.querySelectorAll("td.password");
        passwordTds.forEach(function (td) {
            var originalContent = td.innerHTML;
            td.innerHTML = "****";
        });
    });
</script>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<?php if (isset($_POST['actualizar'])) : ?>
    <script>
        $(document).ready(function () {
            $('#actualizarModal<?= $persona['id'] ?>').modal('hide');
        });
    </script>
<?php endif; ?>

</body>
</html>
