<?php
get_header();

$show_default_title = get_post_meta(get_the_ID(), '_et_pb_show_title', true);

$is_page_builder_used = et_pb_is_pagebuilder_used(get_the_ID());

?>

<div id="main-content">
	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area">
				<?php
				// Chama o cabeçalho que apresenta o sistema de discussão
				get_delibera_header();

				// Chama o loop
				// get_template_part( 'loop', 'pauta' );
				if(file_exists(get_stylesheet_directory()."/content-pauta.php"))
				{
					load_template(get_stylesheet_directory()."/content-pauta.php");
				}
				else
				{
					load_template($this->themeFilePath('content-pauta.php'), true);
				}
				?>

                    </div>
                <?php get_sidebar(); ?>
			</div><!-- #content -->
		</div><!-- #container -->
             </div>
			<!-- #left-area -->
             <?php get_sidebar(); ?>
		</div>
		<!-- #content-area -->
	</div>
	<!-- .container -->
</div>
<!-- #main-content -->
<?php get_footer(); ?>
