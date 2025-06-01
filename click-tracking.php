<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
// יצירת טבלה במסד הנתונים כאשר התוסף מופעל
function fayzak_qc_create_stats_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'fayzak_qc_stats';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        action_type varchar(20) NOT NULL,
        click_time datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'fayzak_qc_create_stats_table');

// פונקציה לקליטת הקלקות באמצעות AJAX
add_action('wp_ajax_fayzak_qc_track_click', 'fayzak_qc_track_click');
add_action('wp_ajax_nopriv_fayzak_qc_track_click', 'fayzak_qc_track_click');

function fayzak_qc_track_click() {
    if (!isset($_POST['action_type'])) {
        wp_send_json_error('Missing action type');
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'fayzak_qc_stats';

    $action_type = sanitize_text_field($_POST['action_type']);

    $wpdb->insert(
        $table_name,
        array('action_type' => $action_type)
    );

    wp_send_json_success('Click recorded');
}
?>
