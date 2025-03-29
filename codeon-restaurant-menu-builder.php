<?php
/**
 * CodeOn Restaurant Menu Builder
 *
 * @package           CodeOnRestaurantMenuBuilder
 * @author            CodeOn
 * @copyright         2025 CodeOn
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       CodeOn Restaurant Menu Builder
 * Plugin URI:        https://codeon.ch/plugins/restaurant-menu-builder
 * Description:       Profesyonel ve özelleştirilebilir restoran menüleri oluşturmak için güçlü bir eklenti.
 * Version:           1.0.0
 * Author:            Codeon
 * Author URI:        https://codeon.ch
 * Text Domain:       codeon-restaurant-menu-builder
 * Domain Path:       /languages
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// Doğrudan erişimi engelle
if (!defined('WPINC')) {
    die;
}

// Eklenti versiyonunu tanımla
define('CODEON_RESTAURANT_MENU_VERSION', '1.0.0');
define('CODEON_RESTAURANT_MENU_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CODEON_RESTAURANT_MENU_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Aktivasyon sırasında çalıştırılacak kod
 */
function activate_codeon_restaurant_menu() {
    require_once CODEON_RESTAURANT_MENU_PLUGIN_DIR . 'includes/class-codeon-restaurant-menu-activator.php';
    CodeOn_Restaurant_Menu_Activator::activate();
}

/**
 * Deaktivasyonda çalıştırılacak kod
 */
function deactivate_codeon_restaurant_menu() {
    require_once CODEON_RESTAURANT_MENU_PLUGIN_DIR . 'includes/class-codeon-restaurant-menu-deactivator.php';
    CodeOn_Restaurant_Menu_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_codeon_restaurant_menu');
register_deactivation_hook(__FILE__, 'deactivate_codeon_restaurant_menu');

/**
 * Ana eklenti sınıfını içe aktar
 */
require CODEON_RESTAURANT_MENU_PLUGIN_DIR . 'includes/class-codeon-restaurant-menu.php';

/**
 * Eklentiyi başlat
 */
function run_codeon_restaurant_menu() {
    $plugin = new CodeOn_Restaurant_Menu();
    $plugin->run();
}

run_codeon_restaurant_menu();