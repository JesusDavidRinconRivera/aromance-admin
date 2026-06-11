<?php

require 'conexion.php';

$perfume_id = $_POST['perfume_id'];

$decants = json_decode(
    $_POST['decants_json'],
    true
);

foreach($decants as $decant){

    $stmt = $conn->prepare("
    INSERT INTO decants
    (
        perfume_id,
        volumen_ml,
        precio,
        stock
    )
    VALUES
    (?,?,?,?)
    ");

    $stmt->bind_param(
        "iidi",
        $perfume_id,
        $decant['ml'],
        $decant['precio'],
        $decant['stock']
    );

    $stmt->execute();
}

echo "Decants guardados";