
jQuery(document).ready(function($){
    $('.fayzak-qc-toggle').click(function(){
        $('.fayzak-qc-menu').slideToggle();
        $.post(fayzak_qc_ajax.ajax_url, { action: 'fayzak_qc_track_click', action_type: 'open' });
    });

    $('.fayzak-qc-menu a[href*="wa.me"]').click(function(){
        $.post(fayzak_qc_ajax.ajax_url, { action: 'fayzak_qc_track_click', action_type: 'whatsapp' });
    });

    $('.fayzak-qc-menu a[href*="tel:"]').click(function(){
        $.post(fayzak_qc_ajax.ajax_url, { action: 'fayzak_qc_track_click', action_type: 'phone' });
    });
});
