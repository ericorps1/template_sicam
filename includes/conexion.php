<?php
/**
 * SISTEMA SICAM - CONFIGURACIÓN DE CONEXIÓN A BASE DE DATOS
 * Archivo de conexión principal del sistema con configuración de WebSocket
 * 
 * @version 1.0
 * @php_version 5.6+
 */

// ============================================================================
// CONFIGURACIÓN DE CONEXIÓN REMOTA - SERVIDOR PRINCIPAL
// ============================================================================

// Parámetros de conexión del servidor remoto
$host = "49.12.79.33";
$port = 3306;
$user = "wpuser";
$pass = "WP!19";
$database = "sicam_ambiente_1";

// ============================================================================
// ESTABLECIMIENTO DE CONEXIÓN PRINCIPAL
// ============================================================================
$db = new mysqli($host, $user, $pass, $database, $port);

// ============================================================================
// VALIDACIÓN DE CONEXIÓN A BASE DE DATOS
// ============================================================================
if ($db->connect_error) {
    // Log del error para debugging
    error_log("SICAM DB ERROR: Conexión fallida - " . $db->connect_error);
    
    // Mensaje de error para el usuario (sin exponer detalles técnicos)
    die("Error de conexión al sistema. Por favor, contacte al administrador.");
}

// ============================================================================
// CONFIGURACIÓN DE CHARSET PARA CARACTERES ESPECIALES
// ============================================================================
if (!$db->set_charset("utf8")) {
    error_log("SICAM DB WARNING: Error al establecer charset UTF-8 - " . $db->error);
}

// ============================================================================
// CONFIGURACIÓN DEL WEBSOCKET
// Configuración para comunicación en tiempo real del sistema
// ============================================================================
$socket = 'wss://socket.ahjende.com/wss/?encoding=text';

// ============================================================================
// LOG DE CONEXIÓN EXITOSA (OPCIONAL - PARA DEBUGGING)
// ============================================================================
// Descomentare la siguiente línea solo para debugging
// error_log("SICAM DB: Conexión establecida exitosamente a " . $database);

// ============================================================================
// CONFIGURACIÓN ADICIONAL DE MYSQL (OPCIONAL)
// ============================================================================
// Configurar timezone si es necesario
// $db->query("SET time_zone = '-06:00'");

// Configurar modo SQL (opcional, para compatibilidad)
// $db->query("SET sql_mode = ''");

?>