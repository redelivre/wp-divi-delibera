<?php
if(defined('WP_DEBUG') && WP_DEBUG)
{
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL & ~E_STRICT);
}

if(!defined('WPOPAUTH_INVALID_EMAIL'))
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
    $terms = get_terms( 'bairro', array( 'hide_empty' => false ) );
    $telefone = get_user_meta($user->ID, 'telefone', true);
    $city = get_user_meta($user->ID, 'city', true); //TODO term?
    $state = get_user_meta($user->ID, 'state', true); //TODO term?
    $cpf = get_user_meta($user->ID, 'cpf', true);
    $company = get_user_meta($user->ID, 'company', true);
    ?>

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
		<tr>
            <th><label for="telefone"><?php _e( 'Telephone' ); ?></label></th>
            <td>
            	<input type="text" name="telefone" id="telefone" value="<?php echo esc_attr( $telefone ); ?>" />
			</td>
			<th><label ><?php _e( 'CPF' ); ?></label></th>
			<td>
				<input type="text" name="cpf" class="cpf" value="<?php echo $cpf; ?>" placeholder="CPF" />
			</td>
			<th><label ><?php _e( 'Cidade' ); ?></label></th>
			<td>
				<input type="text" name="city" class="city" value="<?php echo $city; ?>" placeholder="Cidade" />
			</td>
			<th><label ><?php _e( 'Estado (UF)' ); ?></label></th>
			<td>
				<input type="text" name="state" class="state" value="<?php echo $state; ?>" placeholder="Estado" />
			</td>
		</tr>
		<tr>
			<th><label ><?php _e( 'Instituição' ); ?></label></th>
			<td>
				<input type="text" name="company" class="company" value="<?php echo $company; ?>" placeholder="Instituição" />
			</td>
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
    
    if(array_key_exists('telefone', $_POST))
    {
    	update_user_meta($user_id, 'telefone', esc_attr($_POST['telefone']));
    }
    
    if(array_key_exists('city', $_POST) )
    {
    	update_user_meta($user_id, 'city', sanitize_text_field($_POST['city']));
    }
    if(array_key_exists('state', $_POST) )
    {
    	update_user_meta($user_id, 'state', sanitize_text_field($_POST['state']));
    }
    if(array_key_exists('cpf', $_POST) )
    {
    	update_user_meta($user_id, 'cpf', sanitize_text_field($_POST['cpf']));
    }
    if(array_key_exists('company', $_POST) )
    {
    	update_user_meta($user_id, 'company', sanitize_text_field($_POST['company']));
    }
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
	{?>
		<div class="custom-register-bairro-empty"><?php 
			_e('Ainda não há bairros cadastrados');?>
		</div><?php
	}
}

/**
 * Add Customize Options and settings
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function divi_child_customize_register( $wp_customize )
{
	/*
	 *
	 */
	$wp_customize->add_section( 'SecondForm', array(
		'title'    => __( 'Opções do Segundo Formulário de Cadastro', 'divi-child' ),
		'priority' => 30,
	) );

	// Element to append html content
	$wp_customize->add_setting( 'divi-child-second-form-show', array(
		'default'     => 1,
		'capability'    => 'edit_theme_options',
	) );
	
	$wp_customize->add_control( 'divi-child-second-form-show', array(
		'label'      => __( 'Pedir mais dados ao usuário?', 'divi-child'),
		'section'    => 'SecondForm',
		'type'		 => 'checkbox',
		'std'		 => 1
	) );
	
	// Element to append html content
	$wp_customize->add_setting( 'divi-child-second-form-show-firstname', array(
		'default'     => 0,
		'capability'    => 'edit_theme_options',
	) );

	$wp_customize->add_control( 'divi-child-second-form-show-firstname', array(
		'label'      => __( 'Pedir o primeiro nome?', 'divi-child'),
		'section'    => 'SecondForm',
		'type'		 => 'checkbox',
		'std'		 => 1
	) );
	
	$wp_customize->add_setting( 'divi-child-second-form-required-firstname', array(
		'default'     => 0,
		'capability'    => 'edit_theme_options',
	) );
	
	$wp_customize->add_control( 'divi-child-second-form-required-firstname', array(
		'label'      => __( 'Primeiro nome é obrigatório?', 'divi-child'),
		'section'    => 'SecondForm',
		'type'		 => 'checkbox',
		'std'		 => 1
	) );

	$wp_customize->add_setting( 'divi-child-second-form-show-lastname', array(
		'default'     => 0,
		'capability'    => 'edit_theme_options',
	) );
	
	$wp_customize->add_control( 'divi-child-second-form-show-lastname', array(
		'label'      => __( 'Pedir o sobrenome?', 'divi-child'),
		'section'    => 'SecondForm',
		'type'		 => 'checkbox',
		'std'		 => 1
	) );
	
	$wp_customize->add_setting( 'divi-child-second-form-required-lastname', array(
		'default'     => 0,
		'capability'    => 'edit_theme_options',
	) );
	
	$wp_customize->add_control( 'divi-child-second-form-required-lastname', array(
		'label'      => __( 'O sobrenome é obrigatório?', 'divi-child'),
		'section'    => 'SecondForm',
		'type'		 => 'checkbox',
		'std'		 => 1
	) );
	$wp_customize->add_setting( 'divi-child-second-form-show-bairro', array(
		'default'     => 0,
		'capability'    => 'edit_theme_options',
	) );
	
	$wp_customize->add_control( 'divi-child-second-form-show-bairro', array(
		'label'      => __( 'Pedir o bairro?', 'divi-child'),
		'section'    => 'SecondForm',
		'type'		 => 'checkbox',
		'std'		 => 1
	) );
	
	$wp_customize->add_setting( 'divi-child-second-form-required-bairro', array(
		'default'     => 0,
		'capability'    => 'edit_theme_options',
	) );
	
	$wp_customize->add_control( 'divi-child-second-form-required-bairro', array(
		'label'      => __( 'O bairro é obrigatório?', 'divi-child'),
		'section'    => 'SecondForm',
		'type'		 => 'checkbox',
		'std'		 => 1
	) );
	$wp_customize->add_setting( 'divi-child-second-form-show-city', array(
		'default'     => 0,
		'capability'    => 'edit_theme_options',
	) );
	
	$wp_customize->add_control( 'divi-child-second-form-show-city', array(
		'label'      => __( 'Pedir a cidade?', 'divi-child'),
		'section'    => 'SecondForm',
		'type'		 => 'checkbox',
		'std'		 => 1
	) );
	
	$wp_customize->add_setting( 'divi-child-second-form-required-city', array(
		'default'     => 0,
		'capability'    => 'edit_theme_options',
	) );
	
	$wp_customize->add_control( 'divi-child-second-form-required-city', array(
		'label'      => __( 'A cidade é obrigatória?', 'divi-child'),
		'section'    => 'SecondForm',
		'type'		 => 'checkbox',
		'std'		 => 1
	) );
	
	$wp_customize->add_setting( 'divi-child-second-form-show-state', array(
		'default'     => 0,
		'capability'    => 'edit_theme_options',
	) );
	
	$wp_customize->add_control( 'divi-child-second-form-show-state', array(
		'label'      => __( 'Pedir o estado?', 'divi-child'),
		'section'    => 'SecondForm',
		'type'		 => 'checkbox',
		'std'		 => 1
	) );
	
	$wp_customize->add_setting( 'divi-child-second-form-required-state', array(
		'default'     => 0,
		'capability'    => 'edit_theme_options',
	) );
	
	$wp_customize->add_control( 'divi-child-second-form-required-state', array(
		'label'      => __( 'O estado é obrigatório?', 'divi-child'),
		'section'    => 'SecondForm',
		'type'		 => 'checkbox',
		'std'		 => 1
	) );
	
	$wp_customize->add_setting( 'divi-child-second-form-show-cpf', array(
		'default'     => 0,
		'capability'    => 'edit_theme_options',
	) );
	
	$wp_customize->add_control( 'divi-child-second-form-show-cpf', array(
		'label'      => __( 'Pedir o CPF?', 'divi-child'),
		'section'    => 'SecondForm',
		'type'		 => 'checkbox',
		'std'		 => 1
	) );
	
	$wp_customize->add_setting( 'divi-child-second-form-required-cpf', array(
		'default'     => 0,
		'capability'    => 'edit_theme_options',
	) );
	
	$wp_customize->add_control( 'divi-child-second-form-required-cpf', array(
		'label'      => __( 'O CPF é obrigatório?', 'divi-child'),
		'section'    => 'SecondForm',
		'type'		 => 'checkbox',
		'std'		 => 1
	) );
	
	$wp_customize->add_setting( 'divi-child-second-form-show-company', array(
		'default'     => 0,
		'capability'    => 'edit_theme_options',
	) );
	
	$wp_customize->add_control( 'divi-child-second-form-show-company', array(
		'label'      => __( 'Pedir a instituição?', 'divi-child'),
		'section'    => 'SecondForm',
		'type'		 => 'checkbox',
		'std'		 => 1
	) );
	
	$wp_customize->add_setting( 'divi-child-second-form-required-company', array(
		'default'     => 0,
		'capability'    => 'edit_theme_options',
	) );
	
	$wp_customize->add_control( 'divi-child-second-form-required-company', array(
		'label'      => __( 'A instituição é obrigatória?', 'divi-child'),
		'section'    => 'SecondForm',
		'type'		 => 'checkbox',
		'std'		 => 1
	) );
	
}
add_action( 'customize_register', 'divi_child_customize_register');

/**
 * Add Customize Options and settings
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function divi_child_customize_login( $wp_customize )
{
	/*
	 *
	 */
	$wp_customize->add_section( 'DeliberaLoginForm', array(
		'title'    => __( 'Opções da tela de Login', 'divi-child' ),
		'priority' => 30,
	) );

	// Element to append html content
	$wp_customize->add_setting( 'divi-child-delibera-login-show', array(
		'default'     => 1,
		'capability'    => 'edit_theme_options',
	) );

	$wp_customize->add_control( 'divi-child-delibera-login-show', array(
		'label'      => __( 'Usar tela de login especial do Delibera?', 'divi-child'),
		'section'    => 'DeliberaLoginForm',
		'type'		 => 'checkbox',
		'std'		 => 1
	) );

	$wp_customize->add_setting( 'divi-child-delibera-login-header-text', array(
		'default'     => 'Você precisa se cadastrar para participar',
		'capability'    => 'edit_theme_options',
	) );
	
	$wp_customize->add_control( 'divi-child-delibera-login-header-text', array(
		'label'      => __( 'Texto do cabeçalho da caixa de login', 'divi-child'),
		'section'    => 'DeliberaLoginForm',
		'type'		 => 'text',
		'std'		 => 'Você precisa se cadastrar para participar'
	) );
	
	$wp_customize->add_setting( 'divi-child-delibera-login-show-wordpress-login', array(
		'default'     => 1,
		'capability'    => 'edit_theme_options',
	) );
	
	$wp_customize->add_control( 'divi-child-delibera-login-show-wordpress-login', array(
		'label'      => __( 'Mostrar Login do Wordpress?', 'divi-child'),
		'section'    => 'DeliberaLoginForm',
		'type'		 => 'checkbox',
		'std'		 => 1
	) );
	
	$wp_customize->add_setting( 'divi-child-delibera-login-show-wordpress-register', array(
		'default'     => 1,
		'capability'    => 'edit_theme_options',
	) );
	
	$wp_customize->add_control( 'divi-child-delibera-login-show-wordpress-register', array(
		'label'      => __( 'Mostrar botão de registro do Wordpress?', 'divi-child'),
		'section'    => 'DeliberaLoginForm',
		'type'		 => 'checkbox',
		'std'		 => 1
	) );

	$wp_customize->add_setting( 'divi-child-delibera-login-show-wordpress-lost-password', array(
		'default'     => 1,
		'capability'    => 'edit_theme_options',
	) );
	
	$wp_customize->add_control( 'divi-child-delibera-login-show-wordpress-lost-password', array(
		'label'      => __( 'Mostrar botão de recuperação de senha do Wordpress?', 'divi-child'),
		'section'    => 'DeliberaLoginForm',
		'type'		 => 'checkbox',
		'std'		 => 1
	) );
	
	$wp_customize->add_setting( 'divi-child-delibera-login-force-login-service', array(
		'default'     => 'n',
		'capability'    => 'edit_theme_options',
	) );
	
	$choices = array('n' => 'Selecione um provider' );
	if(class_exists('WPOpauth'))
	{
		global $WPOpauth;
		if(is_object($WPOpauth) && method_exists($WPOpauth, 'getStrategies'))
		{
			$strategies = $WPOpauth->getStrategies();
			foreach ($strategies as $id => $values)
			{
				$choices[$id] = $values['name'];
			}
		}
	}
	
	$wp_customize->add_control( 'divi-child-delibera-login-force-login-service', array(
		'label'      => __( 'Tornar o botão de login o serviço:', 'divi-child'),
		'section'    => 'DeliberaLoginForm',
		'type'		 => 'select',
		'std'		 => 'n',
		'choices'	 => $choices,
	) );
	
}
add_action( 'customize_register', 'divi_child_customize_login');

function divi_child_customize_controls_enqueue_scripts()
{
	wp_enqueue_script('divi-delibera-custom-preview', get_stylesheet_directory_uri().'/js/customizer_section.js', array('customize-controls'));
}
add_action( 'customize_controls_enqueue_scripts', 'divi_child_customize_controls_enqueue_scripts');

function divi_child_second_register()
{
	if(is_user_logged_in() && get_theme_mod('divi-child-second-form-show', true ) )
	{
		$current_user = wp_get_current_user();
		$valid_email = strpos($current_user->user_email, WPOPAUTH_INVALID_EMAIL) === false;
		$telefone = get_user_meta($current_user->ID, 'telefone', true);
		$bairro = array_shift(wp_get_object_terms($current_user->ID, 'bairro'));
		$bairros = get_terms( 'bairro', array( 'hide_empty' => false, 'number' => 1 ) );
		$firstname = $current_user->user_firstname;
		$lastname = $current_user->user_lastname;
		$city = get_user_meta($current_user->ID, 'city', true); //TODO term?
		$state = get_user_meta($current_user->ID, 'state', true); //TODO term?
		$cpf = get_user_meta($current_user->ID, 'cpf', true);
		$company = get_user_meta($current_user->ID, 'company', true);
		
		$firstname_required = get_theme_mod('divi-child-second-form-required-firstname', false);
		$lastname_required = get_theme_mod('divi-child-second-form-required-lastname', false);
		$city_required = get_theme_mod('divi-child-second-form-required-city', false);
		$state_required = get_theme_mod('divi-child-second-form-required-state', false);
		$cpf_required = get_theme_mod('divi-child-second-form-required-cpf', false);
		$company_required = get_theme_mod('divi-child-second-form-required-company', false);
		$email_required = get_theme_mod('divi-child-second-form-required-email', true);
		$telefone_required = get_theme_mod('divi-child-second-form-required-telefone', true);
		$bairro_required = get_theme_mod('divi-child-second-form-required-bairro', false);
		
		$firstname_show = get_theme_mod('divi-child-second-form-show-firstname', false);
		$lastname_show = get_theme_mod('divi-child-second-form-show-lastname', false);
		$city_show = get_theme_mod('divi-child-second-form-show-city', false);
		$state_show = get_theme_mod('divi-child-second-form-show-state', false);
		$cpf_show = get_theme_mod('divi-child-second-form-show-cpf', false);
		$company_show = get_theme_mod('divi-child-second-form-show-company', false);
		$email_show = get_theme_mod('divi-child-second-form-show-email', true);
		$telefone_show = get_theme_mod('divi-child-second-form-show-telefone', true);
		$bairro_show = get_theme_mod('divi-child-second-form-show-bairro', false);
		$display_name_show = get_theme_mod('divi-child-second-form-show-display_name', true);
		
		$logo = ( $user_logo = et_get_option( 'divi_logo' ) ) && '' != $user_logo
		? $user_logo
		: get_template_directory_uri() . '/images/logo.png';
		
		if(!(
				(
						empty($bairros) || !$bairro_show ||
						(is_object($bairro) && ( (get_class($bairro) == 'WP_Term' || get_class($bairro) == 'stdClass')))
				) &&
				$valid_email &&
				strlen($telefone) > 0 &&
				(!$firstname_show || !$firstname_required || strlen($firstname) > 0) &&
				(!$lastname_show || !$lastname_required || strlen($lastname) > 0) &&
				(!$city_show || !$city_required || strlen($city) > 0) &&
				(!$state_show || !$state_required || strlen($state) > 0) &&
				(!$cpf_show || !$cpf_required || strlen($cpf) > 0) &&
				(!$company_show || !$company_required || strlen($company) > 0)
			)
		)
		{
			?>
			<div class="second-register-painel">
				<?php wp_nonce_field("second_register"); ?>
				<span class="close-button">X</span>
				<img alt="" src="<?php echo esc_attr($logo); ?>"><?php
				if($display_name_show)
				{
					?>
					<p>
						<input type="text" class="user-name" value="<?php echo $current_user->display_name; ?>" disabled="disabled" />
					</p><?php
				}
				if($firstname_show)
				{
					?>
					<p>
						<input type="text" name="user-firstname" class="user-firstname" value="<?php echo $firstname; ?>" placeholder="Primeiro Nome" />
					</p><?php
				}
				if($lastname_show)
				{
					?>
					<p>
						<input type="text" name="user-lastname" class="user-lastname" value="<?php echo $lastname; ?>" placeholder="Sobrenome" />
					</p><?php
				}
				if($email_show)
				{
					?>
					<p>
						<input type="text" name="user-email" class="user-email" value="<?php echo $current_user->user_email; ?>" <?php echo $valid_email ? 'disabled="disabled"' : ''; ?> />
					</p><?php
				}
				if($telefone_show)
				{
					?>
					<p>
						<input type="text" placeholder="Telefone (WhatsApp)" name="phone" value="<?php echo $telefone; ?>"/>
					</p><?php
				}
				if($cpf_show)
				{
					?>
					<p>
						<input type="text" name="cpf" class="cpf" value="<?php echo $cpf; ?>" placeholder="CPF" />
					</p><?php
				}
				if($bairro_show)
				{
					?>
					<p>
						<?php
							diviSelectBairro();
						?>
					</p><?php
				}
				if($city_show)
				{
					?>
					<p>
						<input type="text" name="city" class="city" value="<?php echo $city; ?>" placeholder="Cidade" />
					</p><?php
				}
				if($state_show)
				{
					?>
					<p>
						<input type="text" name="state" class="state" value="<?php echo $state; ?>" placeholder="Estado" />
					</p><?php
				}
				if($company_show)
				{
					?>
					<p>
						<input type="text" name="company" class="company" value="<?php echo $company; ?>" placeholder="Instituição" />
					</p><?php
				}
				?>
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
		$user_to_update = array('ID' => $user_id);
		
		if(array_key_exists('email', $_POST) && !empty($_POST['email']))
		{
			$user_to_update['user_email'] = sanitize_email($_POST['email']);
		}
		if(array_key_exists('user-firstname', $_POST) && !empty($_POST['user-firstname']))
		{
			$user_to_update['first_name'] = sanitize_text_field($_POST['user-firstname']);
		}
		if(array_key_exists('user-lastname', $_POST) && !empty($_POST['user-lastname']))
		{
			$user_to_update['last_name'] = sanitize_text_field($_POST['user-lastname']);
		}
		
		if(count($user_to_update) > 1)
		{
			$result = wp_update_user($user_to_update);
		}
		
		$term = (int)esc_attr( $_POST['bairro'] );
		
		/* Sets the terms (we're just using a single term) for the user. */
		wp_set_object_terms( $user_id, $term, 'bairro', false);
		
		clean_object_term_cache( $user_id, 'bairro' );
		
		if(array_key_exists('telefone', $_POST) )
		{
			update_user_meta($user_id, 'telefone', sanitize_text_field($_POST['telefone']));
		}
		if(array_key_exists('city', $_POST) )
		{
			update_user_meta($user_id, 'city', sanitize_text_field($_POST['city']));
		}
		if(array_key_exists('state', $_POST) )
		{
			update_user_meta($user_id, 'state', sanitize_text_field($_POST['state']));
		}
		if(array_key_exists('cpf', $_POST) )
		{
			update_user_meta($user_id, 'cpf', sanitize_text_field($_POST['cpf']));
		}
		if(array_key_exists('company', $_POST) )
		{
			update_user_meta($user_id, 'company', sanitize_text_field($_POST['company']));
		}
		
	}		
	die();
}
add_action('wp_ajax_second_register', 'divi_child_second_register_callback');

function divi_child_login_form()
{
	$text = get_theme_mod('divi-child-delibera-login-header-text', 'Você precisa se cadastrar para participar');
	$content = '[et_pb_ajax_login admin_label="Ajax Login" title="Entrar" current_page_redirect="on" use_background_color="on" background_color="rgba(71,71,71,0.9)" background_layout="dark" text_orientation="center" use_focus_border_color="off" header_font_size_tablet="51" header_line_height_tablet="2" body_font_size_tablet="51" body_line_height_tablet="2" use_border_color="off" border_color="#ffffff" border_style="solid" custom_button="off" button_letter_spacing="0" button_use_icon="default" button_icon_placement="right" button_on_hover="on" button_letter_spacing_hover="0" show_button="off"]'.$text.'[/et_pb_ajax_login]';
	echo do_shortcode($content);
}
add_action('wp_footer', 'divi_child_login_form');

/**
 * Form Query Variables
 */
function wp_divi_delibera_query_vars($public_query_vars) {
	$public_query_vars[] = 'et_pb_formulario_mobilizacao_export';
	return $public_query_vars;
}
add_filter('query_vars', 'wp_divi_delibera_query_vars');

function wp_divi_customize_login_css()
{
	?>
	<style type="text/css"><?php 
		if(!get_theme_mod('divi-child-delibera-login-show-wordpress-login', true))
		{?>
			.et_pb_ajax_login form {
				display: none;
			}<?php
		}
		if(!get_theme_mod('divi-child-delibera-login-show-wordpress-register', true))
		{?>
			.et_pb_ajax_login .et_pb_register_link {
				display: none;
			}<?php
		}
		if(!get_theme_mod('divi-child-delibera-login-show-wordpress-lost-password', true))
		{?>
			.et_pb_ajax_login .et_pb_forgot_password_link {
				display: none;
			}<?php
		}
		/*if(get_theme_mod('divi-child-delibera-login-show-wordpress-login', true))
		{?>
			.et_pb_ajax_login form {
				display: none;
			}<?php
		}*/
	?>
	</style>
	<?php
}
add_action( 'wp_head', 'wp_divi_customize_login_css');

function wp_divi_add_search_template($template) {
  if (is_search() && (
	   array_key_exists ( 'public_agent_state' , $_GET ) ||
	   array_key_exists ( 'public_agent_party' , $_GET ) ||
	   array_key_exists ( 'public_agent_genre' , $_GET ) ||
	   array_key_exists ( 'public_agent_job' , $_GET )
   	) 
  ) {
    $new_template = locate_template( array('/public_agent_search_result.php') );
    if ( '' != $new_template ) {
      return $new_template;
    }
  }
  return $template;
}

add_filter( 'template_include', 'wp_divi_add_search_template', 99 );


function wp_divi_add_facebook_meta(){
  if (!is_search()){
    global $post;
    ?>
    <meta property="og:url" content="<?= get_permalink($post); ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="<?= $post->post_title; ?>" />
    <meta property="og:description" content="<?= bloginfo("name"); ?>" />
    <?php if (!is_null(the_post_thumbnail_url($post->ID))): ?>
    <meta property="og:image" content="<?= the_post_thumbnail_url($post->ID); ?>" />
    <?php elseif ( get_bloginfo("name") == "Brasil 2036" ) : ?>
    <meta property="og:image" content="http://brasil2036.org.br/files/2016/09/topo3.png" />
    <?php else: ?>
    <meta property="og:image" content="<?= get_header_image(); ?>" />
    <?php endif; ?>
    <?php
  }
}

add_action('wp_head', 'wp_divi_add_facebook_meta');

require_once get_stylesheet_directory().'/includes/widgets/WidgetLoginAjax.php';
require_once get_stylesheet_directory().'/includes/modules/modules.php';