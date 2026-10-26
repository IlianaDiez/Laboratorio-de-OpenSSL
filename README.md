# 🔒 Laboratorio de Seguridad en PHP con OpenSSL y Libsodium

Repositorio oficial que contiene el desarrollo de los scripts criptográficos y de seguridad implementados en entorno local (WAMP), cumpliendo con los estándares de cifrado simétrico, asimétrico, firma digital y la investigación teórica-práctica de Libsodium.

---

## 📋 Detalles del Laboratorio
* **Asignatura:** Herramientas de la Programación Aplicada III / Seguridad Informática.
* **Descripción:** Implementación de funciones criptográficas avanzadas utilizando PHP y OpenSSL, integrando interfaces web responsivas con formularios dinámicos y validaciones de seguridad en un servidor local Apache/Wamp.
* **Objetivos:**
  * Configurar correctamente las rutas de los archivos de configuración (`openssl.cnf`) en entornos de desarrollo Windows/Wamp.
  * Desarrollar scripts para generación de claves asimétricas, certificados X.509, firma y verificación de mensajes.
  * Implementar cifrado simétrico (AES-128-CBC) y cifrado autenticado de alto rendimiento mediante la librería moderna Libsodium.

---

## 🛠️ Tecnologías y Versiones Utilizadas
* **Servidor Web:** WampServer 64 bits.
* **Apache:** Versión 2.4.x
* **PHP:** Versión 8.0.30
* **Librerías Criptográficas:** 
  * OpenSSL (Integrado en PHP)
  * Libsodium (Nativo en PHP 8.0+)
* **Lenguajes de Estilo / Frontend:** HTML5, CSS3 (Diseño responsivo tipo tarjeta).

---

## 📦 Procesos de Instalación y Configuración
1. **Clonación o Descarga:** Coloca la carpeta del proyecto dentro del directorio raíz de tu servidor local (ej. `C:/wamp64/www/SeguridadOpenSSL-main/`).
2. **Configuración de OpenSSL:** Asegúrate de que las rutas hacia el archivo de configuración `openssl.cnf` estén correctamente referenciadas en los scripts mediante la función `putenv("OPENSSL_CONF=C:/wamp64/bin/php/php8.0.30/extras/ssl/openssl.cnf");`.
3. **Ejecución:** Inicia los servicios de WampServer y accede a través del navegador mediante la ruta:
   `http://localhost/SeguridadOpenSSL-main/`

---

## 🖥️ Controles Utilizados y Scripts Principales
* **`firma7.php` / `FirmarMensaje.php`:** Gestión de creación de pares de claves RSA, firma digital de mensajes mediante algoritmos de resumen (SHA-256) y validación de autenticidad.
* **`firmaOtra.php`:** Generación y exportación de certificados digitales X.509 firmados localmente con información personalizada (DN - Distinguished Name).
* **`Encriptacion.php`:** Cifrado y descifrado simétrico de datos utilizando bloques AES-128-CBC con vectores de inicialización (IV).
* **`Libsodium.php`:** Investigación y ejecución práctica de cifrado autenticado de datos mediante `sodium_crypto_secretbox`.

---

## 📸 Evidencias del Sistema (Imágenes del Laboratorio)

### 1. Inserción de Registros / Datos de Entrada
*(Ilustra aquí con una captura de pantalla de cualquiera de tus formularios en funcionamiento, por ejemplo, el ingreso de un mensaje en el script de firma o encriptación)*
> ![Insertar Registros](ruta/a/tu/imagen_insercion.png)

### 2. Evidencia de Acciones de Modificar
*(Ilustra aquí con una captura de pantalla cambiando los valores de entrada en el formulario —como modificar el texto predeterminado por uno personalizado— y cómo la aplicación procesa el cambio)*
> ![Modificar Registros](ruta/a/tu/imagen_modificar.png)

### 3. Evidencia de Acciones de Eliminar / Restablecer
*(Ilustra aquí con una captura de pantalla que demuestre la limpieza de estados o la validación de errores/firmas inválidas al alterar los datos)*
> ![Eliminar Acciones](ruta/a/tu/imagen_eliminar.png)

---

## 👤 Detalles del Autores y Referencias
* **Estudiante:** Iliana Navarro y Andrea Sanchez
* **Fecha:** Octubre 2026
* **Referencias:**
  * Documentación Oficial de PHP: [OpenSSL Functions](https://www.php.net/manual/es/book.openssl.php)
  * Documentación Oficial de PHP: [Sodium Functions](https://www.php.net/manual/es/book.sodium.php)
  * Manual de configuración de entornos locales WampServer y directivas de seguridad en Apache.
