<?php
/**
 * Eklenti aktivasyon işlevleri
 *
 * @since      1.0.0
 * @package    CodeOn_Restaurant_Menu
 */

class CodeOn_Restaurant_Menu_Activator {

    /**
     * Eklenti aktivasyon işlemleri
     *
     * @since    1.0.0
     */
    public static function activate() {
        // Varsayılan ayarları oluştur
        if (!get_option('codeon_currency_symbol')) {
            update_option('codeon_currency_symbol', '€');
        }
        
        if (!get_option('codeon_default_template')) {
            update_option('codeon_default_template', 'default');
        }
        
        if (!get_option('codeon_show_images')) {
            update_option('codeon_show_images', '0');
        }
        
        if (!get_option('codeon_primary_color')) {
            update_option('codeon_primary_color', '#000000');
        }
        
        if (!get_option('codeon_secondary_color')) {
            update_option('codeon_secondary_color', '#333333');
        }
        
        if (!get_option('codeon_font_family')) {
            update_option('codeon_font_family', 'inherit');
        }
        
        // Yetkileri temizle (flush rewrite rules)
        flush_rewrite_rules();
    }
}