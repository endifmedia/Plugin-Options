# Plugin-Options
Class used to build dead simple plugin options pages for your WordPress plugin.

#Use:

Way up in the top of your admin class (or whatever you are using) in your constructor initialize the Plugin_Options class;

    public function __construct() {

		$this->options = new Plugin_Options('plugin_name', 'option_name', 'menu_page_name');

	}

The Plugin_Options class accepts 3 arguments when initializing the class.

The first argument ('plugin_name') is used by the Plugin_Options class to display the title at the top of your settings page.

The second argument is the name of your plugin options variable ( get_option('plugin_name_opt')) ) WordPress will expect this to be an underscore '_' separated string. The Plugin_Options class will save all of your settings into one option in the WordPress database.

The third and final argument is the name of your plugin menu page. This should also be an underscore '_' separated string. This is the page where your settings fields will be.

## Setting up tabs and option fields:

There are two sections that need to be setup for the Plugin_Options class to work properly, tabs and options.

## Tabs:

Setting up the tab navigation for your plugin is super simple. Declare a variable and set it to the current active tab.
    
    $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general';

You can set these to whatever you want them to be, but generally, the words should be uppercase and separated by a space.

## Option Fields:

Option fields are a little more in depth. First, setup a function that returns an array (see example below). Again, the words should be lowercase and separated with a space.

    $options = array(
		/** General Settings */
		'tab1' => apply_filters( 'filter_name',
			array(
				'input_name' => array(
					'id'   => 'input_name',
					'label' => __( 'Other', 'plugin-namespace' ),
					'desc' => '',
					'type' => 'text'
				)			
			)	
		),
		'tab2' => apply_filters('filter_name',
			array(					
				'currency_code' => array(
					'id'      => 'input_name',
					'label'    => __( 'Currency Code', 'plugin-namespace' ),
					'desc'    => __( '', 'plugin-namespace' ),
					'type'    => 'select',
					'options' => array(
						'USD' => 'U.S. Dollar',
						'AUD' => 'Austrailian Dollar',
						'BRL' => 'Brazilian Real',
						'CAD' => 'Canadian Dollar',
						'CZK' => 'Czech Koruna',
						'DKK' => 'Danish Krone',
						'EUR' => 'EURO',
						'HKD' => 'Hong Kong Dollar',
						'HUF' => 'Hungarian Forint',
						'ILS' => 'Israeli New Sheqel',
						'JPY' => 'Japanese Yen',
						'MYR' => 'Malaysian Ringgit',
						'MXN' => 'Mexican Peso',
						'NOK' => 'Norwegian Krone',
						'NZD' => 'New Zealand Dollar',
						'PHP' => 'Philippine Peso',
						'PLN' => 'Polish Zloty',
						'GBP' => 'Pound Sterling',
						'SGD' => 'Singapore Dollar',
						'SEK' => 'Swedish Krona',
						'CHF' => 'Swiss Franc',
						'TWD' => 'Taiwan New Dollar',
						'THB' => 'Thai Baht',
						'TRY' => 'Turkish Lira',
					)
				),		
			)	
		),

    );
    return apply_filters( 'filter_name_group', $options );

id -  The name of the input. This is the unique identifier for each input picked up by PHP's post object when the form is submitted.

label - The label for the form input. It will show up on the left next to your input.

type - The type of input you want to use. Currently the Plugin_Options class supports text, select, checkbox, and url input types.

options - The fourth index is an array of option values for the 'select' input_type. Leave this blank '' when the input_type is not set to 'select'.

## License Key option type!:

As of version 2.2 of the Plugin_Options class you can make use of license key input types! There is a hook built into the
class that fires if the field type of 'license' has been saved and passes the sanitized key to the hooked function.

Hook into $this->plugin_slug . '_save_license_key'. $this->plugin_slug would be the lowercase, dash separated, version
of the first param passed into the Plugin_Options class.

    function my_validate_key_function() {
        //some validation code below...


        //then we need to set plugin_status to 'valid'
        $options = get_option('plugin_settings');
        $options['plugin_status'] = 'valid';

        //update our options
        update_option('plugin_settings', $options);
    }
    add_action('plugin-name_save_license_key', 'my_validate_key_function');

The Plugin_Options class will look for a 'plugin_status' key within the settings array. If the value is set to
'valid' the class will add a green check mark next to the license key input box. How nifty!

## Retrieving your options:

The settings page for your plugin will be completly handeled by the Plugin Options class. Though, at some point you will need 
to use the saved option. You can call get_option('your_plugin_settings') anywhere in your code to get the current settings. The returned value will be an array.

## Using retrieved options:

The easiest way is to set your options to a variable.

    $settings = get_option(plugin_name_settings); 

Then you can call the index of the array. 

    $my_option = $settings['index_name'];
