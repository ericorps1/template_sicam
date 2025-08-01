<?php  
/**
 * SISTEMA SICAM - PÁGINA PRINCIPAL (HOME)
 * Página de inicio del sistema con navegación y título dinámico
 * 
 * @version 1.0
 * @php_version 5.6+
 * @description Esta página implementa un sistema de carga con placeholders
 *              y contenido dinámico que se carga después de un delay simulado
 */

// ============================================================================
// INCLUSIÓN DEL HEADER DEL SISTEMA
// ============================================================================
include('inc/header.php');
?>

<!-- ============================================================================ -->
<!-- ESTILOS ESPECÍFICOS PARA ESTA PÁGINA -->
<!-- ============================================================================ -->
<style>
/* Asegurar que las explicaciones técnicas no se vean afectadas por text-transform */
.card-text.small,
.alert span,
pre,
small.text-muted {
    text-transform: none !important;
}

/* Mejorar la apariencia de los códigos inline */
code {
    background-color: #f8f9fa;
    padding: 2px 4px;
    border-radius: 3px;
    font-size: 0.875em;
    color: #e83e8c;
}

/* Estilos para el pre con la estructura del proyecto */
pre {
    overflow-x: auto;
    white-space: pre;
    font-family: 'Courier New', monospace;
}

/* Animación suave para la aparición del contenido */
#homeContent {
    transition: opacity 0.3s ease-in-out;
}

/* ============================================================================ */
/* ESTILOS HOMOLOGADOS - SOLO AZUL TENUE Y GRIS */
/* ============================================================================ */

/* Estilos base para todas las tarjetas explicativas */
.info-card {
    transition: all 0.3s ease-in-out;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    border: 1px solid rgba(0,0,0,0.08);
    border-radius: 8px;
    margin-bottom: 1rem;
    background: #f8f9fa;
}

.info-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.12);
}

.info-card .card-body {
    padding: 1.25rem;
}

.info-card .card-title {
    color: #495057;
    font-weight: 600;
    margin-bottom: 0.75rem;
    font-size: 0.95rem;
}

.info-card .card-text {
    color: #6c757d;
    font-size: 0.85rem;
    line-height: 1.5;
}

/* Estilos para notifications - SOLO AZUL TENUE Y GRIS */
.notification-info {
    border: none;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    margin-bottom: 1rem;
    padding: 1rem 1.25rem;
    background: linear-gradient(135deg, #e3f2fd 0%, #f8f9fa 100%);
    border-left: 4px solid #64b5f6;
    color: #1565c0;
}

.notification-gray {
    border: none;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    margin-bottom: 1rem;
    padding: 1rem 1.25rem;
    background: linear-gradient(135deg, #f5f5f5 0%, #f8f9fa 100%);
    border-left: 4px solid #9e9e9e;
    color: #424242;
}

.notification-info strong,
.notification-gray strong {
    font-weight: 600;
    margin-bottom: 0.5rem;
    display: inline-block;
    text-transform: none !important;
}

/* Badges homologados */
.badge-homologated {
    background: #f8f9fa;
    color: #495057;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    padding: 0.35em 0.65em;
    font-size: 0.75em;
    font-weight: 500;
    margin: 0.2rem;
    display: inline-block;
    text-transform: none !important;
}

/* Estilos para placeholders organizados */
.placeholder-glow .placeholder {
    animation: placeholder-glow 2s ease-in-out infinite alternate;
}

@keyframes placeholder-glow {
    50% {
        opacity: .5;
    }
}

/* Información del sistema destacada */
.text-info {
    font-family: 'Courier New', monospace;
    font-size: 0.85em;
}
</style>

<!-- ============================================================================ -->
<!-- TÍTULO DE PÁGINA Y BREADCRUMB -->
<!-- ============================================================================ -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <!-- Breadcrumb de navegación -->
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="home.php">HOME</a></li>
                </ol>
            </div>
            
            <!-- Título principal de la página -->
            <h4 class="page-title">HOME</h4>
        </div>
    </div>
</div>

<!-- ============================================================================ -->
<!-- CONTENIDO PRINCIPAL DE LA PÁGINA HOME -->
<!-- ============================================================================ -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- Contenido inicial con placeholders animados organizados -->
                <div id="homeContent">
                    <!-- ============================================ -->
                    <!-- PLACEHOLDERS ORGANIZADOS POR SECCIONES -->
                    <!-- ============================================ -->
                    
                    <!-- Placeholder para título principal -->
                    <div class="text-center mb-4">
                        <p class="placeholder-glow">
                            <span class="placeholder col-12"></span>
                        </p>
                        <p class="placeholder-glow">
                            <span class="placeholder col-12"></span>
                        </p>
                    </div>
                    
                    <!-- Placeholders para contenido informativo -->
                    <div class="row">
                        <div class="col-md-6">
                            <p class="placeholder-glow">
                                <span class="placeholder col-12"></span>
                            </p>
                            <p class="placeholder-glow">
                                <span class="placeholder col-12"></span>
                            </p>
                            <p class="placeholder-glow">
                                <span class="placeholder col-12"></span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="placeholder-glow">
                                <span class="placeholder col-12"></span>
                            </p>
                            <p class="placeholder-glow">
                                <span class="placeholder col-12"></span>
                            </p>
                            <p class="placeholder-glow">
                                <span class="placeholder col-12"></span>
                            </p>
                        </div>
                    </div>
                 
                    <!-- Placeholders para sección técnica -->
                    <div class="mt-4">
                        <p class="placeholder-glow">
                            <span class="placeholder col-12"></span>
                        </p>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="placeholder-glow">
                                    <span class="placeholder col-12"></span>
                                </p>
                                <p class="placeholder-glow">
                                    <span class="placeholder col-12"></span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="placeholder-glow">
                                    <span class="placeholder col-12"></span>
                                </p>
                                <p class="placeholder-glow">
                                    <span class="placeholder col-12"></span>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Placeholder para información del sistema -->
                    <div class="mt-4">
                        <p class="placeholder-glow">
                            <span class="placeholder col-12"></span>
                        </p>
                        <p class="placeholder-glow">
                            <span class="placeholder col-12"></span>
                        </p>
                        <p class="placeholder-glow">
                            <span class="placeholder col-12"></span>
                        </p>
                        <p class="placeholder-glow">
                            <span class="placeholder col-12"></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php  
// ============================================================================
// INCLUSIÓN DEL FOOTER DEL SISTEMA
// ============================================================================
include('inc/footer.php');
?>

<!-- ============================================================================ -->
<!-- SCRIPTS ESPECÍFICOS DE LA PÁGINA HOME -->
<!-- ============================================================================ -->
<script>
$(document).ready(function() {
    // ========================================================================
    // CONFIGURACIÓN INICIAL DE LA PÁGINA
    // ========================================================================
    
    // Establecer título dinámico en la pestaña del navegador
    $("#titulo_plataforma").html('<?php echo $nombrePlantel; ?> - HOME');
    
    // Variables del sistema disponibles (desde cabeceras.php)
    var sistemaData = {
        ejecutivo: {
            nombre: '<?php echo strtolower($nombreCompleto); ?>',  // Convertir a minúsculas desde PHP
            id: '<?php echo $id; ?>',                             // Desde: $_SESSION['rol']['id']
            plantel: '<?php echo $plantel; ?>'                    // Desde: $filaConsultaEjecutivo['id_pla']
        },
        websocket: '<?php echo $socket; ?>',                      // Desde: includes/conexion.php
        plantel: '<?php echo $nombrePlantel; ?>'                  // Desde: $filaConsultaEjecutivo['nom_pla']
    };
    
    // Logs para debugging y monitoreo
    console.log('SICAM HOME: Página cargada correctamente');
    console.log('Ejecutivo:', sistemaData.ejecutivo.nombre);
    console.log('ID Ejecutivo:', sistemaData.ejecutivo.id);
    console.log('Plantel ID:', sistemaData.ejecutivo.plantel);
    console.log('Plantel Nombre:', sistemaData.plantel);
    console.log('WebSocket Configurado:', sistemaData.websocket);
    console.log('Timestamp de carga:', new Date().toLocaleString());
    
    // ========================================================================
    // SIMULACIÓN DE CARGA DE CONTENIDO
    // ========================================================================
    
    // Simular carga de contenido después de mostrar placeholders
    // Tiempo de espera: 2000ms (2 segundos)
    setTimeout(function() {
        // Reemplazar placeholders con contenido real
        $('#homeContent').html(`
            <div class="text-center d-flex align-items-center justify-content-center" style="min-height: 400px;">
                <div>
                    <!-- Información del usuario y sistema -->
                    <div class="mb-4">
                        <p class="text-muted mb-1">AMBIENTE_1</p>
                    </div>
             
                    <!-- Hipervínculo centrado -->
                    <div class="text-center mb-5">
                        <span>Inicia aquí</span>
                        <br>
                        <a href="pagina_limpia.php" target="_blank">PÁGINA LIMPIA</a>
                    </div>
                    
                    <!-- Sección explicativa del funcionamiento del loader -->
                    <div class="mt-5 text-start">
                        <h6 class="text-primary">📋 Funcionamiento del Loader en SICAM</h6>
                        
                        <!-- Grid con explicaciones del proceso -->
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="card-body">
                                        <h6 class="card-title">🔄 1. Inicialización</h6>
                                        <p class="card-text small" style="text-transform: none;">
                                            El archivo <code>footer.php</code> contiene el script JavaScript que crea 
                                            el loader automáticamente en cada página que incluya este archivo. 
                                            Esto garantiza consistencia en toda la aplicación.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="card-body">
                                        <h6 class="card-title">⏳ 2. Placeholders</h6>
                                        <p class="card-text small" style="text-transform: none;">
                                            Mientras se carga el contenido real, se muestran placeholders animados 
                                            organizados por secciones utilizando elementos Bootstrap que simulan 
                                            texto cargando.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="card-body">
                                        <h6 class="card-title">🎯 3. Control Local</h6>
                                        <p class="card-text small" style="text-transform: none;">
                                            Cada página individual controla cuándo ocultar su loader específico 
                                            usando jQuery con el comando <code>$('#loader').addClass('hidden')</code>. 
                                            Esto permite personalizar los tiempos de carga por página.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="card-body">
                                        <h6 class="card-title">🌐 4. WebSocket</h6>
                                        <p class="card-text small" style="text-transform: none;">
                                            El sistema utiliza WebSocket configurado en <code>${sistemaData.websocket}</code> 
                                            (definido en includes/conexion.php) para comunicación en tiempo real entre el cliente 
                                            y el servidor, permitiendo actualizaciones instantáneas.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Información sobre la estructura del proyecto -->
                        <div class="mt-3">
                            <strong>📁 Estructura del Proyecto SICAM:</strong>
                            <pre class="bg-dark text-light p-3 mt-2" style="border-radius: 5px; font-size: 12px; text-transform: none;">
SICAM_AMBIENTACION/
├── css/                    ← Hojas de estilo adicionales
├── ejecutivo/              ← Módulo ejecutivo
│   ├── assets/            ← Recursos estáticos (JS, CSS, imágenes)
│   ├── inc/               ← Archivos de inclusión
│   │   ├── cabeceras.php  ← Variables y configuración del sistema
│   │   ├── header.php     ← Encabezado con CSS del loader
│   │   ├── footer.php     ← Pie con script del loader
│   │   ├── funciones.php  ← Funciones PHP aquí
│   │   └── server/        ← Directorio de servicios servidor
│   └── home.php           ← AQUI ESTÁS
├── img/                   ← Directorio de imágenes globales
├── includes/              ← Includes generales del sistema
│   └── conexion.php       ← Configuración de BD y WebSocket
├── uploads/               ← Directorio de archivos subidos
└── index.php              ← Punto de entrada principal</pre>
                        </div>
                        
                        <!-- Variables del Sistema - AZUL TENUE -->
                        <div class="mt-4">
                            <div class="notification-info" role="alert">
                                <strong>📊 Algunas variables y sus procedencias :</strong>
                                <div class="mt-2">
                                    <span class="badge-homologated">nombre: ${sistemaData.ejecutivo.nombre} → inc/cabeceras.php</span>
                                    <span class="badge-homologated">id: ${sistemaData.ejecutivo.id} → $_SESSION</span>
                                    <span class="badge-homologated">plantel: ${sistemaData.ejecutivo.plantel} → inc/cabeceras.php</span>
                                    <span class="badge-homologated">websocket: ${sistemaData.websocket} → includes/conexion.php</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Funciones PHP Centralizadas - GRIS -->
                        <div class="mt-4">
                            <div class="notification-gray" role="alert">
                                <strong>🔧 Funciones PHP Centralizadas:</strong>
                                <div class="mt-2" style="text-transform: none;">
                                    Las funciones PHP del sistema están organizadas en <code>inc/funciones.php</code>
                                    para facilitar el mantenimiento y reutilización. Cada función tiene un título descriptivo
                                    que explica su propósito específico dentro del sistema SICAM.
                                </div>
                            </div>
                        </div>
                        
                        <!-- Notas sobre Estilos - GRIS -->
                        <div class="mt-4">
                            <div class="notification-gray" role="alert">
                                <strong>⚠️ Nota sobre Estilos:</strong>
                                <span style="text-transform: none;">
                                    El CSS <code>body { text-transform: uppercase; }</code> está aplicado 
                                    globalmente para ejecutivos, pero las explicaciones técnicas usan 
                                    <code>style="text-transform: none;"</code> para mantener texto en mayúsculas con minúsculas, dado que por petición es todo en mayúsculas (salvo datos como correos, hipervínculos...etc).
                                </span>
                            </div>
                        </div>
                        
                        <!-- Información de versión y compatibilidad -->
                        <div class="mt-3">
                            <small class="text-muted" style="text-transform: none;">
                                <strong>Versión:</strong> SICAM v1.1 | 
                                <strong>Compatibilidad:</strong> PHP 5.6+ | 
                                <strong>Framework:</strong> Bootstrap 5+ | 
                                <strong>Dependencias:</strong> jQuery 3+ | 
                                <strong>WebSocket:</strong> Activo | 
                                <strong>Funciones:</strong> Centralizadas en funciones.php
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        `);
        
        // ====================================================================
        // FINALIZACIÓN DEL PROCESO DE CARGA
        // ====================================================================
        
        // Ocultar el loader global una vez que el contenido está listo
        $('#loader').addClass('hidden');
        
        // Log de confirmación
        console.log('SICAM HOME: Contenido cargado y loader ocultado');
        console.log('Tiempo total de carga simulada: 2000ms');
        console.log('Datos del sistema cargados correctamente');
        console.log('Funciones disponibles desde funciones.php: mostrarInfoSistema(), testWebSocket()');
        
    }, 2000); // Fin del setTimeout
    
}); // Fin del document ready
</script>