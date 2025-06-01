<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
function fayzak_qc_menu_page(){
    add_options_page('Fayzak Quick Contact', 'Fayzak Quick Contact', 'manage_options', 'fayzak-qc-settings', 'fayzak_qc_settings_page');
}
add_action('admin_menu', 'fayzak_qc_menu_page');

function fayzak_qc_settings_page(){ ?>

<div style="display: flex; flex-wrap: wrap; justify-content: space-between; direction: rtl;">
    <div style="flex: 0 0 58%;">
        <h1>הגדרות כפתור יצירת קשר - איציק פייזק</h1>
        <form method="post" action="options.php">
            <?php
                settings_fields('fayzak_qc_options_group');
                do_settings_sections('fayzak_qc_settings');
                submit_button();
            ?>
        </form>
    </div>
    <div style="flex: 0 0 38%; text-align: right; padding-right: 20px;">
        <h2 style="font-weight: bold; font-size: 18px; border-bottom: 1px solid #ccc; padding-bottom: 5px; margin-bottom: 20px;">תצוגה מקדימה</h2>
        <div id="fayzak-preview-wrapper" style="position: relative; height: 400px; border: 1px dashed #ccc; border-radius: 8px; background: #f9f9f9;">
            <div id="fayzak-preview">
                <div class='fayzak-qc-button' style='right: 20px; bottom: 80px; position:absolute; display:inline-block;'>
                    <div class='fayzak-qc-toggle' style='background-color: #25D366; width: 50px; height: 50px; border-radius: 50%; color: white; line-height: 50px; text-align: center; font-size: 28px;'>+</div>
                    <div class='fayzak-qc-menu' style='margin-top: 10px; background: white; border-radius: 10px; padding: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); display:block;'>
                        <a href='#' style='display:block; color:#333; padding:5px 10px; text-decoration:none; font-weight:bold;'>וואטסאפ</a>
                        <a href='#' style='display:block; color:#333; padding:5px 10px; text-decoration:none; font-weight:bold;'>טלפון</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const preview = document.getElementById('fayzak-preview');
    const toggle = preview.querySelector('.fayzak-qc-toggle');
    const container = preview.querySelector('.fayzak-qc-button');

    document.querySelector('[name="fayzak_qc_color"]').addEventListener('input', function() {
        toggle.style.backgroundColor = this.value;
    });

    document.querySelectorAll('[name="fayzak_qc_position"]').forEach(el => {
        el.addEventListener('change', function() {
            container.style.right = this.value === 'right' ? '20px' : 'auto';
            container.style.left = this.value === 'left' ? '20px' : 'auto';
        });
    });

    document.querySelector('[name="fayzak_qc_offset"]').addEventListener('input', function() {
        container.style.bottom = this.value + 'px';
    });
});
</script>
<?php }

function fayzak_qc_settings_init(){
    register_setting('fayzak_qc_options_group', 'fayzak_qc_phone', 'sanitize_text_field');
    register_setting('fayzak_qc_options_group', 'fayzak_qc_whatsapp', 'sanitize_text_field');
    register_setting('fayzak_qc_options_group', 'fayzak_qc_message', 'sanitize_text_field');
    register_setting('fayzak_qc_options_group', 'fayzak_qc_color', 'sanitize_text_field');
    register_setting('fayzak_qc_options_group', 'fayzak_qc_position', 'sanitize_text_field');
    register_setting('fayzak_qc_options_group', 'fayzak_qc_offset', 'sanitize_text_field');

    add_settings_section('fayzak_qc_main_section', '', null, 'fayzak_qc_settings');

    add_settings_field('fayzak_qc_phone', 'מספר טלפון', function(){
        echo '<input type="text" name="fayzak_qc_phone" value="' . get_option('fayzak_qc_phone') . '" />';
    }, 'fayzak_qc_settings', 'fayzak_qc_main_section');

    add_settings_field('fayzak_qc_whatsapp', 'מספר וואטסאפ (ללא + וללא מקף, לדוגמה: 972528000755)', function(){
        echo '<input type="text" name="fayzak_qc_whatsapp" value="' . get_option('fayzak_qc_whatsapp') . '" />';
    }, 'fayzak_qc_settings', 'fayzak_qc_main_section');

    add_settings_field('fayzak_qc_message', 'הודעת ברירת מחדל', function(){
        echo '<input type="text" name="fayzak_qc_message" value="' . get_option('fayzak_qc_message') . '" />';
    }, 'fayzak_qc_settings', 'fayzak_qc_main_section');

    add_settings_field('fayzak_qc_color', 'צבע הכפתור', function(){
        echo '<input type="color" name="fayzak_qc_color" value="' . get_option('fayzak_qc_color', '#25D366') . '" />';
    }, 'fayzak_qc_settings', 'fayzak_qc_main_section');

    add_settings_field('fayzak_qc_position', 'מיקום צד', function(){
        $value = get_option('fayzak_qc_position', 'right');
        echo '
            <label><input type="radio" name="fayzak_qc_position" value="right" ' . checked($value, 'right', false) . '> ימין</label><br>
            <label><input type="radio" name="fayzak_qc_position" value="left" ' . checked($value, 'left', false) . '> שמאל</label>
        ';
    }, 'fayzak_qc_settings', 'fayzak_qc_main_section');

    add_settings_field('fayzak_qc_offset', 'גובה (px)', function(){
        echo '<input type="number" name="fayzak_qc_offset" value="' . get_option('fayzak_qc_offset', 80) . '" />';
    }, 'fayzak_qc_settings', 'fayzak_qc_main_section');
}
add_action('admin_init', 'fayzak_qc_settings_init');
?>