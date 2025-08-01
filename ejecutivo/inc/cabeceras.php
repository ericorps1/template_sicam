<?php
/**
 * SISTEMA SICAM - MÓDULO DE SESIÓN EJECUTIVO
 * Validación de sesión, obtención de datos del usuario ejecutivo y plantel
 * 
 * @version 1.0
 * @author ericorps
 */

// ============================================================================
// CONFIGURACIÓN INICIAL DE SESIÓN
// ============================================================================

session_start();
$_SESSION['login'] = true;

// Incluir archivo de conexión a base de datos
// La ruta se definio aqui ya que evoca por varios usuarios
require_once(__DIR__ . "/../../includes/conexion.php");

// ============================================================================
// VALIDACIÓN Y CONTROL DE ACCESO POR ROL
// ============================================================================

// Verificar que existe una sesión activa con rol definido
if (!isset($_SESSION['rol'])) {
    // No hay sesión activa, redirigir al cierre de sesión
    header('Location: cerrar_sesion.php');
    exit();
}

// Validar que el tipo de usuario sea "Ejecutivo"
if (!isset($_SESSION['rol']['tipo']) || $_SESSION['rol']['tipo'] !== "Ejecutivo") {
    // Usuario no autorizado para este módulo, redirigir al cierre de sesión
    header('Location: cerrar_sesion.php');
    exit();
}

// ============================================================================
// EXTRACCIÓN DE DATOS DE SESIÓN
// ============================================================================

$datos = $_SESSION['rol'];
$id = isset($datos['id']) ? $datos['id'] : '';
$tipo = isset($datos['tipo']) ? $datos['tipo'] : '';
$nombre = isset($datos['nombre']) ? $datos['nombre'] : '';

// Validar que tenemos un ID válido
if (empty($id)) {
    error_log("SICAM ERROR: ID de ejecutivo no encontrado en sesión");
    header('Location: cerrar_sesion.php');
    exit();
}

// ============================================================================
// CONSULTA DE DATOS COMPLETOS DEL EJECUTIVO Y PLANTEL
// Obtiene información detallada del ejecutivo junto con datos del plantel asociado
// Usa mysqli_real_escape_string para prevenir inyección SQL
// ============================================================================

$id_escaped = mysqli_real_escape_string($db, $id);

$sqlConsultaEjecutivo = "
    SELECT ejecutivo.*, plantel.*
    FROM ejecutivo
    INNER JOIN plantel ON plantel.id_pla = ejecutivo.id_pla
    WHERE ejecutivo.id_eje = '$id_escaped'
";

// Ejecutar consulta con manejo de errores
$resultadoConsultaEjecutivo = mysqli_query($db, $sqlConsultaEjecutivo);

// Verificar que la consulta se ejecutó correctamente
if (!$resultadoConsultaEjecutivo) {
    error_log("SICAM DB ERROR: " . mysqli_error($db));
    header('Location: cerrar_sesion.php');
    exit();
}

// Obtener datos del resultado
$filaConsultaEjecutivo = mysqli_fetch_assoc($resultadoConsultaEjecutivo);

// Verificar que se encontraron datos del ejecutivo
if (!$filaConsultaEjecutivo) {
    error_log("SICAM ERROR: No se encontraron datos para el ejecutivo ID: {$id}");
    header('Location: cerrar_sesion.php');
    exit();
}

// ============================================================================
// FUNCIÓN AUXILIAR PARA VALIDAR ÍNDICES
// ============================================================================
function obtenerValor($array, $indice, $valorPorDefecto = '') {
    return isset($array[$indice]) && !is_null($array[$indice]) ? trim($array[$indice]) : $valorPorDefecto;
}

// ============================================================================
// PROCESAMIENTO Y ASIGNACIÓN DE VARIABLES DEL EJECUTIVO
// Extrae y organiza todos los datos del usuario ejecutivo
// ============================================================================

// Nombre completo del responsable
$nomResponsable = obtenerValor($filaConsultaEjecutivo, 'nom_eje');

// ====================================================================
// DATOS PERSONALES DEL EJECUTIVO
// ====================================================================
$ingresoUsuario = obtenerValor($filaConsultaEjecutivo, 'ing_eje');
$nombreUsuario = obtenerValor($filaConsultaEjecutivo, 'nom_eje');
$correoUsuario = obtenerValor($filaConsultaEjecutivo, 'cor_eje');
$generoUsuario = obtenerValor($filaConsultaEjecutivo, 'gen_eje');
$telefonoUsuario = obtenerValor($filaConsultaEjecutivo, 'tel_eje');
$nacimientoUsuario = obtenerValor($filaConsultaEjecutivo, 'nac_eje');
$fotoUsuario = obtenerValor($filaConsultaEjecutivo, 'fot_eje');
$direccionUsuario = obtenerValor($filaConsultaEjecutivo, 'dir_eje');
$cpUsuario = obtenerValor($filaConsultaEjecutivo, 'cp_eje');
$contrasenaUsuario = obtenerValor($filaConsultaEjecutivo, 'pas_eje');
$descripcionUsuario = obtenerValor($filaConsultaEjecutivo, 'des_eje');

// ====================================================================
// CONFIGURACIÓN Y PERMISOS DEL EJECUTIVO
// ====================================================================
$tipoUsuario = obtenerValor($filaConsultaEjecutivo, 'tip_eje');
$estatusUsuario = obtenerValor($filaConsultaEjecutivo, 'est_eje');
$eli_eje = obtenerValor($filaConsultaEjecutivo, 'eli_eje');
$permisos = obtenerValor($filaConsultaEjecutivo, 'per_eje');
$usuario = obtenerValor($filaConsultaEjecutivo, 'usu_eje');
$rangoUsuario = obtenerValor($filaConsultaEjecutivo, 'ran_eje');

// Variables adicionales para compatibilidad
$foto = obtenerValor($filaConsultaEjecutivo, 'fot_eje');
$id_eje = obtenerValor($filaConsultaEjecutivo, 'id_eje');

// ============================================================================
// DATOS DEL PLANTEL ASOCIADO
// Información completa del plantel al que pertenece el ejecutivo
// ============================================================================

$plantel = obtenerValor($filaConsultaEjecutivo, 'id_pla');
$fotoPlantel = obtenerValor($filaConsultaEjecutivo, 'fot_pla');
$nombrePlantel = obtenerValor($filaConsultaEjecutivo, 'nom_pla');
$esloganPlantel = obtenerValor($filaConsultaEjecutivo, 'esl_pla');
$folioPlantel = obtenerValor($filaConsultaEjecutivo, 'fol_pla');
$descripcionPlantel = obtenerValor($filaConsultaEjecutivo, 'des_pla');
$direccionPlantel = obtenerValor($filaConsultaEjecutivo, 'dir_pla');
$directorPlantel = obtenerValor($filaConsultaEjecutivo, 'jef_pla');
$urlPlantel = obtenerValor($filaConsultaEjecutivo, 'url_pla');
$telefonoPlantel = obtenerValor($filaConsultaEjecutivo, 'tel_pla');
$correoPlantel = obtenerValor($filaConsultaEjecutivo, 'cor_pla');
$correo2Plantel = obtenerValor($filaConsultaEjecutivo, 'cor2_pla');
$ligaPlantel = obtenerValor($filaConsultaEjecutivo, 'lig_pla');
$cuentaPlantel = obtenerValor($filaConsultaEjecutivo, 'cue_pla');

// ====================================================================
// IDENTIFICADOR DE CADENA (MARCA)
// ====================================================================
$cadena = obtenerValor($filaConsultaEjecutivo, 'id_cad1');

// ============================================================================
// VARIABLES CALCULADAS Y AUXILIARES
// ============================================================================

// Nombre completo del ejecutivo para uso general
$nombreCompleto = obtenerValor($filaConsultaEjecutivo, 'nom_eje');

// Fecha actual del sistema
$fechaHoy = date('Y-m-d');

// Validar datos críticos antes de continuar
$datosCriticos = [
    'ID Ejecutivo' => $id_eje,
    'Nombre Usuario' => $nombreUsuario,
    'Email Usuario' => $correoUsuario,
    'ID Plantel' => $plantel,
    'Nombre Plantel' => $nombrePlantel
];

$erroresCriticos = [];
foreach ($datosCriticos as $campo => $valor) {
    if (empty($valor)) {
        $erroresCriticos[] = $campo;
    }
}

if (!empty($erroresCriticos)) {
    error_log("SICAM WARNING: Datos críticos faltantes para ejecutivo ID {$id}: " . implode(', ', $erroresCriticos));
}

// Log de acceso exitoso para auditoría
error_log("SICAM SESSION: Ejecutivo {$correoUsuario} (ID: {$id_eje}) - Datos cargados exitosamente");

?>