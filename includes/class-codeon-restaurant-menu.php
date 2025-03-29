<?php
/**
 * Ana eklenti sınıfı
 *
 * @since      1.0.0
 * @package    CodeOn_Restaurant_Menu
 */

class CodeOn_Restaurant_Menu {

    /**
     * Yükleyici, kancaları kaydetmekten sorumlu
     *
     * @since    1.0.0
     * @access   protected
     * @var      CodeOn_Restaurant_Menu_Loader    $loader    Tüm kancaları yönetir
     */
    protected $loader;

    /**
     * Eklentinin benzersiz tanımlayıcısı
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    Eklenti ID'si
     */
    protected $plugin_name;

    /**
     * Eklentinin mevcut sürümü
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    Eklentinin mevcut sürümü
     */
    protected $version;

    /**
     * Sınıfı tanımla ve gerekli özellikleri ayarla
     *
     * @since    1.0.0
     */
    public function __construct() {
        if (defined('CODEON_RESTAURANT_MENU_VERSION')) {
            $this->version = CODEON_RESTAURANT_MENU_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'codeon-restaurant-menu-builder';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Eklentinin çalışması için ihtiyaç duyulan bağımlılıkları yükle
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {

        /**
         * Kancaları yönetecek sınıf
         */
        require_once CODEON_RESTAURANT_MENU_PLUGIN_DIR . 'includes/class-codeon-restaurant-menu-loader.php';

        /**
         * Uluslararasılaştırma işlemlerini yöneten sınıf
         */
        require_once CODEON_RESTAURANT_MENU_PLUGIN_DIR . 'includes/class-codeon-restaurant-menu-i18n.php';

        /**
         * Özel post tiplerini tanımlayan sınıf
         */
        require_once CODEON_RESTAURANT_MENU_PLUGIN_DIR . 'includes/class-codeon-restaurant-menu-post-types.php';

        /**
         * Meta kutularını tanımlayan sınıf
         */
        require_once CODEON_RESTAURANT_MENU_PLUGIN_DIR . 'includes/class-codeon-restaurant-menu-meta-boxes.php';

        /**
         * Yönetici tarafında çalışan işlevleri tanımlayan sınıf
         */
        require_once CODEON_RESTAURANT_MENU_PLUGIN_DIR . 'admin/class-codeon-restaurant-menu-admin.php';

        /**
         * Ön yüzde çalışan işlevleri tanımlayan sınıf
         */
        require_once CODEON_RESTAURANT_MENU_PLUGIN_DIR . 'public/class-codeon-restaurant-menu-public.php';

        $this->loader = new CodeOn_Restaurant_Menu_Loader();
    }

    /**
     * Eklentinin dil dosyalarını yükle
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale() {
        $plugin_i18n = new CodeOn_Restaurant_Menu_i18n();
        $plugin_i18n->set_domain($this->get_plugin_name());

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Yönetici tarafında çalışacak kancaları tanımla
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {
        $plugin_admin = new CodeOn_Restaurant_Menu_Admin($this->get_plugin_name(), $this->get_version());
        $plugin_post_types = new CodeOn_Restaurant_Menu_Post_Types();
        $plugin_meta_boxes = new CodeOn_Restaurant_Menu_Meta_Boxes();

        // Admin assets
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

        // Menü ekleme
        $this->loader->add_action('admin_menu', $plugin_admin, 'add_admin_menu');

        // Özel post tipleri
        $this->loader->add_action('init', $plugin_post_types, 'register_post_types');
        $this->loader->add_action('init', $plugin_post_types, 'register_taxonomies');

        // Meta kutuları
        $this->loader->add_action('add_meta_boxes', $plugin_meta_boxes, 'add_meta_boxes');
        $this->loader->add_action('save_post', $plugin_meta_boxes, 'save_meta_boxes');

        // AJAX işleyiciler
        $this->loader->add_action('wp_ajax_codeon_save_menu', $plugin_admin, 'ajax_save_menu');
        $this->loader->add_action('wp_ajax_codeon_get_menu_items', $plugin_admin, 'ajax_get_menu_items');
    }

    /**
     * Genel tarafta çalışacak kancaları tanımla
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() {
        $plugin_public = new CodeOn_Restaurant_Menu_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        
        // Kısa kodları kaydet
        $this->loader->add_action('init', $plugin_public, 'register_shortcodes');
    }

    /**
     * Eklentiyi çalıştır, tüm kancaları yükle
     *
     * @since    1.0.0
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * Eklentinin adını al
     *
     * @since     1.0.0
     * @return    string    Eklentinin adı.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * Yükleyici referansını al
     *
     * @since     1.0.0
     * @return    CodeOn_Restaurant_Menu_Loader    Kancalar ile ilgili işlemleri yönetir.
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Eklentinin versiyonunu al
     *
     * @since     1.0.0
     * @return    string    Eklentinin versiyonu.
     */
    public function get_version() {
        return $this->version;
    }
}