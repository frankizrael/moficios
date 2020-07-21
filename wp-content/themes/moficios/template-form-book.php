<?php /* Template Name: Form Book */
set_query_var('ENTRY', 'form_book');
get_header();
?>
<div id="form-book" class="container-fluid">
	<div class="content row">
		<div class="col-md-5 p-4 p-md-5 left">
			<div class="block">
				<h1><?php the_title() ?></h1>
				<?php the_field('business_information') ?>
				<p>Fecha: <?php echo date_i18n('d/m/Y') ?></p>
			</div>
		</div>
		<div class="col-md-7 p-4 p-md-5 right">
			<form id="formBook" action="" method="post" class="row">
				<div class="form-group col-12">
					<input type="text" class="form-control" id="full_name" name="full_name" required>
					<label for="full_name">Nombre:</label>
				</div>
				<div class="form-group col-12">
					<input type="text" class="form-control" id="address" name="address" required>
					<label for="address">Domicilio:</label>
				</div>
				<div class="form-group col-12 col-md-6">
					<input type="text" class="form-control" id="document" name="document" required>
					<label for="document">DNI/C.E.:</label>
				</div>
				<div class="form-group col-12 col-md-6">
					<input type="tel" class="form-control" id="phone" name="phone" required>
					<label for="phone">Teléfono:</label>
				</div>
				<div class="form-group col-12 col-md-6">
					<input type="email" class="form-control" id="email" name="email" required>
					<label for="email">E-mail:</label>
				</div>
				<div class="form-group col-12 col-md-6"></div>
				<div class="form-group col-12 col-md-6">
					<input type="number" class="form-control" id="amount" name="amount" required>
					<label for="amount">Monto reclamado:</label>
				</div>
				<div class="form-group col-12 col-md-6">
					<input type="text" class="form-control" id="description" name="description" required>
					<label for="description">Descripción:</label>
				</div>
				<div class="form-group col-12">
					<textarea rows="3" class="form-control" id="detail" name="detail" required></textarea>
					<label for="detail">Detalle:</label>
				</div>
				<div class="form-group col-12">
					<textarea rows="3" class="form-control" id="request" name="request" required></textarea>
					<label for="request">Pedido:</label>
				</div>
				<div class="form-group col-12 post">
					<?php if(have_posts()){
						while(have_posts()) {
							the_post();
							the_content();
						}
					}
					?>
				</div>
				<div class="form-group col-12">
					<button class="btn-submit">Enviar</button>
				</div>
			</form>
		</div>
	</div>
</div>
<?php get_footer();