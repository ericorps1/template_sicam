<?php  
include('inc/header.php');
?>

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
                    <li class="breadcrumb-item active">NUEVA PÁGINA</li>
                </ol>
            </div>
            
            <!-- Título principal de la página -->
            <h4 class="page-title">NUEVA PÁGINA</h4>
        </div>
    </div>
</div>

<!-- ============================================================================ -->
<!-- CONTENIDO PRINCIPAL DE LA PÁGINA -->
<!-- ============================================================================ -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- Contenido de la página -->
                <div class="pageContent">
                    <!-- Información del usuario y sistema -->
                    <div class="text-center mb-4">
                        <h5 class="mb-4">TEMPLATE BÁSICO - SICAM</h5>
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
<!-- SCRIPTS ESPECÍFICOS DE LA PÁGINA -->
<!-- ============================================================================ -->
<script>
$(document).ready(function() {
    // ========================================================================
    // CONFIGURACIÓN INICIAL DE LA PÁGINA
    // ========================================================================
    
    // Establecer título dinámico en la pestaña del navegador
    $("#titulo_plataforma").html('<?php echo $nombrePlantel; ?> - NUEVA PÁGINA');
    
    // Variables del sistema disponibles (desde cabeceras.php)
    var sistemaData = {
        ejecutivo: {
            nombre: '<?php echo $nombreCompleto; ?>',    // Desde: $filaConsultaEjecutivo['nom_eje']
            id: '<?php echo $id; ?>',                    // Desde: $_SESSION['rol']['id']
            plantel: '<?php echo $plantel; ?>'           // Desde: $filaConsultaEjecutivo['id_pla']
        },
        websocket: '<?php echo $socket; ?>',             // Desde: includes/conexion.php
        plantel: '<?php echo $nombrePlantel; ?>'         // Desde: $filaConsultaEjecutivo['nom_pla']
    };
    
    // Ocultar el loader global
    $('#loader').addClass('hidden');
    
    // Log de confirmación
    console.log('SICAM TEMPLATE: Página cargada correctamente');
    
}); // Fin del document ready
</script>