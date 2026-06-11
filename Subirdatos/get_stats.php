<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Datos de conexión
$host = 'localhost';
$db   = 'Aromance';
$user = 'root';
$pass = 'admin';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stats = [
        'perfumes' => (int) $pdo->query("SELECT COUNT(*) FROM perfumes")->fetchColumn(),
        'marcas'   => (int) $pdo->query("SELECT COUNT(*) FROM marcas")->fetchColumn(),
        'acordes'  => (int) $pdo->query("SELECT COUNT(*) FROM acordes")->fetchColumn(),
        'notas'    => (int) $pdo->query("SELECT COUNT(*) FROM notas")->fetchColumn(),
    ];

    echo json_encode(['success' => true, 'data' => $stats]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>