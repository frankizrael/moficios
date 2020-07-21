<?php /* Template Name: home */
set_query_var('ENTRY', 'homee');
get_header();
?>
<section class="map-home">
	<div class="map-home-item">
		<div class="col izq">
			<div class="f_tit">
			<?php 
				the_field('title');
			?></div>
			<div class="first-file">
				<div class="selectores-padre">
					<div class="option_select">
						<label>Departamento</label>
						<select id="departamento" class="selectores">
							<option disabled selected>Escoger departamento</option>
							<?php
							$ubigeo = get_field('s_ubigeo','options');
							foreach ($ubigeo as $ub) {
							    ?>
							    <option value="<?php echo sanitize_title($ub['departamento']); ?>"><?php echo $ub['departamento']; ?></option>
							    <?php
								}
							?>
						</select>
					</div>
					<div class="option_select">
						<label>Provincias</label>
						<select id="provincia" class="selectores">	
							<option disabled selected>Escoger provincia</option>											
						</select>
					</div>
					<div class="option_select">
						<label>Distrito</label>
						<select id="distrito" class="selectores">	
							<option disabled selected>Escoger distrito</option>										
						</select>
					</div>
					<div class="option_select">
						<label>Oficio</label>
						<select id="oficio" class="selectores">
							<option disabled selected>Escoger oficio</option>
						    <?php
						    $taxonomy = 'category';
							$terms = get_terms($taxonomy);
							if ( $terms && !is_wp_error( $terms ) ) :
							?>
					        <?php foreach ( $terms as $term ) { ?>
					            <option value="<?php echo sanitize_title($term->name); ?>"><?php echo $term->name; ?></option>
					        <?php } ?>
							<?php endif;?>
						</select>
					</div>
				</div>
				<a href="javascript:void(0)" id="sendform" class="button-general disabled" >
					<span>
						Buscar al maestro <img src="<?php echo get_template_directory_uri(); ?>/img/icon-arrow-boton.svg" alt="">
					</span>
				</a>
			</div>
			<div class="second-file" style="display: none;">
				<div class="resultado-busqueda">
					<h4><p><span id="count"></span></p></h4>
					<p>profesionales encontrados</p>
				</div>
				<div id="resultado" class="resultado-autores "></div>
			</div>
		</div>
		<div class="col der">
			<div id="gmap"></div>
		</div>
	</div>
</section>
<section class="solicitados">
	<div class="contenedor">
		<h2>Lo más solicitados</h2>
		<div class="swiper-container">
		    <div class="swiper-wrapper">
		    	<?php
				    $taxonomy = 'category';
					$terms = get_terms($taxonomy);
					if ( $terms && !is_wp_error( $terms ) ) :
					?>
			        <?php foreach ( $terms as $term ) { ?>
			            <div class="swiper-slide">
			            	<div class="solicitados_swip">
					      		<img src="<?php the_field('imagen','category_'.$term->term_id); ?>">
					      		<p><?php echo $term->name; ?></p>
				      		</div>
				      	</div>
			        <?php } ?>
				<?php endif;?>
		    </div>
		    <!-- Add Pagination -->
		    <div class="swiper-pagination"></div>
		    <!-- Add Arrows -->
		    <div class="swiper-button-next"></div>
		    <div class="swiper-button-prev"></div>
		</div>	
	</div>
</section>
<section class="elige-maestro">
	<div class="contenedor">
		<h2>Elige a <strong>tu maestro</strong></h2>
		<div class="item-maestro">
			<div class="subitems-maestro">
				<div class="subitem-number-none active">
					<h2>1</h2>
				</div>
				<div class="subitem-text">
					<h4>Elige el oficio</h4>
					<p>Seleccione la provincia el oficio, distrito y zona</p>
				</div>
			</div>
			<div class="subitems-maestro">
				<div class="subitem-number active">
					<h2>2</h2>
				</div>
				<div class="subitem-text">
					<h4>Elige el oficio</h4>
					<p>Seleccione la provincia el oficio, distrito y zona</p>
				</div>
			</div>
			<div class="subitems-maestro">
				<div class="subitem-number ">
					<h2>3</h2>
				</div>
				<div class="subitem-text ">
					<h4>Elige el oficio</h4>
					<p>Seleccione la provincia el oficio, distrito y zona</p>
				</div>
			</div>
		</div>
	</div>
</section>
<section class="clientes">
	<div class="contenedor">
		<h2>Clientes <strong>satisfechos</strong></h2>
		<div class="swiper-container">
		    <div class="swiper-wrapper">
		    	<?php
		    		$args = array(
					'numberposts'	=> 6,
						'post_type'		=> 'comments'
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
		    	?>
		      	<div class="swiper-slide" id="<?php echo $co->ID; ?>">
		    		<div class="img-cliente">
		    			<img src="<?php echo get_field( 'imagen', 'user_'.$user_id );?>" alt="">
		    			<div class="info-trabajador">
		    				<h5><?php echo $name;?></br><?php echo $lastname;?></h5>
		    				<div class="calificacion">
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
		    				</div>
		    			</div>
		    		</div>
		    		<div class="descripcion-maestro">
		    			<p><?php echo get_field('comentario',$comentario_id); ?></p>
		    			<div class="hora-anunciada"><?php echo get_field('date',$comentario_id); ?></div>
		    		</div>
		      	</div>   
		      	<?php
		      			}
		      		}
		      	?>
		    </div>
		    <!-- Add Pagination -->
		    <div class="swiper-pagination"></div>
		</div>
	</div>
</section>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCcBJvjPVyljL0ErfTjP14Y6AINCap-WoU"></script>
<script type="text/javascript">
	var url_principal = '<?php echo get_template_directory_uri(); ?>';
</script>

<?php get_footer(); ?>