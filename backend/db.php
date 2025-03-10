<?php

// Configuración de la conexión a la base de datos
$host = "localhost"; // Nombre del servidor de la base de datos
$user = "root"; // Usuario de la base de datos
$password = ""; // Contraseña del usuario (vacía por defecto)
$database = "fondo_becas"; // Nombre de la base de datos a la que se conecta

global $conn; // Variable global para la conexión
$conn = new mysqli($host, $user, $password, $database); // Se crea una nueva instancia de conexión con MySQL

// Verificación de la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error); // Termina la ejecución si hay un error en la conexión
}

?>

