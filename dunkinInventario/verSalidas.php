<?php
// Incluir la conexión a la base de datos
include 'conexionDb.php';

// Consultar todas las salidas registradas ordenadas por ID de manera descendente
$sql = "SELECT * FROM Salidas ORDER BY id_salida DESC";
$result = $conn->query($sql);

// Cerrar la conexión a la base de datos
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salidas Registradas - Inventario</title>
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
            <a href="ver_inventario.php" class="btn btn-info btn-lg rounded-pill text-white">📋 Ver Inventario</a>
            <a href="salidas.php" class="btn btn-success btn-lg rounded-pill">📦 Registrar Salidas</a>
        </div>
    </div>
</div>

    <div class="container mt-4">
        <h1 class="text-center mb-4">Salidas Registradas</h1>

        <!-- Tabla para mostrar las salidas registradas -->
        <div class="table-responsive mt-4">
            <table class="table table-bordered table-hover">
                <thead class="table-dark text-center">
                    <tr>
                        <th><input type="text" class="form-control" placeholder="Buscar"></th>
                        <th><input type="text" class="form-control" placeholder="Buscar"></th>
                        <th><input type="text" class="form-control" placeholder="Buscar"></th>
                        <th><input type="text" class="form-control" placeholder="Buscar"></th>
                        <th><input type="text" class="form-control" placeholder="Buscar"></th>
                        <th><input type="text" class="form-control" placeholder="Buscar"></th>
                        <th><input type="text" class="form-control" placeholder="Buscar"></th>
                        <th><input type="text" class="form-control" placeholder="Buscar"></th>
                        <th><input type="text" class="form-control" placeholder="Buscar"></th>
                        <th><input type="text" class="form-control" placeholder="Buscar"></th>
                    </tr>
                    <tr>
                        <th>ID Salida</th>
                        <th>Nombre Elemento</th>
                        <th>Destino</th>
                        <th>Serial</th>
                        <th>Modelo</th>
                        <th>Activo</th>
                        <th>Cantidad</th>
                        <th>Fecha de Salida</th>
                        <th>Observaciones</th>
                        <th>Fecha de Registro</th>
                    </tr>
                </thead>
                <tbody id="tabla_salidas">
                    <?php
                    // Mostrar los registros de salidas
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td class='text-center'>{$row['id_salida']}</td>
                                    <td>{$row['nombre_elemento']}</td>
                                    <td>{$row['destino']}</td>
                                    <td>{$row['serial']}</td>
                                    <td>{$row['modelo']}</td>
                                    <td>{$row['activo']}</td>
                                    <td class='text-center'>{$row['cantidad']}</td>
                                    <td>{$row['fecha_salida']}</td>
                                    <td>{$row['observaciones']}</td>
                                    <td>{$row['fecha_registro']}</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='10' class='text-center'>No hay registros de salidas.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Filtros dinámicos -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filters = document.querySelectorAll('thead input');
            filters.forEach((filter, index) => {
                filter.addEventListener('input', function () {
                    const filterValue = this.value.toLowerCase();
                    const rows = document.querySelectorAll('#tabla_salidas tr');
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
