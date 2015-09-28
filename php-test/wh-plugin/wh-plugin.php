<?php
/*
Plugin Name: WalletHub Plugin
Description: Autocomplete Wordpress Plugin using jQuery for WalletHub MySQL/PHP/JS Test.
Version:     1.0.0
Author:      Fabian Vallejos
Author URI:  http://fabianvallejos.com
Plugin URI:  https://github.com/vallejos/
*/

class WH_Plugin {

    // constructor
    function __construct() {
        add_action('admin_init', array($this, 'whp_init'));
        add_action('admin_menu', array($this, 'whp_add_menu'));
        add_action('wp_enqueue_scripts', array($this, 'whp_load_scripts'));
        add_action('wp_ajax_whp_search_ajax', array($this, 'whp_ajax_process_request'));

        register_activation_hook(__FILE__, array($this, 'whp_install'));
        register_deactivation_hook(__FILE__, array($this, 'whp_uninstall'));

        // set the shortcode to integrate into the theme
        add_shortcode('whp-search', array($this, 'whp_search'));

        // add shortcode support for widgets
        add_filter('widget_text', 'do_shortcode');
    }

    function whp_ajax_process_request() {
        global $wpdb;
        $text = $_REQUEST['search'].'%';

        $query = $wpdb->prepare('SELECT location, slug FROM '.$wpdb->dbname.'.'.$wpdb->prefix.'population WHERE location LIKE "%s" ORDER BY population DESC LIMIT 10', $text);

        error_log($query);

        $result = $wpdb->get_results($query, ARRAY_A);

        die(json_encode($result));
    }

    function whp_init() {
        global $wp_rewrite;
        wp_register_script('whp-search-js', plugin_dir_url(__FILE__).'js/wh.js', array('jquery'), '1.0.0', true);

        add_action('admin_post_import_csv_data', array($this, 'import_csv_data'));
    }

    // load jQuery
    function whp_load_scripts() {
        wp_enqueue_script('jquery');
        wp_enqueue_script('whp-search-js', plugin_dir_url(__FILE__).'js/wh.js', array('jquery'));
        wp_enqueue_style('whp-search-css', plugin_dir_url(__FILE__).'css/wh.css');

        wp_localize_script('whp-search-js', 'whp_search_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php')
        ));
    }

    // shows the search input
    function whp_search() {
        return ' <input type="text" value="" placeholder="Search" name="whp-search" id="whp-search" slug="" />
            <div id="whp-results"></div>';
    }

    // actions performed at loading of admin menu
    function whp_add_menu() {
        add_options_page('WalletHub Plugin', 'WalletHub Plugin', 'manage_options', 'location-search', array($this, 'whp_config_page'));
    }

    function whp_config_page() {
?>
        <div class="wrap">
            <h2>WalletHub Plugin Settings</h2>

            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" enctype="multipart/form-data">
                <input type="hidden" name="action" value="import_csv_data" />
                <?php wp_nonce_field('whp_data'); ?>

                <h3>Import Location Data</h3>
                Import Location Data from CSV File:
                <input name="importcsvdata" type="file" /><br/><br/>
                <a href="<?php echo plugin_dir_url(__FILE__).'data.csv'; ?>">Download sample data.csv</a><br/><br/>

                <input type="submit" value="Load" class="button-primary" />
            </form>
        </div>
<?php
    }

    // Function to be called when importing bugs
    function import_csv_data() {
        global $wpdb;

        // check if user has rights
        if (!current_user_can('manage_options')) wp_die('Access denied.');

        // check if nonce field is present
        check_admin_referer('whp_data');

        // check if file has been uploaded
        if (array_key_exists('importcsvdata', $_FILES) && !$_FILES['importcsvdata']['error']) {
            $upDir = wp_upload_dir();
            $upFile = $upDir['basedir'].'/'.$_FILES['importcsvdata']['name'];
            move_uploaded_file($_FILES['importcsvdata']['tmp_name'], $upFile);

            $h = fopen($upFile, 'r');
            if ($h) {
                $wpdb->query('truncate table '.$wpdb->prefix.'population');
                $query = $wpdb->prepare('load data local infile "%s" into table '.$wpdb->prefix.'population fields terminated by "\t"', $upFile);
                $delete = $wpdb->query($query);
                fclose($h);
            }
        }

        // Redirect the page to the admin page
        wp_redirect(add_query_arg('page', 'location-search', admin_url('options-general.php')));
    }

    // actions to perform on activation of plugin
    function whp_install() {
        error_log('Installing WHP...');
        global $wpdb;

        $tableName = $wpdb->prefix."population";

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $tableName (
            id int(11) unsigned NOT NULL AUTO_INCREMENT,
            location varchar(150) NOT NULL,
            slug varchar(150) NOT NULL,
            population int(10) unsigned NOT NULL,
            PRIMARY KEY  (id),
            KEY population (population),
            KEY location (location)
        ) $charset_collate;";

        require_once(ABSPATH.'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    // actions to perform on de-activation of plugin
    function whp_uninstall() {
        error_log('Uninstalling WHP...');
        // uninstall shortcode
        remove_shortcode('whp-search');
    }

}

$whPlugin = new WH_Plugin();

?>
