<?php

require 'conexion.php';

$nombre = $_POST['nombre'];

$imagen = null;

if(isset($_FILES['imagen']) && $_FILES['imagen']['error']==0){

    $imagen = time().'_'.$_FILES['imagen']['name'];

    move_uploaded_file(
        $_FILES['imagen']['tmp_name'],
        'uploads/notas/'.$imagen
    );
}

$stmt = $conn->prepare("
INSERT INTO notas
(nombre,imagen)
VALUES(?,?)
");

$stmt->bind_param(
    "ss",
    $nombre,
    $imagen
);

$stmt->execute();

echo "Nota guardada";