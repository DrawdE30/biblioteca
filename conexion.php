
<?php
// conexion.php
$servername = "localhost";
$username = "root";
$password = ""; // Tu contraseña de MySQL (vacío si estás en XAMPP o Laragon local)
$database = "biblioteca";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}
?>