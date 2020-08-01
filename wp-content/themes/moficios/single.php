<?php
set_query_var('ENTRY', 'single_promotion');
get_header();
$singleid = get_the_ID();
?>
<section class="cv-profile">
	<div class="contenedor-cv">
		<div class="col izq">
			<div class="perfil-cv">
				<img src="<?php echo get_the_post_thumbnail_url(); ?>" alt="">
				<div class="datos-perfil-cv">
					<h2><strong><?php echo get_field('nombre').'</strong> '.get_field('apellido'); ?></h2>
					<div class="calificacion-stars">
						<?php
							$valoracion = get_field('valoracion',$id);	
							$valoracionespersona = get_field('valoracionespersona',$id);	
							for ($i=1; $i < 6; $i++) { 
								$active = '';
								if ($i <= $valoracion) {												
									$active = '-active';
								}
							?>
							<img class="star-active" src="<?php echo get_template_directory_uri(); ?>/img/star-solid<?php echo $active;?>.svg" alt="">										
								<?php
							}
						?>
						<p class="calificacion">de <strong><?php echo $valoracionespersona; ?></strong>Calificaciones</p>
					</div>
				</div>
			</div>
			<div class="item-especialidades">
				<h2><img src="<?php echo get_template_directory_uri(); ?>/img/icon/icon-seguridad.svg" alt="">Especialidades</h2>
				<div class="especialidades-items">
					<?php
						$term_obj_list = get_the_terms( get_the_ID(), 'category' );
						foreach ($term_obj_list as $tt) {
							?>
						<div class="subitem-especialidad"><?php echo $tt->name;?></div>	
							<?php
						}
					?>
				</div>
			</div>
			<div class="item-datos-per">
				<h2><img src="<?php echo get_template_directory_uri(); ?>/img/icon/icon-perfil.svg" alt="">Datos Personales</h2>
				<div class="datos-per">
					<div class="subdatos">
						<h4>Direccion</h4>
						<p><?php the_field('direccion');?></p>
					</div>
					<div class="subdatos">
						<h4>D.N.I</h4>
						<p><?php the_title(); ?></p>
					</div>
					<div class="subdatos">
						<h4>Correo</h4>
						<p><?php the_field('correo');?></p>
					</div>
					<div class="subdatos">
						<h4>Telefono</h4>
						<p><?php the_field('telefono');?></p>
					</div>
					<div class="subdatos">
						<h4>Fecha de Nacimiento</h4>
						<p><?php the_field('fecha_nacimiento');?></p>
					</div>
				</div>
			</div>
		</div>
		<div class="col der">
			<div class="div-contacto">
				<h3>Comunicate ahora</h3>
				<a href="tel:<?php the_field('telefono');?>">
					<button class="bot-blue"><?php the_field('telefono');?></button>
				</a>
			</div>
			<div class="item-cualidades">
				<h2><img src="<?php echo get_template_directory_uri(); ?>/img/icon/icon-medalla.svg" alt="">Cualidades</h2>
				<p><?php the_field('cualidades');?></p>
			</div>
			<div class="item-otros">
				<h2><img src="<?php echo get_template_directory_uri(); ?>/img/icon/icon-escudo.svg" alt="">Otro</h2>
				<div class="descargas">
					<?php
						if (get_field('antecedentes_penales')) {
					?>
					<a href="<?php the_field('antecedentes_penales');?>" download>
						<img src="<?php echo get_template_directory_uri(); ?>/img/icon/icon-descarga.svg" alt="">
						Registro de Antecedentes penales
					</a>
					<?php
						}
					?>
					<?php
						if (get_field('carnet_sanidad')) {
					?>
					<a href="<?php the_field('carnet_sanidad');?>" download>
						<img src="<?php echo get_template_directory_uri(); ?>/img/icon/icon-descarga.svg" alt="">
						Carnet de sanidad
					</a>
					<?php
						}
					?>
				</div>
			</div>
		</div>
		<div class="img-trabajos">
			<h2><img src="<?php echo get_template_directory_uri(); ?>/img/icon/icon-caja.svg" alt="">Trabajos realizados</h2>
			<div class="swiper-container">
				<div class="swiper-wrapper">
					<?php
						$trabajos = get_field('trabajos');
						foreach ($trabajos as $tr) {
							?>
						<div class="swiper-slide">
							<img src="<?php echo $tr['imagen']; ?>" alt="">
						</div>
							<?php
						}
					?>
				</div>
				<!-- Add Pagination -->
				<div class="swiper-pagination"></div>
				<!-- Add Arrows -->
			</div>
		</div>
		<div class="opiniones">
			<?php
				
				$args = array(
					'numberposts'	=> 6,
					'post_type'		=> 'comments',
					'meta_key'		=> 'profesional',
					'meta_value'	=> $singleid
				);
				$comentarios = get_posts( $args );
				if ($comentarios) {
				foreach ($comentarios as $co) {
					$comentario_id = $co->ID;
					$user_id = get_field('usuario',$comentario_id);
					$new_user = get_userdata( $user_id );
					# Get the user's first and last name
					$name = $new_user->first_name;
					$last_name = $new_user->last_name;
					$img_user = get_avatar_url( $user_id );
			?>	
			<h2><img src="<?php echo get_template_directory_uri(); ?>/img/icon/icon-caja.svg" alt="">Opiniones</h2>			
			<div class="item-cliente" id="<?php echo $co->ID; ?>">
				<div class="comentarista">
					<img src="<?php echo $img_user;?>" alt="">
					<div class="datos-perfil-cv">
						<h2><strong><?php echo $name;?></strong><?php echo $lastname;?></h2>
						<div class="calificacion-stars">
							<?php
								$calification= get_field('calification',$comentario_id);
								for ($i=1; $i < 6; $i++) { 
									$active = '';
									if ($i <= $calification) {												
										$active = '-active';
									}
								?>
								<img class="star-active" src="<?php echo get_template_directory_uri(); ?>/img/star-solid<?php echo $active;?>.svg" alt="">										
									<?php
								}
							?>
							<p class="fecha-publicada"><?php echo get_field('date',$comentario_id); ?></p>
						</div>
					</div>
				</div>
				<div class="comentario-cliente">
					<p><?php echo get_field('comentario',$comentario_id); ?></p>
				</div>
			</div>
					<?php
					}
				}
			?>
		</div>
	</div>
</section>
<section class="add-comentario">
	<div class="back-white">
		<div class="col izq">
			<h2><img src="<?php echo get_template_directory_uri(); ?>/img/icon/icon-comentario.svg" alt="">Danos tu opinión</h2>
			<p>Las opiniones son públicas califica el trabajo que hizo el profesional por ti</p>
		</div>
		<div class="col der">
			<h2><img src="<?php echo get_template_directory_uri(); ?>/img/icon/icon-medalla2.svg" alt="">Valoración</h2>
			<div class="calificacion-stars">
				<?php
					for ($i=0;$i<5;$i++){
				?>
				<div class="stars" data="<?php echo $i; ?>">
					<img src="<?php echo get_template_directory_uri(); ?>/img/star-solid.svg" class="not">
					<img src="<?php echo get_template_directory_uri(); ?>/img/star-solid-active.svg" class="act" style="display: none;">
				</div>
				<?php
					}
				?>
				<p class="text-publicada" style="display: none;">Me ah encantado</p>
			</div>
		</div>
		<textarea placeholder="Cuenta a los demás qué opinas de mi trabajo. ¿La recomendarías? ¿Por qué?" name="" id="comment_textarea" cols="30" rows="10"></textarea>
		<?php
			if ( is_user_logged_in() ) {
				$current_user = wp_get_current_user();
				
			    ?>
				<div class="buttons">
					<a href="javascript:void(0)" id="jsCancell"><button class="bot-white">Cancelar</button></a>
					<a href="javascript:void(0)" id="jsSend" data-user="<?php echo $current_user->ID; ?>" data-pro="<?php echo $singleid; ?>" data-nameuser="<?php echo $current_user->user_firstname.' '.$current_user->user_lastname; ?>" data-namepro="<?php echo get_field('nombre').' '.get_field('apellido'); ?>"><button class="bot-blue">Enviar</button></a>
				</div>			    
			    <?php
			} else {
			    ?>
			    <p class="lr">Para poder valorar por favor ingresa</p>
			    <?php
			}
		?>
	</div>
</section>
<?php get_footer();