<?php
$obj = get_post_type_object ( get_post_type () );
?>
<div class="modal fade" id="new-<?php echo get_post_type();?>">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="box box-info">
				<div class="box-header with-border">
					<h3 class="box-title"><?php echo $obj->labels->add_new_item;?></h3>
				</div>
				<!-- /.box-header -->
				<!-- form start -->
				<form>
					<div class="box-body">
						<div class="row">
							<div class="form-group col-xs-6">
								<label for="patientName">Nome</label>
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-address-card"></i>
									</div>
									<input type="text" class="form-control" id="patientName"
										placeholder="Mario">
								</div>
							</div>
							<div class="form-group col-xs-6">
								<label for="patientSurname">Cognome</label>
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-address-card"></i>
									</div>
									<input type="text" class="form-control" id="patientSurname"
										placeholder="Rossi">
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="patientCity">Citt&agrave;</label>
							<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-map-marker"></i>
								</div>
								<input type="text" class="form-control" id="patientCity"
									placeholder="Verona">
							</div>
						</div>
						<div class="form-group">
							<label for="patientAddress">Indirizzo</label>
							<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-map-marker"></i>
								</div>
								<input type="text" class="form-control" id="patientAddress"
									placeholder="Via giacomo leopardi 43">
							</div>
						</div>

						<textarea class="textarea" placeholder="Place some text here"
							style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
					</div>
					<!-- /.box-body -->
					<div class="box-footer modal-footer">
						<button type="button" class="btn btn-default pull-left"
							data-dismiss="modal">Annulla</button>
						<button type="button" class="btn btn-primary">Salva</button>
					</div>
					<!-- /.box-footer -->
				</form>
			</div>
		</div>
	</div>
</div>

<script>
jQuery('.textarea').wysihtml5();
</script>