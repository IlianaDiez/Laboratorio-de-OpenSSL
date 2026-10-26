<?php
// 1. Forzar la ruta del archivo de configuración para Apache/Wamp
$rutaCnf = 'C:/wamp64/bin/php/php8.0.30/extras/ssl/openssl.cnf';
putenv("OPENSSL_CONF=" . $rutaCnf);

// Datos por defecto para el formulario
$datos = isset($_POST['datos']) ? $_POST['datos'] : 'Este texto será firmado. Thanks for your attention :)';
$resultadoVerificacion = "";
$errorMsg = "";
$ejecutado = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ejecutado = true;

    // Array de configuración para la creación del juego de claves
    $configArgs = array(
        'config' => $rutaCnf,
        'private_key_bits' => 2048,
        'private_key_type' => OPENSSL_KEYTYPE_RSA
    );

    $resourceNewKeyPair = openssl_pkey_new($configArgs);
    if (!$resourceNewKeyPair) {
        $errorMsg = 'Error al crear el par de claves: ' . openssl_error_string();
    } else {
        // Obtengo la clave pública como string 
        $details = openssl_pkey_get_details($resourceNewKeyPair);
        $publicKeyPem = $details['key'];

        // Obtengo la clave privada como string
        if (!openssl_pkey_export($resourceNewKeyPair, $privateKeyPem, NULL, $configArgs)) {
            $errorMsg = 'Error al exportar la clave privada: ' . openssl_error_string();
        } else {
            // Asegurar que la carpeta 'keys' exista
            if (!is_dir('keys')) {
                mkdir('keys', 0777, true);
            }

            // Guardo la clave pública y privada en disco:
            file_put_contents('keys/private_key.pem', $privateKeyPem);
            file_put_contents('keys/public_key.pem', $publicKeyPem);

            // Verifico leyendo la clave privada de disco
            $privateKeyPemFile = file_get_contents('keys/private_key.pem');
            $resourcePrivateKey = openssl_get_privatekey($privateKeyPemFile);

            if (!$resourcePrivateKey) {
                $errorMsg = 'Error al cargar la clave privada desde disco.';
            } else {
                // Crear la firma
                if (!openssl_sign($datos, $firma, $resourcePrivateKey, OPENSSL_ALGO_SHA256)) {
                    $errorMsg = 'Error al firmar: ' . openssl_error_string();
                } else {
                    // Guardar la firma en disco:
                    file_put_contents('keys/signature.dat', $firma);

                    // Comprobar la firma
                    $verifyResult = openssl_verify($datos, $firma, $publicKeyPem, 'sha256WithRSAEncryption');
                    if ($verifyResult === 1) {
                        $resultadoVerificacion = "valida";
                    } elseif ($verifyResult === 0) {
                        $resultadoVerificacion = "invalida";
                    } else {
                        $resultadoVerificacion = "error";
                    }
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Firma y Verificación Digital</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            color: #333;
            margin: 0;
            padding: 40px;
        }
        .container {
            max-width: 700px;
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
            display: block;
            margin-top: 10px;
        }
        textarea {
            width: 100%;
            height: 90px;
            padding: 9px;
            margin-top: 5px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
            font-family: inherit;
        }
        button {
            background-color: #8e44ad;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 15px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 5px;
            transition: background 0.3s;
        }
        button:hover {
            background-color: #732d91;
        }
        hr {
            border: 0;
            height: 1px;
            background: #eaeaea;
            margin: 25px 0;
        }
        .results {
            background: #fdfdfd;
            border-left: 4px solid #8e44ad;
            padding: 15px;
            margin-top: 15px;
            border-radius: 4px;
        }
        .alert-success {
            color: #27ae60;
            background: #e8f8f5;
            padding: 12px;
            border-radius: 5px;
            font-weight: bold;
            border: 1px solid #a3e4d7;
            margin-top: 10px;
        }
        .alert-danger {
            color: #c0392b;
            background: #f9ebea;
            padding: 12px;
            border-radius: 5px;
            font-weight: bold;
            border: 1px solid #f5b7b1;
            margin-top: 10px;
        }
        .diagnostico {
            font-size: 13px;
            color: #666;
            background: #ecf0f1;
            padding: 8px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>✍️ Firmar y Verificar Mensaje (firma7)</h2>
        
        <div class="diagnostico">
            <b>Estado OpenSSL Config:</b> Ruta Wamp cargada correctamente para Apache.
        </div>

        <form method="POST" action="">
            <label>Texto o mensaje que se quiere firmar:</label>
            <textarea name="datos" required><?php echo htmlspecialchars($datos); ?></textarea>
            <button type="submit">Generar Claves, Firmar y Verificar</button>
        </form>

        <?php if ($ejecutado): ?>
            <hr>
            <div class="results">
                <h3>Resultados de la Ejecución</h3>
                <?php if (!empty($errorMsg)): ?>
                    <div class="alert-danger"><?php echo $errorMsg; ?></div>
                <?php else: ?>
                    <p><b>Mensaje procesado:</b> <i><?php echo htmlspecialchars($datos); ?></i></p>
                    <p><b>Archivos guardados en disco:</b> Carpeta <code>keys/</code> (private_key.pem, public_key.pem, signature.dat)</p>
                    
                    <?php if ($resultadoVerificacion === "valida"): ?>
                        <div class="alert-success">✔ La firma es válida y los datos son confiables.</div>
                    <?php elseif ($resultadoVerificacion === "invalida"): ?>
                        <div class="alert-danger">✖ La firma es inválida y/o los datos fueron alterados.</div>
                    <?php else: ?>
                        <div class="alert-danger">⚠ Ocurrió un error al verificar la firma.</div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>