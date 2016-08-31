<?php

/**
 * Class for building wicked easy plugin options pages.
 *
 * @link       http://endif.media
 * @since      1.0.0
 *
 * @package    Plugin_Options
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Post_Sync
 * @author     Ethan Allen <yourfriendethan@gmail.com>
 */
class Plugin_Options {

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $plugin_nicename;

	/**
	 * The plugin option name.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
     private $plugin_option_name;

	/**
	 * The plugin settings.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $plugin_menu_page;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_nicename, $plugin_option_name, $plugin_menu_page ) {

		$this->plugin_nicename = $plugin_nicename;
		$this->plugin_slug = sanitize_title($plugin_nicename);
		$this->plugin_settings = get_option($plugin_option_name);
		$this->plugin_option_name = $plugin_option_name;
		$this->plugin_menu_page = $plugin_menu_page;
		
	}

	/**
	 * Get tab title from navigation tabs.
	 *
	 * @since    1.0.0
	 */
	public function unslugify($active_tab, $tabs) {

		$active_tab = str_replace('-', ' ', $active_tab);//replace dash with space
		foreach($tabs as $tab){

			if (strcasecmp($active_tab, $tab) == 0){
				return $tab;
			}
		}

	}

	/**
	 * Render settings navigation tabs.
	 *
	 * @since    1.0.0
	 */
	public function render_tabs($tabs, $active_tab) {

		$navtabs = '';
		foreach ($tabs as $tab){
        	
        	$tab_slug = sanitize_title($tab);
        	$current  = ($tab_slug == sanitize_title($active_tab)) ? 'nav-tab-active' : '';
        	$navtabs .= '<a href="admin.php?page=' 
        			 . $this->plugin_menu_page
        			 . '&tab=' 
        			 . $tab_slug
        			 . '" class="nav-tab ' 
        			 . $current . '">' 
        			 . $tab . '</a>';     
		}
		return '<h2 class="nav-tab-wrapper">' . $navtabs . '</h2>';

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function render_fields($active_tab, $tabs, $options){
		$fields ='';
	    foreach ($options[$this->unslugify($active_tab, $tabs)] as $optionset) {
        	$opt_label = $optionset[0];
			$opt_name  = $optionset[1];
			$opt_type  = $optionset[2];
			$opt_values= $optionset[3];
			$opt_note  = $optionset[4];

			$fields .= '<tr valign="top"><th scope="row">' . __($opt_label . " : ", '') . '</th><td>'; 

            switch ($opt_type) {
                case 'text':
                    $fields .= '<input type="text" name="'
                                  . $opt_name .'" value="'
                                  . esc_attr($this->plugin_settings[$opt_name]) 
                                  . '" class="regular-text">
                    ';
                    if ($opt_note) 
                    	$fields .= '<br><small>' . $opt_note . '</small>';

                    break;
                case 'checkbox':
                    $fields .= '<input type="checkbox" name="'
                                  . $opt_name . '" value="1"'
                                  . checked($this->plugin_settings[$opt_name], 1, false)//(($this->plugin_settings[$opt_name] === 'true') ? ' checked="checked"' : '')
                                  . ' size="20">
                    ';
                    if ($opt_note) 
                    	$fields .= '<small>' . $opt_note . '</small>';

                    break;

                case 'select':
                    $fields .= '<select name="'
                                  . $opt_name . '" >';
                                  foreach ($opt_values as $val) {
                                    $fields .= '<option value="' . $val . '"' . selected($this->plugin_settings[$opt_name], $val, false) . '>' . $val . '</option>';
                                  }
                                  $fields .= '</select>
                    ';
                    if ($opt_note) 
                    	$fields .= '<br><small>' . $opt_note . '</small>';

                    break;

                case 'url':
                    $fields .= '<input type="url" name="'
                                  . $opt_name .'" value="'
                                  . esc_attr($this->plugin_settings[$opt_name]) 
                                  . '" class="regular-text code">
                    ';
                    if ($opt_note) 
                    	$fields .= '<br><small>' . $opt_note . '</small>';

                    break;
            }
        }
        return $fields;
	}


	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function save_form($active_tab, $tabs, $options){

	    foreach ($options[$this->unslugify($active_tab, $tabs)] as $optionset) {
			$opt_name  = $optionset[1];
			$opt_type  = $optionset[2];

            switch ($opt_type) {
                case 'text':

                    $this->plugin_settings[$opt_name] = sanitize_text_field($_POST[$opt_name]);

                    break;
                case 'checkbox':
                    
                    $this->plugin_settings[$opt_name] = intval($_POST[$opt_name]);

                    break;

                case 'url':
                    //$opt_name = sanitize_
                    $this->plugin_settings[$opt_name] = esc_url_raw($_POST[$opt_name]);

                    break;

                default:
            		# code...
                	$this->plugin_settings[$opt_name] = sanitize_text_field($_POST[$opt_name]);

            		break;
            }

            update_option($this->plugin_option_name, $this->plugin_settings);
 
        }
        echo '<div id="message" class="updated fade"><p>'. __('Options saved successfully.', sanitize_title($this->plugin_name) ) .'</p></div>';
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function render_form($tabs, $options, $active_tab) {
		
		if (isset($_POST[$this->plugin_slug . '_settings_save'])){

			//check referer
			check_admin_referer( 
				$this->plugin_slug . '_popts_save_form', 
				$this->plugin_slug . '_popts_name_of_nonce'
			);

			$this->save_form($active_tab, $tabs, $options);

		}

		echo  '<div class="wrap">
				   <h2>' . $this->plugin_nicename . '- settings' .'</h2>
			       <div id="page-wrap">'
			       	   . $this->render_tabs($tabs, $active_tab) .
			       '<div id="inside">
				    <div id="options-div">
					   <form method="post" class="settings-form" style="margin-left: 15px">'
					     . wp_nonce_field( $this->plugin_slug . '_popts_save_form', $this->plugin_slug . '_popts_name_of_nonce' )
					     . '<fieldset class="options">
					          <table class="form-table">'
		        	            . $this->render_fields($active_tab, $tabs, $options) .
		        	          '</table>
						    </fieldset>
						    <p><input type="submit" name="' 
						      . $this->plugin_slug
						      . '_settings_save" id="' 
						      . $this->plugin_slug 
						      . '_settings_save" class="button button-primary" value="Save Settings"  /></p>
				       </form>
					</div><!-- end options div -->
				    </div><!-- end inside -->
			     <div style="clear: both;"></div>
			    </div><!-- end pagewrap -->
		        </div><!-- end wrap -->';
		        
	}


}
