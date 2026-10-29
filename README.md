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

### 1. Evidencia FirmaMensaje
<img width="940" height="781" alt="WhatsApp Image 2026-10-29 at 1 30 21 PM" src="https://github.com/user-attachments/assets/6133fcf9-30c5-488e-9b43-ee1fe9c434b8" />

### 2. Evidencia Firma de certificado X.509
<img width="940" height="594" alt="WhatsApp Image 2026-10-29 at 1 30 52 PM" src="https://github.com/user-attachments/assets/bc32789d-6506-4760-9400-8a9a3cfc1113" />
<img width="940" height="535" alt="WhatsApp Image 2026-10-29 at 1 31 00 PM" src="https://github.com/user-attachments/assets/22562c3f-36c4-4d73-8ef6-de84fa84574d" />

### 3. Evidencia Firma7
<img width="940" height="756" alt="WhatsApp Image 2026-10-29 at 1 31 09 PM" src="https://github.com/user-attachments/assets/03bf72cf-9f1e-4ada-8084-9bbe151023cc" />

### 3. Evidencia Cifrado OpenSSL
<img width="940" height="719" alt="WhatsApp Image 2026-10-29 at 1 31 16 PM" src="https://github.com/user-attachments/assets/b8d4884c-6547-40fa-bcdc-47ec645c08f4" />

---

## 👤 Detalles del Autores y Referencias
* **Estudiante:** Iliana Navarro y Andrea Sanchez
* **Fecha:** Octubre 2026
* **Referencias:**
  * Documentación Oficial de PHP: [OpenSSL Functions](https://www.php.net/manual/es/book.openssl.php)
  * Documentación Oficial de PHP: [Sodium Functions](https://www.php.net/manual/es/book.sodium.php)
  * Manual de configuración de entornos locales WampServer y directivas de seguridad en Apache.
