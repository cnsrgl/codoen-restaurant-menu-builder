<?php
/**
 * Eklenti deaktivasyon işlevleri
 *
 * @since      1.0.0
 * @package    CodeOn_Restaurant_Menu
 */

class CodeOn_Restaurant_Menu_Deactivator {

    /**
     * Eklenti deaktivasyon işlemleri
     *
     * @since    1.0.0
     */
    public static function deactivate() {
        // Yetkileri temizle
        flush_rewrite_rules();
    }
}