<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
function fayzak_qc_stats_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'fayzak_qc_stats';

    $results = $wpdb->get_results("SELECT * FROM $table_name ORDER BY click_time DESC LIMIT 100");

    echo '<div class="wrap" style="max-width: 900px; width: 100%; margin: 0 auto;">';
    echo '<h1>סטטיסטיקות לחיצות</h1>';



echo '<div style="margin-bottom: 10px; font-size: 13px; color: #555;">';
echo '<strong>פותח באהבה על ידי</strong> <a href="https://fayzakseo.com" target="_blank" style="color: #0073aa; text-decoration: none;"><strong>fayzakseo.com</strong></a>';
echo '</div>';


// ===== איפוס נתונים =====
if ( current_user_can('manage_options') && isset($_POST['fayzak_qc_reset_submit']) ) {
    $secret_code = 'fayzakidit2025';
    if ($_POST['fayzak_qc_reset_code'] === $secret_code) {
        $wpdb->query("DELETE FROM $table_name");
        echo '<div class="notice notice-success is-dismissible"><p>הנתונים אופסו בהצלחה.</p></div>';
    } else {
        echo '<div class="notice notice-error is-dismissible"><p>קוד שגוי. הנתונים לא אופסו.</p></div>';
    }
}

echo '<form method="post" style="margin-top: 20px;">';
wp_nonce_field('fayzak_qc_reset_nonce');
echo '<input type="password" name="fayzak_qc_reset_code" placeholder="' . __('Enter Admin Code', 'fayzak-quick-contact') . '" required style="margin-left: 10px;">';
echo '<input type="submit" class="button button-danger" name="fayzak_qc_reset_submit" value="' . __('Reset Data', 'fayzak-quick-contact') . '">';
echo '</form>';











global $wpdb;
$table_name = $wpdb->prefix . 'fayzak_qc_stats';

$total_clicks = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
$whatsapp_clicks = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE action_type = 'whatsapp'");
$phone_clicks = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE action_type = 'phone'");

echo '<div style="background: #fff; padding: 15px; border: 1px solid #ccd0d4; margin-bottom: 20px;">';
echo '<h2 style="margin-top: 0;">סיכום נתונים</h2>';
echo '<p><strong>סה״כ לחיצות:</strong> ' . $total_clicks . '</p>';
echo '<p><strong>לחיצות על וואטסאפ:</strong> ' . $whatsapp_clicks . '</p>';
echo '<p><strong>לחיצות על טלפון:</strong> ' . $phone_clicks . '</p>';
echo '</div>';



    echo '<table class="widefat striped">';
    echo '<thead><tr><th>ID</th><th>סוג פעולה</th><th>תאריך ושעה</th></tr></thead><tbody>';

    foreach ($results as $row) { echo '<tr style="background-color: ' . ($row->action_type === 'whatsapp' ? '#d4f8d4' : ($row->action_type === 'phone' ? '#f0f0f0' : 'transparent')) . ';">';
        echo '<td>' . esc_html($row->id) . '</td>';
        echo '<td>' . esc_html($row->action_type) . '</td>';
        echo '<td>' . esc_html($row->click_time) . '</td>';
        echo '</tr>';
    }

    echo '</tbody></table></div>';
}