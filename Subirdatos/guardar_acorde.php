<?php

require 'conexion.php';

$nombre = $_POST['nombre'];
$color = $_POST['color'];
$descripcion = $_POST['descripcion'];

$imagen = null;

if(isset($_FILES['imagen']) && $_FILES['imagen']['error']==0){

    $imagen = time().'_'.$_FILES['imagen']['name'];

    move_uploaded_file(
        $_FILES['imagen']['tmp_name'],
        'uploads/acordes/'.$imagen
    );
}

$stmt = $conn->prepare("
INSERT INTO acordes
(nombre,color,imagen,descripcion)
VALUES(?,?,?,?)
");

$stmt->bind_param(
    "ssss",
    $nombre,
    $color,
    $imagen,
    $descripcion
);

$stmt->execute();

$acorde_id = $conn->insert_id;

if(isset($_FILES['imagenes_acorde'])){

    foreach($_FILES['imagenes_acorde']['tmp_name'] as $i=>$tmp){

        if(empty($tmp)) continue;

        $archivo =
        time().'_'.$i.'_'.$_FILES['imagenes_acorde']['name'][$i];

        move_uploaded_file(
            $tmp,
            'uploads/acordes/'.$archivo
        );

        $stmt2 = $conn->prepare("
        INSERT INTO imagenes_acordes
        (acorde_id,url,orden_imagen)
        VALUES(?,?,?)
        ");

        $orden = $i+1;

        $stmt2->bind_param(
            "isi",
            $acorde_id,
            $archivo,
            $orden
        );

        $stmt2->execute();
    }
}

echo "Acorde guardado";