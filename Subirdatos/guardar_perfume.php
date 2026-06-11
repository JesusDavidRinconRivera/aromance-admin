<?php

require 'conexion.php';

$marca_id = $_POST['marca_id'];
$nombre = $_POST['nombre'];
$slug = $_POST['slug'];
$genero = $_POST['genero'];
$concentracion = $_POST['concentracion'];
$descripcion = $_POST['descripcion'];
$precio = $_POST['precio'];
$volumen_ml = $_POST['volumen_ml'];

$imagen = null;

if(isset($_FILES['imagen_principal']) && $_FILES['imagen_principal']['error']==0){

    $imagen = time().'_'.$_FILES['imagen_principal']['name'];

    move_uploaded_file(
        $_FILES['imagen_principal']['tmp_name'],
        'uploads/perfumes/'.$imagen
    );
}

$stmt = $conn->prepare("
INSERT INTO perfumes
(
marca_id,
nombre,
slug,
genero,
concentracion,
descripcion,
precio,
volumen_ml,
imagen_principal
)
VALUES
(?,?,?,?,?,?,?,?,?)
");

$stmt->bind_param(
    "isssssdis",
    $marca_id,
    $nombre,
    $slug,
    $genero,
    $concentracion,
    $descripcion,
    $precio,
    $volumen_ml,
    $imagen
);

$stmt->execute();

echo "Perfume guardado";