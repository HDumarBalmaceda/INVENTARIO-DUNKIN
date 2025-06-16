<?php
// Incluir la conexión a la base de datos
include 'conexionDb.php';

// Inicializar variable para posibles errores
$mensaje_error = "";

// Consulta para obtener todos los registros de la tabla Inventario, ordenados por ID de manera descendente
$sql = "SELECT * FROM Inventario ORDER BY id_inventario DESC";
$resultado = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario - Listado</title>
    <!-- Enlace a Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        background: rgba(30, 30, 30, 0.4); 
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px); 
        min-height: 100vh;
        color: #fff;
    }

    table {
        background-color: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-radius: 10px;
        color: #000;
    }

    thead input {
        background-color: rgba(255, 255, 255, 0.7) !important;
        color: #000 !important;
    }

    .card {
        background-color: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-radius: 20px;
        color: #000;
    }

    h1, h2.text-dark {
        color: #fff !important;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
    }

    .dashboard-bar {
        background-color: rgba(111, 66, 193, 0.7); 
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        padding: 15px;
        text-align: center;
        border-radius: 15px;
    }

    .dashboard-bar a {
        margin: 0 10px;
    }

    .dashboard-bar .btn {
        border-radius: 25px;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .btn-outline-light {
        transition: all 0.3s ease;
    }

    .btn-outline-light:hover {
        background-color: #ffcc00;
        color: #fff;
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
        </div>
    </div>
    <!--<div class="d-grid">
            <a href="IndexDunkin.php" class="btn btn-outline-primary">Volver</a>
        </div>
    </div>-->
    <div class="container mt-5">
        <h1 class="text-center mb-4">Inventario Registrado</h1>

        <!-- Mostrar mensaje de error si existe -->
        <?php if (!empty($mensaje_error)): ?>
            <div class="alert alert-danger text-center" role="alert">
                <?php echo $mensaje_error; ?>
            </div>
        <?php endif; ?>

        <!-- Tabla para mostrar el inventario -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark text-center">
                    <tr>
                        <th>ID</th>
                        <th>Nombre del Elemento</th>
                        <th>Origen</th>
                        <th>Serial</th>
                        <th>Modelo</th>
                        <th>Activo</th>
                        <th>Cantidad</th>
                        <th>Fecha de Registro</th>
                        <th>Observaciones</th>
                        <th>Acciones</th>
                    </tr>
                    <tr>
                        <th><input type="text" class="form-control" id="filter_id" placeholder="Buscar"></th>
                        <th><input type="text" class="form-control" id="filter_nombre_elemento" placeholder="Buscar"></th>
                        <th><input type="text" class="form-control" id="filter_origen" placeholder="Buscar"></th>
                        <th><input type="text" class="form-control" id="filter_serial" placeholder="Buscar"></th>
                        <th><input type="text" class="form-control" id="filter_modelo" placeholder="Buscar"></th>
                        <th><input type="text" class="form-control" id="filter_activo" placeholder="Buscar"></th>
                        <th><input type="text" class="form-control" id="filter_cantidad" placeholder="Buscar"></th>
                        <th><input type="text" class="form-control" id="filter_fecha_registro" placeholder="Buscar"></th>
                        <th><input type="text" class="form-control" id="filter_observaciones" placeholder="Buscar"></th>
                    </tr>
                </thead>
                <tbody id="tabla_inventario">
                    <?php if ($resultado && $resultado->num_rows > 0): ?>
                        <?php while ($row = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td class="text-center"><?php echo $row['id_inventario']; ?></td>
                                <td><?php echo $row['nombre_elemento']; ?></td>
                                <td><?php echo $row['origen']; ?></td>
                                <td><?php echo $row['serial']; ?></td>
                                <td><?php echo $row['modelo']; ?></td>
                                <td><?php echo $row['activo']; ?></td>
                                <td class="text-center"><?php echo $row['cantidad']; ?></td>
                                <td><?php echo $row['fecha_registro']; ?></td>
                                <td><?php echo $row['observaciones'] ? $row['observaciones'] : 'Sin observaciones'; ?></td>
                                <td class="text-center">
                                    <a href="editar_inventario.php?id=<?php echo $row['id_inventario']; ?>" class="btn btn-sm btn-primary">✏️ Editar</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center">No se encontraron registros en el inventario.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script para filtrado dinámico -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filters = document.querySelectorAll('thead input');
            filters.forEach((filter, index) => {
                filter.addEventListener('input', function () {
                    const filterValue = this.value.toLowerCase();
                    const rows = document.querySelectorAll('#tabla_inventario tr');
                    rows.forEach(row => {
                        const cell = row.cells[index];
                        if (cell) {
                            const text = cell.textContent.toLowerCase();
                            row.style.display = text.includes(filterValue) ? '' : 'none';
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>

<?php
// Cerrar conexión
$conn->close();
?>
