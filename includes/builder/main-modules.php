<?php

require_once get_template_directory()."/includes/builder/main-modules.php";

class ET_Builder_Module_Delibera_Member extends ET_Builder_Module {
    function init() {
        $this->name = esc_html__( 'Delibera', 'et_builder' );
        $this->slug = 'et_pb_delibera_pauta22v';

        $this->whitelisted_fields = array(
            'name',
            'position',
            'image_url',
            'animation',
            'background_layout',
            'content_new',
            'admin_label',
            'module_id',
            'module_class',
            'icon_color',
            'icon_hover_color',
            'include_categories',
            'url_tema',
        );

        $this->fields_defaults = array(
            'animation'         => array( 'off' ),
            'background_layout' => array( 'light' ),
        );

        $this->main_css_element = '%%order_class%%.et_pb_delibera_member';
        $this->advanced_options = array(
            'fonts' => array(
                'header' => array(
                    'label'    => esc_html__( 'Header', 'et_builder' ),
                    'css'      => array(
                        'main' => "{$this->main_css_element} h4",
                    ),
                ),
                'body'   => array(
                    'label'    => esc_html__( 'Body', 'et_builder' ),
                    'css'      => array(
                        'main' => "{$this->main_css_element} *",
                    ),
                ),
            ),
            'background' => array(
                'settings' => array(
                    'color' => 'alpha',
                ),
            ),
            'border' => array(),
            'custom_margin_padding' => array(
                'css' => array(
                    'important' => 'all',
                ),
            ),
        );
        $this->custom_css_options = array(
            'member_image' => array(
                'label'    => esc_html__( 'Member Image', 'et_builder' ),
                'selector' => '.et_pb_delibera_member_image',
            ),
            'member_description' => array(
                'label'    => esc_html__( 'Member Description', 'et_builder' ),
                'selector' => '.et_pb_delibera_member_description',
            ),
            'title' => array(
                'label'    => esc_html__( 'Title', 'et_builder' ),
                'selector' => '.et_pb_delibera_member_description h4',
            ),
            'member_position' => array(
                'label'    => esc_html__( 'Member Position', 'et_builder' ),
                'selector' => '.et_pb_member_position',
            ),
            'member_social_links' => array(
                'label'    => esc_html__( 'Member Social Links', 'et_builder' ),
                'selector' => '.et_pb_member_social_links',
            ),
        );
        
        add_action('wp_enqueue_scripts', array($this, 'cssFiles'), 1000);
        add_action('wp_enqueue_scripts', array($this, 'javascriptFiles'), 1000);
    }

    function get_fields() {
        $fields = array(
            'include_categories' => array(
                'label'            => esc_html__( 'Include Categories', 'et_builder' ),
                'renderer'         => 'et_builder_include_categories_delibera_option',
                'option_category'  => 'basic_option',
                'renderer_options' => array(
                    'use_terms' => true,
                    'term_name' => 'tema',
                    'post_type'=>'pauta'
                ),
                'description'      => esc_html__( 'Choose which categories you would like to include in the feed.', 'et_builder' ),
            ),
            'name' => array(
                'label'           => esc_html__( 'Title', 'et_builder' ),
                'type'            => 'text',
                'option_category' => 'basic_option',
                'description'     => 'Insira um título',
            ),
            'url_tema' => array(
                'label'           => esc_html__( 'URL Tema', 'et_builder' ),
                'type'            => 'text',
                'option_category' => 'basic_option',
                'description'     => 'Se preenchida sobreescreve a url do tema',
            ),
            'position' => array(
                'label'           => esc_html__( 'Subtitle', 'et_builder' ),
                'type'            => 'text',
                'option_category' => 'basic_option',
                'description'     =>'insira um subtitulo',
            ),
            'image_url' => array(
                'label'              => esc_html__( 'Image URL', 'et_builder' ),
                'type'               => 'upload',
                'option_category'    => 'basic_option',
                'upload_button_text' => esc_attr__( 'Upload an image', 'et_builder' ),
                'choose_text'        => esc_attr__( 'Choose an Image', 'et_builder' ),
                'update_text'        => esc_attr__( 'Set As Image', 'et_builder' ),
                'description'        => esc_html__( 'Upload your desired image, or type in the URL to the image you would like to display.', 'et_builder' ),
            ),
            'animation' => array(
                'label'             => esc_html__( 'Animation', 'et_builder' ),
                'type'              => 'select',
                'option_category'   => 'configuration',
                'options'           => array(
                    'off'     => esc_html__( 'No Animation', 'et_builder' ),
                    'fade_in' => esc_html__( 'Fade In', 'et_builder' ),
                    'left'    => esc_html__( 'Left To Right', 'et_builder' ),
                    'right'   => esc_html__( 'Right To Left', 'et_builder' ),
                    'top'     => esc_html__( 'Top To Bottom', 'et_builder' ),
                    'bottom'  => esc_html__( 'Bottom To Top', 'et_builder' ),
                ),
                'description'       => esc_html__( 'This controls the direction of the lazy-loading animation.', 'et_builder' ),
            ),
            'background_layout' => array(
                'label'           => esc_html__( 'Text Color', 'et_builder' ),
                'type'            => 'select',
                'option_category' => 'color_option',
                'options'           => array(
                    'light' => esc_html__( 'Dark', 'et_builder' ),
                    'dark'  => esc_html__( 'Light', 'et_builder' ),
                ),
                'description' => esc_html__( 'Here you can choose the value of your text. If you are working with a dark background, then your text should be set to light. If you are working with a light background, then your text should be dark.', 'et_builder' ),
            ),
            'content_new' => array(
                'label'           => esc_html__( 'Description', 'et_builder' ),
                'type'            => 'tiny_mce',
                'option_category' => 'basic_option',
                'description'     => esc_html__( 'Input the main text content for your module here.', 'et_builder' ),
            ),
            'icon_color' => array(
                'label'             => esc_html__( 'Icon Color', 'et_builder' ),
                'type'              => 'color',
                'custom_color'      => true,
                'tab_slug'          => 'advanced',
            ),
            'icon_hover_color' => array(
                'label'             => esc_html__( 'Icon Hover Color', 'et_builder' ),
                'type'              => 'color',
                'custom_color'      => true,
                'tab_slug'          => 'advanced',
            ),
            'disabled_on' => array(
                'label'           => esc_html__( 'Disable on', 'et_builder' ),
                'type'            => 'multiple_checkboxes',
                'options'         => array(
                    'phone'   => esc_html__( 'Phone', 'et_builder' ),
                    'tablet'  => esc_html__( 'Tablet', 'et_builder' ),
                    'desktop' => esc_html__( 'Desktop', 'et_builder' ),
                ),
                'additional_att'  => 'disable_on',
                'option_category' => 'configuration',
                'description'     => esc_html__( 'This will disable the module on selected devices', 'et_builder' ),
            ),
            'admin_label' => array(
                'label'       => esc_html__( 'Admin Label', 'et_builder' ),
                'type'        => 'text',
                'description' => esc_html__( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
            ),
            'module_id' => array(
                'label'           => esc_html__( 'CSS ID', 'et_builder' ),
                'type'            => 'text',
                'option_category' => 'configuration',
                'tab_slug'        => 'custom_css',
                'option_class'    => 'et_pb_custom_css_regular',
            ),
            'module_class' => array(
                'label'           => esc_html__( 'CSS Class', 'et_builder' ),
                'type'            => 'text',
                'option_category' => 'configuration',
                'tab_slug'        => 'custom_css',
                'option_class'    => 'et_pb_custom_css_regular',
            ),
        );
        return $fields;
    }
    
    function cssFiles()
    {
    	if(file_exists(get_stylesheet_directory()."/delibera_style.css"))
    	{
    		wp_enqueue_style('delibera_style', get_stylesheet_directory_uri()."/delibera_style.css");
    	}
    	else
    	{
    		global $deliberaThemes;
    		wp_enqueue_style('delibera_style', $deliberaThemes->themeFileUrl('delibera_style.css'));
    	}
    }
    
    function javascriptFiles()
    {
    	wp_enqueue_script('divi-delibera-concordar', get_stylesheet_directory_uri().'/js/delibera_concordar.js', array('jquery'));
    	
    	$data = array(
    		'ajax_url' => admin_url('admin-ajax.php'),
    	);
    	wp_localize_script('divi-delibera-concordar', 'divi_delibera_concordar', $data);
    }
    
    /**
     * Gera código html para criação do botão curtir/concordar do sistema delibera
     *
     * @param $ID post_ID ou comment_ID
     * @param $type 'pauta' ou 'comment'
     */
    function deliberaGerarCurtir($ID, $type ='pauta')
    {
    	global $post;
    
    	$situacoes_validas = array('validacao' => false, 'discussao' => true, 'relatoria' => true, 'emvotacao' => false, 'comresolucao' => true);
    	if($type == 'pauta')
    	{
    		$situacoes_validas = array('validacao' => true, 'discussao' => true, 'relatoria' => true, 'emvotacao' => true, 'comresolucao' => true);
    	}
    
    	$postID = $ID;
    	if(is_object($ID))
    	{
    		if($type == 'post' || $type == 'pauta')
    		{
    			$ID = $ID->ID;
    			$postID = $ID;
    		}
    		else
    		{
    			$postID = $ID->comment_post_ID;
    			$ID = $ID->comment_ID;
    		}
    	}
    
    	$ncurtiu = intval($type == 'pauta' || $type == 'post' ? get_post_meta($ID, 'delibera_numero_curtir', true) : get_comment_meta($ID, 'delibera_numero_curtir', true));
    	$situacao = delibera_get_situacao($postID);
    	$html = '';
    	
    	global $deliberaThemes;
    	 
    	$svg = $deliberaThemes->themeFileUrl('images/icons.svg');
    	
    	if ($ncurtiu > 0) {
    		$html = '<div id="thebutton'.$type.$ID.'" class="delibera_like" >';
    		$html .= '<span class="delibera-like-count">' . $ncurtiu.'</span><svg class="icon-thumbs-up"><use xlink:href="'.$svg.'#icon-thumbs-up"></use></svg>';
    		$html .= '</div>';
    	} else {
    		$html = '<span class="delibera-like-count" style="display: none;"></span>';
    	}
    
    	if (is_user_logged_in()) {
    		$user_id = get_current_user_id();
    		$ip = $_SERVER['REMOTE_ADDR'];
    
    		if(
    				!delibera_ja_curtiu($ID, $user_id, $ip, $type) && // Ainda não curitu
    				(is_object($situacao) && array_key_exists($situacao->slug, $situacoes_validas)) && $situacoes_validas[$situacao->slug] && // é uma situação válida
    				!(delibera_ja_discordou($ID, $user_id, $ip, $type)) // não discordou
    				)
    		{
    			$html .= '<div id="thebutton'.$type.$ID.'" class="delibera_like" ><svg class="icon-thumbs-up"><use xlink:href="'.$svg.'#icon-thumbs-up"></use></svg>';
    			$html .= "<input type='hidden' name='object_id' value='{$ID}' />";
    			$html .= "<input type='hidden' name='type' value='{$type}' />";
    			$html .= '</div>';
    		}
    	} else {
    		$html .= '<div id="thebutton'.$type.$ID.'" class="delibera_like" >';
    		if (is_object($situacao) && array_key_exists($situacao->slug, $situacoes_validas) && $situacoes_validas[$situacao->slug]) { // é uma situação válida
    			$html .= '<a class="delibera-like-login" href="';
    			$html .= wp_login_url( $type == "pauta" ? get_permalink() : delibera_get_comment_link());
    			$html .= '" ><span class="delibera_like_text">'.__('Concordo','delibera').'</span></a>';
    		}
    		$html .= '</div>';
    	}
    
    	return $html;
    }
    
    /**
     *
     * Gera código html para criação do botão discordar do sistema delibera
     * @param $ID int post_ID ou comment_ID
     * @param $type string 'pauta' ou 'comment'
     */
    function deliberaGerarDiscordar($ID, $type ='pauta')
    {
    	global $post;
    
    	$situacoes_validas = array('validacao' => false, 'discussao' => true, 'relatoria' => true, 'emvotacao' => false, 'comresolucao' => true);
    	if($type == 'pauta')
    	{
    		$situacoes_validas = array('validacao' => true, 'discussao' => true, 'relatoria' => true, 'emvotacao' => true, 'comresolucao' => true);
    	}
    
    	$postID = $ID;
    	if(is_object($ID))
    	{
    		if($type == 'post' || $type == 'pauta')
    		{
    			$ID = $ID->ID;
    			$postID = $ID;
    		}
    		else
    		{
    			$postID = $ID->comment_post_ID;
    			$ID = $ID->comment_ID;
    		}
    	}
		$ndiscordou = intval($type == 'pauta' || $type == 'post' ? get_post_meta($ID, 'delibera_numero_discordar', true) : get_comment_meta($ID, 'delibera_numero_discordar', true));
    	$situacao = delibera_get_situacao($postID);
    	$html = '';
    	 
    	global $deliberaThemes;
    	
    	$svg = $deliberaThemes->themeFileUrl('images/icons.svg');
    	
    	if ($ndiscordou > 0) {
    		$html = '<div id="thebuttonDiscordo'.$type.$ID.'" class="delibera_unlike" >';
    		$html .= '<span class="delibera-unlike-count">' . $ndiscordou .'</span><svg class="icon-thumbs-down"><use xlink:href="'.$svg.'#icon-thumbs-down"></use></svg>';
    		$html .= '</div>';
    	} else {
    		$html = '<div id="thebuttonDiscordo'.$type.$ID.'" class="delibera_unlike" >';
    		$html .= '<span class="delibera-unlike-count" style="display: none;"></span><svg class="icon-thumbs-down"><use xlink:href="'.$svg.'#icon-thumbs-down"></use></svg>';
    		$html .= '</div>';
    	}
    
    	if(is_user_logged_in())
    	{
    		$user_id = get_current_user_id();
    		$ip = $_SERVER['REMOTE_ADDR'];
    
    		if(
    				!delibera_ja_discordou($ID, $user_id, $ip, $type) && // Ainda não curitu
    				(is_object($situacao) && array_key_exists($situacao->slug, $situacoes_validas)) && $situacoes_validas[$situacao->slug] &&// é uma situação válida
    				!(delibera_ja_curtiu($ID, $user_id, $ip, $type)) // não discordou
    				)
    		{
    			$html .= '<div id="thebuttonDiscordo'.$type.$ID.'" class="delibera_unlike" ><svg class="icon-thumbs-down"><use xlink:href="'.$svg.'#icon-thumbs-down"></use></svg>';
    			$html .= "<input type='hidden' name='object_id' value='{$ID}' />";
    			$html .= "<input type='hidden' name='type' value='{$type}' />";
    			$html .= '</div>';
    		}
    	}
    	else
    	{
    		$html = '<div id="thebuttonDiscordo'.$type.$ID.'" class="delibera_unlike" >';
    		if(is_object($situacao) && array_key_exists($situacao->slug, $situacoes_validas) && $situacoes_validas[$situacao->slug]) // é uma situação válida
    		{
    			$html .= '<a class="delibera-unlike-login" href="';
    			$html .= wp_login_url( $type == "pauta" ? get_permalink() : delibera_get_comment_link());
    			$html .= '" ><span class="delibera_unlike_text">'.__('Discordo','delibera').'</span></a>';
    		}
    	}
    	return $html;
    }

    function shortcode_callback( $atts, $content = null, $function_name ) {
        $module_id         = $this->shortcode_atts['module_id'];
        $module_class      = $this->shortcode_atts['module_class'];
        $name              = $this->shortcode_atts['name'];
        $position          = $this->shortcode_atts['position'];
        $image_url         = $this->shortcode_atts['image_url'];
        $animation         = $this->shortcode_atts['animation'];
        $background_layout = $this->shortcode_atts['background_layout'];
        $icon_color        = $this->shortcode_atts['icon_color'];
        $icon_hover_color  = $this->shortcode_atts['icon_hover_color'];
        $include_categories = $this->shortcode_atts['include_categories'];
        $url_tema =          $this->shortcode_atts['url_tema'];

        $module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

        $image = $social_links = '';

        $args = array(
            'post_type' => 'pauta',
            'orderby' => 'rand',
            'post_status'        => 'publish',
            'tax_query' => array(
                array(
                    'taxonomy' => 'tema',
                    'field' => 'tag_id',
                    'terms' => $include_categories,
                    'include_children' => false
                )
            )
        );

        $wp_posts = get_posts($args);

        $image_code = '';
        $pauta_url = "";
        $titulo = "";
        $tema = false;
        $temaLink = "";
        $autor = "";
        $tags = "";
        $avatar = "";
        foreach($wp_posts as $key=>$value)
        {

            $term_list = wp_get_post_terms($wp_posts[$key]->ID, 'tema', array("fields" => "all"));

            $autor = get_userdata($wp_posts[$key]->post_author)->display_name;

            $tags = get_the_tag_list('Tags: ',', ','',$wp_posts[$key]->ID);

            $tema = $term_list[0]->name;

            $avatar = get_avatar( $wp_posts[$key]->post_author, '25');

            $temaLink = get_term_link($term_list[0]->slug,"tema");

            if (has_post_thumbnail( $wp_posts[$key]->ID ) ){
                $image_pauta_url = wp_get_attachment_image_src( get_post_thumbnail_id( $wp_posts[$key]->ID  ), 'thumbnail' );
                $image_code = $image_pauta_url[0];
            }
            $pauta_url = $wp_posts[$key]->guid;
            $titulo = $wp_posts[$key]->post_title;
        }

        if($url_tema!=="")
            $temaLink = $url_tema;

        if ( '' !== $icon_color ) {
            ET_Builder_Element::set_style( $function_name, array(
                'selector'    => '%%order_class%% .et_pb_member_social_links a',
                'declaration' => sprintf(
                    'color: %1$s;',
                    esc_html( $icon_color )
                ),
            ) );
        }

        if ( '' !== $icon_hover_color ) {
            ET_Builder_Element::set_style( $function_name, array(
                'selector'    => '%%order_class%% .et_pb_member_social_links a:hover',
                'declaration' => sprintf(
                    'color: %1$s;',
                    esc_html( $icon_hover_color )
                ),
            ) );
        }

        if($image_code !='')
            $image_url = $image_code;

        if ( '' !== $image_url ) {
            $image = sprintf(
                '<div class="et_pb_delibera_member_image et-waypoint%3$s">
					<img src="%1$s" alt="%2$s" />
				</div>',
                esc_url( $image_url ),
                esc_attr( $titulo ),
                esc_attr( " et_pb_animation_{$animation}" )
            );
        }

        global $deliberaThemes;
        $svg = $deliberaThemes->themeFileUrl('images/icons.svg');
        $like = $this->deliberaGerarCurtir($wp_posts[$key]->ID);
        $unlike = $this->deliberaGerarDiscordar($wp_posts[$key]->ID);
        $comment_count = delibera_comment_number($wp_posts[$key]->ID,'todos');

        $output = sprintf(
            '<div%3$s class="et_pb_module et_pb_delibera_member%4$s%9$s et_pb_bg_layout_%8$s clearfix">
				%2$s
				<div class="et_pb_delibera_member_description">
				<div class="tema" id="tema"><a href="%12$s">%11$s</a></div>
				<a href=%10$s>
					%5$s
					%6$s
					%1$s
					%7$s
				</div> <!-- .et_pb_delibera_member_description -->
			</a>
			<BR><div class="tags" id="tags">%14$s</div>

			<BR><div class="user" id="user">
			<div class="imageInterna">%15$s</div>
			<div class="name">%13$s</div>
			</div>
			%16$s
        	%17$s
			<div class="comments"><span class="comments-count">%18$s<span><svg class="icon-comment"><use xlink:href="%19$s#icon-comment"></use></svg></div>

			<div class="faixa"><img src="http://acidadequeeuquero.beta.campanhacompleta.com.br/files/2016/04/opn.png"></div>

			</div> <!-- .et_pb_delibera_member -->',
            $this->shortcode_content,
            ( '' !== $image ? $image : '' ),
            ( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
            ( '' !== $module_class ? sprintf( ' %1$s', esc_attr( str_replace("delibera","team",$module_class) ) ) : '' ),
            ( '' !== $titulo ? sprintf( '<h4>%1$s</h4>', esc_html( $titulo ) ) : '' ),
            ( '' !== $position ? sprintf( '<p class="et_pb_member_position">%1$s</p>', esc_html( $position ) ) : '' ),
            $social_links,
            $background_layout,
            ( '' === $image ? ' et_pb_delibera_member_no_image' : '' ),
            $pauta_url,
            $tema,
            $temaLink,
            $autor,
            $tags,
            $avatar,
        	$like,
        	$unlike,
        	$comment_count,
        	$svg
        );

       return $output;
    }
}
new ET_Builder_Module_Delibera_Member;

class ET_Builder_Module_Formulario extends ET_Builder_Module {
    function init() {
        $this->name = esc_html__( 'Inserção de pauta', 'et_builder' );
        $this->slug = 'et_pb_formulariooasxqzx';
        $this->child_slug      = 'et_pb_contact_field_delibera';
        $this->child_item_text = esc_html__( 'Field', 'et_builder' );

        $this->whitelisted_fields = array(
            'include_categories',
            'captcha',
            'email',
            'title',
            'admin_label',
            'module_id',
            'module_class',
            'form_background_color',
            'input_border_radius',
            'use_redirect',
            'redirect_url',
            'success_message'
        );

        $this->fields_defaults = array(
            'captcha'      => array( 'off' ),
            'use_redirect' => array( 'on' ),
            'redirect_url' => 'pauta'
        );

        $this->main_css_element = '%%order_class%%.et_pb_delibera_form_container';
        $this->advanced_options = array(
            'fonts' => array(
                'title' => array(
                    'label'    => esc_html__( 'Title', 'et_builder' ),
                    'css'      => array(
                        'main' => "{$this->main_css_element} h1",
                    ),
                ),
                'form_field'   => array(
                    'label'    => esc_html__( 'Form Field', 'et_builder' ),
                    'css'      => array(
                        'main' => "{$this->main_css_element} .input",
                    ),
                ),
            ),
            'border' => array(
                'css'      => array(
                    'main' => "{$this->main_css_element} .input",
                ),
                'settings' => array(
                    'color' => 'alpha',
                ),
            ),
            'button' => array(
                'button' => array(
                    'label' => esc_html__( 'Button', 'et_builder' ),
                ),
            ),
        );
        $this->custom_css_options = array(
            'contact_title' => array(
                'label'    => esc_html__( 'Contact Title', 'et_builder' ),
                'selector' => '.et_pb_contact_main_title',
            ),
            'contact_button' => array(
                'label'    => esc_html__( 'Contact Button', 'et_builder' ),
                'selector' => '.et_pb_contact_submit',
            ),
            'contact_fields' => array(
                'label'    => esc_html__( 'Form Fields', 'et_builder' ),
                'selector' => '.et_pb_contact_left input',
            ),
            'text_field' => array(
                'label'    => esc_html__( 'Message Field', 'et_builder' ),
                'selector' => 'textarea.et_pb_contact_message',
            ),
            'captcha_field' => array(
                'label'    => esc_html__( 'Captcha Field', 'et_builder' ),
                'selector' => 'input.et_pb_contact_captcha',
            ),
            'captcha_label' => array(
                'label'    => esc_html__( 'Captcha Text', 'et_builder' ),
                'selector' => '.et_pb_contact_right p',
            ),
        );
    }

    function get_fields() {
        $fields = array(
            'include_categories' => array(
                'label'            => esc_html__( 'Include Categories', 'et_builder' ),
                'renderer'         => 'et_builder_include_categories_delibera_form_option_radio',
                'option_category'  => 'basic_option',
                'renderer_options' => array(
                    'use_terms' => true,
                    'term_name' => 'tema',
                    'post_type'=>'pauta'
                ),
                'description'      => esc_html__( 'Choose which categories you would like to include in the feed.', 'et_builder' ),
            ),
            'captcha' => array(
                'label'           => esc_html__( 'Display Captcha', 'et_builder' ),
                'type'            => 'yes_no_button',
                'option_category' => 'configuration',
                'options'         => array(
                    'on'  => esc_html__( 'Yes', 'et_builder' ),
                    'off' => esc_html__( 'No', 'et_builder' ),
                ),
                'description' => esc_html__( 'Turn the captcha on or off using this option.', 'et_builder' ),
            ),
            'email' => array(
                'label'           => esc_html__( 'Email', 'et_builder' ),
                'type'            => 'text',
                'option_category' => 'basic_option',
                'description'     => esc_html__( 'Input the email address where messages should be sent.', 'et_builder' ),
            ),
            'title' => array(
                'label'           => esc_html__( 'Title', 'et_builder' ),
                'type'            => 'text',
                'option_category' => 'basic_option',
                'description'     => esc_html__( 'Define a title for your form.', 'et_builder' ),
            ),

            'use_redirect' => array(
                'label'           => esc_html__( 'Enable Redirect URL', 'et_builder' ),
                'type'            => 'yes_no_button',
                'option_category' => 'configuration',
                'options'         => array(
                    'off' => esc_html__( 'No', 'et_builder' ),
                    'on'  => esc_html__( 'Yes', 'et_builder' ),
                ),
                'affects' => array(
                    '#et_pb_redirect_url',
                ),
                'description' => esc_html__( 'Redirect users after successful form submission.', 'et_builder' ),
            ),
            'redirect_url' => array(
                'label'           => esc_html__( 'Redirect URL', 'et_builder' ),
                'type'            => 'text',
                'option_category' => 'configuration',
                'depends_show_if' => 'on',
                'description'     => esc_html__( 'Type the Redirect URL', 'et_builder' ),
            ),
            'success_message' => array(
                'label'           => esc_html__( 'Success Message', 'et_builder' ),
                'type'            => 'text',
                'option_category' => 'configuration',
                'description'     => esc_html__( 'Type the message you want to display after successful form submission. Leave blank for default', 'et_builder' ),
            ),
            'form_background_color' => array(
                'label'             => esc_html__( 'Form Background Color', 'et_builder' ),
                'type'              => 'color-alpha',
                'custom_color'      => true,
                'tab_slug'          => 'advanced',
            ),
            'input_border_radius'   => array(
                'label'             => esc_html__( 'Input Border Radius', 'et_builder' ),
                'type'              => 'range',
                'default'           => '0',
                'range_settings'    => array(
                    'min'  => '0',
                    'max'  => '100',
                    'step' => '1',
                ),
                'option_category'   => 'layout',
                'tab_slug'          => 'advanced',
            ),
            'disabled_on' => array(
                'label'           => esc_html__( 'Disable on', 'et_builder' ),
                'type'            => 'multiple_checkboxes',
                'options'         => array(
                    'phone'   => esc_html__( 'Phone', 'et_builder' ),
                    'tablet'  => esc_html__( 'Tablet', 'et_builder' ),
                    'desktop' => esc_html__( 'Desktop', 'et_builder' ),
                ),
                'additional_att'  => 'disable_on',
                'option_category' => 'configuration',
                'description'     => esc_html__( 'This will disable the module on selected devices', 'et_builder' ),
            ),
            'admin_label' => array(
                'label'       => esc_html__( 'Admin Label', 'et_builder' ),
                'type'        => 'text',
                'description' => esc_html__( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
            ),
            'module_id' => array(
                'label'           => esc_html__( 'CSS ID', 'et_builder' ),
                'type'            => 'text',
                'option_category' => 'configuration',
                'tab_slug'        => 'custom_css',
                'option_class'    => 'et_pb_custom_css_regular',
            ),
            'module_class' => array(
                'label'           => esc_html__( 'CSS Class', 'et_builder' ),
                'type'            => 'text',
                'option_category' => 'configuration',
                'tab_slug'        => 'custom_css',
                'option_class'    => 'et_pb_custom_css_regular',
            ),
        );
        return $fields;
    }

    function predefined_child_modules() {

        $output = sprintf(
            '[et_pb_contact_field_delibera field_title="%1$s" field_type="input" field_id="Titulo" required_mark="on" fullwidth_field="off" /][et_pb_contact_field_delibera field_title="%2$s" field_type="input" field_id="Tags" required_mark="on" fullwidth_field="off" /][et_pb_contact_field_delibera field_title="%3$s" field_type="text" field_id="Descricao" required_mark="on" /]',
            esc_attr__( 'Título', 'et_builder' ),
            esc_attr__( 'Tags', 'et_builder' ),
            esc_attr__( 'Descricao', 'et_builder' )
        );

        return $output;
    }

    function shortcode_callback( $atts, $content = null, $function_name ) {
        $include_categories    = $this->shortcode_atts['include_categories'];
        $module_id             = $this->shortcode_atts['module_id'];
        $module_class          = $this->shortcode_atts['module_class'];
        $captcha               = $this->shortcode_atts['captcha'];
        $email                 = $this->shortcode_atts['email'];
        $title                 = $this->shortcode_atts['title'];
        $form_background_color = $this->shortcode_atts['form_background_color'];
        $input_border_radius   = $this->shortcode_atts['input_border_radius'];
        $button_custom         = $this->shortcode_atts['custom_button'];
        $custom_icon           = $this->shortcode_atts['button_icon'];
        $use_redirect          = $this->shortcode_atts['use_redirect'];
        $redirect_url          = $this->shortcode_atts['redirect_url'];
        $success_message       = $this->shortcode_atts['success_message'];

        global $et_pb_contact_form_num;

        $module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

        if ( '' !== $form_background_color ) {
            ET_Builder_Element::set_style( $function_name, array(
                'selector'    => '%%order_class%% .input',
                'declaration' => sprintf(
                    'background-color: %1$s;',
                    esc_html( $form_background_color )
                ),
            ) );
        }

        if ( ! in_array( $input_border_radius, array( '', '0' ) ) ) {
            ET_Builder_Element::set_style( $function_name, array(
                'selector'    => '%%order_class%% .input',
                'declaration' => sprintf(
                    '-moz-border-radius: %1$s; -webkit-border-radius: %1$s; border-radius: %1$s;',
                    esc_html( et_builder_process_range_value( $input_border_radius ) )
                ),
            ) );
        }

        $success_message = '' !== $success_message ? $success_message : esc_html__( 'Thanks for contacting us', 'et_builder' );

        $et_pb_contact_form_num = $this->shortcode_callback_num();

        $content = $this->shortcode_content;

        $et_error_message = '';
        $et_contact_error = false;
        $current_form_fields = isset( $_POST['et_pb_contact_email_fields_' . $et_pb_contact_form_num] ) ? $_POST['et_pb_contact_email_fields_' . $et_pb_contact_form_num] : '';
        $contact_email = '';
        $processed_fields_values = array();

        $nonce_result = isset( $_POST['_wpnonce-et-pb-contact-form-submitted'] ) && wp_verify_nonce( $_POST['_wpnonce-et-pb-contact-form-submitted'], 'et-pb-contact-form-submit' ) ? true : false;

        // check that the form was submitted and et_pb_contactform_validate field is empty to protect from spam
        if ( $nonce_result && isset( $_POST['et_pb_contactform_submit_' . $et_pb_contact_form_num] ) && empty( $_POST['et_pb_contactform_validate_' . $et_pb_contact_form_num] ) ) {
            if ( '' !== $current_form_fields ) {
                $fields_data_json = str_replace( '\\', '' ,  $current_form_fields );
                $fields_data_array = json_decode( $fields_data_json, true );

                // check whether captcha field is not empty
                if ( 'on' === $captcha && ( ! isset( $_POST['et_pb_contact_captcha_' . $et_pb_contact_form_num] ) || empty( $_POST['et_pb_contact_captcha_' . $et_pb_contact_form_num] ) ) ) {
                    $et_error_message .= sprintf( '<p class="et_pb_contact_error_text">%1$s</p>', esc_html__( 'Make sure you entered the captcha.', 'et_builder' ) );
                    $et_contact_error = true;
                }

                // check all fields on current form and generate error message if needed
                if ( ! empty( $fields_data_array ) ) {
                    foreach( $fields_data_array as $index => $value ) {
                        // check all the required fields, generate error message if required field is empty
                        if ( 'required' === $value['required_mark'] && empty( $_POST[ $value['field_id'] ] ) ) {
                            $et_error_message .= sprintf( '<p class="et_pb_contact_error_text">%1$s</p>', esc_html__( 'Make sure you fill in all required fields.', 'et_builder' ) );
                            $et_contact_error = true;
                            continue;
                        }

                        // additional check for email field
                        if ( 'email' === $value['field_type'] && ! empty( $_POST[ $value['field_id'] ] ) ) {
                            $contact_email = sanitize_email( $_POST[ $value['field_id'] ] );
                            if ( ! is_email( $contact_email ) ) {
                                $et_error_message .= sprintf( '<p class="et_pb_contact_error_text">%1$s</p>', esc_html__( 'Invalid Email.', 'et_builder' ) );
                                $et_contact_error = true;
                            }
                        }

                        // prepare the array of processed field values in convenient format
                        if ( false === $et_contact_error ) {
                            $processed_fields_values[ $value['original_id'] ]['value'] = isset( $_POST[ $value['field_id'] ] ) ? $_POST[ $value['field_id'] ] : '';
                            $processed_fields_values[ $value['original_id'] ]['label'] = $value['field_label'];
                        }
                    }
                }
            } else {
                $et_error_message .= sprintf( '<p class="et_pb_contact_error_text">%1$s</p>', esc_html__( 'Make sure you fill in all required fields.', 'et_builder' ) );
                $et_contact_error = true;
            }
        } else {
            if ( false === $nonce_result && isset( $_POST['et_pb_contactform_submit_' . $et_pb_contact_form_num] ) && empty( $_POST['et_pb_contactform_validate_' . $et_pb_contact_form_num] ) ) {
                $et_error_message .= sprintf( '<p class="et_pb_contact_error_text">%1$s</p>', esc_html__( 'Please refresh the page and try again.', 'et_builder' ) );
            }
            $et_contact_error = true;
        }

        // generate digits for captcha
        $et_pb_first_digit = rand( 1, 15 );
        $et_pb_second_digit = rand( 1, 15 );

        if ( ! $et_contact_error && $nonce_result ) {
            $et_email_to = '' !== $email
                ? $email
                : get_site_option( 'admin_email' );

            $et_site_name = get_option( 'blogname' );

            $contact_name = isset( $processed_fields_values['name'] ) ? stripslashes( sanitize_text_field( $processed_fields_values['name']['value'] ) ) : '';


                // use default message pattern if custom pattern is not defined
                $message_pattern = isset( $processed_fields_values['message']['value'] ) ? $processed_fields_values['message']['value'] : '';

                // Add all custom fields into the message body by default
                foreach ( $processed_fields_values as $key => $value ) {
                    if ( ! in_array( $key, array( 'message', 'name', 'email','descricao','descrição','titulo','tags' ) ) ) {
                        $message_pattern .= "\r\n";
                        $message_pattern .= sprintf(
                            '%1$s: %2$s',
                            '' !== $value['label'] ? $value['label'] : $key,
                            $value['value']
                        );
                    }
                }

         	$my_post = array(
	            'post_title'    => $processed_fields_values['titulo']['value'],
	            'post_content'  => $processed_fields_values['descricao']['value'],
	            'post_type'     => 'pauta',
	            'tags_input'    => $processed_fields_values['tags']['value'],
				'delibera_flow' => array("discussao", "emvotacao", 'comresolucao'), //TODO option
				'redirect' 		=> false
	        );
	        if(!empty( $_POST['et_pb_categories_' . $et_pb_contact_form_num] ))
	        {
	        	$my_post['tema'] = array( $_POST['et_pb_categories_' . $et_pb_contact_form_num] );
	        }
         	
            $post_id = deliberaCreateTopic( $my_post );

            $_SESSION['last_id'] = $post_id;

            if($redirect_url == "pauta")
            {
                $redirect_url = esc_url( get_permalink($post_id) );
            }

            $redirect_url = esc_url( get_permalink($post_id) );

            $et_error_message = sprintf( '<p>%1$s</p>', 'Obrigado por participar, você pode ver sua proposta <a href="'.$redirect_url.'">aqui</a>.' );

        }

        $form = '';

        $et_pb_captcha = sprintf( '
			<div class="et_pb_contact_right">
				<p class="clearfix">
					<span class="et_pb_contact_captcha_question">%1$s</span> = <input type="text" size="2" class="input et_pb_contact_captcha" data-first_digit="%3$s" data-second_digit="%4$s" value="" name="et_pb_contact_captcha_%2$s" data-required_mark="required">
				</p>
			</div> <!-- .et_pb_contact_right -->',
            sprintf( '%1$s + %2$s', esc_html( $et_pb_first_digit ), esc_html( $et_pb_second_digit ) ),
            esc_attr( $et_pb_contact_form_num ),
            esc_attr( $et_pb_first_digit ),
            esc_attr( $et_pb_second_digit )
        );

        if ( '' === trim( $content ) ) {
            $content = do_shortcode( $this->predefined_child_modules() );
        }

        if ( $et_contact_error ) {
            $form = sprintf( '
				<div class="et_pb_contact" onclick="teste();">
					<form class="et_pb_contact_form clearfix" method="post" action="%1$s">
						%8$s
						<input type="hidden" value="et_contact_proccess" name="et_pb_contactform_submit_%7$s">
						<input type="text" value="" name="et_pb_contactform_validate_%7$s" class="et_pb_contactform_validate_field" />
						<div class="et_contact_bottom_container">
							%2$s
							<input type="hidden" value="'.$include_categories.'" name="et_pb_categories_%7$s" />
							<button type="submit" class="et_pb_contact_submit et_pb_button%6$s"%5$s>%3$s</button>
						</div>
						%4$s
					</form>
				</div> <!-- .et_pb_contact -->',
                esc_url( get_permalink( get_the_ID() ) ),
                (  'on' === $captcha ? $et_pb_captcha : '' ),
                esc_html__( 'Submit', 'et_builder' ),
                wp_nonce_field( 'et-pb-contact-form-submit', '_wpnonce-et-pb-contact-form-submitted', true, false ),
                '' !== $custom_icon && 'on' === $button_custom ? sprintf(
                    ' data-icon="%1$s"',
                    esc_attr( et_pb_process_font_icon( $custom_icon ) )
                ) : '',
                '' !== $custom_icon && 'on' === $button_custom ? ' et_pb_custom_button_icon' : '',
                esc_attr( $et_pb_contact_form_num ),
                $content
            );
        }

        $output = sprintf( '
			<div id="%4$s" class="et_pb_module et_pb_contact_form_container clearfix%5$s" data-form_unique_num="%6$s"%7$s>
				%1$s
				<div class="et-pb-contact-message">%2$s</div>
				%3$s
			</div> <!-- .et_pb_contact_form_container -->
			',
            ( '' !== $title ? sprintf( '<h1 class="et_pb_contact_main_title">%1$s</h1>', esc_html( $title ) ) : '' ),
            '' !== $et_error_message ? $et_error_message : '',
            $form,
            ( '' !== $module_id
                ? esc_attr( $module_id )
                : esc_attr( 'et_pb_insert_pauta_' . $et_pb_contact_form_num )
            ),
            ( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' ),
            esc_attr( $et_pb_contact_form_num ),
            'on' === $use_redirect && '' !== $redirect_url ? sprintf( ' data-redirect_url="%1$s"', esc_attr( $redirect_url ) ) : ''
        );

        return $output;
    }
}
new ET_Builder_Module_Formulario;

class ET_Builder_Module_Formulario_item extends ET_Builder_Module {
    function init() {
        $this->name            = esc_html__( 'Field', 'et_builder' );
        $this->slug            = 'et_pb_contact_field_delibera';
        $this->type            = 'child';
        $this->child_title_var = 'field_id';

        $this->whitelisted_fields = array(
            'field_title',
            'field_type',
            'field_id',
            'required_mark',
            'fullwidth_field',
            'input_border_radius',
            'field_background_color',
        );

        $this->advanced_setting_title_text = esc_html__( 'New Field', 'et_builder' );
        $this->settings_text               = esc_html__( 'Field Settings', 'et_builder' );
        $this->main_css_element = '%%order_class%%.et_pb_contact_field .input';
        $this->advanced_options = array(
            'fonts' => array(
                'form_field'   => array(
                    'label'    => esc_html__( 'Field', 'et_builder' ),
                    'css'      => array(
                        'main' => "{$this->main_css_element}",
                    ),
                ),
            ),
            'border' => array(
                'css'      => array(
                    'main' => "{$this->main_css_element}",
                ),
                'settings' => array(
                    'color' => 'alpha',
                ),
            ),
        );
    }

    function get_fields() {
        $fields = array(
            'field_id' => array(
                'label'       => esc_html__( 'Field ID', 'et_builder' ),
                'type'        => 'text',
                'description' => esc_html__( 'Define the unique ID of this field. You should use only English characters without special characters and spaces.', 'et_builder' ),
            ),
            'field_title' => array(
                'label'       => esc_html__( 'Title', 'et_builder' ),
                'type'        => 'text',
                'description' => esc_html__( 'Here you can define the content that will be placed within the current tab.', 'et_builder' ),
            ),
            'field_type' => array(
                'label'       => esc_html__( 'Type', 'et_builder' ),
                'type'        => 'select',
                'option_category' => 'basic_option',
                'options'         => array(
                    'input' => esc_html__( 'Input Field', 'et_builder' ),
                    'email' => esc_html__( 'Email Field', 'et_builder' ),
                    'text'  => esc_html__( 'Textarea', 'et_builder' ),
                ),
                'description' => esc_html__( 'Choose the type of field', 'et_builder' ),
            ),
            'required_mark' => array(
                'label'           => esc_html__( 'Required Field', 'et_builder' ),
                'type'            => 'yes_no_button',
                'option_category' => 'configuration',
                'options'         => array(
                    'on'  => esc_html__( 'Yes', 'et_builder' ),
                    'off' => esc_html__( 'No', 'et_builder' ),
                ),
                'description' => esc_html__( 'Define whether the field should be required or optional', 'et_builder' ),
            ),
            'fullwidth_field' => array(
                'label'           => esc_html__( 'Make Fullwidth', 'et_builder' ),
                'type'            => 'yes_no_button',
                'option_category' => 'layout',
                'options'         => array(
                    'on'  => esc_html__( 'Yes', 'et_builder' ),
                    'off' => esc_html__( 'No', 'et_builder' ),
                ),
                'description' => esc_html__( 'If enabled, the field will take 100% of the width of the content area, otherwise it will take 50%', 'et_builder' ),
            ),
            'field_background_color' => array(
                'label'             => esc_html__( 'Background Color', 'et_builder' ),
                'type'              => 'color-alpha',
                'custom_color'      => true,
                'tab_slug'          => 'advanced',
            ),
            'input_border_radius'   => array(
                'label'             => esc_html__( 'Border Radius', 'et_builder' ),
                'type'              => 'range',
                'default'           => '0',
                'range_settings'    => array(
                    'min'  => '0',
                    'max'  => '100',
                    'step' => '1',
                ),
                'option_category'   => 'layout',
                'tab_slug'          => 'advanced',
            ),
        );
        return $fields;
    }

    function shortcode_callback( $atts, $content = null, $function_name ) {
        $field_title            = $this->shortcode_atts['field_title'];
        $field_type             = $this->shortcode_atts['field_type'];
        $field_id               = $this->shortcode_atts['field_id'];
        $required_mark          = $this->shortcode_atts['required_mark'];
        $fullwidth_field        = $this->shortcode_atts['fullwidth_field'];
        $field_background_color = $this->shortcode_atts['field_background_color'];
        $input_border_radius    = $this->shortcode_atts['input_border_radius'];

        global $et_pb_contact_form_num;

        // do not output the fields with empty ID
        if ( '' === $field_id ) {
            return;
        }

        $field_id = strtolower( $field_id );

        $current_module_num = '' === $et_pb_contact_form_num ? 0 : intval( $et_pb_contact_form_num ) + 1;

        $module_class = ET_Builder_Element::add_module_order_class( '', $function_name );

        $this->half_width_counter = ! isset( $this->half_width_counter ) ? 0 : $this->half_width_counter;

        // count fields to add the et_pb_contact_field_last properly
        if ( 'off' === $fullwidth_field ) {
            $this->half_width_counter++;
        } else {
            $this->half_width_counter = 0;
        }

        $input_field = '';

        if ( '' !== $field_background_color ) {
            ET_Builder_Element::set_style( $function_name, array(
                'selector'    => '%%order_class%% .input',
                'declaration' => sprintf(
                    'background-color: %1$s;',
                    esc_html( $field_background_color )
                ),
            ) );
        }

        if ( ! in_array( $input_border_radius, array( '', '0' ) ) ) {
            ET_Builder_Element::set_style( $function_name, array(
                'selector'    => '%%order_class%% .input',
                'declaration' => sprintf(
                    '-moz-border-radius: %1$s; -webkit-border-radius: %1$s; border-radius: %1$s;',
                    esc_html( et_builder_process_range_value( $input_border_radius ) )
                ),
            ) );
        }

        switch( $field_type ) {
            case 'text':
                $input_field = sprintf(
                    '<textarea name="et_pb_contact_%3$s_%2$s" id="et_pb_contact_%3$s_%2$s" class="et_pb_contact_message input" data-required_mark="%5$s" data-field_type="%4$s" data-original_id="%3$s">%1$s</textarea>',
                    ( isset( $_POST['et_pb_contact_' . $field_id . '_' . $current_module_num] ) ? esc_html( sanitize_text_field( $_POST['et_pb_contact_' . $field_id . '_' . $current_module_num] ) ) : esc_html( $field_title ) ),
                    esc_attr( $current_module_num ),
                    esc_attr( $field_id ),
                    esc_attr( $field_type ),
                    'off' === $required_mark ? 'not_required' : 'required'
                );
                break;
            case 'input' :
            case 'email' :
                $input_field = sprintf(
                    '<input type="text" id="et_pb_contact_%3$s_%2$s" class="input" value="%1$s" name="et_pb_contact_%3$s_%2$s" data-required_mark="%5$s" data-field_type="%4$s" data-original_id="%3$s">',
                    ( isset( $_POST['et_pb_contact_' . $field_id . '_' . $current_module_num] ) ? esc_attr( sanitize_text_field( $_POST['et_pb_contact_' . $field_id . '_' . $current_module_num] ) ) : esc_attr( $field_title ) ),
                    esc_attr( $current_module_num ),
                    esc_attr( $field_id ),
                    esc_attr( $field_type ),
                    'off' === $required_mark ? 'not_required' : 'required'
                );
                break;
        }

        $output = sprintf(
            '<p class="et_pb_contact_field%5$s%6$s%7$s">
				<label for="et_pb_contact_%3$s_%2$s" class="et_pb_contact_form_label">%1$s</label>
				%4$s
			</p>',
            esc_html( $field_title ),
            esc_attr( $current_module_num ),
            esc_attr( $field_id ),
            $input_field,
            esc_attr( $module_class ),
            'off' === $fullwidth_field ? ' et_pb_contact_field_half' : '',
            0 === $this->half_width_counter % 2 ? ' et_pb_contact_field_last' : ''
        );

        return $output;
    }
}
new ET_Builder_Module_Formulario_item;

class ET_Builder_Module_Delibera_Categoria extends ET_Builder_Module {
    function init() {
        $this->name = esc_html__( 'Delibera categoria', 'et_builder' );
        $this->slug = 'et_pb_delibera_categoria';

        $this->whitelisted_fields = array(
            'name',
            'position',
            'image_url',
            'animation',
            'background_layout',
            'content_new',
            'admin_label',
            'module_id',
            'module_class',
            'icon_color',
            'icon_hover_color',
            'include_categories',
            'texto',
        );

        $this->fields_defaults = array(
            'animation'         => array( 'off' ),
            'background_layout' => array( 'light' ),
        );

        $this->main_css_element = '%%order_class%%.et_pb_delibera_categoria';
        $this->advanced_options = array(
            'fonts' => array(
                'header' => array(
                    'label'    => esc_html__( 'Header', 'et_builder' ),
                    'css'      => array(
                        'main' => "{$this->main_css_element} h4",
                    ),
                ),
                'body'   => array(
                    'label'    => esc_html__( 'Body', 'et_builder' ),
                    'css'      => array(
                        'main' => "{$this->main_css_element} *",
                    ),
                ),
            ),
            'background' => array(
                'settings' => array(
                    'color' => 'alpha',
                ),
            ),
            'border' => array(),
            'custom_margin_padding' => array(
                'css' => array(
                    'important' => 'all',
                ),
            ),
        );
        $this->custom_css_options = array(
            'member_image' => array(
                'label'    => esc_html__( 'Member Image', 'et_builder' ),
                'selector' => '.et_pb_delibera_member_image',
            ),
            'member_description' => array(
                'label'    => esc_html__( 'Member Description', 'et_builder' ),
                'selector' => '.et_pb_delibera_member_description',
            ),
            'title' => array(
                'label'    => esc_html__( 'Title', 'et_builder' ),
                'selector' => '.et_pb_delibera_member_description h4',
            ),
            'member_position' => array(
                'label'    => esc_html__( 'Member Position', 'et_builder' ),
                'selector' => '.et_pb_member_position',
            ),
            'member_social_links' => array(
                'label'    => esc_html__( 'Member Social Links', 'et_builder' ),
                'selector' => '.et_pb_member_social_links',
            ),
        );
    }

    function get_fields() {
        $fields = array(
            'include_categories' => array(
                'label'            => esc_html__( 'Include Categories', 'et_builder' ),
                'renderer'         => 'et_builder_include_categories_delibera_option',
                'option_category'  => 'basic_option',
                'renderer_options' => array(
                    'use_terms' => true,
                    'term_name' => 'tema',
                    'post_type'=>'pauta'
                ),
                'description'      => esc_html__( 'Choose which categories you would like to include in the feed.', 'et_builder' ),
            ),
            'name' => array(
                'label'           => esc_html__( 'Title', 'et_builder' ),
                'type'            => 'text',
                'option_category' => 'basic_option',
                'description'     => 'Insira um título',
            ),
            'url_tema' => array(
                'label'           => esc_html__( 'Texto', 'et_builder' ),
                'type'            => 'text',
                'option_category' => 'basic_option',
                'description'     => 'Se preenchida sobreescreve a url do tema',
            ),
            'position' => array(
                'label'           => esc_html__( 'Subtitle', 'et_builder' ),
                'type'            => 'text',
                'option_category' => 'basic_option',
                'description'     =>'insira um subtitulo',
            ),
            'image_url' => array(
                'label'              => esc_html__( 'Image URL', 'et_builder' ),
                'type'               => 'upload',
                'option_category'    => 'basic_option',
                'upload_button_text' => esc_attr__( 'Upload an image', 'et_builder' ),
                'choose_text'        => esc_attr__( 'Choose an Image', 'et_builder' ),
                'update_text'        => esc_attr__( 'Set As Image', 'et_builder' ),
                'description'        => esc_html__( 'Upload your desired image, or type in the URL to the image you would like to display.', 'et_builder' ),
            ),
            'animation' => array(
                'label'             => esc_html__( 'Animation', 'et_builder' ),
                'type'              => 'select',
                'option_category'   => 'configuration',
                'options'           => array(
                    'off'     => esc_html__( 'No Animation', 'et_builder' ),
                    'fade_in' => esc_html__( 'Fade In', 'et_builder' ),
                    'left'    => esc_html__( 'Left To Right', 'et_builder' ),
                    'right'   => esc_html__( 'Right To Left', 'et_builder' ),
                    'top'     => esc_html__( 'Top To Bottom', 'et_builder' ),
                    'bottom'  => esc_html__( 'Bottom To Top', 'et_builder' ),
                ),
                'description'       => esc_html__( 'This controls the direction of the lazy-loading animation.', 'et_builder' ),
            ),
            'background_layout' => array(
                'label'           => esc_html__( 'Text Color', 'et_builder' ),
                'type'            => 'select',
                'option_category' => 'color_option',
                'options'           => array(
                    'light' => esc_html__( 'Dark', 'et_builder' ),
                    'dark'  => esc_html__( 'Light', 'et_builder' ),
                ),
                'description' => esc_html__( 'Here you can choose the value of your text. If you are working with a dark background, then your text should be set to light. If you are working with a light background, then your text should be dark.', 'et_builder' ),
            ),
            'content_new' => array(
                'label'           => esc_html__( 'Description', 'et_builder' ),
                'type'            => 'tiny_mce',
                'option_category' => 'basic_option',
                'description'     => esc_html__( 'Input the main text content for your module here.', 'et_builder' ),
            ),
            'icon_color' => array(
                'label'             => esc_html__( 'Icon Color', 'et_builder' ),
                'type'              => 'color',
                'custom_color'      => true,
                'tab_slug'          => 'advanced',
            ),
            'icon_hover_color' => array(
                'label'             => esc_html__( 'Icon Hover Color', 'et_builder' ),
                'type'              => 'color',
                'custom_color'      => true,
                'tab_slug'          => 'advanced',
            ),
            'disabled_on' => array(
                'label'           => esc_html__( 'Disable on', 'et_builder' ),
                'type'            => 'multiple_checkboxes',
                'options'         => array(
                    'phone'   => esc_html__( 'Phone', 'et_builder' ),
                    'tablet'  => esc_html__( 'Tablet', 'et_builder' ),
                    'desktop' => esc_html__( 'Desktop', 'et_builder' ),
                ),
                'additional_att'  => 'disable_on',
                'option_category' => 'configuration',
                'description'     => esc_html__( 'This will disable the module on selected devices', 'et_builder' ),
            ),
            'admin_label' => array(
                'label'       => esc_html__( 'Admin Label', 'et_builder' ),
                'type'        => 'text',
                'description' => esc_html__( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
            ),
            'module_id' => array(
                'label'           => esc_html__( 'CSS ID', 'et_builder' ),
                'type'            => 'text',
                'option_category' => 'configuration',
                'tab_slug'        => 'custom_css',
                'option_class'    => 'et_pb_custom_css_regular',
            ),
            'module_class' => array(
                'label'           => esc_html__( 'CSS Class', 'et_builder' ),
                'type'            => 'text',
                'option_category' => 'configuration',
                'tab_slug'        => 'custom_css',
                'option_class'    => 'et_pb_custom_css_regular',
            ),
        );
        return $fields;
    }

    function shortcode_callback( $atts, $content = null, $function_name ) {
        $module_id         = $this->shortcode_atts['module_id'];
        $module_class      = $this->shortcode_atts['module_class'];
        $name              = $this->shortcode_atts['name'];
        $position          = $this->shortcode_atts['position'];
        $image_url         = $this->shortcode_atts['image_url'];
        $animation         = $this->shortcode_atts['animation'];
        $background_layout = $this->shortcode_atts['background_layout'];
        $icon_color        = $this->shortcode_atts['icon_color'];
        $icon_hover_color  = $this->shortcode_atts['icon_hover_color'];
        $include_categories = $this->shortcode_atts['include_categories'];
        $texto =          $this->shortcode_atts['texto'];

        $module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

        $image = $social_links = '';

        $args = array(
            'post_type' => 'pauta',
            'orderby' => 'rand',
            'post_status'        => 'publish',
            'tax_query' => array(
                array(
                    'taxonomy' => 'tema',
                    'field' => 'tag_id',
                    'terms' => $include_categories,
                    'include_children' => false
                )
            )
        );

        $wp_posts = get_posts($args);

        $output = "";

        $auxClose = '';

        $i = 0;

        foreach($wp_posts as $key=>$value)
        {

            $term_list = wp_get_post_terms($wp_posts[$key]->ID, 'tema', array("fields" => "all"));

            $autor = get_userdata($wp_posts[$key]->post_author)->display_name;

            $tags = get_the_tag_list('Tags: ',', ','',$wp_posts[$key]->ID);

            $tema = $term_list[0]->name;

            $avatar = get_avatar( $wp_posts[$key]->post_author, '25');

            $temaLink = get_term_link($term_list[0]->slug,"tema");

            $image_code = '';
            $pauta_url = "";
            $titulo = "";

            if (has_post_thumbnail( $wp_posts[$key]->ID ) ){
                $image_pauta_url = wp_get_attachment_image_src( get_post_thumbnail_id( $wp_posts[$key]->ID  ), 'thumbnail' );
                $image_code = $image_pauta_url[0];
            }
            $pauta_url = $wp_posts[$key]->guid;
            $titulo = $wp_posts[$key]->post_title;

        if ( '' !== $icon_color ) {
            ET_Builder_Element::set_style( $function_name, array(
                'selector'    => '%%order_class%% .et_pb_member_social_links a',
                'declaration' => sprintf(
                    'color: %1$s;',
                    esc_html( $icon_color )
                ),
            ) );
        }

        if ( '' !== $icon_hover_color ) {
            ET_Builder_Element::set_style( $function_name, array(
                'selector'    => '%%order_class%% .et_pb_member_social_links a:hover',
                'declaration' => sprintf(
                    'color: %1$s;',
                    esc_html( $icon_hover_color )
                ),
            ) );
        }

        if($image_code !='')
            $image_url = $image_code;

        if ( '' !== $image_url ) {
            $image = sprintf(
                '<div class="et_pb_delibera_member_image et-waypoint%3$s">
					<img src="%1$s" alt="%2$s" />
				</div>',
                esc_url( $image_url ),
                esc_attr( $titulo ),
                esc_attr( " et_pb_animation_{$animation}" )
            );
        }

        $i++;

        if($i==1)
        {
            $aux = '<div style="width: 100%; float: left; min-width: 400px; clear: both; padding-top:20px;">';
            $auxClose = '';
        }
        else
        {
            $aux = '';
            $auxClose = '';
        }

        if($i == 4)
        {
            $auxClose = '</div>';
            $i = 0;
        }

        $output .= $aux;

        $output .= sprintf(
            '
    <div class="et_pb_column et_pb_column_1_4  et_pb_column_4">
        <div class="et_pb_module et_pb_delibera_member%4$s%9$s  et_pb_team_pauta22v_0 et_pb_bg_layout_%8$s clearfix">
            <div class="et_pb_delibera_member_image et-waypoint et_pb_animation_off et-animated"></div>
				%2$s
				<div class="et_pb_delibera_member_description">
				<div class="tema" id="tema"><a href="%12$s">%11$s</a></div>
				<a href=%10$s>
					%5$s
					%6$s
					%1$s
					%7$s
				</div> <!-- .et_pb_delibera_member_description -->
			</a>
			<BR><div class="tags" id="tags">%14$s</div>

			<BR>
			<div class="user" id="user">
                <div class="imageInterna">%15$s</div>
                <div class="name">%13$s</div>
			</div>

			<div class="faixa"><img src="http://acidadequeeuquero.beta.campanhacompleta.com.br/files/2016/04/opn.png"></div>

			</div>
    </div><!-- .et_pb_delibera_member -->',
            $this->shortcode_content,
            ( '' !== $image ? $image : '' ),
            ( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
            ( '' !== $module_class ? sprintf( ' %1$s', esc_attr( str_replace("delibera","team",$module_class) ) ) : '' ),
            ( '' !== $titulo ? sprintf( '<h4>%1$s</h4>', esc_html( $titulo ) ) : '' ),
            ( '' !== $position ? sprintf( '<p class="et_pb_member_position">%1$s</p>', esc_html( $position ) ) : '' ),
            $social_links,
            $background_layout,
            ( '' === $image ? ' et_pb_delibera_member_no_image' : '' ),
            $pauta_url,
            $tema,
            $temaLink,
            $autor,
            $tags,
            $avatar
        );

            $output .= $auxClose;
    }

        $output = '<div class="et_pb_section  et_pb_section_2 et_section_regular">'.$output.'</div>';

        return $output;
    }
}
new ET_Builder_Module_Delibera_Categoria;

class ET_Builder_Module_Ajax_Login extends ET_Builder_Module_Login {
	function init() {
		$this->name = esc_html__( 'Ajax Login', 'et_builder' );
		$this->slug = 'et_pb_ajax_login';

		$this->whitelisted_fields = array(
			'title',
			'current_page_redirect',
			'use_background_color',
			'background_color',
			'background_layout',
			'text_orientation',
			'content_new',
			'admin_label',
			'module_id',
			'module_class',
			'form_field_background_color',
			'form_field_text_color',
			'focus_background_color',
			'focus_text_color',
			'use_focus_border_color',
			'focus_border_color',
			'show_button',
		);

		$this->fields_defaults = array(
			'current_page_redirect'  => array( 'off' ),
			'use_background_color'   => array( 'on' ),
			'background_color'       => array( et_builder_accent_color(), 'add_default_setting' ),
			'background_layout'      => array( 'dark' ),
			'text_orientation'       => array( 'left' ),
			'use_focus_border_color' => array( 'off' ),
		);

		$this->main_css_element = '%%order_class%%.et_pb_login';
		$this->advanced_options = array(
			'fonts' => array(
				'header' => array(
					'label'    => esc_html__( 'Header', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} h2",
						'important' => 'all',
					),
					),
					'body'   => array(
						'label'    => esc_html__( 'Body', 'et_builder' ),
						'css'      => array(
							'line_height' => "{$this->main_css_element} p",
						),
						),
						),
						'border' => array(),
						'custom_margin_padding' => array(
							'css' => array(
								'important' => 'all',
							),
						),
						'button' => array(
							'button' => array(
								'label' => esc_html__( 'Button', 'et_builder' ),
							),
						),
							);
		$this->custom_css_options = array(
			'newsletter_description' => array(
				'label'    => esc_html__( 'Login Description', 'et_builder' ),
				'selector' => '.et_pb_newsletter_description',
			),
			'newsletter_form' => array(
				'label'    => esc_html__( 'Login Form', 'et_builder' ),
				'selector' => '.et_pb_newsletter_form',
			),
			'newsletter_fields' => array(
				'label'    => esc_html__( 'Login Fields', 'et_builder' ),
				'selector' => '.et_pb_newsletter_form input',
			),
			'newsletter_button' => array(
				'label'    => esc_html__( 'Login Button', 'et_builder' ),
				'selector' => '.et_pb_newsletter_button',
			),
		);
		
		add_action('wp_enqueue_scripts', array($this, 'cssFiles'));
		add_action('wp_enqueue_scripts', array($this, 'javascriptFiles'));
		
	}
	
	function cssFiles()
	{
		wp_enqueue_style('ET_Builder_Module_Ajax_Login', get_stylesheet_directory_uri().'/css/ET_Builder_Module_Ajax_Login.css');
	}
	
	function javascriptFiles()
	{
		wp_enqueue_script('ET_Builder_Module_Ajax_Login', get_stylesheet_directory_uri().'/js/ET_Builder_Module_Ajax_Login.js', array('jquery'));
	}

	function get_fields() {
		$fields = array(
			'title' => array(
				'label'           => esc_html__( 'Title', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Choose a title of your login box.', 'et_builder' ),
			),
			'current_page_redirect' => array(
				'label'           => esc_html__( 'Redirect To The Current Page', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'description' => esc_html__( 'Here you can choose whether the user should be redirected to the current page.', 'et_builder' ),
			),
			'use_background_color' => array(
				'label'           => esc_html__( 'Use Background Color', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'color_option',
				'options'         => array(
					'on'          => esc_html__( 'Yes', 'et_builder' ),
					'off'         => esc_html__( 'No', 'et_builder' ),
				),
				'affects' => array(
					'#et_pb_background_color',
				),
				'description' => esc_html__( 'Here you can choose whether background color setting below should be used or not.', 'et_builder' ),
			),
			'background_color' => array(
				'label'             => esc_html__( 'Background Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'description'       => esc_html__( 'Define a custom background color for your module, or leave blank to use the default color.', 'et_builder' ),
				'depends_default'   => true,
			),
			'background_layout' => array(
				'label'           => esc_html__( 'Text Color', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'color_option',
				'options'      	  => array(
					'dark'  => esc_html__( 'Light', 'et_builder' ),
					'light' => esc_html__( 'Dark', 'et_builder' ),
				),
				'description' => esc_html__( 'Here you can choose whether your text should be light or dark. If you are working with a dark background, then your text should be light. If your background is light, then your text should be set to dark.', 'et_builder' ),
			),
			'text_orientation' => array(
				'label'             => esc_html__( 'Text Orientation', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'layout',
				'options'           => et_builder_get_text_orientation_options(),
				'description'       => esc_html__( 'Here you can adjust the alignment of your text.', 'et_builder' ),
			),
			'content_new' => array(
				'label'             => esc_html__( 'Content', 'et_builder' ),
				'type'              => 'tiny_mce',
				'option_category'   => 'basic_option',
				'description'       => esc_html__( 'Input the main text content for your module here.', 'et_builder' ),
			),
			'form_field_background_color' => array(
				'label'             => esc_html__( 'Form Field Background Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
			'form_field_text_color' => array(
				'label'             => esc_html__( 'Form Field Text Color', 'et_builder' ),
				'type'              => 'color',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
			'focus_background_color' => array(
				'label'             => esc_html__( 'Focus Background Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
			'focus_text_color' => array(
				'label'             => esc_html__( 'Focus Text Color', 'et_builder' ),
				'type'              => 'color',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
			'use_focus_border_color' => array(
				'label'           => esc_html__( 'Use Focus Border Color', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'color_option',
				'options'         => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'affects'     => array(
					'#et_pb_focus_border_color',
				),
				'tab_slug' => 'advanced',
			),
			'focus_border_color' => array(
				'label'             => esc_html__( 'Focus Border Color', 'et_builder' ),
				'type'              => 'color',
				'custom_color'      => true,
				'depends_default'   => true,
				'tab_slug'          => 'advanced',
			),
			'disabled_on' => array(
				'label'           => esc_html__( 'Disable on', 'et_builder' ),
				'type'            => 'multiple_checkboxes',
				'options'         => array(
					'phone'   => esc_html__( 'Phone', 'et_builder' ),
					'tablet'  => esc_html__( 'Tablet', 'et_builder' ),
					'desktop' => esc_html__( 'Desktop', 'et_builder' ),
				),
				'additional_att'  => 'disable_on',
				'option_category' => 'configuration',
				'description'     => esc_html__( 'This will disable the module on selected devices', 'et_builder' ),
			),
			'admin_label' => array(
				'label'       => esc_html__( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => esc_html__( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
			),
			'module_id' => array(
				'label'           => esc_html__( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'tab_slug'        => 'custom_css',
				'option_class'    => 'et_pb_custom_css_regular',
			),
			'module_class' => array(
				'label'           => esc_html__( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'tab_slug'        => 'custom_css',
				'option_class'    => 'et_pb_custom_css_regular',
			),
			'show_button' => array(
				'label'           => esc_html__( 'Show Login Button', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'on'          => esc_html__( 'Yes', 'et_builder' ),
					'off'         => esc_html__( 'No', 'et_builder' ),
				),
				'description' => esc_html__( 'Show or not the Login, use this for hide Login on menu, and put this css class on menu: et_pb_ajax_login_button', 'et_builder' ),
			),
		);
		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$module_id                   = $this->shortcode_atts['module_id'];
		$module_class                = $this->shortcode_atts['module_class'];
		$title                       = $this->shortcode_atts['title'];
		$background_color            = $this->shortcode_atts['background_color'];
		$background_layout           = $this->shortcode_atts['background_layout'];
		$text_orientation            = $this->shortcode_atts['text_orientation'];
		$use_background_color        = $this->shortcode_atts['use_background_color'];
		$current_page_redirect       = $this->shortcode_atts['current_page_redirect'];
		$form_field_background_color = $this->shortcode_atts['form_field_background_color'];
		$form_field_text_color       = $this->shortcode_atts['form_field_text_color'];
		$focus_background_color      = $this->shortcode_atts['focus_background_color'];
		$focus_text_color            = $this->shortcode_atts['focus_text_color'];
		$use_focus_border_color      = $this->shortcode_atts['use_focus_border_color'];
		$focus_border_color          = $this->shortcode_atts['focus_border_color'];
		$button_custom               = $this->shortcode_atts['custom_button'];
		$custom_icon                 = $this->shortcode_atts['button_icon'];
		$show_button				 = $this->shortcode_atts['show_button'];
		$content                     = $this->shortcode_content;

		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		if ( '' !== $focus_background_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%.et_pb_newsletter_form p input:focus',
				'declaration' => sprintf(
						'background-color: %1$s;',
						esc_html( $focus_background_color )
						),
			) );
		}

		if ( '' !== $focus_text_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%.et_pb_newsletter_form p input:focus',
				'declaration' => sprintf(
						'color: %1$s;',
						esc_html( $focus_text_color )
						),
			) );
		}

		if ( 'off' !== $use_focus_border_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%%.et_pb_newsletter_form p input:focus',
				'declaration' => sprintf(
						'border: 1px solid %1$s !important;',
						esc_html( $focus_border_color )
						),
			) );
		}

		if ( '' !== $form_field_background_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% input[type="text"], %%order_class%% textarea, %%order_class%% .input',
				'declaration' => sprintf(
						'background-color: %1$s;',
						esc_html( $form_field_background_color )
						),
			) );
		}

		if ( '' !== $form_field_text_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% input[type="text"], %%order_class%% textarea, %%order_class%% .input',
				'declaration' => sprintf(
						'color: %1$s;',
						esc_html( $form_field_text_color )
						),
			) );
		}

		if ( is_rtl() && 'left' === $text_orientation ) {
			$text_orientation = 'right';
		}

		$redirect_url = 'on' === $current_page_redirect
		? ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']
		: '';

		if ( is_user_logged_in() ) {
			global $current_user;
			get_currentuserinfo();

			$content .= sprintf( '<br/>%1$s <a href="%2$s">%3$s</a>',
					sprintf( esc_html__( 'Logged in as %1$s', 'et_builder' ), esc_html( $current_user->display_name ) ),
					esc_url( wp_logout_url( $redirect_url ) ),
					esc_html__( 'Log out', 'et_builder' )
					);
		}

		$class = " et_pb_ajax_login et_pb_module et_pb_bg_layout_{$background_layout} et_pb_text_align_{$text_orientation}";

		$form = '';
		$output = '';

		if ( !is_user_logged_in() ) {
			$username = esc_html__( 'Username', 'et_builder' );
			$password = esc_html__( 'Password', 'et_builder' );

			ob_start();
			do_action('login_form', array($this, 'loginForm'));
			$form_meta = ob_get_contents();
			$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$actual_link = htmlspecialchars( $actual_link, ENT_QUOTES, 'UTF-8' );
			//$form_meta .= '<input type="hidden" name="redirect_to" value="'.$actual_link.'" />';
			
			if (!isset($_SESSION))
			{
				session_start();
			}
			$_SESSION['wp-opauth-redirect'] = $actual_link;
			
			ob_clean();
			
			$form = sprintf( '
				<div class="et_pb_newsletter_form et_pb_login_form">
					'.$form_meta.'
					<form action="%7$s" method="post">
						<p>
							<label class="et_pb_contact_form_label" for="user_login" style="display: none;">%3$s</label>
							<input id="user_login" placeholder="%4$s" class="input" type="text" value="" name="log" />
						</p>
						<p>
							<label class="et_pb_contact_form_label" for="user_pass" style="display: none;">%5$s</label>
							<input id="user_pass" placeholder="%6$s" class="input" type="password" value="" name="pwd" />
						</p>
						<p class="et_pb_forgot_password"><a href="%12$s">%13$s</a><a href="%2$s">%1$s</a></p>
						<p>
							<button type="submit" class="et_pb_newsletter_button et_pb_button%11$s"%10$s>%8$s</button>
							%9$s
						</p>
					</form>
				</div>',
					esc_html__( 'Forgot your password?', 'et_builder' ),
					esc_url( wp_lostpassword_url() ),
					esc_html( $username ),
					esc_attr( $username ),
					esc_html( $password ),
					esc_attr( $password ),
					esc_url( site_url( 'wp-login.php', 'login_post' ) ),
					esc_html__( 'Login', 'et_builder' ),
					( 'on' === $current_page_redirect
							? sprintf( '<input type="hidden" name="redirect_to" value="%1$s" />',  $redirect_url )
							: ''
							),
					'' !== $custom_icon && 'on' === $button_custom ? sprintf(
							' data-icon="%1$s"',
							esc_attr( et_pb_process_font_icon( $custom_icon ) )
							) : '',
					'' !== $custom_icon && 'on' === $button_custom ? ' et_pb_custom_button_icon' : '',
					wp_registration_url(),
					esc_html__( 'Register', 'et_builder' )
					);
			$output = sprintf(
					'<div%6$s class="et_pb_newsletter et_pb_login clearfix%4$s%7$s"%5$s>
					<div style="%8$s" class="et_pb_ajax_login_button">
						%1$s
					</div>
					<div class="et_pb_ajax_login_panel"%5$s>
						<div class="et_pb_newsletter_description">
							%2$s
						</div>
						%3$s
					</div>
				</div>',
					( '' !== $title ? '<h2>' . esc_html( $title ) . '</h2>' : '' ),
					$content,
					$form,
					esc_attr( $class ),
					( 'on' === $use_background_color
							? sprintf( ' style="background-color: %1$s;'.( 'on' === $show_button ? '' : ' height:0;margin:0;padding:0;width:0;' ).'"', esc_attr( $background_color ) )
							: ( 'on' === $show_button ? '' : ' style="height:0;margin:0;padding:0;width:0;"' )
							),
					( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
					( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' ),
					( 'on' === $show_button ? '' : 'display:none;' )
					);
		}

		return $output;
	}
}
new ET_Builder_Module_Ajax_Login;