<?php
/**
 * Yönetici tarafı işlevleri
 *
 * @since      1.0.0
 * @package    CodeOn_Restaurant_Menu
 */

class CodeOn_Restaurant_Menu_Admin {

    /**
     * Eklentinin benzersiz tanımlayıcısı
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    Eklenti ID'si
     */
    private $plugin_name;

    /**
     * Eklentinin mevcut sürümü
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    Eklentinin mevcut sürümü
     */
    private $version;

    /**
     * Sınıfı başlat
     *
     * @since    1.0.0
     * @param    string    $plugin_name    Eklentinin adı
     * @param    string    $version        Eklentinin sürümü
     */
    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Admin stilleri kaydet
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        // Yönetici CSS stillerini kaydet
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/admin.css', array(), $this->version, 'all');
        
        // Özel sayfalarda ek stiller
        $screen = get_current_screen();
        
        if (isset($screen->post_type) && ($screen->post_type === 'codeon_menu' || $screen->post_type === 'codeon_menu_item' || $screen->post_type === 'codeon_menu_category')) {
            wp_enqueue_style($this->plugin_name . '-menu-builder', plugin_dir_url(__FILE__) . 'css/menu-builder.css', array(), $this->version, 'all');
        }
    }

    /**
     * Admin scriptlerini kaydet
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        // jQuery UI ve gerekli diğer bağımlılıklar
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('jquery-ui-draggable');
        wp_enqueue_script('jquery-ui-droppable');
        wp_enqueue_script('wp-color-picker');
        
        // Yönetici JS kaydet
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/admin.js', array('jquery'), $this->version, false);
        
        // Özel sayfalarda ek komut dosyaları
        $screen = get_current_screen();
        
        if (isset($screen->post_type) && $screen->post_type === 'codeon_menu') {
            wp_enqueue_script($this->plugin_name . '-menu-builder', plugin_dir_url(__FILE__) . 'js/menu-builder.js', array('jquery', 'jquery-ui-sortable'), $this->version, false);
        }
        
        // AJAX nonce'unu JS'ye aktar
        wp_localize_script($this->plugin_name, 'codeon_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('codeon_ajax_nonce')
        ));
    }

    /**
     * Admin menüsünü ekle
     *
     * @since    1.0.0
     */
    public function add_admin_menu() {
        // Ana menü öğesi
        add_menu_page(
            __('Restoran Menüsü', 'codeon-restaurant-menu-builder'),
            __('Restoran Menüsü', 'codeon-restaurant-menu-builder'),
            'manage_options',
            'codeon_restaurant_menu',
            array($this, 'display_admin_dashboard'),
            'dashicons-book-alt',
            25
        );
        
        // Alt menü - Kontrol Paneli
        add_submenu_page(
            'codeon_restaurant_menu',
            __('Kontrol Paneli', 'codeon-restaurant-menu-builder'),
            __('Kontrol Paneli', 'codeon-restaurant-menu-builder'),
            'manage_options',
            'codeon_restaurant_menu',
            array($this, 'display_admin_dashboard')
        );
        
        // Alt menü - Tüm Menüler
        add_submenu_page(
            'codeon_restaurant_menu',
            __('Tüm Menüler', 'codeon-restaurant-menu-builder'),
            __('Tüm Menüler', 'codeon-restaurant-menu-builder'),
            'manage_options',
            'edit.php?post_type=codeon_menu'
        );
        
        // Alt menü - Yeni Menü Ekle
        add_submenu_page(
            'codeon_restaurant_menu',
            __('Yeni Menü Ekle', 'codeon-restaurant-menu-builder'),
            __('Yeni Menü Ekle', 'codeon-restaurant-menu-builder'),
            'manage_options',
            'post-new.php?post_type=codeon_menu'
        );
        
        // Alt menü - Menü Öğeleri
        add_submenu_page(
            'codeon_restaurant_menu',
            __('Menü Öğeleri', 'codeon-restaurant-menu-builder'),
            __('Menü Öğeleri', 'codeon-restaurant-menu-builder'),
            'manage_options',
            'edit.php?post_type=codeon_menu_item'
        );
        
        // Alt menü - Menü Kategorileri
        add_submenu_page(
            'codeon_restaurant_menu',
            __('Menü Kategorileri', 'codeon-restaurant-menu-builder'),
            __('Menü Kategorileri', 'codeon-restaurant-menu-builder'),
            'manage_options',
            'edit.php?post_type=codeon_menu_category'
        );
        
        // Alt menü - Ayarlar
        add_submenu_page(
            'codeon_restaurant_menu',
            __('Ayarlar', 'codeon-restaurant-menu-builder'),
            __('Ayarlar', 'codeon-restaurant-menu-builder'),
            'manage_options',
            'codeon_restaurant_menu_settings',
            array($this, 'display_settings_page')
        );
    }

    /**
     * Admin kontrol panelini görüntüle
     *
     * @since    1.0.0
     */
    public function display_admin_dashboard() {
        include_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/dashboard.php';
    }

    /**
     * Ayarlar sayfasını görüntüle
     *
     * @since    1.0.0
     */
    public function display_settings_page() {
        include_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/settings.php';
    }

    /**
     * AJAX: Menüyü kaydet
     *
     * @since    1.0.0
     */
    public function ajax_save_menu() {
        // Nonce kontrolü
        check_ajax_referer('codeon_ajax_nonce', 'security');
        
        // İzin kontrolü
        if (!current_user_can('edit_posts')) {
            wp_send_json_error(array('message' => __('Bu işlemi gerçekleştirmek için izniniz yok.', 'codeon-restaurant-menu-builder')));
        }
        
        // Gönderilen verileri al
        $menu_id = isset($_POST['menu_id']) ? absint($_POST['menu_id']) : 0;
        $menu_structure = isset($_POST['menu_structure']) ? $_POST['menu_structure'] : '';
        
        // Menü ID'sini kontrol et
        if (empty($menu_id)) {
            wp_send_json_error(array('message' => __('Geçersiz menü ID.', 'codeon-restaurant-menu-builder')));
        }
        
        // Menü yapısını kontrol et
        if (empty($menu_structure)) {
            wp_send_json_error(array('message' => __('Geçersiz menü yapısı.', 'codeon-restaurant-menu-builder')));
        }
        
        // JSON'ı çöz
        $structure = json_decode(stripslashes($menu_structure), true);
        
        // JSON çözme hatası kontrolü
        if (json_last_error() !== JSON_ERROR_NONE) {
            wp_send_json_error(array('message' => __('Menü yapısı çözülemedi: ', 'codeon-restaurant-menu-builder') . json_last_error_msg()));
        }
        
        // Menü yapısını sanitize et ve kaydet
        $sanitized_structure = $this->sanitize_menu_structure($structure);
        update_post_meta($menu_id, '_codeon_menu_structure', $sanitized_structure);
        
        // Başarılı yanıt
        wp_send_json_success(array(
            'message' => __('Menü başarıyla kaydedildi.', 'codeon-restaurant-menu-builder')
        ));
    }

    /**
     * AJAX: Menü öğelerini getir
     *
     * @since    1.0.0
     */
    public function ajax_get_menu_items() {
        // Nonce kontrolü
        check_ajax_referer('codeon_ajax_nonce', 'security');
        
        // İzin kontrolü
        if (!current_user_can('edit_posts')) {
            wp_send_json_error(array('message' => __('Bu işlemi gerçekleştirmek için izniniz yok.', 'codeon-restaurant-menu-builder')));
        }
        
        // Öğeleri getir
        $menu_items = get_posts(array(
            'post_type' => 'codeon_menu_item',
            'numberposts' => -1,
            'orderby' => 'title',
            'order' => 'ASC'
        ));
        
        // Öğeleri formatla
        $items = array();
        
        foreach ($menu_items as $item) {
            $price = get_post_meta($item->ID, '_codeon_menu_item_price', true);
            $price_postfix = get_post_meta($item->ID, '_codeon_menu_item_price_postfix', true);
            
            $items[] = array(
                'id' => $item->ID,
                'title' => $item->post_title,
                'price' => $price,
                'price_postfix' => $price_postfix
            );
        }
        
        // Başarılı yanıt
        wp_send_json_success(array(
            'items' => $items
        ));
    }

    /**
     * Menü yapısını sanitize et
     *
     * @since    1.0.0
     * @param    array    $structure    Menü yapısı
     * @return   array                  Sanitize edilmiş menü yapısı
     */
    private function sanitize_menu_structure($structure) {
        $sanitized_structure = array();
        
        if (is_array($structure)) {
            foreach ($structure as $section) {
                $sanitized_section = array(
                    'title' => isset($section['title']) ? sanitize_text_field($section['title']) : '',
                    'description' => isset($section['description']) ? sanitize_textarea_field($section['description']) : '',
                    'items' => array()
                );
                
                if (isset($section['items']) && is_array($section['items'])) {
                    foreach ($section['items'] as $item) {
                        if (isset($item['id'])) {
                            $sanitized_section['items'][] = array(
                                'id' => absint($item['id'])
                            );
                        }
                    }
                }
                
                $sanitized_structure[] = $sanitized_section;
            }
        }
        
        return $sanitized_structure;
    }
}