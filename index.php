<?php
/**
 * SISTEMA SICAM - MÓDULO DE AUTENTICACIÓN
 * Manejo de login 
 * 
 * @version 1.0
 */

// ============================================================================
// CONFIGURACIÓN INICIAL DEL SISTEMA
// ============================================================================

require_once(__DIR__ . "/includes/funciones.php");
session_start();

// Configuración dinámica basada en dominio
$current_domain = $_SERVER['HTTP_HOST'];
$site_name = 'SICAM_AMBIENTE_1';

// ============================================================================
// CONFIGURACIÓN DE CONEXIÓN A BASE DE DATOS
// ============================================================================

// Parámetros centralizados de conexión
$db_host = '49.12.79.33';
$db_port = '3306';
$db_name = 'sicam_ambiente_1';
$db_user = 'wpuser';
$db_pass = 'WP!19';

// Establecer conexión PDO con manejo de errores
try {
    $db = new PDO("mysql:host={$db_host};port={$db_port};dbname={$db_name};charset=utf8", $db_user, $db_pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("SICAM DB ERROR: " . $e->getMessage());
    echo "ERROR DE CONEXIÓN A LA BASE DE DATOS: " . $e->getMessage();
    exit();
}

// ============================================================================
// PROCESAMIENTO DE AUTENTICACIÓN
// ============================================================================

$errores = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitización y validación de entrada
    $correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);

    // Validación básica de campos requeridos
    if (empty($correo) || empty($password)) {
        $errores = "POR FAVOR, COMPLETA TODOS LOS CAMPOS";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores = "FORMATO DE CORREO ELECTRÓNICO INVÁLIDO";
    } else {
        // ====================================================================
        // CONSULTA UNIFICADA DE AUTENTICACIÓN MULTI-ROL
        // Utiliza UNION para buscar en las tres tablas de usuarios principales
        // Eliminados: Encuesta y Servicios (solo Ejecutivo, Profesor, Alumno)
        // ====================================================================
        
        $sql = '
            SELECT 
                cor_eje AS correo, 
                pas_eje AS password, 
                "Ejecutivo" AS tipo, 
                nom_eje AS nombre, 
                app_eje AS apellidoPaterno, 
                apm_eje AS apellidoMaterno, 
                fot_eje AS foto, 
                id_eje AS id, 
                id_pla AS id_pla 
            FROM 
                ejecutivo 
            WHERE 
                cor_eje = :correo AND pas_eje = :password

            UNION

            SELECT 
                cor_alu AS correo, 
                pas_alu AS password, 
                tip_alu AS tipo, 
                nom_alu AS nombre, 
                app_alu AS apellidoPaterno, 
                apm_alu AS apellidoMaterno, 
                fot_alu AS foto, 
                id_alu AS id, 
                id_pla8 AS id_pla 
            FROM 
                alumno 
            WHERE 
                cor_alu = :correo AND pas_alu = :password

            UNION

            SELECT 
                cor_pro AS correo, 
                pas_pro AS password, 
                tip_pro AS tipo, 
                nom_pro AS nombre, 
                app_pro AS apellidoPaterno, 
                apm_pro AS apellidoMaterno, 
                fot_pro AS foto, 
                id_pro AS id, 
                id_pla2 AS id_pla 
            FROM 
                profesor 
            WHERE 
                cor_pro = :correo AND pas_pro = :password
        ';

        try {
            // Preparar y ejecutar la consulta SQL
            $statement = $db->prepare($sql);
            $statement->execute(array(
                ':correo' => $correo,
                ':password' => $password
            ));

            // Obtener el resultado de la consulta
            $resultado = $statement->fetch(PDO::FETCH_ASSOC);

            // ================================================================
            // PROCESAMIENTO DE RESULTADO DE AUTENTICACIÓN
            // ================================================================
            
            if ($resultado !== false && !empty($resultado)) {
                // Inicializar sesión del usuario con datos relevantes
                $_SESSION['rol'] = array(
                    'id' => (int)$resultado['id'],
                    'tipo' => $resultado['tipo'],
                    'nombre' => trim($resultado['nombre']),
                    'correo' => $resultado['correo'],
                    'foto' => $resultado['foto'],
                    'apellidoPaterno' => isset($resultado['apellidoPaterno']) ? $resultado['apellidoPaterno'] : '',
                    'apellidoMaterno' => isset($resultado['apellidoMaterno']) ? $resultado['apellidoMaterno'] : '',
                    'id_pla' => (int)$resultado['id_pla']
                );

                $_SESSION['login'] = true;
                $_SESSION['primera_visita'] = true;

                // Verificar si la sesión se inicializó correctamente
                if (!isset($_SESSION['rol'])) {
                    $errores = "ERROR AL INICIALIZAR LA SESIÓN";
                } else {
                    // ========================================================
                    // SISTEMA DE REDIRECCIÓN OPTIMIZADO POR TIPO DE USUARIO
                    // Solo incluye: Ejecutivo, Profesor, Alumno
                    // ========================================================
                    
                    switch ($resultado['tipo']) {
                        case 'Ejecutivo':
                            header('Location: ejecutivo/home.php');
                            break;
                        case 'Profesor':
                            header('Location: profesor/');
                            break;
                        case 'Alumno':
                            header('Location: alumno/');
                            break;
                        default:
                            header('Location: usuario/');
                            break;
                    }
                    
                    // Log de acceso exitoso para auditoría
                    error_log("SICAM LOGIN: Usuario {$resultado['correo']} ({$resultado['tipo']}) autenticado exitosamente");
                    
                    exit();
                }
            } else {
                $errores = "USUARIO O CONTRASEÑA INCORRECTOS";
            }
        } catch (PDOException $e) {
            $errores = "ERROR EN EL SISTEMA. INTENTE MÁS TARDE.";
            error_log("ERROR SQL DE INICIO DE SESIÓN: " . $e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo htmlspecialchars($site_name); ?> - INICIO DE SESIÓN</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        /* ESTILOS GENERALES DEL CUERPO Y CONTENEDOR DE LOGIN */
        body {
            background: #f0f4f8; /* AZUL CLARO MINIMALISTA */
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #334e68; /* COLOR DE TEXTO BASE */
        }

        .login-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
            overflow: hidden;
            width: 100%;
            max-width: 400px; /* ANCHO LIGERAMENTE REDUCIDO */
            margin: 0 auto;
            position: relative; /* PARA POSICIONAR EL BADGE */
        }

        /* ESTILOS DEL BADGE DE SICAM */
        .sicam-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background-color: #2d3748; /* NEGRO GRISÁCEO */
            color: white;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 0.75em;
            font-weight: 500;
            letter-spacing: 0.5px;
            z-index: 10;
        }

        /* ESTILOS DEL CUERPO DEL FORMULARIO DE LOGIN */
        .login-body {
            padding: 2.5rem;
            background: white;
            text-align: center; /* CENTRAR CONTENIDO DEL CUERPO */
        }

        .login-body h3 {
            color: #2a69ac; /* AZUL PRINCIPAL */
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .login-body p {
            color: #718096;
            margin-bottom: 2rem;
            font-size: 0.95rem;
        }

        /* ESTILOS DE LOS CAMPOS DE FORMULARIO */
        .form-control {
            border-radius: 6px;
            border: 1px solid #cbd5e0; /* BORDE MÁS SUAVE */
            padding: 12px 16px;
            transition: all 0.2s ease-in-out;
            font-size: 0.95rem;
            color: #4a5568;
            background-color: #f7fafc; /* FONDO LIGERAMENTE GRIS */
        }

        .form-control:focus {
            border-color: #4299e1; /* AZUL MÁS BRILLANTE AL ENFOQUE */
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.2);
            background-color: white;
        }

        /* ESTILOS DEL BOTÓN DE LOGIN */
        .btn-login {
            background-color: #3182ce; /* AZUL SÓLIDO */
            border: none;
            border-radius: 6px;
            padding: 12px 30px;
            color: white;
            font-weight: 600;
            transition: background-color 0.2s ease-in-out, transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            width: 100%;
            letter-spacing: 0.5px;
        }

        .btn-login:hover {
            background-color: #2b6cb0; /* AZUL OSCURO AL HOVER */
            color: white;
            transform: translateY(-2px); /* LIGERO EFECTO DE ELEVACIÓN */
            box-shadow: 0 4px 15px rgba(49, 130, 206, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
            box-shadow: none;
        }

        /* ESTILOS DE ALERTAS PERSONALIZADAS */
        .alert-custom {
            border-radius: 6px;
            border: 1px solid #fc8181; /* BORDE ROJO SUAVE */
            background: #fff5f5; /* FONDO ROJO CLARO */
            color: #c53030; /* TEXTO ROJO OSCURO */
            border-left: 4px solid #ef4444; /* BARRA IZQUIERDA ROJA */
            padding: 1rem 1.5rem;
            font-size: 0.9em;
        }

        /* ESTILOS DEL ICONO DE MOSTRAR/OCULTAR CONTRASEÑA */
        .input-group-text {
            background: #f7fafc;
            border: 1px solid #cbd5e0;
            border-left: none;
            cursor: pointer;
            border-radius: 0 6px 6px 0;
            transition: all 0.2s ease-in-out;
            color: #718096;
        }

        .input-group-text:hover {
            background: #edf2f7;
            color: #3182ce;
        }

        /* ESTILOS DE ETIQUETAS DE FORMULARIO */
        .form-label {
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 8px;
            font-size: 0.85rem;
            text-align: left; /* ALINEAR ETIQUETAS A LA IZQUIERDA */
            display: block;
        }

        /* ESTILOS DEL TEXTO DE AYUDA */
        .help-text {
            background: #ebf8ff; /* AZUL CLARO SUAVE */
            border-radius: 6px;
            padding: 1rem;
            margin-top: 2rem;
            border-left: 4px solid #4299e1; /* BARRA AZUL */
            font-size: 0.85rem;
            color: #5a67d8;
        }

        /* MEDIA QUERIES PARA ADAPTAR EL DISEÑO A PANTALLAS PEQUEÑAS */
        @media (max-width: 576px) {
            .login-container {
                margin: 20px;
            }

            .login-body {
                padding: 1.5rem;
            }
        }

        /* ANIMACIÓN DE CARGA PARA BOTÓN */
        .btn-loading {
            position: relative;
            color: transparent !important;
            pointer-events: none; /* DESHABILITAR INTERACCIÓN DURANTE LA CARGA */
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin-left: -10px;
            margin-top: -10px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="login-container">
                    <span class="sicam-badge">SICAM</span>

                    <div class="login-body">
                        <p class="text-muted">AMBIENTE_1</p>

                        <?php if (!empty($errores)) : ?>
                            <div class="alert alert-danger alert-custom mb-4">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <?php echo htmlspecialchars($errores); ?>
                            </div>
                        <?php endif; ?>

                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" id="loginForm">
                            <div class="mb-3">
                                <label for="correo" class="form-label">CORREO ELECTRÓNICO</label>
                                <input type="email" class="form-control" id="correo" name="correo" placeholder="ingresa tu correo" value="<?php echo isset($_POST['correo']) ? htmlspecialchars($_POST['correo']) : ''; ?>" required autofocus>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">CONTRASEÑA</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="ingresa tu contraseña" required>
                                    <span class="input-group-text" id="togglePassword">
                                        <i class="fas fa-eye-slash"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-login" id="btnSubmit">
                                    <i class="fas fa-sign-in-alt me-2"></i>INICIAR SESIÓN
                                </button>
                            </div>
                        </form>

                        <div class="help-text text-center">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                SI TIENES PROBLEMAS PARA ACCEDER, CONTACTA AL ADMINISTRADOR DEL SISTEMA
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // ====================================================================
        // FUNCIONALIDAD DE TOGGLE PARA VISIBILIDAD DE CONTRASEÑA
        // ====================================================================
        document.getElementById('togglePassword').addEventListener('click', function() {
            const password = document.getElementById('password');
            const icon = this.querySelector('i');

            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                password.type = 'password';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        });

        // ====================================================================
        // MANEJO DE ESTADO DE CARGA EN FORMULARIO DE LOGIN
        // Previene múltiples envíos y proporciona feedback visual
        // ====================================================================
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btnSubmit = document.getElementById('btnSubmit');
            btnSubmit.classList.add('btn-loading');
            btnSubmit.disabled = true;

            // Timeout de seguridad para restaurar botón
            setTimeout(function() {
                btnSubmit.classList.remove('btn-loading');
                btnSubmit.disabled = false;
            }, 5000);
        });
    </script>
</body>

</html>