<?php
$mensaje = isset($_POST['mensaje']) ? $_POST['mensaje'] : "No quiero estar en Estadistica";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Firma Digital - Seguridad OpenSSL</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            color: #333;
            margin: 0;
            padding: 40px;
        }
        .container {
            max-width: 650px;
            margin: 0 auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #2c3e50;
            margin-top: 0;
            border-bottom: 2px solid #eaeaea;
            padding-bottom: 10px;
        }
        label {
            font-weight: bold;
            color: #555;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }
        button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 15px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s;
        }
        button:hover {
            background-color: #2980b9;
        }
        hr {
            border: 0;
            height: 1px;
            background: #eaeaea;
            margin: 25px 0;
        }
        .results {
            background: #fdfdfd;
            border-left: 4px solid #3498db;
            padding: 15px;
            margin-top: 15px;
            border-radius: 4px;
        }
        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-family: monospace;
            background: #f8f9fa;
            box-sizing: border-box;
        }
        .alert-success {
            color: #27ae60;
            background: #e8f8f5;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
            border: 1px solid #a3e4d7;
        }
        .alert-danger {
            color: #c0392b;
            background: #f9ebea;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
            border: 1px solid #f5b7b1;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>🔒 Formulario de Firma Digital</h2>
        
        <form method="POST" action="">
            <label for="mensaje">Escribe el mensaje a firmar:</label>
            <input type="text" id="mensaje" name="mensaje" value="<?php echo htmlspecialchars($mensaje); ?>" required>
            <button type="submit">Firmar y Verificar</button>
        </form>
        
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo "<hr>";
            echo "<div class='results'>";
            echo "<h3>Resultados de la ejecución</h3>";
            
            // Cargar la clave privada
            $privateKey = file_get_contents('keysCert/privkey.pem');
            $privKeyResource = openssl_pkey_get_private($privateKey);

            // Firmar el mensaje ingresado
            openssl_sign($mensaje, $firmaBinaria, $privKeyResource, OPENSSL_ALGO_SHA256);
            $firmaBase64 = base64_encode($firmaBinaria);

            echo "<p><b>Mensaje procesado:</b> " . htmlspecialchars($mensaje) . "</p>";
            echo "<p><b>Firma en base64:</b></p>";
            echo "<textarea rows='3' readonly>" . $firmaBase64 . "</textarea><br><br>";

            // Cargar certificado y verificar
            $cert = file_get_contents('keysCert/certout.csr');
            $pubKeyResource = openssl_pkey_get_public($cert);
            $ok = openssl_verify($mensaje, $firmaBinaria, $pubKeyResource, OPENSSL_ALGO_SHA256);

            if ($ok === 1) {
                echo "<div class='alert-success'>✅ Firma válida. El mensaje no fue alterado.</div>";
            } elseif ($ok === 0) {
                echo "<div class='alert-danger'>❌ Firma inválida. El mensaje pudo haber sido modificado.</div>";
            } else {
                echo "<div class='alert-danger'>⚠ Error al verificar la firma: " . openssl_error_string() . "</div>";
            }
            
            echo "</div>";
        }
        ?>
    </div>

</body>
</html>