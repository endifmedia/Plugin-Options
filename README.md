# Plugin-Options
Class used to build dead simple plugin options pages for your WordPress plugin.

#Use:

Way up in the top of your admin class (or whatever you are using) in your constructor initialize the Plugin_Settings class;

    public function __construct() {

		$this->options = new Plugin_Options('Post Sync', 'post_sync_settings', $this->plugin_name . '_menu_page');

	}

The Plugin_Settings class accepts 3 arguements when intializing the class.

The first arguement ('Post Sync') is used by the Plugin_Settings class to display the title at the top of your settings page.

The second arguement is the name of your plugin options variable ( get_option('plugin_name_opt')) ) WordPress will expect this to be an underscore '_' separated string. The Plugin_Options class will save all of your settings into one option in the WordPress database.

The third and final arguement is the name of your plugin menu page. This should also be an underscore '_' separated string. This is the page where your settings fields will be.

#Setting up fields and options:

There are two sections that need to be setup for the Plugin_Options class to work properly, tabs and options.

#Tabs:

Setting up the tab navigation for your plugin is super simple. Declare a variable and set it to a simple array of strings.
$tabs = array('tabname');

You can add multiple strings to build a large tab navigation structure.
    $tabs = array('General', 'Payment', 'Stats', 'Email Templates');

You can set these to whatever you want them to be, but generally, the first letter should be uppercase and words should be separated by a space.

#Option Fields:

Option fields are a little more in depth. First, setup a mixed array with the tabs you created in the last step. Again, the first letter should be uppercase and words should be separated with a space.

    $options = array(
        'General' => '',
        'Payment' => '',
    )

Next add an array of options to the tab name array. The values the Plugin_Options class exp

    $options = array(
        'General' => array(
            array('option_label', 'input_name', 'input_type', array('array', of', 'select', 'option', 'values'), 'input_notes'),
        ),
        'Post Settings' => array(
            array('Check for new posts', 'check_posts', 'select', array('daily', 'weekly', 'monthly'), ''),
            array('Date', 'post_date', 'select', array('Today\'s date', 'Original'), '', ''),
        )
    )


option_label - The label for the form input. It will show up on the left next to your input.

input_name -  The name of the input. This is the unique identifier for each input picked up by php's post object when the form is sumbmitted.

input_type - The type of input you want to use. Currently the Plugin_Options class supports text, select, checkbox, and url input types.

array() - The fourth index is an array of option values for the 'select' input_type. Leave this blank '' when the input_type is not set to 'select'.

input_notes - Any notes you want to add to an input box. Basically a description toyour plugin users about the input. Notes appear next to checkboxes and directly below all others.


#Retrieving your options:

The settings page for your plugin will be completly handeled by the Plugin Options class. Though, at some point you will need 
to use the saved option. You can call get_option('your_plugin_settings') anywhere in your code to get the current settings. The returned value will be and array.

Using retrieved options:

The easiest way is to set your options to a variable ($settings = get_option(plugin_name_settings)) then you can call the index
of the array $settings['url'];
