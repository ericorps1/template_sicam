<?php  
/**
 * SISTEMA SICAM - HEADER ÁREA DE ADMISIONES
 * Cabecera HTML enfocada únicamente en el área de admisiones
 * 
 * @version 1.0
 * @php_version 5.6+
 */

// ============================================================================
// INICIALIZACIÓN Y CARGA DE DEPENDENCIAS
// ============================================================================
ob_start();
require('cabeceras.php');
include('funciones.php');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- ============================================================================ -->
    <!-- CONFIGURACIÓN META Y TÍTULO -->
    <!-- ============================================================================ -->
    <meta charset="utf-8" />
    <title id="titulo_plataforma"></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    
    <!-- ============================================================================ -->
    <!-- FAVICON Y ICONOS -->
    <!-- ============================================================================ -->
    <link rel="shortcut icon" href="../img/iconEnde.png">
    
    <!-- ============================================================================ -->
    <!-- HOJAS DE ESTILO PRINCIPALES -->
    <!-- ============================================================================ -->
    <!-- Componentes de interfaz -->
    <link href="assets/libs/jstree/themes/default/style.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style" />
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />

    <!-- ============================================================================ -->
    <!-- LIBRERÍAS DE TABLAS Y DATOS -->
    <!-- ============================================================================ -->
    <link href="assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/datatables.net-select-bs5/css//select.bootstrap5.min.css" rel="stylesheet" type="text/css" />

    <!-- ============================================================================ -->
    <!-- HANDSONTABLE Y CALENDARIOS -->
    <!-- ============================================================================ -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pikaday@1.8.2/css/pikaday.css">

    <!-- ============================================================================ -->
    <!-- COMPONENTES DE SELECCIÓN -->
    <!-- ============================================================================ -->
    <link href="assets/libs/multiselect/css/multi-select.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/selectize/css/selectize.bootstrap3.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet" type="text/css" />

    <!-- ============================================================================ -->
    <!-- ALERTAS Y NOTIFICACIONES -->
    <!-- ============================================================================ -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.css">
    <link href="assets/libs/toastr/build/toastr.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/mohithg-switchery/switchery.min.css" rel="stylesheet" type="text/css" />

    <!-- ============================================================================ -->
    <!-- SUBIDA DE ARCHIVOS -->
    <!-- ============================================================================ -->
    <link href="assets/libs/dropzone/min/dropzone.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/dropify/css/dropify.min.css" rel="stylesheet" type="text/css" />

    <!-- ============================================================================ -->
    <!-- CALENDARIO COMPLETO -->
    <!-- ============================================================================ -->
    <link href="assets/libs/fullcalendar/main.min.css" rel="stylesheet" type="text/css" />

    <!-- ============================================================================ -->
    <!-- FUENTES DE GOOGLE -->
    <!-- ============================================================================ -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Audiowide&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=Audiowide&display=swap" rel="stylesheet">

    <!-- ============================================================================ -->
    <!-- ANIMACIONES -->
    <!-- ============================================================================ -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <!-- ============================================================================ -->
    <!-- ESTILOS PERSONALIZADOS DEL SISTEMA -->
    <!-- ============================================================================ -->
    <style>
        /* Enlaces personalizados */
        .custom-link {
            text-decoration: none;
        }
        .custom-link:hover {
            text-decoration: underline;
        }

        /* Badges de estado */
        .badge-danger {
            background-color: #e74c3c;
            color: white;
            font-size: 13px;
            border-radius: 6px;
            padding: 3px 8px;
        }

        .badge-warning {
            background-color: #f0ad4e;
            color: black;
            font-size: 13px;
            border-radius: 6px;
            padding: 3px 8px;
        }

        .badge-success {
            background-color: #218838;
            color: white;
            font-size: 13px;
            border-radius: 6px;
            padding: 3px 8px;
        }

        .badge-primary {
            background-color: #0056b3;
            color: white;
            font-size: 13px;
            border-radius: 6px;
            padding: 3px 8px;
        }

        .badge-info {
            background-color: #138496;
            color: white;
            font-size: 13px;
            border-radius: 6px;
            padding: 3px 8px;
        }

        .badge-secondary {
            background-color: #545b62;
            color: white;
            font-size: 13px;
            border-radius: 6px;
            padding: 3px 8px;
        }

        .badge-dark {
            background-color: #343a40;
            color: white;
            font-size: 13px;
            border-radius: 6px;
            padding: 3px 8px;
        }

        /* Tipografías del sistema */
        .letraSicamInicio {
            font-family: "Audiowide", sans-serif;
            font-weight: 400;
            font-style: normal;
            font-size: 18px;
            display: block;
            margin-top: 10px;
            color: white;
        }

        .letraSicam {
            font-family: "Audiowide", sans-serif;
            font-weight: 400;
            font-style: normal;
            font-size: 14px;
            display: block;
            margin-top: 10px;
        }

        .letraMonday {
            font-family: "Archivo Black", sans-serif;
            font-weight: 400;
            font-style: normal;
            color: grey;
        }

        .letraDiminuta {
            font-size: 9px;
        }

        .letraPequena {
            font-size: 10px;
        }

        /* Posicionamiento especial */
        .prueba_posicion.show{
            position: inherit !important !important;
        }

        /* Loader del sistema */
        #loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.2);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            flex-direction: column;
        }
        #loader.hidden {
            display: none;
        }

        .spinner-border {
            animation-duration: 0.5s;
        }

        /* Fondos y selecciones */
        .bg-light {
            background-color: #e9ecef !important;
            color: black !important;
        }

        .bg-light::selection {
            background-color: #F8F9FA;
            color: black;
        }

        body.lighten {
            background-color: #FFF;
            opacity: 1;
        }

        /* Animaciones del logo */
        @keyframes fadeInLogo {
            from {
                opacity: 0;
                transform: scale(0.5);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes fadeOutLogo {
            from {
                opacity: 1;
            }
            to {
                opacity: 0;
            }
        }

        /* Overlay para modales */
        #overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>

    <!-- ============================================================================ -->
    <!-- ESTILOS CONDICIONALES POR TIPO DE USUARIO -->
    <!-- ============================================================================ -->
    <style type="text/css">
        <?php if( $tipoUsuario == 'Ejecutivo' ): ?>
            body {
                text-transform: uppercase;
            }
        <?php endif; ?>
    </style>

</head>

<body class="loading" data-layout-mode="horizontal" data-layout-color="light"
    data-layout-size="fluid" data-topbar-color="dark" data-leftbar-position="scrollable" data-leftbar-color="gradient">

    <!-- Begin page -->
    <div id="wrapper">

        <!-- ============================================================================ -->
        <!-- BARRA SUPERIOR (TOPBAR) -->
        <!-- ============================================================================ -->
        <div class="navbar-custom" style="background-color: #777; background-image: linear-gradient(to right, #777, #000);">

            <!-- Badges informativos -->
            <?php
           echo ($permisos == '1') ? '<span class="badge bg-success" style="position: fixed; top: 10px; right: -36px; transform: translate(-50%, -50%); z-index: 9999;">Permisos CDE</span>' : 
            (($permisos == '2') ? '<span class="badge bg-success" style="position: fixed; top: 10px; right: -36px; transform: translate(-50%, -50%); z-index: 9999;">Permisos AHJ ENDE</span>' : ''); 
            ?>

            <span class="badge bg-dark" style="position: fixed; top: 10px; left: 100px; transform: translate(-50%, -50%); z-index: 9999;">
                <?php echo obtenerSemanaTrabajo( $fechaHoy ); ?>
            </span>
            
            <div class="container">
                <ul class="list-unstyled topnav-menu float-end mb-0">

                    <!-- Buscador desktop -->
                    <li class="d-none d-lg-block">
                        <form class="app-search" id="formulario_buscador_citas">
                            <div class="app-search-box">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Buscar..." id="searchInput2">
                                    <button class="btn input-group-text" type="submit">
                                        <i class="fe-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </li>

                    <!-- Buscador móvil -->
                    <li class="dropdown d-inline-block d-lg-none">
                        <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light"
                            data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                            aria-expanded="false">
                            <i class="fe-search noti-icon"></i>
                        </a>
                        <div class="dropdown-menu dropdown-lg dropdown-menu-end p-0">
                            <form id="formulario_buscador_citas2" class="p-3">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Buscar..."
                                    aria-label="Buscar..." id="searchInput3">
                                </div>
                            </form>
                        </div>
                    </li>

                    <!-- ============================================================================ -->
                    <!-- NOTIFICACIONES -->
                    <!-- ============================================================================ -->
                    <?php if( $id == 2311 ): ?>
                        <!-- NOTIFICACIONES ESPECIALES -->
                        <?php 
                        $sqlNotificaciones = "SELECT * FROM notificacion_pago WHERE est_not_pag = 'Pendiente2'";
                        $resultado = $db->query($sqlNotificaciones);
                        $totalNotifiaciones = $resultado->num_rows;
                        ?>

                        <li class="dropdown notification-list topbar-dropdown">
                            <a class="nav-link dropdown-toggle waves-effect waves-light" data-bs-toggle="dropdown" href="#"
                                role="button" aria-haspopup="false" aria-expanded="false">
                                <i class="fe-bell noti-icon"></i>
                                <span class="badge bg-danger rounded-circle noti-icon-badge"><?php echo $totalNotifiaciones; ?></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end dropdown-lg">
                                <div class="dropdown-item noti-title">
                                    <h5 class="m-0">
                                        <span class="float-end"></span>Notificaciones
                                    </h5>
                                </div>

                                <div class="noti-scroll" data-simplebar>
                                    <?php while($row = $resultado->fetch_assoc()): ?>
                                        <a href="javascript:void(0);" class="dropdown-item notify-item">
                                            <div class="notify-icon bg-primary">
                                                <i class="mdi mdi-bell-outline"></i>
                                            </div>
                                            <p class="text-muted mb-0 user-msg modal_notificaciones">
                                                <small><?php echo $row['mot_not_pag']; ?></small>
                                            </p>
                                        </a>
                                    <?php endwhile; ?>
                                </div>

                                <a href="javascript:void(0);"
                                    class="dropdown-item text-center text-primary notify-item notify-all modal_notificaciones">
                                    Ver todo
                                    <i class="fe-arrow-right"></i>
                                </a>
                            </div>
                        </li>

                    <?php else: ?>
                        <!-- Notificaciones ejecutivos -->
                        <li class="dropdown notification-list topbar-dropdown" id="contenedor_notificaciones_ejecutivo">
                            <a class="nav-link dropdown-toggle waves-effect waves-light" data-bs-toggle="dropdown" href="#"
                                role="button" aria-haspopup="false" aria-expanded="false">
                                <i class="fe-bell noti-icon"></i>
                                <span class="badge bg-danger rounded-circle noti-icon-badge" id="badge_contador_notificaciones" style="background-color: #dc3545 !important; font-weight: bold;">0</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end dropdown-lg" style="background-color: #ffffff !important; opacity: 1 !important;">
                                <div class="dropdown-item noti-title">
                                    <h5 class="m-0">
                                        <span class="float-end">
                                            <a href="javascript:void(0);" class="text-dark" id="limpiar_todas_notificaciones">
                                                <small>Limpiar todo</small>
                                            </a>
                                        </span>Notificaciones
                                    </h5>
                                </div>

                                <div class="noti-scroll" data-simplebar id="lista_notificaciones_contenedor" style="max-height: 240px; overflow-y: auto;">
                                    <!-- Aquí se cargarán las notificaciones via AJAX -->
                                </div>
                                
                                <div id="loading_notificaciones" style="display: none; text-align: center; padding: 10px;">
                                    <small class="text-muted">Cargando más notificaciones...</small>
                                </div>
                            </div>
                        </li>
                    <?php endif; ?>

                    <!-- ============================================================================ -->
                    <!-- MENÚ DE USUARIO -->
                    <!-- ============================================================================ -->
                    <li class="dropdown notification-list topbar-dropdown">
                        <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light"
                            data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                            aria-expanded="false">
                            
                            <span class="pro-user-name ms-1">
                                <?php echo $nombre; ?> <i class="mdi mdi-chevron-down"></i>
                            </span>

                            <?php if( $usuario == null ): ?>
                                <?php echo obtener_rango_usuario_badge( $rangoUsuario ); ?>
                            <?php else: ?>
                                <?php echo obtener_usuario_ejecutivo($usuario, $estatusUsuario); ?>
                            <?php endif; ?>

                            <?php if( $tipoUsuario == 'Ejecutivo' && $foto == NULL ): ?>
                                <img src="<?php echo obtenerValidacionFotoUsuario( $foto ); ?>"
                                alt="user-image" class="rounded-circle" id="foto_usuario">
                            <?php elseif( $tipoUsuario == 'Ejecutivo' && $foto != NULL ): ?>
                                <img src="<?php echo obtenerValidacionFotoUsuario( $foto ); ?>"
                                alt="user-image" class="rounded-circle" id="foto_usuario">
                            <?php else: ?>
                                <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png"
                                alt="user-image" class="rounded-circle" id="foto_usuario">
                            <?php endif; ?>

                        </a>
                        <div class="dropdown-menu dropdown-menu-end profile-dropdown ">
                            <div class="dropdown-header noti-title">
                                <h6 class="text-overflow m-0">Bienvenido, <?php echo $nombre; ?></h6>
                            </div>

                            <a href="perfil.php" class="dropdown-item notify-item">
                                <i class="fe-user"></i>
                                <span>Mi perfil</span>
                            </a>

                        
                            <div class="dropdown-divider"></div>

                            <a href="cerrar_sesion.php" class="dropdown-item notify-item">
                                <i class="fe-log-out"></i>
                                <span>Cerrar sesión</span>
                            </a>

                        </div>
                    </li>

                </ul>

                <!-- ============================================================================ -->
                <!-- LOGO DEL SISTEMA -->
                <!-- ============================================================================ -->
                <div class="logo-box">
                    <a href="home.php" class="logo logo-light text-center">
                        <span class="logo-sm">
                            <img src="../img/iconEnde.png" alt="" height="44">
                        </span>
                        <span class="logo-lg">
                            <img src="../img/logoLoginEslogan.png" alt="" height="66">
                        </span>
                    </a>
                    <a href="home.php" class="logo logo-dark text-center">
                        <span class="logo-sm">
                            <img src="../img/iconEnde.png" alt="" height="44">
                        </span>
                        <span class="logo-lg">
                            <img src="../img/logoLoginEslogan.png" alt="" height="66">
                        </span>
                    </a>
                </div>

                <!-- Toggle móvil -->
                <ul class="list-unstyled topnav-menu topnav-menu-left mb-0">
                    <li>
                        <a class="navbar-toggle nav-link" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                            <div class="lines">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </a>
                    </li>
                </ul>

                <div class="clearfix"></div>
            </div>
        </div>
        <!-- end Topbar -->

        <!-- ============================================================================ -->
        <!-- NAVEGACIÓN PRINCIPAL - SOLO DROPDOWN ÁREA ADMISIONES -->
        <!-- ============================================================================ -->
        <div class="topnav">
            <div class="container-fluid">
                <nav class="navbar navbar-light navbar-expand-lg topnav-menu">
                    <div class="collapse navbar-collapse" id="topnav-menu-content">
                        <ul class="navbar-nav letraPequena">
                            
                            <!-- HOME - Solo para usuarios con permisos -->
                            <?php if( $usuario != null ): ?>
                                <li class="nav-item dropdown">
                                    <a class="nav-link arrow-none" href="home.php" id="topnav-dashboard" role="button"
                                        aria-haspopup="true" aria-expanded="false">
                                        HOME
                                    </a>
                                </li>
                            <?php endif; ?>

                            <!-- ÁREA DE ADMISIONES -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-admisiones" role="button" 
                                   data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    ÁREA ADMISIONES <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-admisiones">
                                    
                                    <!-- Citas - dentro del dropdown -->
                                    <a href="citas.php" class="dropdown-item">CITAS</a>
                                    
                                    <!-- Ejecutivos - dentro del dropdown -->
                                    <a href="ejecutivos.php" class="dropdown-item">EJECUTIVOS</a>
                                </div>
                            </li>

                        </ul>
                    </div>
                </nav>
            </div>
        </div>
        <!-- end topnav-->

        <!-- ============================================================================ -->
        <!-- INICIO DEL CONTENIDO DE LA PÁGINA -->
        <!-- ============================================================================ -->
        <div class="content-page">
            <div class="content">
                <!-- Start Content-->
                <div class="">