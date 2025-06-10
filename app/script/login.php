<?php
#session_start();
#include("conexion.php");
$fecha_creacion = date('Y-m-d');
$asunto = $_POST['asunto'];

	if($asunto=="login"){
		$documento = $_POST['documento'];
		$password = md5($_POST['password']);
		
		$datos = [
			"estatus"	=> "ok",
		];
		echo json_encode($datos);

		/*
		$sql1 = "SELECT * FROM usuarios WHERE usuario = '$usuario' and password = '$password' LIMIT 1";
		$proceso1 = mysqli_query($conexion,$sql1);
		$contador1 = mysqli_num_rows($proceso1);
		if($contador1==0){
			$datos = [
				"estatus"	=> "error",
				"msg"	=> "Credenciales Incorrectas",
			];
			echo json_encode($datos);
			exit;
		}else if($contador1>=1){
			while($row1=mysqli_fetch_array($proceso1)){
				$usuarioId = $row1["id"];
				$rol = $row1["rol"];
			}

			$redireccion = "admin.php";
			session_start();
			$_SESSION["systemDiazStoreId"] = $usuarioId;
			$_SESSION["systemDiazStoreRol"] = $rol;
			
			$datos = [
				"estatus"	=> "ok",
				"redireccion"	=> $redireccion,
			];
			echo json_encode($datos);
		}
		*/
	}

	if($asunto=='table1'){
		$pagina = $_POST["pagina"];
		$consultasporpagina = $_POST["consultasporpagina"];
		$filtrado = $_POST["filtrado"];

		if($pagina==0 or $pagina==''){
			$pagina = 1;
		}

		if($consultasporpagina==0 or $consultasporpagina==''){
			$consultasporpagina = 10;
		}

		if($filtrado!=''){
			$filtrado = ' and (identificacion LIKE "%'.$filtrado.'%" or nombre LIKE "%'.$filtrado.'%" or telefono LIKE "%'.$filtrado.'%")';
		}

		$limit = $consultasporpagina;
		$offset = ($pagina - 1) * $consultasporpagina;

		$sql1 = "SELECT * FROM clientes WHERE id != 0 ".$filtrado;
		$sql2 = "SELECT * FROM clientes WHERE id != 0 ".$filtrado." ORDER BY id DESC LIMIT ".$limit." OFFSET ".$offset;
		$proceso1 = mysqli_query($conexion,$sql1);
		$proceso2 = mysqli_query($conexion,$sql2);
		$conteo1 = mysqli_num_rows($proceso1);
		$paginas = ceil($conteo1 / $consultasporpagina);

		$html = '';

		$html .= '
			<div class="col-12">
		        <table class="table table-bordered">
		            <thead>
		            <tr>
						<th class="text-center">Identificación</th>
						<th class="text-center">Nombre</th>
						<th class="text-center">Teléfono</th>
						<th class="text-center">Correo</th>
						<th class="text-center">Acciones</th>
		            </tr>
		            </thead>
		            <tbody>
		';
		if($conteo1>=1){
			while($row2 = mysqli_fetch_array($proceso2)) {
				$id = $row2["id"];
				$identificacion = $row2["identificacion"];
				$nombre = $row2["nombre"];
				$telefono = $row2["telefono"];
				$correo = $row2["correo"];
				$html .= '
			                <tr id="">
			                    <td style="text-align:center;">'.$identificacion.'</td>
			                    <td style="text-align:center;">'.$nombre.'</td>
			                    <td style="text-align:center;">'.$telefono.'</td>
			                    <td style="text-align:center;">'.$correo.'</td>
			                    <td style="text-align:center;">
			                    	<button class="btn btn-primary" data-toggle="modal" data-target="#modificar" onclick="modificar('.$id.');">Modificar</button>
			                    </td>
			                </tr>
				';
			}
		}else{
			$html .= '<tr><td colspan="5" class="text-center" style="font-weight:bold;font-size:20px;">Sin Resultados</td></tr>';
		}

		$html .= '
		            </tbody>
		        </table>
		        <nav>
		            <div class="row">
		                <div class="col-xs-12 col-sm-4 text-center">
		                    <p>Mostrando '.$consultasporpagina.' de '.$conteo1.' Datos disponibles</p>
		                </div>
		                <div class="col-xs-12 col-sm-4 text-center">
		                    <p>Página '.$pagina.' de '.$paginas.' </p>
		                </div> 
		                <div class="col-xs-12 col-sm-4">
				            <nav aria-label="Page navigation" style="float:right; padding-right:2rem;">
								<ul class="pagination">
		';
		
		if ($pagina > 1) {
			$html .= '
									<li class="page-item">
										<a class="page-link" onclick="paginacion1('.($pagina-1).');" href="#">
											<span aria-hidden="true">Anterior</span>
										</a>
									</li>
			';
		}

		$diferenciapagina = 3;
		
		/*********MENOS********/
		if($pagina==2){
			$html .= '
			                		<li class="page-item">
				                        <a class="page-link" onclick="paginacion1('.($pagina-1).');" href="#">
				                            '.($pagina-1).'
				                        </a>
				                    </li>
			';
		}else if($pagina==3){
			$html .= '
				                    <li class="page-item">
				                        <a class="page-link" onclick="paginacion1('.($pagina-2).');" href="#"">
				                            '.($pagina-2).'
				                        </a>
				                    </li>
				                    <li class="page-item">
				                        <a class="page-link" onclick="paginacion1('.($pagina-1).');" href="#"">
				                            '.($pagina-1).'
				                        </a>
				                    </li>
		';
		}else if($pagina>=4){
			$html .= '
			                		<li class="page-item">
				                        <a class="page-link" onclick="paginacion1('.($pagina-3).');" href="#"">
				                            '.($pagina-3).'
				                        </a>
				                    </li>
				                    <li class="page-item">
				                        <a class="page-link" onclick="paginacion1('.($pagina-2).');" href="#"">
				                            '.($pagina-2).'
				                        </a>
				                    </li>
				                    <li class="page-item">
				                        <a class="page-link" onclick="paginacion1('.($pagina-1).');" href="#"">
				                            '.($pagina-1).'
				                        </a>
				                    </li>
			';
		} 

		/*********MAS********/
		$opcionmas = $pagina+3;
		if($paginas==0){
			$opcionmas = $paginas;
		}else if($paginas>=1 and $paginas<=4){
			$opcionmas = $paginas;
		}
		
		for ($x=$pagina;$x<=$opcionmas;$x++) {
			$html .= '
				                    <li class="page-item 
			';

			if ($x == $pagina){ 
				$html .= '"active"';
			}

			$html .= '">';

			$html .= '
				                        <a class="page-link" onclick="paginacion1('.($x).');" href="#"">'.$x.'</a>
				                    </li>
			';
		}

		if ($pagina < $paginas) {
			$html .= '
				                    <li class="page-item">
				                        <a class="page-link" onclick="paginacion1('.($pagina+1).');" href="#"">
				                            <span aria-hidden="true">Siguiente</span>
				                        </a>
				                    </li>
			';
		}

		$html .= '

							</ul>
						</nav>
					</div>
		        </nav>
		    </div>
		';

		$datos = [
			"estatus"	=> "ok",
			"html"	=> $html,
		];
		echo json_encode($datos);
	}

	if($asunto=="register"){
		$correo = $_POST['correo'];
		$tipoDocumento = $_POST['tipoDocumento'];
		$documento = $_POST['documento'];
		$password = md5($_POST['password']);
		
		$datos = [
			"estatus"	=> "ok",
		];
		echo json_encode($datos);
	}

	if($asunto=='crear'){
		$nombre = $_POST['nombre'];
		$numeroIdentificacion = $_POST['numeroIdentificacion'];
		$tipoIdentificacion = $_POST['tipoIdentificacion'];
		$telefono = $_POST['telefono'];
		$direccion = $_POST['direccion'];
		$correo = $_POST['correo'];
		$cumpleaños = $_POST['cumpleaños'];

		$sql3 = "SELECT * FROM clientes WHERE identificacion = '$numeroIdentificacion'";
		$proceso3 = mysqli_query($conexion,$sql3);
		$contador3 = mysqli_num_rows($proceso3);
		if($contador3>0){
			$datos = [
				"estatus"	=> "error",
				"msg"	=> "El cliente ya existe",
			];
			echo json_encode($datos);
			exit;
		}
		
		$sql1 = "INSERT INTO clientes (identificacion,tipoIdentificacion,nombre,telefono,direccion,correo,cumpleanios) VALUES ('$numeroIdentificacion','$tipoIdentificacion','$nombre','$telefono','$direccion','$correo','$cumpleaños')";
		$proceso1 = mysqli_query($conexion,$sql1);
		$datos = [
			"estatus"	=> "ok",
			"msg"	=> "Se ha creado exitosamente",
		];
		echo json_encode($datos);
	}

?>