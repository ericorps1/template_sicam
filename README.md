# ERP - SICAM
> 🏢 **ERP Escolar** con PHP 5.6 + MySQL + jQuery + Bootstrap  
> Arquitectura modular, sesiones multi-rol, loader automático

---

## 🎯 **STACK TECNOLÓGICO**

- **Backend**: PHP 5.6 (paradigma funcional)
- **Base de Datos**: MySQL con `mysqli_*` (conexión `$db`)
- **Frontend**: jQuery + Bootstrap 5
- **Tablas**: Handsontable para CRUDs
- **Tiempo Real**: WebSocket

---

## 🏗️ **ESTRUCTURA DEL PROYECTO**

```
proyecto/
├── css/                    ← Hojas de estilo adicionales
├── ejecutivo/              ← Módulo ejecutivo
│   ├── assets/            ← Recursos estáticos (JS, CSS, imágenes)
│   ├── inc/               ← Archivos de inclusión
│   │   ├── cabeceras.php  ← Variables y configuración del sistema
│   │   ├── header.php     ← Encabezado con CSS del loader
│   │   ├── footer.php     ← Pie con script del loader
│   │   ├── funciones.php  ← Funciones PHP aquí
│   │   └── server/        ← Directorio de servicios servidor
│   ├── home.php           ← AQUI ESTÁS
│   ├── pagina_limpia.php  ← Página para comenzar
│   └── cerrar_sesion.php  ← Logout del sistema
├── img/                   ← Directorio de imágenes globales
├── includes/              ← Includes generales del sistema
│   └── conexion.php       ← Configuración de BD ($db) y WebSocket
├── uploads/               ← Directorio de archivos subidos
└── index.php              ← Punto de entrada principal
```

---

## 🔐 **SESIONES EN PHP**

### 🚀 **Flujo de Autenticación**

```php
# 1. index.php - Login unificado
$sql = 'SELECT * FROM ejecutivo WHERE cor_eje = :correo 
        UNION 
        SELECT * FROM profesor WHERE cor_pro = :correo';

# 2. Inicialización de sesión
$_SESSION['rol'] = [
    'id' => $resultado['id'],
    'tipo' => 'Ejecutivo',           # Define permisos y redirección
    'nombre' => $resultado['nombre'],
    'correo' => $resultado['correo'],
    'id_pla' => $resultado['id_pla']
];

$_SESSION['login'] = true;

# 3. Redirección automática
header('Location: ejecutivo/home.php');
```

### 📊 **Variables Globales (cabeceras.php)**

```php
# Validación de sesión activa
if (!isset($_SESSION['rol']) || $_SESSION['rol']['tipo'] !== "Ejecutivo") {
    header('Location: cerrar_sesion.php');
    exit();
}

# Extracción de datos básicos
$id = $_SESSION['rol']['id'];            # ID del ejecutivo
$nombre = $_SESSION['rol']['nombre'];     # Nombre del usuario

# Consulta enriquecida con JOIN usando conexión $db
$sql = "SELECT ejecutivo.*, plantel.*
        FROM ejecutivo 
        INNER JOIN plantel ON plantel.id_pla = ejecutivo.id_pla
        WHERE ejecutivo.id_eje = '$id'";

# Variables disponibles globalmente
$nombreCompleto = $fila['nom_eje'];
$nombrePlantel = $fila['nom_pla'];
$plantel = $fila['id_pla'];
$socket = 'wss://socket.ahjende.com/wss/?encoding=text';
```

### 🚪 **Cierre de Sesión (cerrar_sesion.php)**

```php
<?php  
    require('inc/cabeceras.php');     # Cargar variables de sesión
    session_destroy();                # Destruir sesión activa
    header('Location: ../');          # Redirigir al index.php
?>
```

**⏱️ Duración de Sesión:**
- **Por defecto**: 1440 segundos (24 minutos)
- **Regulado por**: `session.gc_maxlifetime` en `php.ini`
- **Auto-renovación**: Cada request extiende el tiempo de vida

---

## 💾 ** CONEXION A BASE DE DATOS**

- **Conexión**: Variable `$db` definida en `includes/conexion.php`

---



## 📄 **ANATOMÍA DE UNA PÁGINA**

### 🔧 **Estructura de home.php**

```php
<?php
# 1. HEADER: Template + CSS + Variables
include('inc/header.php');        # ← Incluye cabeceras.php + funciones.php
?>

<!-- 2. CONTENIDO PRINCIPAL -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div id="homeContent">
                    <!-- Placeholders animados mientras carga -->
                    <p class="placeholder-glow">
                        <span class="placeholder col-12"></span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
# 3. FOOTER: Scripts + Loader
include('inc/footer.php');
?>

<!-- 4. JAVASCRIPT ESPECÍFICO -->
<script>
$(document).ready(function() {
    // Establecer título dinámico en la pestaña del navegador
    $("#titulo_plataforma").html('<?php echo $nombrePlantel; ?> - HOME');
    
    // Variables disponibles desde cabeceras.php
    var sistemaData = {
        nombre: '<?php echo $nombreCompleto; ?>',
        plantel: '<?php echo $plantel; ?>',
        websocket: '<?php echo $socket; ?>'
    };
});
</script>
```

### 🧩 **Qué Contiene Cada Archivo**

| Archivo | Contiene | Propósito |
|---------|----------|-----------|
| `header.php` | `cabeceras.php` + `funciones.php` + HTML + CSS | Variables de sesión + template superior |
| `cabeceras.php` | Validación sesión + consulta BD + variables | Datos del usuario + plantel |
| `funciones.php` | `ejecutarConsulta()`, `escape()`, `respuestaExito()` | Funciones reutilizables con `$db` |
| `footer.php` | Scripts JS + Handsontable + **Loader automático** | Librerías + UX |

---

## ⏳ **MECÁNICA DEL LOADER**

### 🔄 **Sistema de Carga Automatizado**

```javascript
# footer.php - Loader automático
$(document).ready(function() {
    // 1. Crear loader en TODAS las páginas
    $('body').prepend(`
        <div id="loader">
            <div class="spinner-border avatar-lg text-primary m-2"></div>
            <span class="letraSicam">SICAM</span>
        </div>
    `);
    
    // 2. NO ocultar automáticamente (control local)
});

# home.php - Control específico
setTimeout(function() {
    // 3. Reemplazar placeholders con contenido
    $('#homeContent').html('<!-- Contenido real -->');
    
    // 4. Ocultar loader cuando todo esté listo
    $('#loader').addClass('hidden');
}, 2000);
```

### 🎯 **Flujo de UX**

```
1. Usuario entra → footer.php crea loader → pantalla cargando
2. Placeholders animados → simula contenido cargando  
3. JavaScript específico → genera contenido real
4. $('#loader').addClass('hidden') → loader desaparece
```

**🔑 Ventajas:**
- **Consistencia**: Mismo loader en todas las páginas
- **Control granular**: Cada página decide cuándo ocultarlo
- **UX mejorada**: Placeholders organizados durante espera
- **Centralizado**: Una sola implementación en `footer.php`

---

## 🔧 **PATRÓN DE CONTROLADORES**

### 📋 **Backend Estándar**

```php
<?php
# server/controlador_ejecutivos.php
include '../inc/conexion.php';           # MySQL connection ($db)
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = escape($_POST['action'], $db);
    
    switch($action) {
        case 'obtener_datos':
            $query = "SELECT * FROM ejecutivo WHERE id_pla = '$plantel'";
            $datos = ejecutarConsulta($query, $db);
            echo respuestaExito($datos);
        break;
        
        case 'guardar_cambio':
            $id = escape($_POST['id'], $db);
            $campo = escape($_POST['campo'], $db);
            $valor = escape($_POST['valor'], $db);
            
            $query = "UPDATE ejecutivo SET $campo = '$valor' WHERE id_eje = '$id'";
            
            if(mysqli_query($db, $query)) {
                echo respuestaExito(null, 'Actualizado correctamente');
            } else {
                echo respuestaError('Error: ' . mysqli_error($db));
            }
        break;
    }
    
    mysqli_close($db);
    exit;
}
?>
```

### 🎛️ **Frontend jQuery**

```javascript
// Llamada AJAX estándar
$.ajax({
    url: 'server/controlador_ejecutivos.php',
    type: 'POST',
    data: { action: 'obtener_datos' },
    dataType: 'json',
    success: function(response) {
        if(response.success) {
            renderizar(response.data);
        }
    }
});
```

---

## 🚀 **CARACTERÍSTICAS CLAVE**

✅ **Autenticación Multi-Rol**: Ejecutivos, Profesores, Alumnos  
✅ **Sesión Activa**: Validación en cada archivo del template  
✅ **Loader Automático**: UX consistente sin configuración manual  
✅ **Variables Globales**: Datos usuario + plantel disponibles  
✅ **Controladores REST**: Patrón MVC simplificado  
