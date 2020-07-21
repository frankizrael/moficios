<?php /* Template Name: Contact */
set_query_var('ENTRY', 'contact');
get_header();

$customer_service = get_field('customer_service');
$location = get_field('map');
?>
<div id="contact" class="x-container shadow py-4 px-4 px-md-5">
	<div class="row">
		<div class="col-12">
			<h1><?php the_title() ?></h1>
			<hr>
		</div>
		<div class="col-md-6 align-self-center">
			<div class="customer-service">
				<h4>Servicio al cliente</h4>
				<h2><?php echo $customer_service['phone'] ?></h2>
				<h5><?php echo $customer_service['option'] ?></h5>
				<div>Horario de atención:</div>
				<div><?php echo $customer_service['hours'] ?></div>
			</div>
			<hr class="my-4">
			<div class="address">
				<h4>Ubícanos</h4>
				<h2>Oficina principal</h2>
				<div><?php the_field('address') ?></div>
			</div>
		</div>
		<div class="col-md-6 my-5 my-md-0">
			<div class="acf-map shadow">
				<div class="marker" data-lat="<?php echo $location['lat']; ?>" data-lng="<?php echo $location['lng']; ?>"></div>
			</div>
		</div>
	</div>
</div>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCcBJvjPVyljL0ErfTjP14Y6AINCap-WoU"></script>
<script type="text/javascript">
	var url_principal = '<?php echo esc_url( get_template_directory_uri() ); ?>';
</script>
<?php get_footer();