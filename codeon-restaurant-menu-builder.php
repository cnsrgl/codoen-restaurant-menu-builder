<?php
/**
 * Plugin Name: Codeon Restaurant Menu Builder
 * Plugin URI: https://codeon.ch
 * Description: Un constructeur de menu de restaurant personnalisable avec Elementor
 * Version: 1.0.0
 * Author: Codeon
 * Author URI: https://codeon.ch
 * Text Domain: codeon-restaurant-menu-builder
 * Domain Path: /languages
 */

// Si ce fichier est appelé directement, on sort.
if (!defined('ABSPATH')) {
    exit;
}

// Définir les constantes du plugin
define('CRMB_VERSION', '1.0.0');
define('CRMB_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CRMB_PLUGIN_URL', plugin_dir_url(__FILE__));
define('CRMB_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Fonction d'activation du plugin
 */
function crmb_activate() {
    // Créer les tables personnalisées si nécessaire
    require_once CRMB_PLUGIN_DIR . 'includes/class-codeon-restaurant-menu-builder.php';
    $plugin = new Codeon_Restaurant_Menu_Builder();
    $plugin->activate();
}
register_activation_hook(__FILE__, 'crmb_activate');

/**
 * Fonction de désactivation du plugin
 */
function crmb_deactivate() {
    // Nettoyer si nécessaire
}
register_deactivation_hook(__FILE__, 'crmb_deactivate');

/**
 * Chargement des traductions
 */
function crmb_load_textdomain() {
    load_plugin_textdomain('codeon-restaurant-menu-builder', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'crmb_load_textdomain');

/**
 * Initialisation du plugin
 */
function crmb_init() {
    if (class_exists('Elementor\\Plugin')) {
        require_once CRMB_PLUGIN_DIR . 'includes/class-codeon-restaurant-menu-builder.php';
        require_once CRMB_PLUGIN_DIR . 'includes/class-codeon-restaurant-menu-widget.php';
        require_once CRMB_PLUGIN_DIR . 'includes/class-codeon-restaurant-menu-admin.php';
        
        // Enregistrer le widget avec Elementor
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Codeon_Restaurant_Menu_Widget());
        
        // Initialiser l'admin
        new Codeon_Restaurant_Menu_Admin();
    } else {
        add_action('admin_notices', 'crmb_admin_notice_elementor_not_active');
    }
}
add_action('init', 'crmb_init');

/**
 * Ajouter une notice si Elementor n'est pas activé
 */
function crmb_admin_notice_elementor_not_active() {
    $message = sprintf(
        esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'codeon-restaurant-menu-builder'),
        '<strong>Codeon Restaurant Menu Builder</strong>',
        '<strong>Elementor</strong>'
    );
    
    printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
}

/**
 * Enregistrer les styles et scripts pour la partie publique
 */
function crmb_enqueue_scripts() {
    wp_enqueue_style('crmb-public-css', CRMB_PLUGIN_URL . 'assets/css/codeon-restaurant-menu-public.css', array(), CRMB_VERSION);
    wp_enqueue_script('crmb-public-js', CRMB_PLUGIN_URL . 'assets/js/codeon-restaurant-menu-public.js', array('jquery'), CRMB_VERSION, true);
}
add_action('wp_enqueue_scripts', 'crmb_enqueue_scripts');

/**
 * Enregistrer les styles et scripts pour l'admin
 */
function crmb_admin_enqueue_scripts($hook) {
    if ('toplevel_page_codeon-restaurant-menu' !== $hook) {
        return;
    }
    
    wp_enqueue_style('crmb-admin-css', CRMB_PLUGIN_URL . 'assets/css/codeon-restaurant-menu-admin.css', array(), CRMB_VERSION);
    wp_enqueue_script('crmb-admin-js', CRMB_PLUGIN_URL . 'assets/js/codeon-restaurant-menu-admin.js', array('jquery'), CRMB_VERSION, true);
}
add_action('admin_enqueue_scripts', 'crmb_admin_enqueue_scripts');