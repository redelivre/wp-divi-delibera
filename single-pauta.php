<?php
get_header();

$show_default_title = get_post_meta(get_the_ID(), '_et_pb_show_title', true);

$is_page_builder_used = et_pb_is_pagebuilder_used(get_the_ID());

?>

<div id="single wrap">
    <main class="single wrap">

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
					global $deliberaThemes;
					load_template($deliberaThemes->themeFilePath('content-pauta.php'), true);
				}
				?>

<section class="single-sidebar">
    <div class="sidebar-section">
        <h2 class="sidebar-title">outras pautas</h2>
        <div class="yarpp-related">
            <ol class="yarpp-list">

                <?php
                $posts_array = get_posts(
                array(
                'posts_per_page' => 10,
                'post_type' => 'pauta',
                    'order'            => 'rand',
                    'orderby'=>'rand'
                )
                );

                foreach($posts_array as $key=>$value)
                {
                    ?>
                    <li class="yarpp-item">
                        <a class="yarpp-link" rel="bookmark" href="<?=$posts_array[$key]->guid?>"> <?=$posts_array[$key]->post_title?> </a>
                    </li>
                <?

                }

                ?>

                <?php get_sidebar(); ?>
            </ol>
        </div>
    </div>
</section>

        <?php comments_template( '', true ); ?>

</main>
<?php get_footer(); ?>