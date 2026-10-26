<?php
// Texto por defecto para el formulario
$mensaje = isset($_POST['mensaje']) ? $_POST['mensaje'] : "Mensaje ultra secreto con Libsodium";
$cifradoBase64 = "";
$descifrado = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Verificar si la extensión Sodium está activa en PHP/Wamp
    if (!extension_loaded('sodium')) {
        $error = "La extensión Libsodium no está habilitada en PHP.";
    } else {
        // 2. Generar una clave secreta simétrica aleatoria (o usar una fija de 32 bytes)
        $clave = sodium_crypto_secretbox_keygen();
        
        // 3. Generar un nonce (número usado una vez) único y seguro
        $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);

        // 4. Cifrado autenticado de datos
        $cifradoBinario = sodium_crypto_secretbox($mensaje, $nonce, $clave);
        $cifradoBase64 = base64_encode($cifradoBinario);

        // 5. Descifrado y verificación de autenticidad
        $descifradoBinario = sodium_crypto_secretbox_open($cifradoBinario, $nonce, $clave);
        
        if ($descifradoBinario === false) {
            $error = "Error: El mensaje fue alterado o la clave/nonce son inválidos.";
        } else {
            $descifrado = $descifradoBinario;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ejemplo Práctico con Libsodium</title>
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
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }
        button {
            background-color: #16a085;
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
            background-color: #1abc9c;
        }
        hr {
            border: 0;
            height: 1px;
            background: #eaeaea;
            margin: 25px 0;
        }
        .results {
            background: #fdfdfd;
            border-left: 4px solid #16a085;
            padding: 15px;
            margin-top: 15px;
            border-radius: 4px;
        }
        .alert-box {
            background: #e8f8f5;
            color: #16a085;
            padding: 10px;
            border-radius: 5px;
            font-family: monospace;
            border: 1px solid #a3e4d7;
            margin-top: 5px;
            word-break: break-all;
            font-size: 13px;
        }
        .alert-success {
            color: #27ae60;
            background: #e8f8f5;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
            border: 1px solid #a3e4d7;
            margin-top: 5px;
        }
        .alert-danger {
            color: #c0392b;
            background: #f9ebea;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
            border: 1px solid #f5b7b1;
            margin-top: 5px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>🛡️ Cifrado Autenticado con Libsodium (`sodium_crypto_secretbox`)</h2>
        
        <form method="POST" action="">
            <label>Mensaje a procesar con Libsodium:</label>
            <input type="text" name="mensaje" value="<?php echo htmlspecialchars($mensaje); ?>" required>
            <button type="submit">Ejecutar Cifrado Autenticado</button>
        </form>

        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <hr>
            <div class="results">
                <h3>Resultados de la Ejecución</h3>
                <?php if (!empty($error)): ?>
                    <div class="alert-danger"><?php echo $error; ?></div>
                <?php else: ?>
                    <p><b>Mensaje Original:</b> <?php echo htmlspecialchars($mensaje); ?></p>
                    
                    <label>Texto Cifrado (Autenticado en Base64):</label>
                    <div class="alert-box"><?php echo htmlspecialchars($cifradoBase64); ?></div>

                    <label style="margin-top: 15px;">Texto Descifrado y Verificado:</label>
                    <div class="alert-success">✔ <?php echo htmlspecialchars($descifrado); ?></div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>