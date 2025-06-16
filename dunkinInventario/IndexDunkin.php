<?php
// Conexión a la base de datos
include 'conexionDb.php';

// Función de envío de correos 
require 'vendor/autoload.php';

// Inicialización de mensaje de respuesta
$mensaje = "";

// Verificar que el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_elemento = $_POST['nombre_elemento'] ?? null;
    $origen = $_POST['origen'] ?? null;
    $serial = $_POST['serial'] ?? null;
    $modelo = $_POST['modelo'] ?? null;
    $activo = $_POST['activo'] ?? null;
    $cantidad = $_POST['cantidad'] ?? null;
    $fecha_registro = $_POST['fecha_registro'] ?? null;
    $observaciones = $_POST['observaciones'] ?? null;
    $enviar_correo = $_POST['enviar_correo'] ?? null;
    $correos = $_POST['correos'] ?? null; 

    // Validar que no falten datos
    if ($nombre_elemento && $origen && $serial && $modelo && $activo && $cantidad && $fecha_registro && $enviar_correo) {
        $nombre_elemento = $conn->real_escape_string($nombre_elemento);
        $origen = $conn->real_escape_string($origen);
        $serial = $conn->real_escape_string($serial);
        $modelo = $conn->real_escape_string($modelo);
        $activo = $conn->real_escape_string($activo);
        $cantidad = intval($cantidad);
        $fecha_registro = $conn->real_escape_string($fecha_registro);
        $observaciones = $conn->real_escape_string($observaciones);
        $correos = $conn->real_escape_string($correos);

        // Insertar en la base de datos
        $sql = "INSERT INTO Inventario (nombre_elemento, origen, serial, modelo, activo, cantidad, fecha_registro, observaciones) 
                VALUES ('$nombre_elemento', '$origen', '$serial', '$modelo', '$activo', $cantidad, '$fecha_registro', '$observaciones')";

        if ($conn->query($sql) === TRUE) {
            $mensaje = "Elemento agregado correctamente al inventario.";

            // Enviar correo si está activada la opción
            if ($enviar_correo == "si") {
                $mail = new PHPMailer\PHPMailer\PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'andresramirezmunoz11@gmail.com'; 
                    $mail->Password = 'auqk hwte eoul ebjs'; 
                    $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    $mail->setFrom('andresramirezmunoz11@gmail.com', 'Sistema Inventario Dunkin');
                    

                    // Agregar los correos adicionales
                    $emails = explode(',', $correos);
                    foreach ($emails as $email) {
                        $mail->addAddress(trim($email)); // Agregar cada dirección
                    }

                    $mail->isHTML(true);
                    $mail->Subject = 'Confirmacion de ingreso al inventario';
                    $mail->Body = "
<html>
<head>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 20px;
        }
        h3 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 10px;
            border: 1px solid #ccc;
        }
        .label {
            background-color: #2c3e50;
            color: #ffffff;
            font-weight: bold;
            width: 40%;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class='container'>
        <h3>Registro de Nueva Entrada al Inventario</h3>
        <table>
            <tr>
                <td class='label'>Nombre del Elemento</td>
                <td>$nombre_elemento</td>
            </tr>
            <tr>
                <td class='label'>Origen</td>
                <td>$origen</td>
            </tr>
            <tr>
                <td class='label'>Serial</td>
                <td>$serial</td>
            </tr>
            <tr>
                <td class='label'>Modelo</td>
                <td>$modelo</td>
            </tr>
            <tr>
                <td class='label'>Activo</td>
                <td>$activo</td>
            </tr>
            <tr>
                <td class='label'>Cantidad</td>
                <td>$cantidad</td>
            </tr>
            <tr>
                <td class='label'>Fecha de Registro</td>
                <td>$fecha_registro</td>
            </tr>
            <tr>
                <td class='label'>Observaciones</td>
                <td>$observaciones</td>
            </tr>
        </table>
        <div class='footer'>
            Este es un mensaje automático del Sistema de Inventario Dunkin. Por favor, no responda a este correo.
        </div>
    </div>
</body>
</html>
";



                    // Adjuntar archivo si existe
                    if (!empty($_FILES['archivo']['name'])) {
                        $archivo_tmp = $_FILES['archivo']['tmp_name'];
                        $archivo_nombre = $_FILES['archivo']['name'];
                        $mail->addAttachment($archivo_tmp, $archivo_nombre);
                    }

                    $mail->send();
                } catch (Exception $e) {
                    $mensaje .= " Error al enviar el correo: {$mail->ErrorInfo}";
                }
            }

            header("Location: " . $_SERVER['PHP_SELF'] . "?mensaje=" . urlencode($mensaje));
            exit;
        } else {
            $mensaje = "Error: " . $conn->error;
        }
    } else {
        $mensaje = "Por favor, completa todos los campos.";
    }
}

// Recuperar los datos de la base de datos
$sql = "SELECT * FROM Inventario";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario Dunkin</title>
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
            -webkit-backdrop-filter: blur(10px);
            border-radius: 20px;
            color: #000;
        }

        h1, h2.text-dark, h4.text-dark, label.text-dark {
            color: #fff !important;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
        }

        .dashboard-bar {
            background-color: rgba(111, 66, 193, 0.7);
            backdrop-filter: blur(6px);
            padding: 15px;
            border-radius: 15px;
            text-align: center;
        }

        .dashboard-bar .btn {
            border-radius: 25px;
        }

        .btn-outline-light:hover {
            background-color: #ffcc00;
            color: #fff;
        }

        .form-control, textarea {
            background-color: rgba(255, 255, 255, 0.8);
            color: #000;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="card p-4 mb-4 text-center">
        <h2 class="text-dark">📊 Inventario Dunkin</h2>
        <div class="d-flex justify-content-center gap-3 mt-3 flex-wrap">
            <a href="ver_inventario.php" class="btn btn-warning btn-lg rounded-pill">📋 Ver Inventario</a>
            <a href="salidas.php" class="btn btn-info btn-lg rounded-pill text-white">➕ Registrar Salidas</a>
            <a href="verSalidas.php" class="btn btn-success btn-lg rounded-pill">📦 Ver Salidas</a>
        </div>
    </div>
</div>
    <div class="container mt-5">
        <h1 class="text-center mb-4" style="color: white;">Inventario Dunkin</h1>

        <?php if (isset($_GET['mensaje'])): ?>
            <div class="alert alert-success text-center" id="alerta-mensaje">
        <?php echo htmlspecialchars($_GET['mensaje']); ?>
    </div>
        <?php endif; ?>

        <div class="card p-4">
            <h4 class="text-center text-dark">Agregar Elemento al Inventario</h4>
            <form action="" method="POST" enctype="multipart/form-data">
                <label class="text-dark">Nombre del Elemento:</label>
                <input type="text" class="form-control" name="nombre_elemento" required placeholder="Nombre del elemento">

                <label class="text-dark">Origen:</label>
                <input type="text" class="form-control" name="origen" required placeholder="Origen">

                <label class="text-dark">Serial:</label>
                <input type="text" class="form-control" name="serial" required placeholder="Serial">

                <label class="text-dark">Modelo:</label>
                <input type="text" class="form-control" name="modelo" required placeholder="Modelo">

                <label class="text-dark">Activo:</label>
                <input type="text" class="form-control" name="activo" required placeholder="Activo">

                <label class="text-dark">Cantidad:</label>
                <input type="number" class="form-control" name="cantidad" min="1" required placeholder="Cantidad">

                <label class="text-dark">Fecha de Registro:</label>
                <input type="date" class="form-control" name="fecha_registro" value="<?php echo date('Y-m-d'); ?>" required>

                <label class="text-dark">Observaciones:</label>
                <textarea class="form-control" name="observaciones"></textarea>

                <label class="text-dark">Adjuntar Archivo:</label>
                <input type="file" class="form-control" name="archivo">

                <label class="text-dark">¿Enviar correo?</label>
                <select class="form-control" name="enviar_correo" required>
                    <option value="">Seleccione...</option>
                    <option value="si">Sí</option>
                    <option value="no">No</option>
                </select>

                <label class="text-dark">Correos adicionales (separados por coma):</label>
                <input type="text" class="form-control" name="correos">

                <br>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Agregar Elemento</button>
                </div>   
            </form>
        </div>
    </div>
</body>
</html>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const alerta = document.getElementById('alerta-mensaje');
        if (alerta) {
            setTimeout(() => {
                alerta.style.transition = "opacity 0.5s";
                alerta.style.opacity = "0";
                setTimeout(() => alerta.remove(), 500);
            }, 5000);
        }
    });
</script>
