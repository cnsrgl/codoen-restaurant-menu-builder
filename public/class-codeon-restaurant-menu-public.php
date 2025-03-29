<?php
/**
 * Genel görünüm işlevleri
 *
 * @since      1.0.0
 * @package    CodeOn_Restaurant_Menu
 */

class CodeOn_Restaurant_Menu_Public {

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
     * Genel stilleri kaydet
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        // Google Fonts
        wp_enqueue_style('codeon-google-fonts', 'https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Playfair+Display:wght@400;700&family=Roboto:wght@400;500&family=Montserrat:wght@400;700&family=Lato:wght@400;700&display=swap', array(), null);
        
        // Font Awesome
        wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css', array(), '5.15.3');
        
        // Genel CSS 
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/codeon-restaurant-menu-public.css', array(), $this->version, 'all');
    }

    /**
     * Genel scriptleri kaydet
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/codeon-restaurant-menu-public.js', array('jquery'), $this->version, false);
    }

    /**
     * Kısa kodları kaydet
     *
     * @since    1.0.0
     */
    public function register_shortcodes() {
        add_shortcode('codeon_menu', array($this, 'menu_shortcode'));
        add_shortcode('codeon_menu_item', array($this, 'menu_item_shortcode'));
        add_shortcode('codeon_menu_category', array($this, 'menu_category_shortcode'));
    }

    /**
     * Menü kısa kodu
     *
     * @since    1.0.0
     * @param    array     $atts    Kısa kod özellikleri
     * @return   string             Kısa kod çıktısı
     */
    public function menu_shortcode($atts) {
        $atts = shortcode_atts(array(
            'id' => 0,
            'title' => 'yes',
            'description' => 'yes',
            'image' => 'yes',
            'template' => ''
        ), $atts, 'codeon_menu');
        
        // ID kontrolü
        $menu_id = absint($atts['id']);
        if (empty($menu_id)) {
            return '<p class="codeon-error">' . __('Lütfen bir menü ID giriniz.', 'codeon-restaurant-menu-builder') . '</p>';
        }
        
        // Menüyü al
        $menu = get_post($menu_id);
        if (!$menu || $menu->post_type !== 'codeon_menu') {
            return '<p class="codeon-error">' . __('Belirtilen ID için menü bulunamadı.', 'codeon-restaurant-menu-builder') . '</p>';
        }
        
        // Menü yapısını al
        $structure = get_post_meta($menu_id, '_codeon_menu_structure', true);
        if (empty($structure)) {
            return '<p class="codeon-error">' . __('Bu menü için yapılandırılmış öğe bulunamadı.', 'codeon-restaurant-menu-builder') . '</p>';
        }
        
        // Menü stillerini al
        $template = !empty($atts['template']) ? sanitize_text_field($atts['template']) : get_post_meta($menu_id, '_codeon_menu_template', true);
        $primary_color = get_post_meta($menu_id, '_codeon_menu_primary_color', true);
        $secondary_color = get_post_meta($menu_id, '_codeon_menu_secondary_color', true);
        $font_family = get_post_meta($menu_id, '_codeon_menu_font_family', true);
        $show_images = get_post_meta($menu_id, '_codeon_menu_show_images', true);
        $currency_symbol = get_post_meta($menu_id, '_codeon_menu_currency_symbol', true);
        
        // Varsayılan değerleri ayarla
        if (empty($template)) {
            $template = 'default';
        }
        
        if (empty($primary_color)) {
            $primary_color = '#000000';
        }
        
        if (empty($secondary_color)) {
            $secondary_color = '#333333';
        }
        
        if (empty($font_family)) {
            $font_family = 'inherit';
        }
        
        if (empty($currency_symbol)) {
            $currency_symbol = '€';
        }
        
        // Çıktıyı başlat
        ob_start();
        
        // Dinamik CSS
        ?>
        <style type="text/css">
            .codeon-menu-<?php echo $menu_id; ?> {
                --primary-color: <?php echo esc_attr($primary_color); ?>;
                --secondary-color: <?php echo esc_attr($secondary_color); ?>;
                font-family: <?php echo esc_attr($font_family); ?>;
            }
        </style>
        <?php
        
        // Menü şablonunu yükle
        $template_path = 'partials/menu-templates/' . $template . '.php';
        $template_file = plugin_dir_path(__FILE__) . $template_path;
        
        if (!file_exists($template_file)) {
            $template_file = plugin_dir_path(__FILE__) . 'partials/menu-templates/default.php';
        }
        
        // Şablonu dahil et
        include $template_file;
        
        // Çıktıyı döndür
        return ob_get_clean();
    }

    /**
     * Menü öğesi kısa kodu
     *
     * @since    1.0.0
     * @param    array     $atts    Kısa kod özellikleri
     * @return   string             Kısa kod çıktısı
     */
    public function menu_item_shortcode($atts) {
        $atts = shortcode_atts(array(
            'id' => 0,
            'title' => 'yes',
            'description' => 'yes',
            'price' => 'yes',
            'image' => 'no',
            'ingredients' => 'no',
            'allergens' => 'no',
            'template' => 'default'
        ), $atts, 'codeon_menu_item');
        
        // ID kontrolü
        $item_id = absint($atts['id']);
        if (empty($item_id)) {
            return '<p class="codeon-error">' . __('Lütfen bir menü öğesi ID giriniz.', 'codeon-restaurant-menu-builder') . '</p>';
        }
        
        // Menü öğesini al
        $item = get_post($item_id);
        if (!$item || $item->post_type !== 'codeon_menu_item') {
            return '<p class="codeon-error">' . __('Belirtilen ID için menü öğesi bulunamadı.', 'codeon-restaurant-menu-builder') . '</p>';
        }
        
        // Metadataları al
        $price = get_post_meta($item_id, '_codeon_menu_item_price', true);
        $price_postfix = get_post_meta($item_id, '_codeon_menu_item_price_postfix', true);
        $discount_price = get_post_meta($item_id, '_codeon_menu_item_discount_price', true);
        $description = get_post_meta($item_id, '_codeon_menu_item_description', true);
        $ingredients = get_post_meta($item_id, '_codeon_menu_item_ingredients', true);
        $allergens = get_post_meta($item_id, '_codeon_menu_item_allergens', true);
        $is_spicy = get_post_meta($item_id, '_codeon_menu_item_is_spicy', true);
        $is_vegetarian = get_post_meta($item_id, '_codeon_menu_item_is_vegetarian', true);
        $is_vegan = get_post_meta($item_id, '_codeon_menu_item_is_vegan', true);
        $is_gluten_free = get_post_meta($item_id, '_codeon_menu_item_is_gluten_free', true);
        $has_variations = get_post_meta($item_id, '_codeon_menu_item_has_variations', true);
        $variations = get_post_meta($item_id, '_codeon_menu_item_variations', true);
        
        // Görseli al
        $image_url = '';
        if (has_post_thumbnail($item_id) && $atts['image'] === 'yes') {
            $image_url = get_the_post_thumbnail_url($item_id, 'medium');
        }
        
        // Çıktıyı başlat
        ob_start();
        
        // Şablonu yükle
        $template_path = 'partials/item-templates/' . $atts['template'] . '.php';
        $template_file = plugin_dir_path(__FILE__) . $template_path;
        
        if (!file_exists($template_file)) {
            $template_file = plugin_dir_path(__FILE__) . 'partials/item-templates/default.php';
        }
        
        // Şablonu dahil et
        include $template_file;
        
        // Çıktıyı döndür
        return ob_get_clean();
    }

    /**
     * Menü kategorisi kısa kodu
     *
     * @since    1.0.0
     * @param    array     $atts    Kısa kod özellikleri
     * @return   string             Kısa kod çıktısı
     */
    public function menu_category_shortcode($atts) {
        $atts = shortcode_atts(array(
            'id' => 0,
            'title' => 'yes',
            'description' => 'yes',
            'image' => 'no',
            'limit' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
            'template' => 'default'
        ), $atts, 'codeon_menu_category');
        
        // ID kontrolü
        $category_id = absint($atts['id']);
        if (empty($category_id)) {
            return '<p class="codeon-error">' . __('Lütfen bir menü kategorisi ID giriniz.', 'codeon-restaurant-menu-builder') . '</p>';
        }
        
        // Kategoriyi al
        $category = get_post($category_id);
        if (!$category || $category->post_type !== 'codeon_menu_category') {
            return '<p class="codeon-error">' . __('Belirtilen ID için menü kategorisi bulunamadı.', 'codeon-restaurant-menu-builder') . '</p>';
        }
        
        // Metadataları al
        $description = get_post_meta($category_id, '_codeon_category_description', true);
        $icon = get_post_meta($category_id, '_codeon_category_icon', true);
        
        // Görseli al
        $image_url = '';
        if (has_post_thumbnail($category_id) && $atts['image'] === 'yes') {
            $image_url = get_the_post_thumbnail_url($category_id, 'medium');
        }
        
        // Bu kategoriye ait öğeleri al
        $items = get_posts(array(
            'post_type' => 'codeon_menu_item',
            'numberposts' => intval($atts['limit']),
            'orderby' => sanitize_text_field($atts['orderby']),
            'order' => sanitize_text_field($atts['order']),
            'meta_query' => array(
                array(
                    'key' => '_codeon_menu_item_category',
                    'value' => $category_id,
                    'compare' => '='
                )
            )
        ));
        
        // Çıktıyı başlat
        ob_start();
        
        // Şablonu yükle
        $template_path = 'partials/category-templates/' . $atts['template'] . '.php';
        $template_file = plugin_dir_path(__FILE__) . $template_path;
        
        if (!file_exists($template_file)) {
            $template_file = plugin_dir_path(__FILE__) . 'partials/category-templates/default.php';
        }
        
        // Şablonu dahil et
        include $template_file;
        
        // Çıktıyı döndür
        return ob_get_clean();
    }
}