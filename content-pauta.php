<?php if ( have_posts() ) while ( have_posts() ) : the_post();
	$status_pauta = delibera_get_situacao($post->ID)->slug;
//echo $status_pauta;
	global $DeliberaFlow;
	$flow = $DeliberaFlow->get(get_the_ID());

	$term_list = wp_get_post_terms(get_the_ID(), 'tema', array("fields" => "names"));
?>

            <article class="single-content">
                <h4 class="single-taxonomy"><?=$term_list[0]?></h4>
                    <h2 class="single-title">
                        <a href="#"><?php the_title(); ?></a>
                    </h2>

                    <!--h2>
                        <?php
                        if ( \Delibera\Flow::getDeadlineDays( $post->ID ) == -1 )
                            _e( 'Prazo encerrado', 'delibera' );
                        else
                            printf( _n( 'por mais <span class="numero">1</span> dia', 'por mais <span class="numero">%1$s</span> dias', \Delibera\Flow::getDeadlineDays( $post->ID ), 'delibera' ), number_format_i18n( \Delibera\Flow::getDeadlineDays( $post->ID ) ) );
                        ?>
                    </h2-->
                    <BR>
                    <p class="grid-ideia-tags">

                        <?php
                        $tags = get_the_tags();
                        $html = '<div class="post_tags">';
                        foreach ($tags as $tag){
                            $tag_link = get_tag_link($tag->term_id);

                            $html .= "<a href='{$tag_link}' title='{$tag->name} Tag' class='grid-ideia-tag'>";
                            $html .= "{$tag->name}</a>";
                        }
                        echo $html;
                        ?>
                    </p>
                    <div class="single-meta social-media">
<span class="single-author">
<a href="<?php echo get_site_url().'/delibera/' . get_the_author_meta( 'ID' ) . '/pautas' ; ?>" >
    <span class="author-picture" style="background-image: url('<?php echo get_avatar_url(get_avatar(get_the_author_meta( 'ID' ))); ?> ');"> </span>
    <?php the_author(); ?>
</a>
</span>
                        <span class="single-meta-sep">·</span>
                        <span class="single-date">
                          <?php the_time('j \d\e F \d\e Y') ?> </span>
<span class="social-sep">
<hr>
</span>
<span class="social-buttons">
<a class="social-button share-facebook" target="_blank" href="https://facebook.com/sharer/sharer.php?u=<?=get_permalink();?>">
    <i class="fa fa-fw fa-lg fa-facebook"></i>
    Compartilhar
</a>
<a class="social-button share-twitter" target="_blank" href="https://twitter.com/share?url=<?=get_permalink();?>">
    <i class="fa fa-fw fa-lg fa-twitter"></i>
    Tuitar
</a>
</span>
                    </div>
                    <div class="entry">
                        <p><?php the_content(); ?></p>
                    </div>

                    <!--div id="comments">
                        <div class="ideia-comments-header">
                            <a class="ideia-action ideia-upvote" data-vote="post_id|1407" href="#vote">
                                <i class="fa fa-fw fa-lg fa-thumbs-up"></i>
                                <span class="number">0</span>
                            </a>
                            <a class="ideia-action ideia-downvote" data-vote="post_id|1407" href="#vote">
                                <i class="fa fa-fw fa-lg fa-thumbs-down"></i>
                                <span class="number">0</span>
                            </a>
<span class="social-media">
                        </div>
                        <ul class="ideia-comments-list">
                    </div-->
            </article>

<div class="pauta-content <?php echo $status_pauta; ?>" style="display: none" >
	<div class="banner-ciclo status-ciclo">
		<h3 class="title">Estágio da pauta</h3>
		<ul class="ciclos"><?php
			$i = 1;
			foreach ($flow as $situacao)
			{
				switch($situacao)
				{
					case 'validacao':?>
						<li class="validacao <?php echo ($status_pauta != "validacao" ? "inactive" : ""); ?>"><?php echo $i; ?><br>Validação</li><?php
					break;
					case 'discussao': ?>
						<li class="discussao <?php echo ($status_pauta != "discussao" ? "inactive" : ""); ?>"><?php echo $i; ?><br>Discussão</li><?php
					break;
					case 'relatoria':
					case 'eleicao_relator': ?>
						<li class="relatoria <?php echo ($status_pauta != "relatoria" ? "inactive" : ""); ?>"><?php echo $i; ?><br>Relatoria</li><?php
					break;
					case 'emvotacao': ?>
						<li class="emvotacao <?php echo ($status_pauta != "emvotacao" ? "inactive" : ""); ?>"><?php echo $i; ?><br>Votação</li><?php
					break;
					case 'naovalidada':
					case 'comresolucao': ?>
						<li class="comresolucao <?php echo ($status_pauta != "comresolucao" && $status_pauta != "naovalidada" ? "inactive" : ""); ?>"><?php echo $i; ?><br>Resolução</li><?php
					break;
				}
				$i++;
			}?>
		</ul>
	</div>



				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<div id="leader">
						<h1 class="entry-title"><?php the_title(); ?></h1>
						<div class="entry-prazo">

							<?php
							if ( \Delibera\Flow::getDeadlineDays( $post->ID ) == -1 )
								_e( 'Prazo encerrado', 'delibera' );
							else
								printf( _n( 'Por mais <br><span class="numero">1</span> dia', 'Por mais <br><span class="numero">%1$s</span> dias', \Delibera\Flow::getDeadlineDays( $post->ID ), 'delibera' ), number_format_i18n( \Delibera\Flow::getDeadlineDays( $post->ID ) ) );
							?>
						</div><!-- .entry-prazo -->
						<!--div class="entry-meta">
							<div class="entry-situacao">
								<?php printf( __( 'Situação da pauta', 'delibera' ).': %s', delibera_get_situacao($post->ID)->name );?>
							</div .entry-situacao -->
							<div class="entry-author">
								<?php _e( 'Criado por', 'delibera' ); ?>
								<span class="author vcard">
									<a class="url fn n" href="<?php echo get_site_url().'/delibera/' . get_the_author_meta( 'ID' ) . '/pautas' ; ?>" title="<?php printf( __( 'Ver o perfil de %s', 'delibera' ), get_the_author() ); ?>">
										<?php the_author(); ?>
									</a>
								</span>
							</div><!-- .entry-author -->
							<!--div class="entry-comment">
								<?php if(comments_open(get_the_ID()) && is_user_logged_in())
								{?>
									<a href="#delibera-comments">
										<?php _e( 'Discuta', 'delibera' ); ?>
										<?php comments_number( '', '('. __( 'Um comentário', 'delibera' ) . ')', '('. __( '% comentários', 'delibera' ) . ')' ); ?>
									</a>
								<?php
								}
								elseif(delibera_comments_is_open(get_the_ID()) && !is_user_logged_in())
								{
								?>
									<a href="<?php echo wp_login_url( get_post_type() == "pauta" ? get_permalink() : delibera_get_comment_link());?>#delibera-comments">
										<?php _e( 'Discuta', 'delibera' ); ?>
										<?php comments_number( '', '('. __( 'Um comentário', 'delibera' ) . ')', '('. __( '% comentários', 'delibera' ) . ')' ); ?>
									</a>
								<?php
								}
								?>
							</div> .entry-comment -->

							<div class="entry-attachment">
							</div><!-- .entry-attachment -->

						<!--/div><!-- .entry-meta -->
					</div><!-- #leader -->

					<div class="entry-content">
						<?php the_content(); ?>
					</div><!-- .entry-content -->

							<div class="entry-print button">
								<a href="?delibera_print=1" class="">Imprimir</a>
							</div><!-- .entry-print -->
							<div class="entry-seguir button">
								<?php echo delibera_gerar_seguir($post->ID); ?>
							</div>
							<?php social_buttons(get_permalink(), get_the_title()); ?>

				</div><!-- #post-## -->

</div>
<?php endwhile; // end of the loop. ?>
