<?php
/**
 * The front page template file
 *
 * If the user has selected a static page for their homepage, this is what will
 * appear.
 * Learn more: https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */
get_header(); ?>



<?php /*
<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">

		<?php // Show the selected frontpage content.
		if ( have_posts() ) :
			while ( have_posts() ) : the_post();
				get_template_part( 'template-parts/page/content', 'front-page' );
			endwhile;
		else : // I'm not sure it's possible to have no posts when this page is shown, but WTH.
			get_template_part( 'template-parts/post/content', 'none' );
		endif; ?>

	</main><!-- #main -->
</div><!-- #primary -->
*/?>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
			<div class="box">
            <!-- /.box-header -->
            <div class="box-header">
            	<div class="col-xs-2">
            		<?php 
	            		$obj = get_post_type_object( get_post_type() );
            		?>
              		<button type="button" class="btn btn-block btn-primary"  data-toggle="modal" data-target="#new-<?php echo get_post_type();?>"><?php echo $obj->labels->add_new_item;?></button>
              	</div>
            </div>
            <div class="box-body">
              <table id="archive" class="table table-bordered table-hover">
                <thead>
                	<?php get_template_part( 'template-parts/archive/table-head', get_post_type());?>
                </thead>
                <tbody>
                <?php // Show the selected frontpage content.
					if ( have_posts() ) :
						while ( have_posts() ) : the_post();
							get_template_part( 'template-parts/archive/single', get_post_type());
						endwhile;
					else : // I'm not sure it's possible to have no posts when this page is shown, but WTH.
						get_template_part( 'template-parts/post/content', 'none' );
					endif; ?>
                <tfoot>
                	<?php get_template_part( 'template-parts/archive/table-head', get_post_type());?>
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
</div>
</div>
</section>
<script>
jQuery(document).ready(function(){
	jQuery('#archive').DataTable({
	    'paging'      : true,
	    'lengthChange': false,
	    'searching'   : true,
	    'ordering'    : true,
	    'info'        : true,
	    'autoWidth'   : true
	  })
});
</script>
<?php get_footer();

get_template_part( 'template-parts/archive/new', get_post_type());