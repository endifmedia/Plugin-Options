<?php

/**
 * Class for building wicked easy plugin options pages.
 *
 * @link       http://endif.media
 * @since      0.2.1
 *
 * @package    Plugin_Options
 * @author     Ethan Allen
 */
class Plugin_Options {

	/**
	 * The nice name this plugin.
	 *
	 * @since    0.2.1
	 * @access   private
	 * @var      string    $version    The user readable name of this plugin.
	 */
	private $plugin_nicename;

	/**
	 * The plugin option name.
	 *
	 * @since    0.1
	 * @access   private
	 * @var      string    $version    The option name of this plugin.
	 */
        private $plugin_option_name;

	/**
	 * The plugin menu page.
	 *
	 * @since    0.2.1
	 * @access   private
	 * @var      string    $version    The menu/settings page for this plugin.
	 */
	private $plugin_menu_page;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since      0.1
	 * @param      string    $plugin_nicename       User readable name of this plugin.
	 * @param      string    $plugin_option_name    Option variable name you are using to store settings in the DB.
	 * @param      string    $plugin_menu_page      Settings page slug where setting will display.
	 */
	public function __construct( $plugin_nicename, $plugin_option_name, $plugin_menu_page ) {

		$this->plugin_slug_w_underscores = 'invoice_app';

		$this->plugin_nicename = $plugin_nicename; //plugin nicename 'My Plugin'
		$this->plugin_slug = sanitize_title($plugin_nicename);//slug version of plugin name  'my-plugin'
		$this->plugin_settings = get_option($plugin_option_name);//current plugin settings
		$this->plugin_option_name = $plugin_option_name;//plugin option name
		$this->plugin_menu_page = $plugin_menu_page;//plugin menu page
		
	}

	/**
	 * Render navigation tabs.
	 *
	 * @since    0.1
	 */
	public function render_tabs($tabs, $active_tab) {

		$navtabs = '';
		foreach ($tabs as $tab => $settings){
        	
        	$tab_slug = sanitize_title($tab);
        	$current  = ($tab_slug == sanitize_title($active_tab)) ? 'nav-tab-active' : '';
        	$navtabs .= '<a href="admin.php?page=' 
        			 . $this->plugin_menu_page
        			 . '&tab=' 
        			 . $tab_slug
        			 . '" class="nav-tab ' 
        			 . $current . '">' 
        			 . ucwords($tab) . '</a>';     
		}
		echo '<h2 class="nav-tab-wrapper">' . $navtabs . '</h2>';

	}

	/**
	 * Render form fields.
	 *
	 * @since    0.2.0
	 */
	public function render_fields($active_tab, $options){
		
		$html ='';
	    foreach ($options as $tab => $fields) {

			if ($active_tab == sanitize_title($tab)){
				foreach ($fields as $field) {

					$html .= '<tr valign="top"><th scope="row">' . __($field['label'] , '') . '</th><td>'; 

		            switch ($field['type']) {
		                case 'text':
		                    $size = ( isset( $field['size'] ) && ! is_null( $field['size'] ) ) ? $field['size'] : 'regular';
		                    $html .= '<input type="text" name="'
		                                  . $field['id'] .'" value="'
		                                  . esc_attr($this->plugin_settings[$field['id']]) 
		                                  . '" class="' . sanitize_html_class( $size ) . '-text">
		                    ';
		                    if ($field['desc']) 
		                    		if (isset($size) && $size == 'small') {
		                    	    	$html .= '<small>' . $field['desc'] . '</small>';
		                    	    }else {
		                    	    	$html .= '<br><small>' . $field['desc'] . '</small>';
		                    	    }

		                    break;

		                case 'upload':
		                    $size = ( isset( $field['size'] ) && ! is_null( $field['size'] ) ) ? $field['size'] : 'regular';
		                    /*$html .= '<input type="text" name="'
		                                  . $field['id'] .'" value="'
		                                  . esc_attr($this->plugin_settings[$field['id']]) 
		                                  . '" class="' . sanitize_html_class( $size ) . '-text">
		                    ';
		                    if ($field['desc']) 
		                    	$html .= '<br><small>' . $field['desc'] . '</small>';*/
		                    //$html .= '<label for="' . $field['id'] . '"> ' . $field['label'] . '</label>';
		                    $html .= '<input type="text" class="' . sanitize_html_class( $size ) . '-text" id="' . $field['id'] . '" name="' . $field['id'] . '" value="' . esc_attr( $this->plugin_settings[$field['id']] ) . '"/>';
							$html .= '<span>&nbsp;<input type="button" class="edd_settings_upload_button button-secondary" value="' . __( 'Upload File', $this->plugin_slug ) . '"/></span>';


		                    break;
		            
				        case 'checkbox':
		                    $html .= '<input type="checkbox" name="'
		                                  . $field['id'] . '" value="1"'
		                                  . checked($this->plugin_settings[$field['id']], 1, false)//(($this->plugin_settings[$opt_name] === 'true') ? ' checked="checked"' : '')
		                                  . ' size="20">
		                    ';
		                    if ($field['desc']) 
		                    	$html .= '<small>' . $field['desc'] . '</small>';

		                    break;

		                case 'select':
		                    $html .= '<select name="'
		                                  . $field['id'] . '" >';
		                                  foreach ($field['options'] as $value => $name) {
		                                    $html .= '<option value="' . $value . '"' . selected($this->plugin_settings[$field['id']], $value, false) . '>' . $name . '</option>';
		                                  }
		                                  $html .= '</select>
		                    ';
		                    if ($field['desc']) 
		                    	$html .= '<br><small>' . $field['desc'] . '</small>';

		                    break;

		                case 'url':
		                    $html .= '<input type="url" name="'
		                                  . $opt_name .'" value="'
		                                  . esc_attr($this->plugin_settings[$field['id']]) 
		                                  . '" class="regular-text code">
		                    ';
		                    if ($field['desc']) 
		                    	$html .= '<br><small>' . $field['desc'] . '</small>';

		                    break;

		                case 'textarea':
		                    $html .= '<textarea class="large-text" cols="50" rows="5" name="'
		                                  . $opt_name .'" rows="4" cols="16" class="large-text">'
		                                  . esc_attr($this->plugin_settings[$field['id']]) 
		                                  . '</textarea>
		                    ';
		                    if ($field['desc']) 
		                    	$html .= '<br><small>' . $field['desc'] . '</small>';

		                    break;  
		             
		            }
		        }
		    }
        }
        echo $html;
	}


	/**
	 * Save form.
	 *
	 * Function used to save plugin settings.
	 *
	 * @since    0.1
	 */
	public function save_form($active_tab, $options){
		
        //for each option[current tab}
        foreach ($options as $tab => $fields) { //tabs and fields

			if ($active_tab == sanitize_title($tab)){
				foreach ($fields as $field) {

					switch ($field['type']) {
		                case 'text':

		                    $this->plugin_settings[$field['id']] = sanitize_text_field($_POST[$field['id']]);

		                    break;

		                case 'upload':
		                    
		                    $this->plugin_settings[$field['id']] = esc_url_raw($_POST[$field['id']]);

		                    break;

		                case 'checkbox':
		                    
		                    $this->plugin_settings[$field['id']] = intval($_POST[$field['id']]);

		                    break;
		                    
						case 'select':
		                    
		                    $this->plugin_settings[$field['id']] = sanitize_text_field($_POST[$field['id']]);

		                    break;

		                case 'url':
		                    //$opt_name = sanitize_
		                    $this->plugin_settings[$field['id']] = esc_url_raw($_POST[$field['id']]);

		                    break;

		                case 'textarea':
		                    //$opt_name = sanitize_
		                    $this->plugin_settings[$field['id']] = sanitize_text_field($_POST[$field['id']]);

		                    break;

		                default:
		            		# code...
		                	$this->plugin_settings[$field['id']] = sanitize_text_field($_POST[$field['id']]);

		            		break;
		            }

				}

				update_option($this->plugin_option_name, $this->plugin_settings);

			}

		}
        echo '<div id="message" class="updated fade"><p>'. __('Options saved successfully.', sanitize_title($this->plugin_name) ) .'</p></div>';
	}

	/**
	 * Render form.
	 *
	 * Function that handles the display of plugin settings page.
	 *
	 * @since    0.2.1
	 */
	public function render_form($options, $active_tab) {
		
		if (isset($_POST[$this->plugin_slug . '_settings_save'])){

			//check referer
			check_admin_referer( 
				$this->plugin_slug . '_popts_save_form', 
				$this->plugin_slug . '_popts_name_of_nonce'
			);

			$this->save_form($active_tab, $options);

		} ?>

		<div class="wrap">
		    <h2><?php echo $this->plugin_nicename; ?> - settings</h2>
		    <?php $this->render_tabs($options, $active_tab); ?>
		    <form method="post" action=""> 
		    	<?php wp_nonce_field( $this->plugin_slug . '_popts_save_form', $this->plugin_slug . '_popts_name_of_nonce' ); ?>

		        <fieldset class="options">
					<table class="form-table">
					<?php $this->render_fields($active_tab, $options); ?>
					</table>
			    </fieldset>

		        <input type="submit" class="button button-primary" name="<?php echo $this->plugin_slug . '_settings_save'; ?>">
		    </form>
		</div>

		<?php

	}

}
