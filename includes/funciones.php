<?php

	function obtenerTotalAbonadoPago( $id_pag ) {
		require('conexion.php');
	
			$sqlValidacionPagado = "
			  SELECT *
			  FROM abono_pago
			  WHERE id_pag1 = '$id_pag'
			";
	
			$resultadoValidacionPagado = mysqli_query( $db, $sqlValidacionPagado );
	
			if ( $resultadoValidacionPagado ) {
			  
		  $validacionPagado = mysqli_num_rows( $resultadoValidacionPagado );
	
		  if ( $validacionPagado > 0 ) {
	
			$sqlTotalPagado = "
			  SELECT SUM(mon_abo_pag) AS totalPagado
			  FROM abono_pago
			  WHERE id_pag1 = '$id_pag'
			";
	
			$resultadoTotalPagado = mysqli_query( $db, $sqlTotalPagado );
	
			if ( $resultadoTotalPagado ) {
			  $filaTotalPagado = mysqli_fetch_assoc( $resultadoTotalPagado );
			  $totalAbonado = $filaTotalPagado['totalPagado'];
			  return $totalAbonado;
	
			} else {
			  echo $sqlTotalPagado;
			}
		  }
		} else {
		  echo $sqlValidacionPagado;
		}
	
	}
	function obtenerValidacionFotoUsuario( $foto ){

		if( ( $foto == NULL ) ){ 
		
		  return 'img/usuario2.jpg'; 
		
		} else if( ( file_exists( '../uploads/'.$foto ) != 1 ) ){ 
		  
		  return 'img/usuario2.jpg'; 
		  
		} else {
		  
		  return 'uploads/'.$foto;
		
		}
	  
	}

	function generar_primera_colegiatura($db, $id_alu_ram) {
		// Obtener datos de matrícula
		$sqlMatricula = "
			SELECT *
			FROM vista_pagos
			WHERE id_alu_ram = '$id_alu_ram'
			ORDER BY ini_pag DESC
			LIMIT 1
		";
		
		$datos = obtener_datos_consulta($db, $sqlMatricula)['datos'];
		$ini_gen = $datos['ini_gen'];
		$mon_alu_ram = $datos['mon_alu_ram'];
		
		$dia_ini_gen = date('d', strtotime($ini_gen));
		
		// Determinar fechas de inicio y fin según el día del mes
		if ($dia_ini_gen > 15) {
			// Si es después del día 15
			$ini_pag = cambiarDiaFecha($ini_gen, 27); // Día 27 del mes actual
			$fin_pag = cambiarDiaFecha(sumarUnMes($ini_gen), 5); // Día 5 del mes siguiente
		} else {
			// Si es antes del día 15
			$mes_anterior = date('Y-m-d', strtotime($ini_gen . ' -1 month'));
			$ini_pag = cambiarDiaFecha($mes_anterior, 27); // Día 27 del mes anterior
			$fin_pag = cambiarDiaFecha($ini_gen, 5); // Día 5 del mes actual
		}
		
		// Variables para el pago
		$id_alu_ram10 = $id_alu_ram;
		$fec_pag = date('Y-m-d');
		$mon_ori_pag = $mon_alu_ram;
		$mon_pag = $mon_ori_pag;
		$con_pag = 'COLEGIATURA 1';
		$est_pag = 'Pendiente';
		$res_pag = 'Sistema';
		$tip_pag = 'Colegiatura';
		
		// Agregar el pago
		agregar_pago(
			$db, 
			$id_alu_ram10, 
			$fec_pag, 
			$mon_ori_pag, 
			$mon_pag, 
			$con_pag, 
			$est_pag, 
			$res_pag, 
			$ini_pag, 
			$fin_pag, 
			$tip_pag
		);
	}


	function enviar_correo_ejecutivo($id_eje, $db) {
		// Requiere conexión y funciones
		require_once(__DIR__."/../includes/funciones.php");
		
		// Incluir PHPMailer
		require_once(__DIR__.'/../vendor/PHPMailer-master/src/PHPMailer.php');
		require_once(__DIR__.'/../vendor/PHPMailer-master/src/Exception.php');
		require_once(__DIR__.'/../vendor/PHPMailer-master/src/SMTP.php');
		
		// Obtener datos del ejecutivo
		$sql = "
			SELECT e.*, p.nom_pla 
			FROM ejecutivo e
			LEFT JOIN plantel p ON e.id_pla = p.id_pla
			WHERE e.id_eje = $id_eje
		";
		
		$datos = obtener_datos_consulta($db, $sql)['datos'];
		
		// Si no se encuentran datos, salir
		if (empty($datos)) {
			return false;
		}
		
		$ejecutivo = $datos;
		
		// Verificar que el ejecutivo tenga un correo válido
		if (empty($ejecutivo['cor2_eje'])) {
			error_log("Error: El ejecutivo ID $id_eje no tiene correo personal");
			return false;
		}
		
		// Crear la instancia de PHPMailer
		$mail = new PHPMailer\PHPMailer\PHPMailer(true);
		
		try {
			// Configuración del servidor
			$mail->isSMTP();
			$mail->Host = 'svgp291.serverneubox.com.mx';
			$mail->SMTPAuth = true;
			$mail->Username = 'contacto@ahjende.com';
			$mail->Password = 'AHJ_ENDE_2025';
			$mail->SMTPSecure = 'ssl';
			$mail->Port = 465;
			$mail->CharSet = 'UTF-8';
			
			// Remitente y destinatarios
			$mail->setFrom('contacto@ahjende.com', 'AHJ ENDE - Bienvenida Consultor');
			$mail->addAddress($ejecutivo['cor2_eje']); // Correo personal del ejecutivo
			
			// Título del correo personalizado
			$mail->Subject = '🎉 BIENVENIDO AL EQUIPO AHJ ENDE - Tu cuenta de consultor está lista';
			
			// Definir colores CORRECTOS
			$color_principal = '#0588a6';  // Azul tenue para cards menos relevantes
			$color_secundario = '#304357'; // Azul oscuro para header y elementos principales
			$color_verde = '#4caf50';      // Verde para textos significativos
			
			// Obtener primer nombre del ejecutivo
			$primer_nombre = explode(' ', $ejecutivo['nom_eje'])[0];
			
			// Crear el contenido HTML del correo con diseño limpio y profesional
			$mail->isHTML(true);
			$mail->Body = '
			<!DOCTYPE html>
			<html lang="es">
			<head>
				<meta charset="UTF-8">
				<meta name="viewport" content="width=device-width, initial-scale=1.0">
				<title>🎉 Bienvenido al Equipo AHJ ENDE</title>
				<style>
					body {
						font-family: Arial, sans-serif;
						line-height: 1.6;
						color: #333333;
						margin: 0;
						padding: 0;
						font-size: 14px;
						background-color: #ffffff;
					}
					.container {
						max-width: 600px;
						margin: 0 auto;
						background-color: #ffffff;
					}
					.header {
						background-color: '.$color_secundario.';
						padding: 20px;
						text-align: center;
					}
					.header img {
						max-width: 200px;
						filter: brightness(0) invert(1);
					}
					.content {
						padding: 20px;
						background-color: #ffffff;
					}
					h1 {
						color: '.$color_secundario.';
						margin-top: 0;
						margin-bottom: 20px;
						font-size: 20px;
						font-weight: bold;
					}
					.datos {
						background-color: #ffffff;
						border-left: 3px solid '.$color_principal.';
						padding: 15px;
						margin: 15px 0;
						border: 1px solid #e5e7eb;
						border-radius: 4px;
					}
					.footer {
						text-align: center;
						padding: 15px;
						background-color: #f8f9fa;
						font-size: 12px;
						color: #666;
					}
					.privacidad {
						margin-top: 10px;
						font-size: 11px;
					}
					.consultor {
						font-weight: bold;
						color: '.$color_verde.';
					}
					.welcome-confirmation {
						background-color: #f0f9ff;
						border: 1px solid '.$color_verde.';
						border-radius: 4px;
						padding: 12px;
						margin: 20px 0;
						text-align: center;
					}
					.welcome-confirmation .check-icon {
						color: '.$color_verde.';
						font-size: 16px;
						margin-right: 5px;
					}
					.credentials {
						margin-bottom: 8px;
					}
					.credentials strong {
						display: inline-block;
						width: 85px;
					}
					.platform-section {
						background-color: #ffffff;
						border: 1px solid '.$color_principal.';
						padding: 20px;
						margin: 25px 0;
						text-align: center;
						border-radius: 4px;
					}
					.platform-title {
						font-size: 16px;
						font-weight: bold;
						color: '.$color_verde.';
						margin: 0 0 15px 0;
					}
					.platform-link {
						display: block;
						color: '.$color_principal.';
						font-weight: bold;
						text-decoration: underline;
						font-size: 16px;
						margin: 10px 0;
					}
					.social-section {
						margin: 25px 0;
						text-align: center;
					}
					.social-title {
						font-weight: bold;
						margin-bottom: 15px;
					}
					.social-table {
						width: 100%;
						max-width: 320px;
						margin: 0 auto;
						border-spacing: 0;
						border-collapse: separate;
					}
					.social-cell {
						width: 25%;
						padding: 5px;
						text-align: center;
					}
					.social-link {
						display: inline-block;
						width: 40px;
						height: 40px;
						line-height: 40px;
						text-align: center;
						border-radius: 50%;
						color: #ffffff !important;
						font-weight: bold;
						text-decoration: none;
						font-size: 18px;
					}
					.social-text {
						display: block;
						font-size: 10px;
						margin-top: 5px;
						color: #666;
					}
					.facebook {
						background-color: #3b5998;
					}
					.instagram {
						background: linear-gradient(45deg, #405de6, #5851db, #833ab4, #c13584, #e1306c, #fd1d1d);
					}
					.tiktok {
						background-color: #000000;
					}
					.youtube {
						background-color: #ff0000;
					}
					.highlight {
						background-color: #e3f2fd;
						padding: 3px 5px;
						border-radius: 3px;
						color: '.$color_secundario.';
						font-weight: bold;
					}
					.benefits-section {
						background-color: #f8f9fa;
						border-radius: 4px;
						padding: 20px;
						margin: 20px 0;
					}
					.benefits-title {
						color: '.$color_secundario.';
						font-weight: bold;
						margin-bottom: 15px;
					}
					.benefits-list {
						list-style: none;
						padding: 0;
						margin: 0;
					}
					.benefits-list li {
						margin-bottom: 8px;
						padding-left: 25px;
						position: relative;
					}
					.benefits-list li::before {
						content: "✓";
						position: absolute;
						left: 0;
						color: '.$color_verde.';
						font-weight: bold;
					}
				</style>
			</head>
			<body>
				<div class="container">
					<div class="header">
						<img src="https://plataforma.ahjende.com/img/logoLoginEslogan.png" alt="AHJ ENDE">
					</div>
					
					<div class="content">
						<h1>¡Bienvenido al equipo, <span class="consultor">' . htmlspecialchars($primer_nombre) . '</span>! 🎉</h1>
						
						<p>✨ Nos complace darte la bienvenida a nuestro equipo de consultores profesionales. Has tomado una excelente decisión al unirte a la familia AHJ ENDE.</p>
						
						<div class="welcome-confirmation">
							<p style="margin: 0; font-weight: bold; font-size: 14px; color: '.$color_verde.';">
								<span class="check-icon">✅</span>Tu cuenta de consultor ha sido creada exitosamente
							</p>
							<p style="margin: 5px 0 0 0; font-size: 12px;">Ya formas parte del equipo líder en educación empresarial de México.</p>
						</div>
						
						<div class="datos">
							<h3 style="font-size: 15px; margin-top: 0; color: '.$color_verde.';">📋 Información de tu perfil:</h3>
							<p><strong>Nombre:</strong> ' . htmlspecialchars($ejecutivo['nom_eje']) . '</p>
							<p><strong>Rango:</strong> Consultor Educativo</p>
							<p><strong>CDE Asignado:</strong> ' . htmlspecialchars($ejecutivo['nom_pla']) . '</p>
							<p><strong>Correo Personal:</strong> ' . htmlspecialchars($ejecutivo['cor2_eje']) . '</p>
							<p><strong>Teléfono:</strong> ' . htmlspecialchars($ejecutivo['tel_eje']) . '</p>
						</div>
						
						<p>🔑 <strong style="color: '.$color_verde.';">CONSULTOR ' . htmlspecialchars($primer_nombre) . '</strong>, tus credenciales para acceder a la plataforma SICAM son:</p>
						
						<div class="datos">
							<p class="credentials"><strong>Usuario:</strong> <span class="highlight">' . htmlspecialchars($ejecutivo['cor_eje']) . '</span></p>
							<p class="credentials"><strong>Contraseña:</strong> <span class="highlight">' . htmlspecialchars($ejecutivo['pas_eje']) . '</span></p>
							<p class="credentials"><strong>Plataforma:</strong> <span class="highlight"><a href="https://plataforma.ahjende.com/" style="color: '.$color_principal.';">https://plataforma.ahjende.com/</a></span></p>
						</div>
						
						<div class="benefits-section">
							<p class="benefits-title">🚀 Ya formas parte de la comunidad AHJ ENDE:</p>
							<ul class="benefits-list">
								<li>Ayudarás a transformar vidas a través de la capacitación</li>
								<li>Sistema de gestión de registros y seguimiento</li>
								<li>Desarrollo profesional continuo</li>
								<li>Ambiente de trabajo colaborativo y dinámico</li>
								<li>Oportunidades de crecimiento dentro de la organización</li>
							</ul>
						</div>
						
						<div class="platform-section">
							<p class="platform-title">🌟 Descubre todo el ecosistema AHJ ENDE 🌟</p>
							<a href="https://ahjende.com" class="platform-link">https://ahjende.com</a>
							<p style="margin-top: 5px; font-size: 12px;">Conoce todos nuestros programas, servicios y beneficios</p>
						</div>
						
						<div class="social-section">
							<p class="social-title">✓ Síguenos en nuestras redes oficiales:</p>
							<table class="social-table">
								<tr>
									<td class="social-cell">
										<a href="https://www.facebook.com/escueladenegociosydesarrolloempresarial" class="social-link facebook">f</a>
										<span class="social-text">Facebook</span>
									</td>
									<td class="social-cell">
										<a href="https://www.instagram.com/ahjendeoficial/" class="social-link instagram">i</a>
										<span class="social-text">Instagram</span>
									</td>
									<td class="social-cell">
										<a href="https://www.tiktok.com/@ahj.endeoficial" class="social-link tiktok">t</a>
										<span class="social-text">TikTok</span>
									</td>
									<td class="social-cell">
										<a href="https://www.youtube.com/@ahj-endeescueladenegocios4351" class="social-link youtube">y</a>
										<span class="social-text">YouTube</span>
									</td>
								</tr>
							</table>
						</div>
					</div>
					
					<div class="footer">
						<p>© ' . date('Y') . ' AHJ ENDE - Todos los derechos reservados</p>
						<p class="privacidad">Revisa nuestro <a href="https://ahjende.com/aviso-de-privacidad" style="color: '.$color_principal.';">Aviso de Privacidad</a></p>
					</div>
				</div>
			</body>
			</html>
			';
			
			// Versión en texto plano
			$mail->AltBody = 
				"¡Bienvenido al equipo, {$primer_nombre}! 🎉\n\n" .
				"Nos complace darte la bienvenida a nuestro equipo de consultores profesionales. Has tomado una excelente decisión al unirte a la familia AHJ ENDE.\n\n" .
				"✅ Tu cuenta de consultor ha sido creada exitosamente\n" .
				"Ya formas parte del equipo líder en educación empresarial de México.\n\n" .
				"Información de tu perfil:\n" .
				"Nombre: {$ejecutivo['nom_eje']}\n" .
				"Rango: {$ejecutivo['ran_eje']}\n" .
				"CDE Asignado: {$ejecutivo['nom_pla']}\n" .
				"Correo Personal: {$ejecutivo['cor2_eje']}\n" .
				"Teléfono: {$ejecutivo['tel_eje']}\n\n" .
				"CONSULTOR {$primer_nombre}, tus credenciales para acceder a la plataforma son:\n" .
				"Usuario: {$ejecutivo['cor_eje']}\n" .
				"Contraseña: {$ejecutivo['pas_eje']}\n" .
				"Plataforma: https://plataforma.ahjende.com/\n\n" .
				"🚀 Como consultor de AHJ ENDE tendrás acceso a:\n" .
				"✓ Plataforma educativa líder en el mercado\n" .
				"✓ Herramientas de gestión de alumnos y cursos\n" .
				"✓ Capacitación continua y certificaciones\n" .
				"✓ Red de consultores profesionales\n" .
				"✓ Soporte técnico especializado\n" .
				"✓ Oportunidades de crecimiento profesional\n\n" .
				"🌟 Descubre todo el ecosistema AHJ ENDE 🌟\n" .
				"https://ahjende.com\n" .
				"Conoce todos nuestros programas, servicios y beneficios\n\n" .
				"Síguenos en nuestras redes oficiales:\n" .
				"Facebook: https://www.facebook.com/escueladenegociosydesarrolloempresarial\n" .
				"Instagram: https://www.instagram.com/ahjendeoficial/\n" .
				"TikTok: https://www.tiktok.com/@ahj.endeoficial\n" .
				"YouTube: https://www.youtube.com/@ahj-endeescueladenegocios4351\n\n" .
				"© " . date('Y') . " AHJ ENDE - Todos los derechos reservados\n" .
				"Revisa nuestro Aviso de Privacidad: https://ahjende.com/aviso-de-privacidad";
			
			// Enviar el correo
			$mail->send();
			
			return true;
			
		} catch (Exception $e) {
			// Registrar el error
			error_log("Error al enviar correo de bienvenida al consultor {$ejecutivo['nom_eje']} (ID: $id_eje): " . $mail->ErrorInfo);
			
			return false;
		}
	}

	function enviar_correo_alumno($id_alu_ram, $db) {
		// Requiere conexión y funciones
		require_once(__DIR__."/../includes/funciones.php");
		
		// Incluir PHPMailer
		require_once(__DIR__.'/../vendor/PHPMailer-master/src/PHPMailer.php');
		require_once(__DIR__.'/../vendor/PHPMailer-master/src/Exception.php');
		require_once(__DIR__.'/../vendor/PHPMailer-master/src/SMTP.php');
		
		// Obtener datos del alumno de vista_alumnos
		$sql = "
			SELECT * 
			FROM vista_alumnos 
			WHERE id_alu_ram = $id_alu_ram
		";
		
		$datos = obtener_datos_consulta($db, $sql)['datos'];
		
		// Si no se encuentran datos, salir
		if (empty($datos)) {
			return false;
		}
		
		$alumno = $datos; // Primer resultado
		
		// Verificar que el alumno tenga un correo válido
		if (empty($alumno['cor1_alu'])) {
			error_log("Error: El alumno ID $id_alu_ram no tiene correo electrónico");
			return false;
		}
		
		// Crear la instancia de PHPMailer
		$mail = new PHPMailer\PHPMailer\PHPMailer(true);
		
		try {
			// Configuración del servidor
			$mail->isSMTP();
			$mail->Host = 'svgp291.serverneubox.com.mx';
			$mail->SMTPAuth = true;
			$mail->Username = 'contacto@ahjende.com';
			$mail->Password = 'AHJ_ENDE_2025';
			$mail->SMTPSecure = 'ssl';
			$mail->Port = 465;
			$mail->CharSet = 'UTF-8';
			
			// Remitente y destinatarios
			$mail->setFrom('contacto@ahjende.com', 'AHJ ENDE - Inscripción');
			$mail->addAddress($alumno['cor1_alu']); // Correo personal del alumno
			
			// Título del correo personalizado con el nombre del programa
			$mail->Subject = '🚀 SOLICITUD DE INSCRIPCIÓN - ' . $alumno['nom_ram'];
			
			// Generar y adjuntar el PDF
			$pdfUrl = "https://plataforma.ahjende.com/solicitud_inscripcion.php?id_alu_ram=$id_alu_ram";
			$tempPdfPath = __DIR__ . '/../temp/solicitud_' . $id_alu_ram . '_' . time() . '.pdf';
			
			// Verificar si existe la carpeta temp
			if (!is_dir(__DIR__ . '/../temp')) {
				mkdir(__DIR__ . '/../temp', 0755, true);
			}
			
			// Obtener el PDF usando cURL
			$ch = curl_init($pdfUrl);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			$pdfContent = curl_exec($ch);
			curl_close($ch);
			
			// Guardar el PDF temporalmente y adjuntarlo
			if ($pdfContent && file_put_contents($tempPdfPath, $pdfContent)) {
				$mail->addAttachment($tempPdfPath, 'solicitud_inscripcion_' . $id_alu_ram . '.pdf');
			}
			
			// Formatear la fecha de inicio
			$fecha_inicio = fechaFormateadaCompacta4($alumno['ini_gen']);
			
			// Verificar si es programa especial (competencias)
			$programas_especiales = [364, 363, 361, 360, 359, 357];
			$es_programa_especial = in_array($alumno['id_ram3'], $programas_especiales);
			
			// Definir colores CORRECTOS
			$color_principal = '#0588a6';  // Azul tenue para cards menos relevantes
			$color_secundario = '#304357'; // Azul oscuro para header y elementos principales
			$color_verde = '#4caf50';      // Verde para textos significativos
			
			// Obtener URL de la plataforma según programa
			$url_plataforma = $es_programa_especial ? 'https://competencias.ahjende.com/' : 'https://plataforma.ahjende.com/';
			
			// Crear el contenido HTML del correo con diseño limpio y profesional
			$mail->isHTML(true);
			$mail->Body = '
			<!DOCTYPE html>
			<html lang="es">
			<head>
				<meta charset="UTF-8">
				<meta name="viewport" content="width=device-width, initial-scale=1.0">
				<title>🚀 Solicitud de Inscripción - ' . htmlspecialchars($alumno['nom_ram']) . '</title>
				<style>
					body {
						font-family: Arial, sans-serif;
						line-height: 1.6;
						color: #333333;
						margin: 0;
						padding: 0;
						font-size: 14px;
						background-color: #ffffff;
					}
					.container {
						max-width: 600px;
						margin: 0 auto;
						background-color: #ffffff;
					}
					.header {
						background-color: '.$color_secundario.';
						padding: 20px;
						text-align: center;
					}
					.header img {
						max-width: 200px;
						filter: brightness(0) invert(1);
					}
					.content {
						padding: 20px;
						background-color: #ffffff;
					}
					h1 {
						color: '.$color_secundario.';
						margin-top: 0;
						margin-bottom: 20px;
						font-size: 20px;
						font-weight: bold;
					}
					.datos {
						background-color: #ffffff;
						border-left: 3px solid '.$color_principal.';
						padding: 15px;
						margin: 15px 0;
						border: 1px solid #e5e7eb;
						border-radius: 4px;
					}
					.footer {
						text-align: center;
						padding: 15px;
						background-color: #f8f9fa;
						font-size: 12px;
						color: #666;
					}
					.privacidad {
						margin-top: 10px;
						font-size: 11px;
					}
					.lider {
						font-weight: bold;
						color: '.$color_verde.';
					}
					.payment-confirmation {
						background-color: #f0f9ff;
						border: 1px solid '.$color_verde.';
						border-radius: 4px;
						padding: 12px;
						margin: 20px 0;
						text-align: center;
					}
					.payment-confirmation .check-icon {
						color: '.$color_verde.';
						font-size: 16px;
						margin-right: 5px;
					}
					.credentials {
						margin-bottom: 8px;
					}
					.credentials strong {
						display: inline-block;
						width: 85px;
					}
					.website-section {
						background-color: #ffffff;
						border: 1px solid '.$color_principal.';
						padding: 20px;
						margin: 25px 0;
						text-align: center;
						border-radius: 4px;
					}
					.website-title {
						font-size: 16px;
						font-weight: bold;
						color: '.$color_verde.';
						margin: 0 0 15px 0;
					}
					.website-link {
						display: block;
						color: '.$color_principal.';
						font-weight: bold;
						text-decoration: underline;
						font-size: 16px;
						margin: 10px 0;
					}
					.social-section {
						margin: 25px 0;
						text-align: center;
					}
					.social-title {
						font-weight: bold;
						margin-bottom: 15px;
					}
					.social-table {
						width: 100%;
						max-width: 320px;
						margin: 0 auto;
						border-spacing: 0;
						border-collapse: separate;
					}
					.social-cell {
						width: 25%;
						padding: 5px;
						text-align: center;
					}
					.social-link {
						display: inline-block;
						width: 40px;
						height: 40px;
						line-height: 40px;
						text-align: center;
						border-radius: 50%;
						color: #ffffff !important;
						font-weight: bold;
						text-decoration: none;
						font-size: 18px;
					}
					.social-text {
						display: block;
						font-size: 10px;
						margin-top: 5px;
						color: #666;
					}
					.facebook {
						background-color: #3b5998;
					}
					.instagram {
						background: linear-gradient(45deg, #405de6, #5851db, #833ab4, #c13584, #e1306c, #fd1d1d);
					}
					.tiktok {
						background-color: #000000;
					}
					.youtube {
						background-color: #ff0000;
					}
					.highlight {
						background-color: #e3f2fd;
						padding: 3px 5px;
						border-radius: 3px;
						color: '.$color_secundario.';
						font-weight: bold;
					}
				</style>
			</head>
			<body>
				<div class="container">
					<div class="header">
						<img src="https://plataforma.ahjende.com/img/logoLoginEslogan.png" alt="AHJ ENDE">
					</div>
					
					<div class="content">
						<h1>¡Bienvenido a AHJ ENDE, LÍDER <span class="lider">' . htmlspecialchars($alumno['nom_alu']) . '</span>! 🚀</h1>
						
						<p>✨ Nos complace darte la bienvenida a nuestra comunidad educativa. Has tomado una excelente decisión al unirte a nosotros.</p>
						
						<div class="payment-confirmation">
							<p style="margin: 0; font-weight: bold; font-size: 14px; color: '.$color_verde.';">
								<span class="check-icon">✅</span>Tu pago ha sido procesado con éxito
							</p>
							<p style="margin: 5px 0 0 0; font-size: 12px;">Tu inscripción está confirmada y tus datos han sido registrados correctamente.</p>
						</div>
						
						<div class="datos">
							<h3 style="font-size: 15px; margin-top: 0; color: '.$color_verde.';">📋 Datos de tu inscripción:</h3>
							<p><strong>Programa:</strong> ' . htmlspecialchars($alumno['nom_ram']) . '</p>
							<p><strong>Grupo:</strong> ' . htmlspecialchars($alumno['nom_gen']) . '</p>
							<p><strong>Fecha de inicio:</strong> ' . $fecha_inicio . '</p>
						</div>
						
						<p>🔑 <strong style="color: '.$color_verde.';">LÍDER ' . htmlspecialchars(explode(' ', $alumno['nom_alu'])[0]) . '</strong>, tus credenciales para acceder a la plataforma son:</p>
						
						<div class="datos">
							<p class="credentials"><strong>Usuario:</strong> <span class="highlight">' . htmlspecialchars($alumno['cor_alu']) . '</span></p>
							<p class="credentials"><strong>Contraseña:</strong> <span class="highlight">' . htmlspecialchars($alumno['pas_alu']) . '</span></p>
							<p class="credentials"><strong>Plataforma:</strong> <span class="highlight"><a href="' . $url_plataforma . '" style="color: '.$color_principal.';">' . $url_plataforma . '</a></span></p>
						</div>
						
						<p>📎 Adjunto a este correo encontrarás el PDF con todos los detalles de tu inscripción.</p>
						
						<div class="website-section">
							<p class="website-title">🌟 Descubre todo lo que AHJ ENDE tiene para ti 🌟</p>
							<a href="https://ahjende.com" class="website-link">https://ahjende.com</a>
							<p style="margin-top: 5px; font-size: 12px;">Da clic en el enlace para conocer nuestros programas y beneficios</p>
						</div>
						
						<div class="social-section">
							<p class="social-title">✓ Síguenos en nuestras redes oficiales:</p>
							<table class="social-table">
								<tr>
									<td class="social-cell">
										<a href="https://www.facebook.com/escueladenegociosydesarrolloempresarial" class="social-link facebook">f</a>
										<span class="social-text">Facebook</span>
									</td>
									<td class="social-cell">
										<a href="https://www.instagram.com/ahjendeoficial/" class="social-link instagram">i</a>
										<span class="social-text">Instagram</span>
									</td>
									<td class="social-cell">
										<a href="https://www.tiktok.com/@ahj.endeoficial" class="social-link tiktok">t</a>
										<span class="social-text">TikTok</span>
									</td>
									<td class="social-cell">
										<a href="https://www.youtube.com/@ahj-endeescueladenegocios4351" class="social-link youtube">y</a>
										<span class="social-text">YouTube</span>
									</td>
								</tr>
							</table>
						</div>
					</div>
					
					<div class="footer">
						<p>© ' . date('Y') . ' AHJ ENDE - Todos los derechos reservados</p>
						<p class="privacidad">Revisa nuestro <a href="https://ahjende.com/aviso-de-privacidad" style="color: '.$color_principal.';">Aviso de Privacidad</a></p>
					</div>
				</div>
			</body>
			</html>
			';
			
			// Versión en texto plano
			$mail->AltBody = 
				"¡Bienvenido a AHJ ENDE, LÍDER {$alumno['nom_alu']}! 🚀\n\n" .
				"Nos complace darte la bienvenida a nuestra comunidad educativa. Has tomado una excelente decisión al unirte a nosotros.\n\n" .
				"✅ Tu pago ha sido procesado con éxito\n" .
				"Tu inscripción está confirmada y tus datos han sido registrados correctamente.\n\n" .
				"Datos de tu inscripción:\n" .
				"Programa: {$alumno['nom_ram']}\n" .
				"Grupo: {$alumno['nom_gen']}\n" .
				"Fecha de inicio: {$fecha_inicio}\n\n" .
				"LÍDER " . explode(' ', $alumno['nom_alu'])[0] . ", tus credenciales para acceder a la plataforma son:\n" .
				"Usuario: {$alumno['cor_alu']}\n" .
				"Contraseña: {$alumno['pas_alu']}\n" .
				"Plataforma: {$url_plataforma}\n\n" .
				"Adjunto a este correo encontrarás el PDF con todos los detalles de tu inscripción.\n\n" .
				"🌟 Descubre todo lo que AHJ ENDE tiene para ti 🌟\n" .
				"https://ahjende.com\n" .
				"Da clic en el enlace para conocer nuestros programas y beneficios\n\n" .
				"Síguenos en nuestras redes oficiales:\n" .
				"Facebook: https://www.facebook.com/escueladenegociosydesarrolloempresarial\n" .
				"Instagram: https://www.instagram.com/ahjendeoficial/\n" .
				"TikTok: https://www.tiktok.com/@ahj.endeoficial\n" .
				"YouTube: https://www.youtube.com/@ahj-endeescueladenegocios4351\n\n" .
				"© " . date('Y') . " AHJ ENDE - Todos los derechos reservados\n" .
				"Revisa nuestro Aviso de Privacidad: https://ahjende.com/aviso-de-privacidad";
			
			// Enviar el correo
			$mail->send();
			
			// Eliminar el archivo temporal del PDF
			if (file_exists($tempPdfPath)) {
				unlink($tempPdfPath);
			}
			
			return true;
			
		} catch (Exception $e) {
			// Registrar el error
			error_log("Error al enviar correo de bienvenida al LÍDER {$alumno['nom_alu']} (ID: $id_alu_ram): " . $mail->ErrorInfo);
			
			// Eliminar el archivo temporal del PDF si existe
			if (isset($tempPdfPath) && file_exists($tempPdfPath)) {
				unlink($tempPdfPath);
			}
			
			return false;
		}
	}

	// ESTE ALGORITMO PROCESA LA TRANSACCION DE STRIPE
	// BÁSICAMENTE SI EL ALUMNO PAGA, ES LA FUNCION RESPONSABLE DE DISPERSAR EL DINERO A SU INSCRIPCION CON O SIN COLEGIATURAS
	// IMPACTA TAMBIÉN EN LOS EGRESOS REFLEJANDO LA COMISION GENERADA DE LA TERMINAL
	// TAMBIÉN GENERA UNA NOTIFICACION EN EL PERFIL DEL DIRECTOR
	// -----------
	function algoritmo_pago_terminal($id_cit, $db) {
    
		// OBTENER DATOS DE LA CITA
		$sqlCita = "SELECT * FROM cita WHERE id_cit = $id_cit";
		$datosCita = obtener_datos_consulta($db, $sqlCita)['datos'];
		
		// EXTRAER VARIABLES NECESARIAS DE LA CITA CON VALIDACIÓN PARA PHP 5.6
		$mon_col_cit = isset($datosCita['mon_col_cit']) ? $datosCita['mon_col_cit'] : 0;
		$can_col_cit = isset($datosCita['can_col_cit']) ? $datosCita['can_col_cit'] : 0;
		$mon_ins_cit = isset($datosCita['mon_ins_cit']) ? $datosCita['mon_ins_cit'] : 0;
		$can_ins_cit = isset($datosCita['can_ins_cit']) ? $datosCita['can_ins_cit'] : 1;
		$con_str_cit = isset($datosCita['con_str_cit']) ? $datosCita['con_str_cit'] : '';
		$com_str_cit = isset($datosCita['com_str_cit']) ? $datosCita['com_str_cit'] : 0;
		$msi_str_cit = isset($datosCita['msi_str_cit']) ? $datosCita['msi_str_cit'] : 0;
		$id_pla_des = $datosCita['id_pla_des'];
		$id_eje3 = $datosCita['id_eje3'];
		
		// SI NO HAY COMISIÓN TOTAL, SALIR CON ERROR
		if ($com_str_cit == 0 || $com_str_cit == null) {
			echo "ERROR: La comisión total (com_str_cit) está vacía o en 0 para la cita $id_cit";
			return;
		}
		
		// OBTENER ID DEL ALUMNO Y TODOS LOS DATOS NECESARIOS
		$sqlAlumno = "
			SELECT *
			FROM alu_ram
			INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
			INNER JOIN generacion ON generacion.id_gen = alu_ram.id_gen1
			INNER JOIN rama ON rama.id_ram = generacion.id_ram5
			WHERE id_cit1 = $id_cit
		";
		$datosAlumno = obtener_datos_consulta($db, $sqlAlumno)['datos'];
		$id_alu_ram = $datosAlumno['id_alu_ram'];
		$mon_col_gen = $datosAlumno['mon_col_gen']; // Monto de colegiatura de la generación
		
		// EXTRAER DATOS DEL ALUMNO PARA LA NOTIFICACIÓN
		$nom_alu = strtoupper($datosAlumno['nom_alu']);     // Nombre del alumno
		$app_alu = strtoupper($datosAlumno['app_alu']);     // Apellido paterno
		$apm_alu = strtoupper($datosAlumno['apm_alu']);     // Apellido materno

		$tel_alu = strtoupper($datosAlumno['tel_alu']);     // Teléfono principal
		$tel2_alu = strtoupper($datosAlumno['tel2_alu']);   // Teléfono secundario

		$nom_ram = strtoupper($datosAlumno['nom_ram']);     // Nombre del programa
		$nom_gen = strtoupper($datosAlumno['nom_gen']);     // Nombre de la generación/grupo
		
		// OBTENER DATOS DEL EJECUTIVO RESPONSABLE DE LA VENTA
		$nom_eje = 'NO IDENTIFICADO'; // Valor por defecto
		if (!empty($id_eje3)) {
			$sqlEjecutivo = "
				SELECT nom_eje
				FROM ejecutivo 
				WHERE id_eje = $id_eje3
			";
			$resultadoEjecutivo = obtener_datos_consulta($db, $sqlEjecutivo);
			if (!empty($resultadoEjecutivo) && isset($resultadoEjecutivo['datos']) && !empty($resultadoEjecutivo['datos'])) {
				$nom_eje = strtoupper($resultadoEjecutivo['datos']['nom_eje']);
			}
		}
		
		// DETERMINAR EL MONTO DE COLEGIATURA A USAR PARA ACTUALIZAR mon_alu_ram
		// Si hay colegiaturas en el paquete, usar mon_col_cit
		// Si no hay colegiaturas (solo inscripción), usar mon_col_gen de la generación
		$monto_colegiatura_a_usar = ($can_col_cit > 0) ? $mon_col_cit : $mon_col_gen;
		
		// ACTUALIZAR mon_alu_ram CON EL MONTO CORRECTO
		$sqlUpdateMontoAluRam = "
			UPDATE alu_ram 
			SET mon_alu_ram = $monto_colegiatura_a_usar 
			WHERE id_alu_ram = $id_alu_ram
		";
		$resultadoUpdateMontoAluRam = mysqli_query($db, $sqlUpdateMontoAluRam);
		
		if (!$resultadoUpdateMontoAluRam) {
			echo "ERROR UPDATE mon_alu_ram: " . $sqlUpdateMontoAluRam;
			return;
		}
		
		// =========================================================================
		// CALCULAR DISPERSIÓN PROPORCIONAL DE COMISIONES
		// =========================================================================
		
		// CALCULAR MONTOS TOTALES POR CONCEPTO SEGÚN EL PAQUETE
		$total_inscripcion = $mon_ins_cit * $can_ins_cit;
		$total_colegiaturas = $mon_col_cit * $can_col_cit;
		$total_servicios = $total_inscripcion + $total_colegiaturas;
		
		// VALIDAR QUE HAYA SERVICIOS PARA EVITAR DIVISIÓN POR CERO
		if ($total_servicios == 0) {
			echo "ERROR: Total de servicios es cero, no se puede calcular la dispersión";
			return;
		}
		
		// CALCULAR PROPORCIONES Y DISPERSAR COMISIÓN PROPORCIONALMENTE
		$proporcion_inscripcion = $total_inscripcion / $total_servicios;
		$proporcion_colegiaturas = $total_colegiaturas / $total_servicios;
		
		// CALCULAR COMISIONES DISPERSADAS
		$comision_inscripcion = $com_str_cit * $proporcion_inscripcion;
		$comision_colegiaturas = $com_str_cit * $proporcion_colegiaturas;
		
		// ACTUALIZAR LA TABLA CITA CON LAS COMISIONES CALCULADAS
		$sqlUpdateComisiones = "
			UPDATE cita 
			SET 
				com_ins_cit = $comision_inscripcion,
				com_col_cit = $comision_colegiaturas,
				can_ins_cit = $can_ins_cit
			WHERE id_cit = $id_cit
		";
		$resultadoUpdateComisiones = mysqli_query($db, $sqlUpdateComisiones);
		
		if (!$resultadoUpdateComisiones) {
			echo "ERROR UPDATE COMISIONES: " . $sqlUpdateComisiones;
			return;
		}
		
		// =========================================================================
		// PROCESAR INSCRIPCIÓN
		// =========================================================================
		
		// CREAR PAGO DE INSCRIPCIÓN
		$fec_pag = date('Y-m-d');
		$sqlInscripcion = "
			INSERT INTO pago (
				fec_pag, mon_ori_pag, mon_pag, con_pag, est_pag, res_pag, 
				ini_pag, fin_pag, pro_pag, pri_pag, tip1_pag, des_pag, 
				tip2_pag, car_pag, id_alu_ram10, tip_pag
			) VALUES (
				'$fec_pag', '$mon_ins_cit', '$mon_ins_cit', 'Inscripción', 'Pendiente', 'Sistema',
				'$fec_pag', '$fec_pag', '$fec_pag', 1, 'NA', 0,
				'NA', 0, '$id_alu_ram', 'Inscripción'
			)
		";
		
		$resultadoInscripcion = mysqli_query($db, $sqlInscripcion);
		
		if (!$resultadoInscripcion) {
			echo "ERROR INSCRIPCIÓN: " . $sqlInscripcion;
			return;
		}
		
		$id_pago_inscripcion = mysqli_insert_id($db);
		
		// PAGAR INSCRIPCIÓN 
		// Esto generará automáticamente las colegiaturas necesarias usando mon_alu_ram actualizado
		agregar_abono_pago_server(
			$id_pago_inscripcion, 
			$mon_ins_cit, 
			'Depósito', 
			$mon_ins_cit, 
			'TERMINAL - STRIPE', 
			'Inscripción',
			$db
		);
		
		// =========================================================================
		// PAGAR COLEGIATURAS SEGÚN EL PAQUETE
		// =========================================================================
		
		if ($can_col_cit > 0) {
			// HAY COLEGIATURAS EN EL PAQUETE - PAGAR LAS N COLEGIATURAS ITERATIVAMENTE
			
			for ($i = 0; $i < $can_col_cit; $i++) {
				// BUSCAR LA ÚLTIMA COLEGIATURA PENDIENTE DEL ALUMNO
				$sqlUltimaColegiatura = "
					SELECT id_pag
					FROM pago 
					WHERE id_alu_ram10 = $id_alu_ram 
					AND tip_pag = 'Colegiatura' 
					AND est_pag = 'Pendiente'
					ORDER BY id_pag DESC
					LIMIT 1
				";
				
				$datosUltimaColegiatura = obtener_datos_consulta($db, $sqlUltimaColegiatura)['datos'];
				
				if (!empty($datosUltimaColegiatura)) {
					$id_colegiatura_actual = $datosUltimaColegiatura['id_pag'];
					
					// PAGAR ESTA COLEGIATURA (generará la siguiente automáticamente)
					agregar_abono_pago_server(
						$id_colegiatura_actual,
						$mon_col_cit,
						'Depósito',
						$mon_col_cit,
						'TERMINAL - STRIPE',
						'Colegiatura',
						$db
					);
				}
			}
		}
		// SI can_col_cit == 0: Solo se pagó inscripción, la primera colegiatura queda PENDIENTE
		
		// =========================================================================
		// REGISTRAR EGRESOS POR COMISIÓN SEGÚN DISPERSIÓN CALCULADA
		// =========================================================================
		
		// GENERAR EGRESO PARA INSCRIPCIÓN (SIEMPRE HAY AL MENOS UNA INSCRIPCIÓN)
		if ($total_inscripcion > 0 && $comision_inscripcion > 0) {
			// CONSTRUIR CONCEPTO DEL EGRESO DE INSCRIPCIÓN
			$concepto_egreso_ins = ($msi_str_cit > 0) 
				? "COMISIÓN STRIPE INSCRIPCIÓN $" . number_format($comision_inscripcion, 2) . " - FOLIO CITA: $id_cit - CONCEPTO: $con_str_cit - MSI: $msi_str_cit"
				: "COMISIÓN STRIPE INSCRIPCIÓN $" . number_format($comision_inscripcion, 2) . " - FOLIO CITA: $id_cit - CONCEPTO: $con_str_cit";
			
			// INSERTAR EGRESO PARA INSCRIPCIÓN CON TIPO inscripcion_deposito
			$sqlEgresoInscripcion = "
				INSERT INTO egreso (
					res_egr, for_egr, cat_egr, con_egr, mon_egr, 
					obs_egr, fec_egr, id_pla13, tip_egr
				) VALUES (
					'LIGA DE PAGO',
					'inscripcion_deposito',
					'COMISIÓN - TERMINAL - STRIPE',
					'$concepto_egreso_ins',
					'$comision_inscripcion',
					'LIGA DE PAGO',
					'" . date('Y-m-d H:i:s') . "',
					'$id_pla_des',
					'Egreso'
				)
			";
			
			$resultadoEgresoInscripcion = mysqli_query($db, $sqlEgresoInscripcion);
			
			if (!$resultadoEgresoInscripcion) {
				echo "ERROR EGRESO INSCRIPCIÓN: " . mysqli_error($db);
			}
		}
		
		// GENERAR EGRESO PARA COLEGIATURAS (SOLO SI HAY COLEGIATURAS EN EL PAQUETE)
		if ($total_colegiaturas > 0 && $comision_colegiaturas > 0) {
			// CONSTRUIR CONCEPTO DEL EGRESO DE COLEGIATURAS
			$concepto_egreso_col = ($msi_str_cit > 0) 
				? "COMISIÓN STRIPE COLEGIATURAS $" . number_format($comision_colegiaturas, 2) . " - FOLIO CITA: $id_cit - CONCEPTO: $con_str_cit - MSI: $msi_str_cit"
				: "COMISIÓN STRIPE COLEGIATURAS $" . number_format($comision_colegiaturas, 2) . " - FOLIO CITA: $id_cit - CONCEPTO: $con_str_cit";
			
			// INSERTAR EGRESO PARA COLEGIATURAS CON TIPO colegiatura_deposito
			$sqlEgresoColegiatura = "
				INSERT INTO egreso (
					res_egr, for_egr, cat_egr, con_egr, mon_egr, 
					obs_egr, fec_egr, id_pla13, tip_egr
				) VALUES (
					'LIGA DE PAGO',
					'colegiatura_deposito',
					'COMISIÓN - TERMINAL - STRIPE',
					'$concepto_egreso_col',
					'$comision_colegiaturas',
					'LIGA DE PAGO',
					'" . date('Y-m-d H:i:s') . "',
					'$id_pla_des',
					'Egreso'
				)
			";
			
			$resultadoEgresoColegiatura = mysqli_query($db, $sqlEgresoColegiatura);
			
			if (!$resultadoEgresoColegiatura) {
				echo "ERROR EGRESO COLEGIATURA: " . mysqli_error($db);
			}
		}
		
		// =========================================================================
		// ENVIAR NOTIFICACIÓN A DIRECTORES DEL PLANTEL
		// =========================================================================
		
		// BUSCAR DIRECTORES DE NEGOCIO DEL PLANTEL DESTINO
		$sqlDirectores = "
			SELECT id_eje
			FROM ejecutivo 
			WHERE usu_eje = 'DIR. NEGOCIO' 
			AND id_pla = $id_pla_des
		";
		$resultadoDirectores = mysqli_query($db, $sqlDirectores);

		// CONSTRUIR ENLACE PARA CONSULTAR AL ALUMNO
		$enlace_alumno = "https://plataforma.ahjende.com/ejecutivo/consulta_alumno.php?id_alu_ram=" . $id_alu_ram;

		$pdf_alumno = "https://plataforma.ahjende.com/ejecutivo/solicitud_inscripcion.php?id_alu_ram=" . $id_alu_ram;

		// CONSTRUIR TÍTULO DE LA NOTIFICACIÓN EN MAYÚSCULAS
		$titulo_notificacion = "NUEVO REGISTRO - $nom_alu $app_alu $apm_alu";

		// CONSTRUIR MENSAJE DETALLADO EN MAYÚSCULAS
		$detalle_msi = ($msi_str_cit > 0) ? " A $msi_str_cit MSI" : " DE CONTADO";

		$mensaje_notificacion = "SE HA PROCESADO UNA NUEVA INSCRIPCIÓN VIA TERMINAL STRIPE:\n\n";
		$mensaje_notificacion .= "ALUMNO: $nom_alu $app_alu $apm_alu\n";
		$mensaje_notificacion .= "TELÉFONO: $tel_alu - $tel2_alu\n";
		$mensaje_notificacion .= "PROGRAMA: $nom_ram\n";
		$mensaje_notificacion .= "GENERACIÓN: $nom_gen\n";
		$mensaje_notificacion .= "PAQUETE: " . strtoupper($con_str_cit) . "\n";
		$mensaje_notificacion .= "MONTO TOTAL: $" . number_format($total_servicios, 2) . "$detalle_msi\n";
		$mensaje_notificacion .= "COMISIÓN TOTAL: $" . number_format($com_str_cit, 2) . "\n";

		if ($can_col_cit > 0) {
			$mensaje_notificacion .= "COMISIÓN INSCRIPCIÓN: $" . number_format($comision_inscripcion, 2) . "\n";
			$mensaje_notificacion .= "COMISIÓN COLEGIATURAS: $" . number_format($comision_colegiaturas, 2) . "\n";
		}

		$mensaje_notificacion .= "CONSULTOR: $nom_eje\n";
		$mensaje_notificacion .= "FOLIO CITA: $id_cit\n\n";
		$mensaje_notificacion .= "CONSULTA ALUMNO: $enlace_alumno\n";
		$mensaje_notificacion .= "SOLICITUD DE INSCRIPCIÓN: $pdf_alumno\n";

		// ESCAPAR EL MENSAJE Y TÍTULO PARA SQL
		$mensaje_notificacion_escaped = mysqli_real_escape_string($db, $mensaje_notificacion);
		$titulo_notificacion_escaped = mysqli_real_escape_string($db, $titulo_notificacion);

		// ITERAR DIRECTORES CON MYSQLI_QUERY
		while ($director = mysqli_fetch_assoc($resultadoDirectores)) {
			$id_director = $director['id_eje'];

			$sqlNotificacion = "
				INSERT INTO notificacion_ejecutivo (
					tit_not_eje, men_not_eje, est_not_eje, 
					tip_not_eje, id_eje
				) VALUES (
					'$titulo_notificacion_escaped',
					'$mensaje_notificacion_escaped',
					'Pendiente',
					'pago_stripe',
					'$id_director'
				)
			";

			$resultadoNotificacion = mysqli_query($db, $sqlNotificacion);

			if (!$resultadoNotificacion) {
				echo "ERROR NOTIFICACIÓN DIRECTOR $id_director: " . mysqli_error($db);
			}
		}
	}
	// -----------


	function fechaFormateadaCompacta3( $fecha ){
		$dia = date("d", strtotime($fecha));
		$mes = substr( getMonth( date( "m", strtotime( $fecha ) ) ) , 0, 3 );
		$annio = date("Y", strtotime($fecha));


		return $dia."/".$mes;
	}

	function formatearDinero( $dinero ){

		if ( $dinero < 0 ) {
		
		$dinero = $dinero*(-1);
		
		return "-$".number_format(  $dinero,  0, '.', ',');
		
		} else {
		
		return "$".number_format(  $dinero,  0, '.', ',');
		
		}
	}
	function calcular_edad($nacimiento) {
		$fecha_nacimiento = new DateTime($nacimiento);
		$hoy = new DateTime();
		$edad = $hoy->diff($fecha_nacimiento);
		return $edad->y;
	}
	function fechaFormateadaCompacta4( $fecha ){
		$dia = date("d", strtotime($fecha));
		  $mes = substr( getMonth( date( "m", strtotime( $fecha ) ) ) , 0, 3 );
		  $annio = date("Y", strtotime($fecha));
	
	
		  return $dia."/".$mes.'/'.$annio;
	}
	
	function getMonth($mes){
	    switch ($mes) {

	      case 1:
	        return "Enero";
	        
	        break;

	      case 2:
	        return "Febrero";
	        
	        break;

	      case 3:
	        return "Marzo";
	        
	        break;

	      case 4:
	        return "Abril";
	        
	        break;

	      case 5:
	        return "Mayo";
	        
	        break;

	      case 6:
	        return "Junio";
	        
	        break;


	      case 7:
	        return "Julio";
	        
	        break;

	      case 8:
	        return "Agosto";
	        
	        break;

	      case 9:
	        return "Septiembre";
	        
	        break;
	            

	      case 10:
	        return "Octubre";
	        
	        break;

	      case 11:
	        return "Noviembre";
	        
	        break;

	      case 12:
	        return "Diciembre";
	        
	        break;
	      
	    }

	}


	function obtenerMesServer( $fecha ) {

			$mes = date("m", strtotime($fecha));


			return $mes;
	}


	function obtenerSemanaTrabajo2($fecha) {
		// Validar formato de fecha
		if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $fecha)) {
			return false;
		}
		
		// Convertir string a timestamp
		$timestamp = strtotime($fecha);
		if ($timestamp === false) {
			return false;
		}
		
		// Obtener número de semana usando ISO-8601
		// %V nos da el número de semana ISO-8601 (semanas empiezan en lunes)
		return (int)date('W', $timestamp);
	}


	function obtenerDiferenciaFechasSemanas( $fecha1, $fecha2 ){

	    $inicioEntero = strtotime( $fecha1 ) - strtotime( $fecha2 );

	    $inicio = round( ( $inicioEntero / ( 60 * 60 * 24) ) / 7 );

	      return $inicio;

	}


	function fechaFormateadaCompacta2($fecha){

	    if ( $fecha != null ) {
	      $dia = date("d", strtotime($fecha));
	      $mes = substr( getMonth( date( "m", strtotime( $fecha ) ) ) , 0, 3 );
	      $annio = date("Y", strtotime($fecha));


	      return $dia."/".$mes."/".$annio;
	    } else {
	      return 'Sin definir';
	    }
	    
	    
	}

	function obtener_datos_consulta( $db, $sql ){

	    $datos = array();
	    $datos['total'] = '';
	    $datos['datos'] = '';

	    $resultado = mysqli_query( $db, $sql );

	    if ( $resultado ) {
	      
	      $resultado2 = mysqli_query( $db, $sql );

	      $datos['total'] = mysqli_num_rows( $resultado2 );

	      $datos['datos'] = mysqli_fetch_assoc( $resultado );

	      return $datos;
	    
	    } else {

	      echo $sql;
	    
	    }
	    
	}


	function fechaHoraFormateadaCompactaIncludes($fecha){
		
		$dia = date("d", strtotime($fecha));
	  	$mes = date("m", strtotime($fecha));
	  	$annio = date("Y", strtotime($fecha));
	  	$hora = date("h:i A", strtotime($fecha));

	  	return $dia."/".$mes."/".$annio." ".$hora;
	}

	function logSesiones ( $tip_log, $usr_log, $id_usr_log, $ent_log, $id_pla10 ) {




	}
	// FIN logSesiones


	function agregar_pago( $db, $id_alu_ram10, $fec_pag, $mon_ori_pag, $mon_pag, $con_pag, $est_pag, $res_pag, $ini_pag, $fin_pag, $tip_pag ) {

		$sql = "
			INSERT INTO pago(fec_pag, mon_ori_pag, mon_pag, con_pag, est_pag, res_pag, ini_pag, fin_pag, id_alu_ram10, tip_pag ) 
			VALUES('$fec_pag', '$mon_ori_pag', '$mon_pag', '$con_pag', '$est_pag', '$res_pag', '$ini_pag', '$fin_pag', '$id_alu_ram10', '$tip_pag' )
		";
	
		$resultado = mysqli_query( $db, $sql);	
	
		if ( !$resultado ) {
			echo $sql."<br>";
			echo 'Pago NO GENERADO para: '.$id_alu_ram10."<br>";       	
		}
	}
	
	function sumarDias( $fecha, $dias ){
		return date('Y-m-d', strtotime($fecha . ' + '.$dias.' days'));
	}
	
	function restarDias( $fecha, $dias ){
	  return date('Y-m-d', strtotime($fecha . ' - '.$dias.' days'));
	}
	
	function sumarUnMes ( $fecha ){
		return date("Y-m-d",strtotime($fecha."+ 1 month")); 
	}
	
	function cambiarDiaFecha ($fecha, $dia){
		$dia_fijo = $dia;
		$mes = date("m",strtotime($fecha));
		//echo $mes;
		$anio = date("Y", strtotime($fecha));
		$formato = $dia_fijo.'-'.$mes.'-'.$anio;
		//echo $formato;
		$result =  date("Y-m-d",strtotime($formato));
		
		return $result;
	}


	// Función auxiliar para calcular la diferencia en meses entre dos fechas
	function calcularDiferenciaMeses($fechaInicio, $fechaFin) {
		$inicio = new DateTime($fechaInicio);
		$fin = new DateTime($fechaFin);
		$diff = $inicio->diff($fin);
		$meses = ($diff->y * 12) + $diff->m;
		if ($diff->d > 0) {
			$meses++;
		}
		return $meses;
	}



	function agregar_abono_pago_server( $id_pag, $mon_pag, $tip_abo_pag, $mon_abo_pag, $nomResponsable, $tip_pag, $db ){

		// 
		$sqlAlumno = "
			SELECT *
			FROM pago
			INNER JOIN alu_ram ON alu_ram.id_alu_ram = pago.id_alu_ram10
			INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
			WHERE id_pag = '$id_pag'
		";

		$resultadoAlumno = mysqli_query( $db, $sqlAlumno );

		$filaAlumno = mysqli_fetch_assoc( $resultadoAlumno );

		$id_alu_ram = $filaAlumno['id_alu_ram'];
		$con_pag = $filaAlumno['con_pag'];
		$abono = $mon_abo_pag;


		//echo $mon_pag;
		$fechaHoy = date('Y-m-d');

		$montoAbonado = $mon_abo_pag;
		$diferencia = $mon_pag - $montoAbonado;
		// diferenciaCondonacion = montoAdeudo-cantidadCondonacion;
		
		// UNA VEZ OBTENEMOS LA DIFERENCIA, ES NECESARIO CONOCER SI ES IGUAL O MENOR
		// echo "diferencia: ".$diferencia;
		// echo "<br>";
		// echo "mon_pag: ".$mon_pag;
		// echo "<br>";
		// echo "mon_abo_pag: ".$mon_abo_pag;
		if ( $diferencia > 0 ) {
		// PAGO PENDIENTE
			//echo "pendiente";
			$mon_pag = $diferencia;

			$sqlUpdatePago = "
				UPDATE pago
				SET
				mon_pag = '$mon_pag'
				WHERE 
				id_pag = '$id_pag'
			";

			$resultadoUpdatePago = mysqli_query( $db, $sqlUpdatePago );

			if ( $resultadoUpdatePago ) {

				// HISTORIAL
				$con_his_pag = "Abono por $".$montoAbonado." realizado por ".$nomResponsable." la fecha del ".fechaFormateadaCompacta($fechaHoy).".";

				$fec_his_pag = $fechaHoy;

				$res_his_pag = $nomResponsable;

				$est_his_pag = 'Pendiente';

				$tip_his_pag = "Parcialidad";

				$med_his_pag = "Sistema";

				$id_pag4 = $id_pag;


				// INSERCION HISTORIAL
				$sqlInsercionHistorial = "
					INSERT INTO historial_pago( con_his_pag, fec_his_pag, res_his_pag, est_his_pag, tip_his_pag, med_his_pag, id_pag4 ) 
					VALUES( '$con_his_pag', '$fec_his_pag', '$res_his_pag', '$est_his_pag', '$tip_his_pag', '$med_his_pag', '$id_pag4' )
				";

				$resultadoInsercionHistorial = mysqli_query( $db, $sqlInsercionHistorial );

				if ( !$resultadoInsercionHistorial ) {
					echo $sqlInsercionHistorial;
				}else{
					$sqlInsercionAbono = "
						INSERT INTO abono_pago( mon_abo_pag, fec_abo_pag, tip_abo_pag, res_abo_pag, id_pag1 ) 
						VALUES( '$mon_abo_pag', '$fechaHoy', '$tip_abo_pag', '$nomResponsable', '$id_pag' )

					";

					$resultadoInsercionAbono = mysqli_query( $db, $sqlInsercionAbono );

					if ( $resultadoInsercionAbono ) {

						// LOG
						// $nombreAlumno = obtenerNombreAlumnoServer ( $id_alu_ram );

						// $des_log = obtenerDescripcionAbonosLogServer( $tipoUsuario, $nomResponsable, 'abono', $abono, $con_pag, $nombreAlumno  );

						// logServer ( 'Alta', $tipoUsuario, $id, 'Abono', $des_log, $plantel );

						// FIN LOG

						if ( $tip_abo_pag == 'Saldo_Digital' ) {
							
							$sqlAlumno = "
								SELECT *
								FROM pago
								INNER JOIN alu_ram ON alu_ram.id_alu_ram = pago.id_alu_ram10
								INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
								WHERE id_pag = '$id_pag'
							";

							$resultadoAlumno = mysqli_query( $db, $sqlAlumno );

							$filaAlumno = mysqli_fetch_assoc( $resultadoAlumno );

							$id_alu = $filaAlumno['id_alu'];
							$sal_alu = $filaAlumno['sal_alu'];

							$saldoFinal = $sal_alu - $mon_abo_pag;

							$sqlUpdateAlumno = "
								UPDATE alumno
								SET
								sal_alu = '$saldoFinal'
								WHERE 
								id_alu = '$id_alu'
							";

							$resultadoUpdateAlumno = mysqli_query( $db, $sqlUpdateAlumno );

							if ( $resultadoUpdateAlumno ) {
								
								// HISTORIAL SALDO
								$con_his_sal = "Se egresó saldo digital por la cantidad de $".$montoAbonado;

								$fec_his_sal = $fechaHoy;

								$res_his_sal = $nomResponsable;

								$est_his_sal = 'Pendiente';

								$tip_his_sal = "Egreso";

								$id_alu3 = $id_alu;


								// INSERCION HISTORIAL
								$sqlInsercionHistorialSaldo = "
									INSERT INTO historial_saldo ( con_his_sal, fec_his_sal, res_his_sal, est_his_sal, tip_his_sal,  id_alu3 ) 
									VALUES( '$con_his_sal', '$fec_his_sal', '$res_his_sal', '$est_his_sal', '$tip_his_sal', '$id_alu3' )
								";

								$resultadoInsercionHistorialSaldo = mysqli_query( $db, $sqlInsercionHistorialSaldo );

								if ( $resultadoInsercionHistorialSaldo ) {									

									echo "Exito";

								} else {
									echo $sqlInsercionHistorialSaldo;
								}


							} else {
								echo $sqlUpdateAlumno;
							}


						}
						
					}else{
						echo $sqlInsercionAbono;
					}
				}
				


				// FIN HISTORIAL
			}else{
				echo $sqlUpdatePago;
			}


		//FIN PAGO PENDIENTE
		}else{
			// PAGO PAGADO
			//echo "saldado";
			$mon_pag = $diferencia;
			$est_pag = 'Pagado';
			$pag_pag = $fechaHoy;

			$sqlUpdatePago = "
				UPDATE pago
				SET
				mon_pag = '$mon_pag',
				est_pag = '$est_pag',
				pag_pag = '$pag_pag'
				WHERE 
				id_pag = '$id_pag'
			";

			$resultadoUpdatePago = mysqli_query( $db, $sqlUpdatePago );

			if( $tip_pag == 'Colegiatura' || $tip_pag == 'Inscripción' ){
				calendario_pagos( $id_alu_ram, $db );
			}

			if ( $resultadoUpdatePago ) {

				// HISTORIAL
				$con_his_pag = "Abono por $".$montoAbonado." realizado por ".$nomResponsable." la fecha del ".fechaFormateadaCompacta($fechaHoy)." liquidando el saldo pendiente.";

				$fec_his_pag = $fechaHoy;

				$res_his_pag = $nomResponsable;

				$est_his_pag = 'Pendiente';

				$tip_his_pag = "Liquidación";

				$med_his_pag = "Sistema";

				$id_pag4 = $id_pag;


				// INSERCION HISTORIAL
				$sqlInsercionHistorial = "
					INSERT INTO historial_pago( con_his_pag, fec_his_pag, res_his_pag, est_his_pag, tip_his_pag, med_his_pag, id_pag4 ) 
					VALUES( '$con_his_pag', '$fec_his_pag', '$res_his_pag', '$est_his_pag', '$tip_his_pag', '$med_his_pag', '$id_pag4' )
				";

				$resultadoInsercionHistorial = mysqli_query( $db, $sqlInsercionHistorial );

				if ( !$resultadoInsercionHistorial ) {
					echo $sqlInsercionHistorial;
				}else{
					
					$sqlInsercionAbono = "
						INSERT INTO abono_pago( mon_abo_pag, fec_abo_pag, tip_abo_pag, res_abo_pag, id_pag1 ) 
						VALUES( '$mon_abo_pag', '$fechaHoy', '$tip_abo_pag', '$nomResponsable', '$id_pag' )

					";

					$resultadoInsercionAbono = mysqli_query( $db, $sqlInsercionAbono );

					if ( $resultadoInsercionAbono ) {


						// LOG
						//$nombreAlumno = obtenerNombreAlumnoServer ( $id_alu_ram );

						// $des_log = obtenerDescripcionAbonosLogServer( $tipoUsuario, $nomResponsable, 'abono', $abono, $con_pag, $nombreAlumno  );

						// logServer ( 'Alta', $tipoUsuario, $id, 'Abono', $des_log, $plantel );

						// FIN LOG

						
						if ( $tip_abo_pag == 'Saldo_Digital' ) {
							
							$sqlAlumno = "
								SELECT *
								FROM pago
								INNER JOIN alu_ram ON alu_ram.id_alu_ram = pago.id_alu_ram10
								INNER JOIN alumno ON alumno.id_alu = alu_ram.id_alu1
								WHERE id_pag = '$id_pag'
							";

							$resultadoAlumno = mysqli_query( $db, $sqlAlumno );

							$filaAlumno = mysqli_fetch_assoc( $resultadoAlumno );

							$id_alu = $filaAlumno['id_alu'];
							$sal_alu = $filaAlumno['sal_alu'];

							$saldoFinal = $sal_alu - $mon_abo_pag;

							$sqlUpdateAlumno = "
								UPDATE alumno
								SET
								sal_alu = '$saldoFinal'
								WHERE 
								id_alu = '$id_alu'
							";

							$resultadoUpdateAlumno = mysqli_query( $db, $sqlUpdateAlumno );

							if ( $resultadoUpdateAlumno ) {
								
								// HISTORIAL SALDO
								$con_his_sal = "Se egresó saldo digital por la cantidad de $".$montoAbonado;

								$fec_his_sal = $fechaHoy;

								$res_his_sal = $nomResponsable;

								$est_his_sal = 'Pendiente';

								$tip_his_sal = "Egreso";

								$id_alu3 = $id_alu;


								// INSERCION HISTORIAL
								$sqlInsercionHistorialSaldo = "
									INSERT INTO historial_saldo ( con_his_sal, fec_his_sal, res_his_sal, est_his_sal, tip_his_sal,  id_alu3 ) 
									VALUES( '$con_his_sal', '$fec_his_sal', '$res_his_sal', '$est_his_sal', '$tip_his_sal', '$id_alu3' )
								";

								$resultadoInsercionHistorialSaldo = mysqli_query( $db, $sqlInsercionHistorialSaldo );

								if ( $resultadoInsercionHistorialSaldo ) {
									
									
									echo "Exito";

								} else {
									echo $sqlInsercionHistorialSaldo;
								}


							} else {
								echo $sqlUpdateAlumno;
							}


						}

						
					}else{
						echo $sqlInsercionAbono;
					}
				}
				

				//FIN HISTORIAL
			}else{
				echo $sqlUpdatePago;
			}


		// FIN PAGO PAGADO
		}
		// 
	}



	function calendario_pagos($id_alu_ram, $db) {
		// FUNCIÓN QUE CARGA PAGOS DE COLEGIATURA
	
		// OBTENER DATOS DE LA ÚLTIMA COLEGIATURA O INSCRIPCIÓN
		$sqlMatricula = "
			SELECT * 
			FROM vista_pagos
			WHERE id_alu_ram = '$id_alu_ram' AND tip_pag = 'Colegiatura'
			ORDER BY ini_pag DESC
			LIMIT 1
		";
	
		$validacionColegiatura = obtener_datos_consulta($db, $sqlMatricula)['total'];
	
		if ($validacionColegiatura == 0) {
			$sqlMatricula = "
				SELECT * 
				FROM vista_pagos
				WHERE id_alu_ram = '$id_alu_ram' AND tip_pag = 'Inscripción'
				ORDER BY ini_pag DESC
				LIMIT 1
			";
		}
	
		$resultado = obtener_datos_consulta($db, $sqlMatricula);
		$datos = $resultado['datos'];
	
		$id_alu_ram = $datos['id_alu_ram'];
		$estatus_general = $datos['estatus_general'];
		$estatus_academico = $datos['estatus_academico'];
		$ini_gen = $datos['ini_gen'];
		$fin_gen = $datos['fin_gen'];
		$conteo_colegiaturas = (int)$datos['conteo_colegiaturas'];
		$conteo_tramites = $datos['conteo_tramites'];
		$conteo_inscripciones = $datos['conteo_inscripciones'];
		$ini_pag = $datos['ini_pag'];
		$mon_alu_ram = $datos['mon_alu_ram'];
		$ini_pag_aux = $datos['fin_pag'];
	
		// Calcular la cantidad máxima de colegiaturas
		$max_colegiaturas = calcularDiferenciaMeses($ini_gen, $fin_gen);
	
		// Verificar que aún no se haya alcanzado el número máximo de colegiaturas
		if ($conteo_colegiaturas >= $max_colegiaturas) {
			// Ya no generar más colegiaturas porque se alcanzó el tope
			return;
		}
	
		// Determinar la fecha de inicio del nuevo pago
		if ($conteo_colegiaturas == 0) {
			// PRIMERA COLEGIATURA: Fecha de inicio es el 27 del mes anterior a ini_gen
			$mes_anterior = date('Y-m-d', strtotime($ini_gen . ' -1 month')); // Mes anterior
			$ini_pag_nueva = cambiarDiaFecha($mes_anterior, 27); // Día 27 del mes anterior
			$fin_pag = cambiarDiaFecha($ini_gen, 5); // Día 5 del mes actual (ini_gen)
		} else {
			// COLEGIATURA 2 EN ADELANTE: Fecha de inicio es el 1 del mes siguiente al último pago
			$ini_pag_nueva = cambiarDiaFecha(sumarUnMes($ini_pag_aux), 1);
			$fin_pag = cambiarDiaFecha(sumarUnMes($ini_pag_aux), 5); // Día 5 del mes siguiente
		}
	
		// Validar que la fecha de inicio no sea mayor que fin_gen
		if (strtotime($ini_pag_nueva) <= strtotime($fin_gen)) {
			// Podemos generar la nueva colegiatura
			$id_alu_ram10 = $id_alu_ram;
			$fec_pag = date('Y-m-d');
			$mon_ori_pag = $mon_alu_ram;
			$mon_pag = $mon_ori_pag;
			$con_pag = 'COLEGIATURA ' . ($conteo_colegiaturas + 1);
			$est_pag = 'Pendiente';
			$res_pag = 'Sistema';
			$ini_pag = $ini_pag_nueva;
			$tip_pag = 'Colegiatura';
	
			// Validar si el pago ya existe antes de agregarlo
			if (validar_alta_pago($id_alu_ram10, $ini_pag_aux, $db) == 0) {
				agregar_pago(
					$db,
					$id_alu_ram10,
					$fec_pag,
					$mon_ori_pag,
					$mon_pag,
					$con_pag,
					$est_pag,
					$res_pag,
					$ini_pag,
					$fin_pag,
					$tip_pag
				);
			}
		}
	}


	function validar_alta_pago ($id_alu_ram, $fecha_pago, $db){
		
		$sql_pago = "SELECT valida_carga_pago('$id_alu_ram', '$fecha_pago') AS total";
		$validacion_pago = obtener_datos_consulta( $db, $sql_pago )['datos']['total'];
		return $validacion_pago;
	
	}


	function fechaFormateadaCompacta($fecha){
		$dia = date("d", strtotime($fecha));
	  	$mes = date("m", strtotime($fecha));
	  	$annio = date("Y", strtotime($fecha));


	  	return $dia."/".$mes."/".$annio;
	}
	

?>