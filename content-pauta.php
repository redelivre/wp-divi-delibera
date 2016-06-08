<?php if ( have_posts() ) while ( have_posts() ) : the_post();
	$status_pauta = delibera_get_situacao($post->ID)->slug;
//echo $status_pauta;
	global $DeliberaFlow;
	$flow = $DeliberaFlow->get(get_the_ID());

	$term_list = wp_get_post_terms(get_the_ID(), 'tema', array("fields" => "names"));
?>

            <article class="single-content">
                <h4 class="single-taxonomy"><?=is_array($term_list) && count($term_list) > 0 ? $term_list[0]: "" ?></h4>
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
                        if(is_array($tags))
                        {
	                        $html = '<div class="post_tags">';
	                        foreach ($tags as $tag){
	                            $tag_link = get_tag_link($tag->term_id);
	
	                            $html .= "<a href='{$tag_link}' title='{$tag->name} Tag' class='grid-ideia-tag'>";
	                            $html .= "{$tag->name}</a>";
	                        }

                            $html .= '</div>';

	                        echo $html;
                        }
                        ?>
                    </p>
                    <div class="single-meta social-media">
<span class="single-author">
<a href="<?php echo get_site_url().'/delibera/' . get_the_author_meta( 'ID' ) . '/pautas' ; ?>" >
    <span class="author-picture" style="background-image: url('<?php echo divi_child_get_avatar_url(get_avatar(get_the_author_meta( 'ID' ))); ?> ');"> </span>
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
<a class="social-button share-twitter" target="_blank" href="https://twitter.com/share?url=<?=get_permalink();?>&text=<?=the_title();?>">
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

                <!--
                <?php

                if (is_user_logged_in()) {
                    $user_id = get_current_user_id();
                    $ip = $_SERVER['REMOTE_ADDR'];
                }

                $type = "pauta";

                if(get_post_meta($post->ID, 'delibera_numero_curtir', true) == "")
                {
                    $cutidas = 0;
                }
                else
                {
                    $cutidas = get_post_meta($post->ID, 'delibera_numero_curtir', true);
                }

                $jacurtiu = delibera_ja_curtiu($ID, $user_id, $ip, $type);

                if(get_post_meta($post->ID, 'delibera_numero_discordar', true) == "")
                {
                    $descutidas = 0;
                }
                else
                {
                    $descutidas = get_post_meta($post->ID, 'delibera_numero_discordar', true);
                }

                $jadescurtiu = delibera_ja_discordou($post->ID, $user_id, $ip, $type);

                ?>

                <div>

                    <div class="delibera_like" style="float: left; padding-right: 15px; background:none; text-indent:unset;">
                        <img src="http://acidadequeeuquero.beta.campanhacompleta.com.br/files/2016/04/up.png">
                        <div class="delibera-like-count" style="float: right"><?php echo $cutidas; ?></div>
                        <input type='hidden' name='object_id' value='<?php echo $post->ID?>' />
                        <input type='hidden' name='type' value='pauta' />
                    </div>
                    <div class="deslike delibera_unlike" style="float: left; padding-right: 15px; background:none;">
                        <img src="http://acidadequeeuquero.beta.campanhacompleta.com.br/files/2016/04/down.png">
                        <div class="delibera-unlike-count" style="float: right"><?php echo $descutidas; ?></div>
                        <input type='hidden' name='object_id' value='<?php echo $post->ID?>' />
                        <input type='hidden' name='type' value='pauta' />
                    </div>
                    <div class="coment">
                        <img src="http://acidadequeeuquero.beta.campanhacompleta.com.br/files/2016/04/com.png">
                        01
                    </div>

                </div>

                <div id="delibera-comment-botoes-<?php echo $post->ID;?>" class="delibera-comment-botoes" >
                </div-->

                <div id="delibera-comment-botoes" style="float: left; text-align: left; width: 100%" class="delibera-comment-botoes" ><?php
                    echo delibera_gerar_curtir($post->ID, 'pauta');
                    echo delibera_gerar_discordar($post->ID, 'pauta');?>
                </div>

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
