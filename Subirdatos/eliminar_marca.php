<?php

require 'conexion.php';

if (!isset($_GET['id'])) {
    die("ID no especificado");
}

$id = (int)$_GET['id'];

$stmt = $conn->prepare("
    DELETE FROM marcas
    WHERE id = ?
");

$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: ver_marcas.php");
    exit;
} else {
    echo "Error al eliminar la marca";
}
?>