<?php
/*
Plugin Name: Fayzak Quick Contact
Plugin URI: https://fayzakseo.com/fayzak-quick-contact
Description: כפתור צף ליצירת קשר מהירה - וואטסאפ וטלפון.
Version: 1.1
Author: איציק פייזק – fayzakseo.com
Author URI: https://fayzakseo.com
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

defined('ABSPATH') or die('No script kiddies please!');

// Load assets
function fayzak_qc_enqueue_assets() {
    wp_enqueue_style('fayzak-qc-style', plugin_dir_url(__FILE__) . 'assets/css/style.css');
    wp_enqueue_script('fayzak-qc-script', plugin_dir_url(__FILE__) . 'assets/js/script.js', array('jquery'), null, true);

    wp_localize_script('fayzak-qc-script', 'fayzak_qc_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));

}
add_action('wp_enqueue_scripts', 'fayzak_qc_enqueue_assets');

// Render the button with settings
function fayzak_qc_render_button() {
    $phone = get_option('fayzak_qc_phone', '0501234567');
    $wa_number = get_option('fayzak_qc_whatsapp', '972501234567');
    $message = urlencode(get_option('fayzak_qc_message', 'שלום, אני מתעניין בשירותים שלך'));
    $color = get_option('fayzak_qc_color', '#25D366');
    $position = get_option('fayzak_qc_position', 'right');
    $offset = get_option('fayzak_qc_offset', 80);

    $phone_link = 'tel:' . $phone;
    $wa_link = "https://wa.me/{$wa_number}?text={$message}";

    echo "
    <div class='fayzak-qc-button' style='{$position}: 20px; bottom: {$offset}px;'>
        <div class='fayzak-qc-toggle' style='background-color: {$color};'>+</div>
        <div class='fayzak-qc-menu'>
            <a href='{$wa_link}' target='_blank'>וואטסאפ</a>
            <a href='{$phone_link}'>טלפון</a>
        </div>
    </div>";
}
add_action('wp_footer', 'fayzak_qc_render_button');

// Load settings page
require_once plugin_dir_path(__FILE__) . 'settings-page.php';

// יצירת טבלה גם בכל טעינה רגילה אם לא קיימת
function fayzak_qc_check_create_stats_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'fayzak_qc_stats';
    if ($wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") != $table_name) {
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            action_type varchar(20) NOT NULL,
            click_time datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}
add_action('plugins_loaded', 'fayzak_qc_check_create_stats_table');

// טוען את הקובץ של מעקב הקלקות
require_once plugin_dir_path(__FILE__) . 'click-tracking.php';
// טוען את עמוד הסטטיסטיקות מקובץ נפרד
require_once plugin_dir_path(__FILE__) . 'stats-page.php';

// מוסיף את עמוד הסטטיסטיקות לתפריט ניהול
function fayzak_qc_register_stats_page() {
    add_menu_page(
        'סטטיסטיקות לחיצות',
        'סטטיסטיקות לחיצות',
        'manage_options',
        'fayzak-qc-stats',
        'fayzak_qc_stats_page',
        'dashicons-chart-bar',
        81
    );
}
add_action('admin_menu', 'fayzak_qc_register_stats_page');


// 🔒 פונקציה לאיפוס נתונים בצורה מאובטחת
if (!function_exists('fayzak_qc_handle_stats_reset')) {
    add_action('admin_post_fayzak_qc_reset_stats', 'fayzak_qc_handle_stats_reset');
    function fayzak_qc_handle_stats_reset() {
        if (
            current_user_can('manage_options') &&
            isset($_POST['fayzak_qc_reset_nonce']) &&
            wp_verify_nonce($_POST['fayzak_qc_reset_nonce'], 'fayzak_qc_reset_action')
        ) {
            if ($_POST['fayzak_qc_reset_code'] === 'fayzakidit2025') {
                global $wpdb;
                $table_name = $wpdb->prefix . 'fayzak_qc_stats';
                $wpdb->query("DELETE FROM $table_name");
                wp_redirect(admin_url('admin.php?page=fayzak-qc-stats&reset=success'));
                exit;
            }
        }

        wp_redirect(admin_url('admin.php?page=fayzak-qc-stats&reset=fail'));
        exit;
    }
}

?>