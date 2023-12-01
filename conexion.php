<?php
$servername = "localhost";//Define el nombre del servidor de la BD
$username = "root";//Define el nombre de usuario que utilizará para conectarse a la BD
$password = "";//Define la contraseña del usuario de la BD
$dbname = "usuarios";//Especifica el nombre de la BD a la que se va a hacer la conexion

$conn = new mysqli($servername, $username, $password, $dbname);//Define los paramteros para la conexion con la BD

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>