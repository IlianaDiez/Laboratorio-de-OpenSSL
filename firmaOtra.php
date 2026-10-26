<?php
// 1. Forzar la ruta del archivo de configuración para Apache/Wamp
$rutaCnf = 'C:/wamp64/bin/php/php8.0.30/extras/ssl/openssl.cnf';
putenv("OPENSSL_CONF=" . $rutaCnf);

$generadoExitosamente = false;
$certout = "";
$errorMsg = "";

// Valores por defecto para el formulario DN (Distinguished Name)
$commonName = isset($_POST['commonName']) ? $_POST['commonName'] : "Irina Fong";
$orgName = isset($_POST['orgName']) ? $_POST['orgName'] : "MEF";
$email = isset($_POST['email']) ? $_POST['email'] : "dreamsweb7@gmail.com";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    define("OPEN_SSL_CERT_DAYS_VALID", 365);

    $configArgs = array(
        'config' => $rutaCnf,
        'private_key_bits' => 2048,
        'private_key_type' => OPENSSL_KEYTYPE_RSA
    );

    $dn = array (
        "countryName" => "PA",
        "localityName" => "PA-8",
        "organizationName" => $orgName,
        "commonName" => $commonName,
        "emailAddress" => $email
    );

    $resourceNewKeyPair = openssl_pkey_new($configArgs);
    if (!$resourceNewKeyPair) {
        $errorMsg = "Error al generar la clave: ";
        while ($msg = openssl_error_string()) {
            $errorMsg .= $msg . "<br>";
        }
    } else {
        $details = openssl_pkey_get_details($resourceNewKeyPair);
        $publicKeyPem = $details['key'];

        if (!openssl_pkey_export($resourceNewKeyPair, $privkey, null, $configArgs)) {
            $errorMsg = "Error al exportar la clave privada: " . openssl_error_string();
        } else {
            $csr = openssl_csr_new($dn, $resourceNewKeyPair, $configArgs);
            if (!$csr) {
                $errorMsg = "Error al crear la CSR: " . openssl_error_string();
            } else {
                $sscert = openssl_csr_sign($csr, null, $resourceNewKeyPair, OPEN_SSL_CERT_DAYS_VALID, $configArgs);
                if (!$sscert) {
                    $errorMsg = "Error al firmar la CSR: " . openssl_error_string();
                } else {
                    openssl_x509_export($sscert, $certout);

                    // Asegurar que la carpeta keysCert exista
                    if (!is_dir('keysCert')) {
                        mkdir('keysCert', 0777, true);
                    }

                    file_put_contents('keysCert/certout.csr', $certout);
                    file_put_contents('keysCert/privkey.pem', $privkey);
                    $generadoExitosamente = true;
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
    <title>Generación de Certificado X.509</title>
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
        input[type="text"], input[type="email"] {
            width: 100%;
            padding: 9px;
            margin-top: 5px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }
        button {
            background-color: #27ae60;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 15px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 10px;
            transition: background 0.3s;
        }
        button:hover {
            background-color: #219653;
        }
        hr {
            border: 0;
            height: 1px;
            background: #eaeaea;
            margin: 25px 0;
        }
        .results {
            background: #fdfdfd;
            border-left: 4px solid #27ae60;
            padding: 15px;
            margin-top: 15px;
            border-radius: 4px;
        }
        textarea {
            width: 100%;
            height: 150px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-family: monospace;
            background: #f8f9fa;
            box-sizing: border-box;
            font-size: 12px;
        }
        .alert-success {
            color: #27ae60;
            background: #e8f8f5;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
            border: 1px solid #a3e4d7;
            margin-bottom: 15px;
        }
        .alert-danger {
            color: #c0392b;
            background: #f9ebea;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
            border: 1px solid #f5b7b1;
            margin-bottom: 15px;
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
        <h2>📜 Generación y Firma de Certificado X.509</h2>
        
        <div class="diagnostico">
            <b>Diagnóstico OpenSSL:</b> 
            Ruta Cnf válida (<i><?php echo is_file($rutaCnf) ? 'true' : 'false'; ?></i>) | 
            php.ini: <?php echo htmlspecialchars(php_ini_loaded_file()); ?>
        </div>

        <form method="POST" action="">
            <label>Common Name (Nombre):</label>
            <input type="text" name="commonName" value="<?php echo htmlspecialchars($commonName); ?>" required>

            <label>Organization (Organización):</label>
            <input type="text" name="orgName" value="<?php echo htmlspecialchars($orgName); ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

            <button type="submit">Generar Certificado y Llaves</button>
        </form>

        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <hr>
            <div class="results">
                <?php if ($generadoExitosamente): ?>
                    <div class="alert-success">✅ ¡Certificado X.509 creado y guardado exitosamente en la carpeta <code>keysCert/</code>!</div>
                    <label>Certificado PEM Generado:</label>
                    <textarea readonly><?php echo htmlspecialchars($certout); ?></textarea>
                <?php else: ?>
                    <div class="alert-danger"><?php echo $errorMsg; ?></div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>