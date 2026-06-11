<?php

require 'conexion.php';

$nombre = $_POST['nombre'];
$pais = $_POST['pais'];
$descripcion = $_POST['descripcion'];

$logo = null;

if(isset($_FILES['logo']) && $_FILES['logo']['error'] == 0){

    $logo = time().'_'.$_FILES['logo']['name'];

    move_uploaded_file(
        $_FILES['logo']['tmp_name'],
        'uploads/marcas/'.$logo
    );
}

$stmt = $conn->prepare("
INSERT INTO marcas
(nombre, logo, pais, descripcion)
VALUES (?, ?, ?, ?)
");

$stmt->bind_param(
    "ssss",
    $nombre,
    $logo,
    $pais,
    $descripcion
);

$stmt->execute();

echo "Marca guardada correctamente";