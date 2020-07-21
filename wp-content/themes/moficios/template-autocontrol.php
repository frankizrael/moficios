<?php /* Template Name: autocontrol */
set_query_var('ENTRY', 'autocontrol');
get_header();
$terms_conditions = get_field('terms_conditions');
?>
<div id="autocontrol" class="x-container shadow py-4 px-4 px-md-5">
	<div class="content">
		<div class="row">
			<div class="col-12">
				<h1><?php the_title() ?></h1>
				<hr>
				<div class="left-id-content">
					<div class="description-low">
						<?php if (have_posts()):
							while (have_posts()):
								the_post();
								the_content();
							endwhile;
						endif; ?>
					</div>
				</div>				
			</div>
			<div class="col-md-6">
				<div class="item-my_form">
					<div class="item-my_form__icon">
						<svg xmlns="http://www.w3.org/2000/svg" width="43.601" height="43.602" viewBox="0 0 43.601 43.602"><defs><style>.a,.b{fill:#f78f1e;}.b{stroke:#f78f1e;}</style></defs><g transform="translate(6597 952)"><path class="a" d="M21.8,0A21.8,21.8,0,1,0,43.6,21.8,21.825,21.825,0,0,0,21.8,0Zm0,41.619A19.819,19.819,0,1,1,41.619,21.8,19.841,19.841,0,0,1,21.8,41.619Z" transform="translate(-6597 -952)"/><path class="a" d="M36.27,19.5a16.77,16.77,0,1,0,16.77,16.77A16.789,16.789,0,0,0,36.27,19.5Zm0,32.984A16.214,16.214,0,1,1,52.484,36.27,16.232,16.232,0,0,1,36.27,52.484Z" transform="translate(-6611.47 -966.469)"/><g transform="translate(-6585.262 -938.584)"><g transform="translate(0 0)"><path class="b" d="M155.923,422.371a4.4,4.4,0,0,0,.6,2.16,3.949,3.949,0,0,0,3.579,1.556,6.184,6.184,0,0,0,2.093-.34,2.4,2.4,0,0,0,1.825-2.365,2.065,2.065,0,0,0-.773-1.819,7.635,7.635,0,0,0-2.455-.926l-2.051-.48a9.14,9.14,0,0,1-2.846-1.033,3.3,3.3,0,0,1-1.444-2.928,4.573,4.573,0,0,1,1.419-3.46,5.6,5.6,0,0,1,4.02-1.352,6.853,6.853,0,0,1,4.065,1.187,4.331,4.331,0,0,1,1.674,3.795H163.64a4.027,4.027,0,0,0-.654-1.927,3.711,3.711,0,0,0-3.153-1.224,3.541,3.541,0,0,0-2.583.788,2.517,2.517,0,0,0-.787,1.831,1.8,1.8,0,0,0,.929,1.682,13.362,13.362,0,0,0,2.754.85l2.125.5a6.415,6.415,0,0,1,2.371.991,3.747,3.747,0,0,1,1.446,3.181,4,4,0,0,1-1.843,3.716,8.087,8.087,0,0,1-4.283,1.119,6.308,6.308,0,0,1-4.452-1.489,5.087,5.087,0,0,1-1.578-4.015Z" transform="translate(-153.932 -411.385)"/></g><rect class="b" width="16.49" height="1.676" transform="matrix(0.25, -0.968, 0.968, 0.25, 13.026, 16.784)"/></g></g></svg>
					</div>
					<div class="item-my_form__content">
						<div class="descp">
							Define el monto máximo mensual para realizar tus compras.
						</div>
						<div class="form-basic">
							<form action="" method="" id="creditMensual">
								<div class="my-input-item">
									<label for="credit">Soles:</label>
									<input type="text" name="credit" placeholder="0.00">
								</div>
								<div class="my-button-item">
									<button type="submit" class="my-button">Guardar</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="item-my_form">
					<div class="item-my_form__icon">
						<img src="data:image/png;base64,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">
					</div>
					<div class="item-my_form__content">
						<div class="descp">
							Escoge el tiempo máximo diario para permanecer conectado en tu cuenta
						</div>
						<div class="form-basic">
							<form action="" method="" id="creditMensual">
								<div class="my-input-item">
									<label for="credit">Horas:</label>
									<select id="selectHour">
										<option selected="true" disabled="disabled">Ninguno</option> 
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
										<option value="6">6</option>
									</select>
								</div>
								<div class="my-button-item">
									<button type="submit" class="my-button">Guardar</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="col-12">
				<div class="my-terms-and-conditions">
					<?php echo $terms_conditions; ?>
				</div>
			</div>
		</div>		
	</div>
</div>
<?php get_footer();