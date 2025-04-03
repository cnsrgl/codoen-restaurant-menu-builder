<?php
/**
 * Classe principale du plugin Codeon Restaurant Menu Builder
 */
class Codeon_Restaurant_Menu_Builder {

    /**
     * Version du plugin
     */
    protected $version;

    /**
     * Instance singleton
     */
    private static $instance = null;

    /**
     * Constructeur
     */
    public function __construct() {
        $this->version = CRMB_VERSION;
        $this->init_hooks();
    }

    /**
     * Obtenir l'instance unique
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Initialiser les hooks
     */
    private function init_hooks() {
        // Ajouter les hooks nécessaires ici
    }

    /**
     * Activation du plugin
     */
    public function activate() {
        // Créer les tables personnalisées pour stocker les menus
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        // Table des catégories de menu (ex: Entrées, Plats, Desserts)
        $table_categories = $wpdb->prefix . 'crmb_menu_categories';
        
        // Table des éléments de menu individuels
        $table_items = $wpdb->prefix . 'crmb_menu_items';
        
        // Table des menus complets
        $table_menus = $wpdb->prefix . 'crmb_menus';

        // SQL pour la table des catégories
        $sql_categories = "CREATE TABLE $table_categories (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            menu_id mediumint(9) NOT NULL,
            name varchar(255) NOT NULL,
            description text,
            position int(11) DEFAULT 0,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        // SQL pour la table des éléments
        $sql_items = "CREATE TABLE $table_items (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            category_id mediumint(9) NOT NULL,
            name varchar(255) NOT NULL,
            description text,
            price decimal(10,2) NOT NULL DEFAULT 0,
            price_suffix varchar(50),
            options text,
            variations text,
            position int(11) DEFAULT 0,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        // SQL pour la table des menus
        $sql_menus = "CREATE TABLE $table_menus (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            description text,
            settings longtext,
            date_created datetime DEFAULT CURRENT_TIMESTAMP,
            date_modified datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_categories);
        dbDelta($sql_items);
        dbDelta($sql_menus);
    }

    /**
     * Récupérer tous les menus
     */
    public function get_menus() {
        global $wpdb;
        $table_menus = $wpdb->prefix . 'crmb_menus';
        return $wpdb->get_results("SELECT * FROM $table_menus ORDER BY date_modified DESC");
    }

    /**
     * Récupérer un menu avec toutes ses catégories et éléments
     */
    public function get_menu($menu_id) {
        global $wpdb;
        
        $table_menus = $wpdb->prefix . 'crmb_menus';
        $table_categories = $wpdb->prefix . 'crmb_menu_categories';
        $table_items = $wpdb->prefix . 'crmb_menu_items';
        
        // Récupérer les informations du menu
        $menu = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_menus WHERE id = %d", $menu_id));
        
        if (!$menu) {
            return false;
        }
        
        // Récupérer les catégories du menu
        $menu->categories = $wpdb->get_results($wpdb->prepare("
            SELECT * FROM $table_categories 
            WHERE menu_id = %d 
            ORDER BY position ASC
        ", $menu_id));
        
        // Récupérer les éléments pour chaque catégorie
        foreach ($menu->categories as $category) {
            $category->items = $wpdb->get_results($wpdb->prepare("
                SELECT * FROM $table_items 
                WHERE category_id = %d 
                ORDER BY position ASC
            ", $category->id));
        }
        
        return $menu;
    }

    /**
     * Ajouter un nouveau menu
     */
    public function add_menu($name, $description = '', $settings = array()) {
        global $wpdb;
        $table_menus = $wpdb->prefix . 'crmb_menus';
        
        $wpdb->insert(
            $table_menus,
            array(
                'name' => $name,
                'description' => $description,
                'settings' => maybe_serialize($settings)
            )
        );
        
        return $wpdb->insert_id;
    }

    /**
     * Mettre à jour un menu
     */
    public function update_menu($menu_id, $data) {
        global $wpdb;
        $table_menus = $wpdb->prefix . 'crmb_menus';
        
        if (isset($data['settings']) && is_array($data['settings'])) {
            $data['settings'] = maybe_serialize($data['settings']);
        }
        
        $wpdb->update(
            $table_menus,
            $data,
            array('id' => $menu_id)
        );
        
        return true;
    }

    /**
     * Supprimer un menu et tous ses éléments associés
     */
    public function delete_menu($menu_id) {
        global $wpdb;
        
        $table_menus = $wpdb->prefix . 'crmb_menus';
        $table_categories = $wpdb->prefix . 'crmb_menu_categories';
        $table_items = $wpdb->prefix . 'crmb_menu_items';
        
        // Récupérer toutes les catégories
        $categories = $wpdb->get_results($wpdb->prepare("
            SELECT id FROM $table_categories WHERE menu_id = %d
        ", $menu_id));
        
        // Supprimer tous les éléments de ces catégories
        foreach ($categories as $category) {
            $wpdb->delete($table_items, array('category_id' => $category->id));
        }
        
        // Supprimer les catégories
        $wpdb->delete($table_categories, array('menu_id' => $menu_id));
        
        // Supprimer le menu
        $wpdb->delete($table_menus, array('id' => $menu_id));
        
        return true;
    }
}