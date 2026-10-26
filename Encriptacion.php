<?php
// Texto por defecto para el formulario
$texto = isset($_POST['texto']) ? $_POST['texto'] : "Mensaje secreto";
$cifrado = "";
$descifrado = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clave = "clave123456789012"; // 16 caracteres para AES-128
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length("AES-128-CBC"));

    // Cifrar el texto ingresado
    $cifrado = openssl_encrypt($texto, "AES-128-CBC", $clave, 0, $iv);
    
    // Descifrar el texto
    $descifrado = openssl_decrypt($cifrado, "AES-128-CBC", $clave, 0, $iv);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cifrado Simétrico - OpenSSL</title>
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
            background-color: #e67e22;
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
            background-color: #d35400;
        }
        hr {
            border: 0;
            height: 1px;
            background: #eaeaea;
            margin: 25px 0;
        }
        .results {
            background: #fdfdfd;
            border-left: 4px solid #e67e22;
            padding: 15px;
            margin-top: 15px;
            border-radius: 4px;
        }
        .alert-box {
            background: #fef5e7;
            color: #d35400;
            padding: 10px;
            border-radius: 5px;
            font-family: monospace;
            border: 1px solid #f8c471;
            margin-top: 5px;
            word-break: break-all;
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
    </style>
</head>
<body>

    <div class="container">
        <h2>🔐 Cifrado Simétrico con OpenSSL (AES-128-CBC)</h2>
        
        <form method="POST" action="">
            <label>Texto a cifrar:</label>
            <input type="text" name="texto" value="<?php echo htmlspecialchars($texto); ?>" required>
            <button type="submit">Ejecutar Cifrado y Descifrado</button>
        </form>

        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <hr>
            <div class="results">
                <h3>Resultados de la Ejecución</h3>
                <p><b>Texto Original:</b> <?php echo htmlspecialchars($texto); ?></p>
                
                <label>Resultado del cifrado:</label>
                <div class="alert-box"><?php echo htmlspecialchars($cifrado); ?></div>

                <label style="margin-top: 15px;">Resultado del descifrado:</label>
                <div class="alert-success">✔ <?php echo htmlspecialchars($descifrado); ?></div>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>