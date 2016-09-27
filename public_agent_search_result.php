<?php

get_header();
global $wp_query;

query_posts(array_merge($wp_query->query, array(
    'posts_per_page' => -1
)));

if ( have_posts() ) {
?>
<div class="entry-content">
  <div class="et_pb_section  et_pb_section_0 et_section_regular">
    <div class=" et_pb_row et_pb_row_0">
      <h1><?php _e("Veja o resultado da sua Busca", "makepressure"); ?>:</h1>
      <div class="et_pb_column et_pb_column_4_4  et_pb_column_2">
        <div class="et_pb_portfolio_grid clearfix et_pb_module et_pb_bg_layout_light  et_pb_public_agent_0">
          <?php 
          while ( have_posts() ) {
            the_post();
          ?>
            <div class="makepressure_grid makepressure_grid_item post-7848 public_agent type-public_agent status-publish has-post-thumbnail hentry public_agent_state-ac public_agent_party-pcdob public_agent_job-deputado_federal public_agent_genre-masculino public_agent_commission-suplente-cffc" id="post-7848">


            <a href="<?php esc_url( the_permalink() ); ?>">
              <?php the_post_thumbnail(array(175,175), array('class' => 'makepressure_post_main_image')); ?>
            </a>
            <div class="makepressure_label">
              <h2 class="makepressure_title">
                <a href="<?php esc_url( the_permalink() ); ?>"><?= get_the_title(); ?></a>
              </h2>
              <?php 
                $state = wp_get_post_terms( get_the_ID() , 'public_agent_state');
                $party = wp_get_post_terms( get_the_ID() , 'public_agent_party');
                if (is_array($state)) {
                  $state =  $state[0];
                }
                if (is_array($party)) {
                  $party = $party[0];
                }
              ?>
              <strong class="makepressure_upper"><?=  $state->slug ?> / <?= $party->slug ?></strong>
            </div>

            <p class="post-meta"></p>
            <?php 
              $email_subject = get_option( 'makepressure_email_title' );
              $email_body = get_option( 'makepressure_email_body' );
              $more_emails = get_option( 'makepressure_more_emails' );

              $twitter_text = get_option( 'makepressure_twitter_text' );
              $twitter_url = get_option( 'makepressure_twitter_url' );
              $twitter_hashtag = get_option( 'makepressure_twitter_hashtag' );
            ?>

              <div class="makepressure_action" >
                <?php
                $genre = wp_get_post_terms( get_the_ID() , 'public_agent_genre');
                  if (is_array($genre)) {
                    $genre = $genre[0];
                    $genre_slug = $genre->slug;
                  }
                if ( get_post_meta(  get_the_ID(), 'public_agent_email', true) ) : ?>
                    <a id="<?php echo get_the_ID(); ?>" class="fa fa-3x fa-envelope makepressure_email" href="mailto:<?php print_r(get_post_meta(  get_the_ID(), 'public_agent_email', true)); ?>?subject=Excelentissim<?php echo $genre_slug=='feminino'?'a':'o'; ?>%20<?php echo get_post_meta(  get_the_ID(), 'public_agent_cargo', true)?get_post_meta(  get_the_ID(), 'public_agent_cargo', true):""; ?>%20<?php echo get_the_title(); ?>&body=Excelentissim<?php echo $genre_slug=='feminino'?'a':'o'; ?>%20<?php echo get_post_meta(  get_the_ID(), 'public_agent_cargo', true) ?get_post_meta(  get_the_ID(), 'public_agent_cargo', true):""; ?>%20<?php echo get_the_title(); ?>,  %0A%0A<?php echo $email_body; ?>" ></a>
                <?php endif; ?>

                <?php if ( get_post_meta(  get_the_ID(), 'public_agent_twitter', true) ) : ?>
                  <a id="<?php echo get_the_ID(); ?>" class="fa fa-twitter fa-3x makepressure_twitter" href="https://twitter.com/intent/tweet?text=@<?php echo get_post_meta(  get_the_ID(), 'public_agent_twitter', true ); ?><?php echo $twitter_text; ?>&url=<?php echo $twitter_url; ?>&hashtags=<?php echo $twitter_hashtag; ?>" data-show-count="false"></a>
                <?php endif; ?>
                
                <?php if ( get_post_meta(  get_the_ID(), 'public_agent_facebook', true) ) : ?>
                  <a id="<?php echo get_the_ID(); ?>" class="fa fa-facebook-official fa-3x makepressure_facebook" target="_brank" href="<?php echo get_post_meta(  get_the_ID(), 'public_agent_facebook', true); ?>""></a>
                <?php endif; ?>
              </div>
              </div> <!-- .et_pb_portfolio_item -->
          <?php
          }
          ?>
        </div>
      </div>
    </div> <!-- .et_pb_column -->
  </div> <!-- .et_pb_row -->
</div> <!-- .et_pb_section -->
<?php
}
else{
  ?>
  <h1><? _e("Nenhum post encontrado :(", "makepressure"); ?></h1>
<?php  
}

get_footer();