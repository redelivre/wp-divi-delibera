<?php
if(defined(WP_DEBUG) && WP_DEBUG)
{
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
if(!defined(WPOPAUTH_INVALID_EMAIL))
{
	define('WPOPAUTH_INVALID_EMAIL', 'noemail@example.com');
}

/**
 * Enqueues child theme stylesheet, loading first the parent theme stylesheet.
 */
function themify_custom_enqueue_child_theme_styles() {
    wp_enqueue_style( 'parent-theme-css', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'themify_custom_enqueue_child_theme_styles' );

function divi_child_clear_local_storage () {
	wp_enqueue_script( 'divi_child_clear_local_storage', get_stylesheet_directory_uri() . '/js/clear_local_storage.js' );
}
add_action( 'admin_enqueue_scripts', 'divi_child_clear_local_storage', 9999 );

if ( ! function_exists( 'et_builder_add_main_elements' ) ) :
function et_builder_add_main_elements() {
    require ET_BUILDER_DIR . 'main-structure-elements.php';
    require 'includes/builder/main-modules.php';
    do_action( 'et_builder_ready' );
}
endif;

function disable_media_comments( $post_id ) {
    if( get_post_type( $post_id ) == 'attachment' ) {
        wp_die("Comment not allowed.");
    }
    return $open;
}
add_action( 'pre_comment_on_post', 'disable_media_comments' );

/** modifica��es para alterar a taxonomia bairro */

/**
 * Registers the 'bairro' taxonomy for users.  This is a taxonomy for the 'user' object type rather than a
 * post being the object type.
 */
function my_register_user_taxonomy() {

    register_taxonomy(
        'bairro',
        'user',
        array(
            'public' => true,
            'labels' => array(
                'name' => __( 'bairros' ),
                'singular_name' => __( 'bairro' ),
                'menu_name' => __( 'bairros' ),
                'search_items' => __( 'Search bairros' ),
                'popular_items' => __( 'Popular bairros' ),
                'all_items' => __( 'All bairros' ),
                'edit_item' => __( 'Edit bairro' ),
                'update_item' => __( 'Update bairro' ),
                'add_new_item' => __( 'Add New bairro' ),
                'new_item_name' => __( 'New bairro Name' ),
                'separate_items_with_commas' => __( 'Separate bairros with commas' ),
                'add_or_remove_items' => __( 'Add or remove bairros' ),
                'choose_from_most_used' => __( 'Choose from the most popular bairros' ),
            ),
            'rewrite' => array(
                'with_front' => true,
                'slug' => 'author/bairro' // Use 'author' (default WP user slug).
            ),
            'capabilities' => array(
                'manage_terms' => 'edit_users', // Using 'edit_users' cap to keep this simple.
                'edit_terms'   => 'edit_users',
                'delete_terms' => 'edit_users',
                'assign_terms' => 'read',
            ),
            'update_count_callback' => 'my_update_bairro_count' // Use a custom function to update the count.
        )
    );
}
add_action( 'init', 'my_register_user_taxonomy' );

function my_update_bairro_count( $terms, $taxonomy ) {
    global $wpdb;

    foreach ( (array) $terms as $term ) {

        $count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->term_relationships WHERE term_taxonomy_id = %d", $term ) );

        do_action( 'edit_term_taxonomy', $term, $taxonomy );
        $wpdb->update( $wpdb->term_taxonomy, compact( 'count' ), array( 'term_taxonomy_id' => $term ) );
        do_action( 'edited_term_taxonomy', $term, $taxonomy );
    }
}

function my_add_bairro_admin_page() {

    $tax = get_taxonomy( 'bairro' );

    add_users_page(
        esc_attr( $tax->labels->menu_name ),
        esc_attr( $tax->labels->menu_name ),
        $tax->cap->manage_terms,
        'edit-tags.php?taxonomy=' . $tax->name
    );
}

function add_external_link_admin_submenu() {
    global $submenu;
    $permalink = admin_url( 'edit-tags.php' ).'?taxonomy=bairro';
    $submenu['users.php'][] = array( 'Bairros', 'manage_options', $permalink );
}
add_action('admin_menu', 'add_external_link_admin_submenu');

/* Create custom columns for the manage bairro page. */
add_filter( 'manage_edit-bairro_columns', 'my_manage_bairro_user_column' );

/**
 * Unsets the 'posts' column and adds a 'users' column on the manage bairro admin page.
 *
 * @param array $columns An array of columns to be shown in the manage terms table.
 */
function my_manage_bairro_user_column( $columns ) {

    $columns['users'] = __( 'Users' );

    return $columns;
}

/* Customize the output of the custom column on the manage bairros page. */
add_action( 'manage_bairro_custom_column', 'my_manage_bairro_column', 10, 4 );

/**
 * Displays content for custom columns on the manage bairros page in the admin.
 *
 * @param string $display WP just passes an empty string here.
 * @param string $column The name of the custom column.
 * @param int $term_id The ID of the term being displayed in the table.
 */
function my_manage_bairro_column( $display, $column, $term_id ) {


    if ( 'users' === $column ) {
        $term = get_term( $term_id, 'bairro' );
        echo $term->count;
    }
}

/* Add section to the edit user page in the admin to select bairro. */
add_action( 'show_user_profile', 'my_edit_user_bairro_section' );
add_action( 'edit_user_profile', 'my_edit_user_bairro_section' );

/**
 * Adds an additional settings section on the edit user/profile page in the admin.  This section allows users to
 * select a bairro from a checkbox of terms from the bairro taxonomy.  This is just one example of
 * many ways this can be handled.
 *
 * @param object $user The user object currently being edited.
 */
function my_edit_user_bairro_section( $user ) {

    $tax = get_taxonomy( 'bairro' );

    /* Make sure the user can assign terms of the bairro taxonomy before proceeding. */
    if ( !current_user_can( $tax->cap->assign_terms ) )
        return;

    /* Get the terms of the 'bairro' taxonomy. */
    $terms = get_terms( 'bairro', array( 'hide_empty' => false ) ); ?>

    <h3><?php _e( 'Bairro' ); ?></h3>

    <table class="form-table">

        <tr>
            <th><label for="bairro"><?php _e( 'Select bairro' ); ?></label></th>

            <td><?php

                /* If there are any bairro terms, loop through them and display checkboxes. */
                if ( !empty( $terms ) ) {

                    foreach ( $terms as $term ) { ?>
                        <input type="radio" name="bairro" id="bairro-<?php echo esc_attr( $term->slug ); ?>" value="<?php echo esc_attr( $term->slug ); ?>" <?php checked( true, is_object_in_term( $user->ID, 'bairro', $term ) ); ?> /> <label for="bairro-<?php echo esc_attr( $term->slug ); ?>"><?php echo $term->name; ?></label> <br />
                    <?php }
                }

                /* If there are no bairro terms, display a message. */
                else {
                    _e( 'There are no bairros available.' );
                }

                ?></td>
        </tr>

    </table>
<?php }


/* Update the bairro terms when the edit user page is updated. */
add_action( 'personal_options_update', 'my_save_user_bairro_terms' );
add_action( 'edit_user_profile_update', 'my_save_user_bairro_terms' );

/**
 * Saves the term selected on the edit user/profile page in the admin. This function is triggered when the page
 * is updated.  We just grab the posted data and use wp_set_object_terms() to save it.
 *
 * @param int $user_id The ID of the user to save the terms for.
 */
function my_save_user_bairro_terms( $user_id ) {

    $tax = get_taxonomy( 'bairro' );

    /* Make sure the current user can edit the user and assign terms before proceeding. */
    if ( !current_user_can( 'edit_user', $user_id ) && current_user_can( $tax->cap->assign_terms ) )
        return false;

    $term = esc_attr( $_POST['bairro'] );

    /* Sets the terms (we're just using a single term) for the user. */
    wp_set_object_terms( $user_id, array( $term ), 'bairro', false);

    clean_object_term_cache( $user_id, 'bairro' );
}


function my_select_bairro(  ) {

    $tax = get_taxonomy( 'bairro' );

    /* Get the terms of the 'bairro' taxonomy. */
    $terms = get_terms( 'bairro', array( 'hide_empty' => false ) ); ?>

    <h3><?php _e( 'Bairro' ); ?></h3>

    <table class="form-table">

        <tr>
            <th><label for="bairro"><?php _e( 'Select bairro' ); ?></label></th>

            <td><?php

                /* If there are any bairro terms, loop through them and display checkboxes. */
                if ( !empty( $terms ) ) {

                    foreach ( $terms as $term ) { ?>
                        <input type="radio" name="custom-register-bairro" id="bairro-<?php echo esc_attr( $term->slug ); ?>" value="<?php echo esc_attr( $term->slug ); ?>">
                        <label for="bairro-<?php echo esc_attr( $term->slug ); ?>"><?php echo $term->name; ?></label> <br />
                    <?php }
                }

                /* If there are no bairro terms, display a message. */
                else {
                    _e( 'There are no bairros available.' );
                }

                ?></td>
        </tr>

    </table>


<?php
}

if ( ! function_exists( 'custom_register_ajax_send' ) ) :
function custom_register_ajax_send(){
    $dados = array(
        'user_login' => $_POST['username'],
        'user_pass' => $_POST['password'],
        'user_email' => $_POST['email'],
        'first_name' => array_key_exists('realname', $_POST) ? $_POST['realname'] : $_POST['username'],
        'captcha_code'  => $_POST['captcha_code']
    );
    session_start();
    if(class_exists('siCaptcha', false))
    {
        global $registro_captcha;
        $registro_captcha = new siCaptcha();

        if(strcasecmp( $_SESSION["securimage_code_si_com"], $dados['captcha_code'] ) != 0 )
        {
            echo "<div class='erro'>".__('Wrong CAPTCHA', 'si-captcha')."</div>";
            exit();
        }
    }

    $usuario = wp_insert_user($dados);

    if(!is_wp_error($usuario)){

        $term = esc_attr( 'Bairro Alto' );

        /* Sets the terms (we're just using a single term) for the user. */
        wp_set_object_terms( $usuario, array( $term ), 'bairro', false);

        clean_object_term_cache( $usuario, 'bairro' );


        $url = array_key_exists('redirect_to', $_REQUEST) ? $_REQUEST['redirect_to'] : '';
        echo _x('Cadastro efetuado com sucesso. <a href="' . wp_login_url($url) . '">Clique aqui</a> para fazer o login.', 'registro-de-usuario', 'campanha-completa');
        echo '<script>jQuery(".formulario-de-registro-padrao .campos").slideUp();</script>';
    } else {
        $erros = $usuario->get_error_message();
        echo "<div class='erro'>{$erros}</div>";
    }

    exit();
}
endif;

add_action('wp_ajax_check_user_logged_in', 'check_user_logged_in');
add_action('wp_ajax_nopriv_check_user_logged_in', 'check_user_logged_in');

function check_user_logged_in() {
    echo is_user_logged_in() ? '1':'0';
    die();
}

if ( function_exists('register_sidebar') ) {
    register_sidebar(array(
        'name' => 'Homepage Sidebar',
        'id' => 'homepage-sidebar',
        'description' => 'Appears as the sidebar on the custom homepage',
        'before_widget' => '<div style="height: 280px"></div><li id="%1$s" class="widget %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>',
    ));
}

function divi_child_get_avatar_url($get_avatar){
    preg_match("/src='(.*?)'/i", $get_avatar, $matches);
    return $matches[1];
}

function diviSelectBairro(  ) {

	$tax = get_taxonomy( 'bairro' );
	if(is_user_logged_in())
	{
		$user = wp_get_current_user();
	}

	/* Get the terms of the 'bairro' taxonomy. */
	$terms = get_terms( 'bairro', array( 'hide_empty' => false ) );
	if(! empty($terms))
	{
		?>
		<select name="custom-register-bairro" class="custom-register-bairro">
			<option value="-1"><?php _e( 'Selecione um Bairro' ); ?></option><?php
			foreach($terms as $term)
			{?>
				<option value="<?php echo $term->term_id; ?>" <?php if(is_user_logged_in()) selected( is_object_in_term( $user->ID, 'bairro', $term ), true, true ); ?> ><?php echo $term->name; ?></option><?php
			}?>
		</select><?php
		
	}
	/* If there are no bairro terms, display a message. */
	else
	{
		_e('Ainda não há bairros cadastrados');
	}
}

function divi_child_second_register()
{
	if(is_user_logged_in())
	{
		$current_user = wp_get_current_user();
		$valid_email = strpos($current_user->user_email, WPOPAUTH_INVALID_EMAIL) === false;
		$telefone = get_user_meta($current_user->ID, 'telefone', true);
		$bairro = array_shift(wp_get_object_terms($current_user->ID, 'bairro'));
		
		if(!(is_object($bairro) && get_class($bairro) == 'WP_Term' && $valid_email && strlen($telefone) > 0))
		{
			?>
			<div class="second-register-painel">
				<?php wp_nonce_field("second_register"); ?>
				<span class="close-button">X</span>
				<img alt="" src="http://acidadequeeuquero.org.br/files/2016/04/A-cidade-que-eu-quero_Logo-sombra.png">
				<p>
					<input type="text" class="user-name" value="<?php echo $current_user->display_name; ?>" disabled="disabled" />
				</p>
				<p>
					<input type="text" name="user-email" class="user-email" value="<?php echo $current_user->user_email; ?>" <?php echo $valid_email ? 'disabled="disabled"' : ''; ?> />
				</p>
				<p>
					<input type="text" placeholder="Telefone (WhatsApp)" name="phone" value="<?php echo $telefone; ?>"/>
				</p>
				<p>
					<?php
						diviSelectBairro();
					?>
				</p>
				<span class="submit-button"><?php _e('Cadastrar'); ?></span>
			</div>
			<?php
		}
	}
}
add_action('wp_footer', 'divi_child_second_register');

function divi_child_second_register_callback()
{
	if(check_ajax_referer( 'second_register'))
	{
		$current_user = wp_get_current_user();
		$user_id = $current_user->ID;
		$result = false;
		if(array_key_exists('email', $_POST) && !empty($_POST['email']))
		{
			$result = wp_update_user(array('ID' => $user_id, 'user_email' => sanitize_email($_POST['email'])));
		}
		
		$term = (int)esc_attr( $_POST['bairro'] );
		
		/* Sets the terms (we're just using a single term) for the user. */
		wp_set_object_terms( $user_id, $term, 'bairro', false);
		
		clean_object_term_cache( $user_id, 'bairro' );
		
		update_user_meta($user_id, 'telefone', sanitize_text_field($_POST['telefone']));
		
	}		
	die();
}
add_action('wp_ajax_second_register', 'divi_child_second_register_callback');

function divi_child_login_form()
{
	$content = '[et_pb_ajax_login admin_label="Ajax Login" title="Entrar" current_page_redirect="on" use_background_color="on" background_color="rgba(71,71,71,0.9)" background_layout="dark" text_orientation="center" use_focus_border_color="off" header_font_size_tablet="51" header_line_height_tablet="2" body_font_size_tablet="51" body_line_height_tablet="2" use_border_color="off" border_color="#ffffff" border_style="solid" custom_button="off" button_letter_spacing="0" button_use_icon="default" button_icon_placement="right" button_on_hover="on" button_letter_spacing_hover="0" show_button="off"]Você precisa se cadastrar para participar[/et_pb_ajax_login]';
	echo do_shortcode($content);
}
add_action('wp_footer', 'divi_child_login_form');

require_once get_stylesheet_directory().'/includes/widgets/WidgetLoginAjax.php';
?>