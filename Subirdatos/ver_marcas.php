<?php

require 'conexion.php';

$resultado = $conn->query("
    SELECT *
    FROM marcas
    ORDER BY id DESC
");

?>

<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Marcas Registradas - Aromance</title>

<style>

body{
    font-family: Arial, sans-serif;
    background:#f5f5f5;
    padding:40px;
}

h1{
    margin-bottom:20px;
}

table{
    width:100%;
    border-collapse:collapse;
    background:white;
    box-shadow:0 2px 10px rgba(0,0,0,0.1);
}

th,
td{
    padding:12px;
    border:1px solid #ddd;
    text-align:left;
}

th{
    background:#111;
    color:white;
}

tr:nth-child(even){
    background:#fafafa;
}

img{
    max-width:80px;
    max-height:80px;
    border-radius:8px;
}

.btn-delete{
    background:#c62828;
    color:white;
    padding:8px 12px;
    border-radius:6px;
    text-decoration:none;
    display:inline-block;
}

.btn-delete:hover{
    background:#a30000;
}

.badge{
    background:#eee;
    padding:4px 8px;
    border-radius:4px;
}

</style>

</head>

<body>

<h1>Marcas Registradas</h1>

<table>

<thead>

<tr>
    <th>ID</th>
    <th>Nombre</th>
    <th>Logo</th>
    <th>País</th>
    <th>Descripción</th>
    <th>Acciones</th>
</tr>

</thead>

<tbody>

<?php while($marca = $resultado->fetch_assoc()): ?>

<tr>

    <td>
        <?= $marca['id'] ?>
    </td>

    <td>
        <?= htmlspecialchars($marca['nombre']) ?>
    </td>

    <td>

        <?php if(!empty($marca['logo'])): ?>

            <img
                src="uploads/marcas/<?= htmlspecialchars($marca['logo']) ?>"
                alt="Logo"
            >

        <?php else: ?>

            <span class="badge">
                Sin imagen
            </span>

        <?php endif; ?>

    </td>

    <td>
        <?= htmlspecialchars($marca['pais']) ?>
    </td>

    <td>
        <?= htmlspecialchars($marca['descripcion']) ?>
    </td>

    <td>

        <a
            href="eliminar_marca.php?id=<?= $marca['id'] ?>"
            class="btn-delete"
            onclick="return confirm('¿Seguro que deseas eliminar esta marca?');"
        >
            Eliminar
        </a>

    </td>

</tr>

<?php endwhile; ?>

</tbody>

</table>

</body>
</html>