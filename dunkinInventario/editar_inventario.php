<?php
include 'conexionDb.php';

// Validar si llega el ID
if (!isset($_GET['id'])) {
    echo "ID de inventario no proporcionado.";
    exit;
}

$id = intval($_GET['id']);
$mensaje = "";

// Si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_elemento = $_POST['nombre_elemento'];
    $origen = $_POST['origen'];
    $serial = $_POST['serial'];
    $modelo = $_POST['modelo'];
    $activo = $_POST['activo'];
    $cantidad = $_POST['cantidad'];
    $observaciones = $_POST['observaciones'];

    $sql_update = "UPDATE Inventario SET 
        nombre_elemento = ?, 
        origen = ?, 
        serial = ?, 
        modelo = ?, 
        activo = ?, 
        cantidad = ?, 
        observaciones = ? 
        WHERE id_inventario = ?";

    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("sssssisi", $nombre_elemento, $origen, $serial, $modelo, $activo, $cantidad, $observaciones, $id);

    if ($stmt->execute()) {
        $mensaje = "Registro actualizado correctamente.";
    } else {
        $mensaje = "Error al actualizar: " . $conn->error;
    }
}

// Consultar los datos actuales
$sql = "SELECT * FROM Inventario WHERE id_inventario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$registro = $resultado->fetch_assoc();

if (!$registro) {
    echo "Registro no encontrado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Inventario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: rgba(30, 30, 30, 0.4);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            min-height: 100vh;
            color: #fff;
        }
        .card {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            color: #000;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="card p-4 mb-4 text-center">
        <h2 class="text-dark">📊 Dashboard del Inventario</h2>
        <div class="d-flex justify-content-center gap-3 mt-3 flex-wrap">
            <a href="IndexDunkin.php" class="btn btn-warning btn-lg rounded-pill">➕ Registrar Entrada</a>
            <a href="salidas.php" class="btn btn-info btn-lg rounded-pill text-white">➕ Registrar Salidas</a>
            <a href="verSalidas.php" class="btn btn-success btn-lg rounded-pill">📦 Ver Salidas</a>
            <a href="ver_inventario.php" class="btn btn-secondary btn-lg rounded-pill">🔙 Volver</a>
        </div>
    </div>
<div class="container mt-5">
    <div class="card p-4">
        <h2 class="text-center text-dark">✏️ Editar Registro del Inventario</h2>

        <?php if ($mensaje): ?>
            <div class="alert alert-info text-center"><?php echo $mensaje; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nombre del Elemento</label>
                <input type="text" name="nombre_elemento" class="form-control" value="<?php echo $registro['nombre_elemento']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Origen</label>
                <input type="text" name="origen" class="form-control" value="<?php echo $registro['origen']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Serial</label>
                <input type="text" name="serial" class="form-control" value="<?php echo $registro['serial']; ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Modelo</label>
                <input type="text" name="modelo" class="form-control" value="<?php echo $registro['modelo']; ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Activo</label>
                <input type="text" name="activo" class="form-control" value="<?php echo $registro['activo']; ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Cantidad</label>
                <input type="number" name="cantidad" class="form-control" value="<?php echo $registro['cantidad']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Observaciones</label>
                <textarea name="observaciones" class="form-control"><?php echo $registro['observaciones']; ?></textarea>
            </div>
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">💾 Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
