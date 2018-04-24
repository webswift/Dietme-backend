<tr>
	<td><?php echo get_field(Dietme_Post_Types_Patient::FIELDTEXT_PREFIX . "name")?></td>
	<td><?php echo get_field(Dietme_Post_Types_Patient::FIELDTEXT_PREFIX . "surname")?></td>
	<td><?php echo get_field(Dietme_Post_Types_Patient::FIELDTEXT_PREFIX . "city")?></td>
	<td><?php echo get_field(Dietme_Post_Types_Patient::FIELDTEXT_PREFIX . "telephone")?></td>
	<td><?php echo get_field(Dietme_Post_Types_Patient::FIELDTEXT_PREFIX . "note")?></td>
	<td>
		<a href="<?php echo get_permalink();?>" class="btn btn-app">
        	<i class="fa fa-edit"></i> Modifica
        </a>
        <a class="btn btn-app">
        	<i class="fa fa-trash"></i> Cancella
        </a>
	</td>
</tr>