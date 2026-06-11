<?php

require 'conexion.php';

$perfume_id = $_POST['perfume_id'];
$tipo = $_POST['tipo'];

if(isset($_FILES['imagenes'])){

    foreach($_FILES['imagenes']['tmp_name'] as $i=>$tmp){

        if(empty($tmp)) continue;

        $archivo =
        time().'_'.$i.'_'.$_FILES['imagenes']['name'][$i];

        move_uploaded_file(
            $tmp,
            'uploads/perfumes/'.$archivo
        );

        $stmt = $conn->prepare("
        INSERT INTO imagenes_perfume
        (
            perfume_id,
            url,
            tipo,
            orden_imagen
        )
        VALUES
        (?,?,?,?)
        ");

        $orden = $i+1;

        $stmt->bind_param(
            "issi",
            $perfume_id,
            $archivo,
            $tipo,
            $orden
        );

        $stmt->execute();
    }
}

echo "Galería guardada";