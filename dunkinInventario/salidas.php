<?php
//Conexión a la base de datos
include 'conexionDb.php';

//Función de envío de correos
require 'vendor/autoload.php';

//Inicialización de mensaje de respuesta
$mensaje = "";

//Verificar que el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_elemento = $_POST['nombre_elemento'] ?? null;
    $destino = $_POST['destino'] ?? null;
    $serial = $_POST['serial'] ?? null;
    $modelo = $_POST['modelo'] ?? null;
    $activo = $_POST['activo'] ?? null;
    $cantidad = $_POST['cantidad'] ?? null;
    $fecha_salida = $_POST['fecha_salida'] ?? null;
    $observaciones = $_POST['observaciones'] ?? null;
    $enviar_correo = $_POST['enviar_correo'] ?? null;
    $correo_destino = $_POST['correo_destino'] ?? null;

    //Validar que no falten datos
    if ($nombre_elemento && $destino && $serial && $modelo && $activo && $cantidad && $fecha_salida && $enviar_correo && $correo_destino) {
        $nombre_elemento = $conn->real_escape_string($nombre_elemento);
        $destino = $conn->real_escape_string($destino);
        $serial = $conn->real_escape_string($serial);
        $modelo = $conn->real_escape_string($modelo);
        $activo = $conn->real_escape_string($activo);
        $cantidad = intval($cantidad);
        $fecha_salida = $conn->real_escape_string($fecha_salida);
        $observaciones = $conn->real_escape_string($observaciones);
        $correo_destino = $conn->real_escape_string($correo_destino);

        //Insertar en la base de datos
        $sql = "INSERT INTO Salidas (id_inventario, nombre_elemento, destino, serial, modelo, activo, cantidad, fecha_salida, observaciones, fecha_registro) 
                VALUES 
                ((SELECT id_inventario FROM Inventario WHERE activo = '$activo' LIMIT 1), '$nombre_elemento', '$destino', '$serial', '$modelo', '$activo', '$cantidad', '$fecha_salida', '$observaciones', CURDATE())";

        if ($conn->query($sql) === TRUE) {
            $mensaje = "Salida registrada exitosamente.";

            //Enviar correo si está activada la opción
            $correos_destino = explode(',', $correo_destino);
            $correos_destino = array_map('trim', $correos_destino);

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
                    foreach ($correos_destino as $correo) {
                        $mail->addAddress($correo); // Agregar cada dirección
                    }

                    $mail->isHTML(true);
                    $mail->Subject = 'Confirmacion de salida del inventario';

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
        <h3>Confirmacion de Salida de Inventario</h3>
        <table>
            <tr>
                <td class='label'>Nombre del Elemento</td>
                <td>$nombre_elemento</td>
            </tr>
            <tr>
                <td class='label'>Destino</td>
                <td>$destino</td>
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
                <td class='label'>Fecha de Salida</td>
                <td>$fecha_salida</td>
            </tr>
            <tr>
                <td class='label'>Observaciones</td>
                <td>$observaciones</td>
            </tr>
        </table>
        <div class='footer'>
            Este mensaje fue generado automáticamente por el Sistema de Inventario Dunkin. No responder a este correo.
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
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Salidas - Inventario</title>
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
        <h2 class="text-dark">📊 Dashboard del Inventario</h2>
        <div class="d-flex justify-content-center gap-3 mt-3 flex-wrap">
            <a href="IndexDunkin.php" class="btn btn-warning btn-lg rounded-pill">➕ Registrar Entrada</a>
            <a href="ver_inventario.php" class="btn btn-info btn-lg rounded-pill text-white">📋 Ver Inventario</a>
            <a href="verSalidas.php" class="btn btn-success btn-lg rounded-pill">📦 Ver Salidas</a>
        </div>
    </div>
</div>

<div class="container mt-5">
    <h1 class="text-center mb-4">Registrar Salida de Inventario</h1>

    <?php if (isset($_GET['mensaje'])): ?>
        <div class="alert alert-success text-center" id="alerta-mensaje">
            <?php echo htmlspecialchars($_GET['mensaje']); ?>
        </div>
    <?php endif; ?>

    <div class="card p-4">
        <h4 class="text-center text-dark">Registrar Salida</h4>
        <form action="" method="POST" enctype="multipart/form-data">
            <label class="text-dark">Correos Destino:</label>
            <input type="text" class="form-control" name="correo_destino" required placeholder="Separar correos por coma">

            <label class="text-dark">Nombre del Elemento:</label>
            <input type="text" class="form-control" name="nombre_elemento" required placeholder="Nombre del Elemento">

            <label class="text-dark">Destino:</label>
            <input type="text" class="form-control" name="destino" required placeholder="Destino">

            <label class="text-dark">Serial:</label>
            <input type="text" class="form-control" name="serial" required placeholder="Serial">

            <label class="text-dark">Modelo:</label>
            <input type="text" class="form-control" name="modelo" required placeholder="Modelo">

            <label class="text-dark">Activo:</label>
            <input type="text" class="form-control" name="activo" required placeholder="Activo">

            <label class="text-dark">Cantidad:</label>
            <input type="number" class="form-control" name="cantidad" min="1" required placeholder="Cantidad">

            <label class="text-dark">Fecha de Salida:</label>
            <input type="date" class="form-control" name="fecha_salida" value="<?php echo date('Y-m-d'); ?>" required placeholder="Fecha de Salida">

            <label class="text-dark">Observaciones:</label>
            <textarea class="form-control" name="observaciones"></textarea>

            <label class="text-dark">¿Enviar correo?</label>
            <select class="form-control" name="enviar_correo" required>
                <option value="">Seleccione...</option>
                <option value="si">Sí</option>
                <option value="no">No</option>
            </select>

            <label class="text-dark mt-3">Adjuntar Archivo (opcional):</label>
            <input type="file" class="form-control" name="archivo">

            <br>
            <button type="submit" class="btn btn-primary w-100">Registrar Salida</button>
        </form>
    </div>
</div>
</body>
</html>
