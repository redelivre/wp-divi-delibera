<?php

get_header();
global $wp_query;

query_posts(array_merge($wp_query->query, array(
    'posts_per_page' => -1,
    //'fields'     => 'ids',
)));

if ( have_posts() ) {
?>
<div class="entry-content">
  <div class="et_pb_section  et_pb_section_0 et_section_regular">
    <div class=" et_pb_row et_pb_row_0">
      <?php
        $button_url = "mailto:";
        $button_text = "Super Pressão!";
        $emails = "";
        $aux = "";
        // The Loop
        if ( have_posts() ) {

          while ( have_posts() ) {
            the_post();
            $emails = get_post_meta(  get_the_ID(), 'public_agent_email', true) ? get_post_meta(  get_the_ID(), 'public_agent_email', true):"";
            if ($emails) $aux .= $aux ? "," . $emails: $emails;
          }
          wp_reset_postdata();
          /* Restore original Post Data */
        } else {
          // no posts found
        }
        $button_url .= $aux . "?subject=" . get_option('makepressure_email_title') . "&body=" . get_option('makepressure_email_body');
        // Nothing to output if neither Button Text nor Button URL defined
        if ( '' === $button_text && '' === $button_url ) {
          return;
        }
        $module_class = " et_pb_module";
        $output = sprintf(
          '<div class="et_pb_button_module_wrapper makepressure_search_button">
            <a class="makepressure_superpressure et_pb_button  et_pb_makepressure_button_0 et_pb_module et_pb_bg_layout_light" href="%1$s">%2$s</a>
          </div>',
          esc_url( $button_url ),
          '' !== $button_text ? esc_html( $button_text ) : esc_url( $button_url )
        );
          echo $output;
      ?>
      <?php
        $button_url = "https://mail.google.com/mail?view=cm&tf=0&to=";
        $button_text = "Super Pressão Gmail!";

        $button_url .= $aux . "&su=" . get_option('makepressure_email_title') . "&body=" . get_option('makepressure_email_body');
        // Nothing to output if neither Button Text nor Button URL defined
        if ( '' === $button_text && '' === $button_url ) {
          return;
        }
        $module_class = " et_pb_module";
        $output = sprintf(
          '<div class="et_pb_button_module_wrapper makepressure_search_button_gmail">
            <a class="makepressure_superpressure et_pb_button  et_pb_makepressure_button_0 et_pb_module et_pb_bg_layout_light" href="%1$s">%2$s</a>
          </div>',
          esc_url( $button_url ),
          '' !== $button_text ? esc_html( $button_text ) : esc_url( $button_url )
        );
          echo $output;
      ?>
      <div><h1><?php _e("Veja o resultado da sua Busca", "makepressure"); ?>:</h1></div>
      <div class="et_pb_column et_pb_column_4_4  et_pb_column_2">
        <div class="et_pb_portfolio_grid clearfix et_pb_module et_pb_bg_layout_light  et_pb_public_agent_0">
          <?php 
          while ( have_posts() ) {
            the_post();
          ?>
            <div class="makepressure_grid makepressure_grid_item post-7848 public_agent type-public_agent status-publish has-post-thumbnail hentry public_agent_state-ac public_agent_party-pcdob public_agent_job-deputado_federal public_agent_genre-masculino public_agent_commission-suplente-cffc" id="post-7848">


            <div class="makepressure_label">
              <h2 class="makepressure_title">
                <a href="<?php esc_url( the_permalink() ); ?>"><?= get_the_title(); ?></a>
              </h2>
              <strong class="makepressure_upper">
              <?php 
                $state = wp_get_post_terms( get_the_ID() , 'public_agent_state');
                $party = wp_get_post_terms( get_the_ID() , 'public_agent_party');
                if (isset($state[0])) {
                  $state =  $state[0];
                  echo $state->slug;
                }
                if (isset($party[0])) {
                  $party = $party[0];
                  echo ' / ';
                  echo $party->slug;
                }
              ?>
              </strong>
            </div>
            <a href="<?php esc_url( the_permalink() ); ?>">
              <?php if(has_post_thumbnail()) : ?>
              <?php the_post_thumbnail(array(175,175), array('class' => 'makepressure_post_main_image')); ?>
              <?php endif; ?>
            </a>

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

                <?php if ( get_post_meta(  get_the_ID(), 'public_agent_email', true) ) : ?>
                  <a id="<?php echo get_the_ID(); ?>" target="_blank" class="fa fa-3x fa-google makepressure_gmail" href="https://mail.google.com/mail?view=cm&tf=0&to=<?php print_r(get_post_meta(  get_the_ID(), 'public_agent_email', true)); ?>&su=Excelentissim<?php echo $genre_slug=='feminino'?'a':'o'; ?>%20<?php echo get_post_meta(  get_the_ID(), 'public_agent_cargo', true)?get_post_meta(  get_the_ID(), 'public_agent_cargo', true):""; ?>%20<?php echo get_the_title(); ?>&body=Excelentissim<?php echo $genre_slug=='feminino'?'a':'o'; ?>%20<?php echo get_post_meta(  get_the_ID(), 'public_agent_cargo', true) ?get_post_meta(  get_the_ID(), 'public_agent_cargo', true):""; ?>%20<?php echo get_the_title(); ?>,  %0A%0A<?php echo $email_body; ?>" ></a>
                <?php endif; ?>

                <?php if ( get_post_meta(  get_the_ID(), 'public_agent_twitter', true) ) : ?>
                  <a id="<?php echo get_the_ID(); ?>" class="fa fa-twitter fa-3x makepressure_twitter" href="https://twitter.com/intent/tweet?text=@<?php echo get_post_meta(  get_the_ID(), 'public_agent_twitter', true ); ?><?php echo $twitter_text; ?>&url=<?php echo $twitter_url; ?>&hashtags=<?php echo $twitter_hashtag; ?>" data-show-count="false"></a>
                <?php endif; ?>
                
                <?php if ( get_post_meta(  get_the_ID(), 'public_agent_facebook', true) ) : ?>
                  <a id="<?php echo get_the_ID(); ?>" class="fa fa-facebook-official fa-3x makepressure_facebook" target="_brank" href="<?php echo get_post_meta(  get_the_ID(), 'public_agent_facebook', true); ?>"></a>
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
