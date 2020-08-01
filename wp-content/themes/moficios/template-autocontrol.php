<?php /* Template Name: autocontrol */
set_query_var('ENTRY', 'autocontrol');
get_header();
?>
<section class="autocontrol">
	<div class="autocontrol__login" id="login">
		<h1>¡Hola profesional! ingresa a tu panel</h1>
		<div class="login-authO">
			<div class="inputFerm">
				<div class="coreferm">
					<label>DNI</label>
				</div>
				<div class="insideferm">
					<i><img src="<?php echo get_template_directory_uri(); ?>/img/icon/icon-perfil.svg" alt=""></i>				
					<input type="number" name="user" id="user" onKeyPress="if(this.value.length==8) return false;" pattern="[0-9]*" placeholder="Ingresa tu documento de identidad">
				</div>		
			</div>
			<div class="inputFerm">
				<div class="coreferm">
					<label>CONTRASEÑA</label>
				</div>
				<div class="insideferm">
					<i><img src="<?php echo get_template_directory_uri(); ?>/img/icon/icon-seguridad.svg" alt=""></i>
					<input type="password" name="password" id="password" placeholder="Ingresa tu contraseña">					
				</div>
			</div>					
			<p class="error errorJsMsj"></p>
			<div class="button_send">
				<a href="javascript:void(0)" class="btn" id="jsSend">Ingresar</a>
			</div>
		</div>
	</div>
	<div class="autocontrol__content" id="data" style="display: none;">
		<div class="autocontrol__tab">
			<ul>
				<li data="#datos">Datos Personales</li>
				<li data="#cualidades">Cualidades y Documentos</li>
				<li data="#trabajos">Trabajos</li>
			</ul>
		</div>
		<div class="autocontrol__cont">
			<div class="tab_con" id="datos">
				<div class="flex">
					<div class="flex-50">
						<div class="ro">
							<div class="col-full">
								<div class="input-re">
									<div class="re">
										<label>DNI</label>
										<p class="input docJS"></p>
									</div>					
								</div>						
							</div>
						</div>
						<div class="ro">
							<div class="col-full-50">
								<div class="input-re">
									<div class="re">
										<label>Nombre</label>
										<input type="text" name="name" id="nombreJs" >
									</div>							
								</div>						
							</div>
							<div class="col-full-50">
								<div class="input-re">
									<div class="re">
										<label>Apellido</label>
										<input type="text" name="lastname" id="apellidoJs" >
									</div>							
								</div>						
							</div>
						</div>
						<div class="ro">
							<div class="col-full">
								<div class="input-re">
									<div class="re">
										<label>Direccion</label>
										<input type="text" name="direction" id="directionJs" >
									</div>							
								</div>						
							</div>
						</div>
						<div class="ro">
							<div class="col-full-50">
								<div class="input-re">
									<div class="re">
										<label>Correo</label>
										<input type="email" name="correo" id="correoJs">
									</div>							
								</div>						
							</div>
							<div class="col-full-50">
								<div class="input-re">
									<div class="re">
										<label>Fecha de Nacimiento</label>
										<input type="text" name="datenac" id="datenacJs">
									</div>							
								</div>						
							</div>
						</div>	
						<div class="ro ">							
							<div class="col-full">
								<div class="input-re roTelefono">
									<div class="re">
										<label>Teléfono</label>
										<input type="tel" name="telefono" id="telefonoJs">
										<p>Se recomienda tener el whatsapp habilitado en el teléfono</p>
									</div>							
								</div>						
							</div>
						</div>
						<div class="ro">
							<div class="button">								
								<div class="editJs btn" id="editFirstData">
									Guardar 
								</div>
							</div>
						</div>	
					</div>
					<div class="flex-50">
						<div class="imagen_top">
							<img src="#" id="imagenJs">
							<div class="inputImagen">
								<input type="file" name="imagenInput" id="imagenInput">
							</div>
							<div class="editarImagenJs">Editar</div>
						</div>
					</div>
				</div>
			</div>
			<div class="tab_con" id="cualidades">
				<div class="flex">
					<div class="flex-50">
						<div class="con_inside">
							<h2>Especialidades</h2>
							<ul id="jsEspecialidades"></ul>
							<div class="editEspecialidades">Agregar Especialidad</div>
							<select id="oficio" class="selectores">
								<option disabled selected>Escoger oficio</option>
							    <?php
								$terms = get_terms(array('taxonomy' => 'category','hide_empty' => false));
								if ( $terms && !is_wp_error( $terms ) ) :
								?>
						        <?php foreach ( $terms as $term ) { ?>
						            <option value="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></option>
						        <?php } ?>
								<?php endif;?>
							</select>
							<p>Para acelerar la busqueda de especialistas no está permitido eliminar un oficio practicado, en caso necesite eliminar uno o más oficios escríba a administracion.</p>
						</div>
					</div>
					<div class="flex-50">						
						<div class="con_inside">
							<h2>Cualidades</h2>
							<div class="input-textarea">
								<div class="re">
									<textarea id="cualidadesJs"></textarea>
								</div>
								<div class="editTextarea">
									Editar
								</div>							
							</div>	
						</div>
					</div>
				</div>
				<div class="documentos">
					<h2>Documentos</h2>
					<div class="ro">
						<div class="col-full-50">
							<div class="input-file_ext">
								<div class="re">
									<a href="#" id="registroJs" download>Registro de Antecedentes penales</a>
								</div>
								<div class="inputEdit">
									<div class="edit">
										Editar
									</div>
									<input type="file" name="registro" id="InputregistroJs">							
								</div>						
							</div>						
						</div>
						<div class="col-full-50">
							<div class="input-file_ext">
								<div class="re">
									<a href="#" id="carnetJs" download>Carnet de sanidad</a>
								</div>
								<div class="inputEdit">
									<div class="edit">
										Editar
									</div>
									<input type="file" name="carnet" id="InputcarnetJs">							
								</div>
							</div>						
						</div>
					</div>	
					<p>El archivo no debe ser mayor a 2Mb, en formato pdf; puede usar compresores en linea para reducir el peso objetivo.</p>				
				</div>
			</div>
			<div class="tab_con" id="trabajos">
				<div class="con_works">
					<h2>Work</h2>
					<ul id="jsTrabajos"></ul>
					<div class="jobSend">
						<input type="file" name="trabajoImg" id="sendTrabajo">
						<div class="editEspecialidades">Agregar Trabajo</div>
					</div>					
					<p>Para acelerar la busqueda de especialistas <b>no está permitido eliminar un trabajo</b>, en caso necesite eliminar uno o más trabajos escríba a administracion.</p>
					<p>La fotografía no debe ser mayor a 2Mb, en formato png; puede usar compresores en linea para reducir el peso objetivo.</p>
				</div>

			</div>
		</div>
	</div>
</section>
<script type='text/javascript'>
/* <![CDATA[ */
	var aw = {"ajaxurl":"<?php echo site_url(); ?>/wp-admin/admin-ajax.php"};
/* ]]> */
</script>
<?php get_footer();