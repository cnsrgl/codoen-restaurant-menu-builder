<?php
/**
 * Meta kutuları sınıfı
 *
 * @since      1.0.0
 * @package    CodeOn_Restaurant_Menu
 */

class CodeOn_Restaurant_Menu_Meta_Boxes {

    /**
     * Meta kutularını kaydet
     *
     * @since    1.0.0
     */
    public function add_meta_boxes() {
        // Menü öğeleri için fiyat meta kutusu
        add_meta_box(
            'codeon_menu_item_price',
            __('Fiyat Bilgileri', 'codeon-restaurant-menu-builder'),
            array($this, 'render_price_meta_box'),
            'codeon_menu_item',
            'normal',
            'high'
        );

        // Menü öğeleri için açıklama meta kutusu
        add_meta_box(
            'codeon_menu_item_description',
            __('Detaylı Açıklama', 'codeon-restaurant-menu-builder'),
            array($this, 'render_description_meta_box'),
            'codeon_menu_item',
            'normal',
            'high'
        );

        // Menü yapılandırması meta kutusu
        add_meta_box(
            'codeon_menu_structure',
            __('Menü Yapılandırması', 'codeon-restaurant-menu-builder'),
            array($this, 'render_menu_structure_meta_box'),
            'codeon_menu',
            'normal',
            'high'
        );

        // Menü stilleri meta kutusu
        add_meta_box(
            'codeon_menu_styles',
            __('Menü Stilleri', 'codeon-restaurant-menu-builder'),
            array($this, 'render_menu_styles_meta_box'),
            'codeon_menu',
            'side',
            'default'
        );

        // Kategori açıklaması meta kutusu
        add_meta_box(
            'codeon_category_description',
            __('Kategori Açıklaması', 'codeon-restaurant-menu-builder'),
            array($this, 'render_category_description_meta_box'),
            'codeon_menu_category',
            'normal',
            'high'
        );
    }

    /**
     * Fiyat meta kutusunu görüntüle
     *
     * @since    1.0.0
     * @param    WP_Post    $post    Mevcut post
     */
    public function render_price_meta_box($post) {
        // Nonce alanı ekle
        wp_nonce_field('codeon_menu_item_price_data', 'codeon_menu_item_price_nonce');

        // Mevcut değerleri al
        $price = get_post_meta($post->ID, '_codeon_menu_item_price', true);
        $price_postfix = get_post_meta($post->ID, '_codeon_menu_item_price_postfix', true);
        $discount_price = get_post_meta($post->ID, '_codeon_menu_item_discount_price', true);
        $has_variations = get_post_meta($post->ID, '_codeon_menu_item_has_variations', true);
        $variations = get_post_meta($post->ID, '_codeon_menu_item_variations', true);

        if (empty($variations)) {
            $variations = array(
                array(
                    'name' => '',
                    'price' => '',
                    'description' => ''
                )
            );
        }

        // Form alanlarını görüntüle
        ?>
        <div class="codeon-meta-box-container">
            <div class="codeon-meta-box-row">
                <label for="codeon_menu_item_price">
                    <?php _e('Fiyat', 'codeon-restaurant-menu-builder'); ?>:
                </label>
                <input type="text" id="codeon_menu_item_price" name="codeon_menu_item_price" value="<?php echo esc_attr($price); ?>" />
            </div>

            <div class="codeon-meta-box-row">
                <label for="codeon_menu_item_price_postfix">
                    <?php _e('Fiyat Son Eki', 'codeon-restaurant-menu-builder'); ?>:
                </label>
                <input type="text" id="codeon_menu_item_price_postfix" name="codeon_menu_item_price_postfix" value="<?php echo esc_attr($price_postfix); ?>" placeholder="€, CHF, $" />
                <p class="description"><?php _e('Fiyatın yanında görüntülenecek metin (ör. "€", "CHF", "$")', 'codeon-restaurant-menu-builder'); ?></p>
            </div>

            <div class="codeon-meta-box-row">
                <label for="codeon_menu_item_discount_price">
                    <?php _e('İndirimli Fiyat', 'codeon-restaurant-menu-builder'); ?>:
                </label>
                <input type="text" id="codeon_menu_item_discount_price" name="codeon_menu_item_discount_price" value="<?php echo esc_attr($discount_price); ?>" />
                <p class="description"><?php _e('Eğer öğe indirimde ise, indirimli fiyatı girin. Boş bırakırsanız, indirim uygulanmayacaktır.', 'codeon-restaurant-menu-builder'); ?></p>
            </div>

            <div class="codeon-meta-box-row">
                <label for="codeon_menu_item_has_variations">
                    <input type="checkbox" id="codeon_menu_item_has_variations" name="codeon_menu_item_has_variations" value="1" <?php checked($has_variations, '1'); ?> />
                    <?php _e('Bu öğenin fiyat varyasyonları var', 'codeon-restaurant-menu-builder'); ?>
                </label>
            </div>

            <div id="codeon_variations_container" class="<?php echo $has_variations ? '' : 'hidden'; ?>">
                <h4><?php _e('Fiyat Varyasyonları', 'codeon-restaurant-menu-builder'); ?></h4>
                
                <div class="codeon-variations-list">
                    <?php foreach ($variations as $index => $variation) : ?>
                        <div class="codeon-variation-item">
                            <div class="codeon-meta-box-row">
                                <label><?php _e('Varyasyon Adı', 'codeon-restaurant-menu-builder'); ?>:</label>
                                <input type="text" name="codeon_menu_item_variations[<?php echo $index; ?>][name]" value="<?php echo esc_attr($variation['name']); ?>" />
                            </div>
                            <div class="codeon-meta-box-row">
                                <label><?php _e('Fiyat', 'codeon-restaurant-menu-builder'); ?>:</label>
                                <input type="text" name="codeon_menu_item_variations[<?php echo $index; ?>][price]" value="<?php echo esc_attr($variation['price']); ?>" />
                            </div>
                            <div class="codeon-meta-box-row">
                                <label><?php _e('Açıklama', 'codeon-restaurant-menu-builder'); ?>:</label>
                                <textarea name="codeon_menu_item_variations[<?php echo $index; ?>][description]"><?php echo esc_textarea($variation['description']); ?></textarea>
                            </div>
                            <?php if ($index > 0) : ?>
                                <button type="button" class="button remove-variation"><?php _e('Kaldır', 'codeon-restaurant-menu-builder'); ?></button>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <button type="button" class="button add-variation"><?php _e('Varyasyon Ekle', 'codeon-restaurant-menu-builder'); ?></button>
            </div>
        </div>
        <script>
            jQuery(document).ready(function($) {
                // Varyasyonları göster/gizle
                $('#codeon_menu_item_has_variations').change(function() {
                    if ($(this).is(':checked')) {
                        $('#codeon_variations_container').removeClass('hidden');
                    } else {
                        $('#codeon_variations_container').addClass('hidden');
                    }
                });

                // Yeni varyasyon ekle
                $('.add-variation').click(function() {
                    var index = $('.codeon-variation-item').length;
                    var newVariation = `
                        <div class="codeon-variation-item">
                            <div class="codeon-meta-box-row">
                                <label><?php _e('Varyasyon Adı', 'codeon-restaurant-menu-builder'); ?>:</label>
                                <input type="text" name="codeon_menu_item_variations[${index}][name]" value="" />
                            </div>
                            <div class="codeon-meta-box-row">
                                <label><?php _e('Fiyat', 'codeon-restaurant-menu-builder'); ?>:</label>
                                <input type="text" name="codeon_menu_item_variations[${index}][price]" value="" />
                            </div>
                            <div class="codeon-meta-box-row">
                                <label><?php _e('Açıklama', 'codeon-restaurant-menu-builder'); ?>:</label>
                                <textarea name="codeon_menu_item_variations[${index}][description]"></textarea>
                            </div>
                            <button type="button" class="button remove-variation"><?php _e('Kaldır', 'codeon-restaurant-menu-builder'); ?></button>
                        </div>
                    `;
                    $('.codeon-variations-list').append(newVariation);
                });

                // Varyasyon kaldır
                $(document).on('click', '.remove-variation', function() {
                    $(this).closest('.codeon-variation-item').remove();
                });
            });
        </script>
        <?php
    }
/**
 * Menü stilleri meta kutusunu görüntüle
 *
 * @since    1.0.0
 * @param    WP_Post    $post    Mevcut post
 */
public function render_menu_styles_meta_box($post) {
    // Nonce alanı ekle
    wp_nonce_field('codeon_menu_styles_data', 'codeon_menu_styles_nonce');
    
    // Mevcut değerleri al
    $template = get_post_meta($post->ID, '_codeon_menu_template', true);
    $primary_color = get_post_meta($post->ID, '_codeon_menu_primary_color', true);
    $secondary_color = get_post_meta($post->ID, '_codeon_menu_secondary_color', true);
    $font_family = get_post_meta($post->ID, '_codeon_menu_font_family', true);
    $show_images = get_post_meta($post->ID, '_codeon_menu_show_images', true);
    $currency_symbol = get_post_meta($post->ID, '_codeon_menu_currency_symbol', true);
    
    // Varsayılan değerleri ayarla
    if (empty($template)) {
        $template = get_option('codeon_default_template', 'default');
    }
    
    if (empty($primary_color)) {
        $primary_color = get_option('codeon_primary_color', '#000000');
    }
    
    if (empty($secondary_color)) {
        $secondary_color = get_option('codeon_secondary_color', '#333333');
    }
    
    if (empty($font_family)) {
        $font_family = get_option('codeon_font_family', 'inherit');
    }
    
    if (empty($currency_symbol)) {
        $currency_symbol = get_option('codeon_currency_symbol', '€');
    }
    
    // Form alanlarını görüntüle
    ?>
    <div class="codeon-meta-box-container">
        <div class="codeon-meta-box-row">
            <label for="codeon_menu_template">
                <?php _e('Şablon', 'codeon-restaurant-menu-builder'); ?>:
            </label>
            <select id="codeon_menu_template" name="codeon_menu_template">
                <option value="default" <?php selected($template, 'default'); ?>><?php _e('Varsayılan', 'codeon-restaurant-menu-builder'); ?></option>
                <option value="elegant" <?php selected($template, 'elegant'); ?>><?php _e('Zarif', 'codeon-restaurant-menu-builder'); ?></option>
                <option value="modern" <?php selected($template, 'modern'); ?>><?php _e('Modern', 'codeon-restaurant-menu-builder'); ?></option>
                <option value="classic" <?php selected($template, 'classic'); ?>><?php _e('Klasik', 'codeon-restaurant-menu-builder'); ?></option>
                <option value="bistro" <?php selected($template, 'bistro'); ?>><?php _e('Bistro', 'codeon-restaurant-menu-builder'); ?></option>
            </select>
        </div>

        <div class="codeon-meta-box-row">
            <label for="codeon_menu_primary_color">
                <?php _e('Ana Renk', 'codeon-restaurant-menu-builder'); ?>:
            </label>
            <input type="color" id="codeon_menu_primary_color" name="codeon_menu_primary_color" value="<?php echo esc_attr($primary_color); ?>" />
        </div>

        <div class="codeon-meta-box-row">
            <label for="codeon_menu_secondary_color">
                <?php _e('İkincil Renk', 'codeon-restaurant-menu-builder'); ?>:
            </label>
            <input type="color" id="codeon_menu_secondary_color" name="codeon_menu_secondary_color" value="<?php echo esc_attr($secondary_color); ?>" />
        </div>

        <div class="codeon-meta-box-row">
            <label for="codeon_menu_font_family">
                <?php _e('Yazı Tipi', 'codeon-restaurant-menu-builder'); ?>:
            </label>
            <select id="codeon_menu_font_family" name="codeon_menu_font_family">
                <option value="inherit" <?php selected($font_family, 'inherit'); ?>><?php _e('Varsayılan', 'codeon-restaurant-menu-builder'); ?></option>
                <option value="Arial, sans-serif" <?php selected($font_family, 'Arial, sans-serif'); ?>>Arial</option>
                <option value="'Times New Roman', serif" <?php selected($font_family, "'Times New Roman', serif"); ?>>Times New Roman</option>
                <option value="Georgia, serif" <?php selected($font_family, 'Georgia, serif'); ?>>Georgia</option>
                <option value="'Courier New', monospace" <?php selected($font_family, "'Courier New', monospace"); ?>>Courier New</option>
                <option value="'Open Sans', sans-serif" <?php selected($font_family, "'Open Sans', sans-serif"); ?>>Open Sans</option>
                <option value="'Roboto', sans-serif" <?php selected($font_family, "'Roboto', sans-serif"); ?>>Roboto</option>
                <option value="'Lato', sans-serif" <?php selected($font_family, "'Lato', sans-serif"); ?>>Lato</option>
                <option value="'Montserrat', sans-serif" <?php selected($font_family, "'Montserrat', sans-serif"); ?>>Montserrat</option>
                <option value="'Playfair Display', serif" <?php selected($font_family, "'Playfair Display', serif"); ?>>Playfair Display</option>
            </select>
        </div>

        <div class="codeon-meta-box-row">
            <label for="codeon_menu_currency_symbol">
                <?php _e('Para Birimi Sembolü', 'codeon-restaurant-menu-builder'); ?>:
            </label>
            <input type="text" id="codeon_menu_currency_symbol" name="codeon_menu_currency_symbol" value="<?php echo esc_attr($currency_symbol); ?>" />
        </div>

        <div class="codeon-meta-box-row">
            <label for="codeon_menu_show_images">
                <input type="checkbox" id="codeon_menu_show_images" name="codeon_menu_show_images" value="1" <?php checked($show_images, '1'); ?> />
                <?php _e('Menü öğe resimlerini göster', 'codeon-restaurant-menu-builder'); ?>
            </label>
        </div>
    </div>
    <?php
}


    /**
     * Açıklama meta kutusunu görüntüle
     *
     * @since    1.0.0
     * @param    WP_Post    $post    Mevcut post
     */
    public function render_description_meta_box($post) {
        // Nonce alanı ekle
        wp_nonce_field('codeon_menu_item_description_data', 'codeon_menu_item_description_nonce');
        
        // Mevcut değerleri al
        $description = get_post_meta($post->ID, '_codeon_menu_item_description', true);
        $ingredients = get_post_meta($post->ID, '_codeon_menu_item_ingredients', true);
        $allergens = get_post_meta($post->ID, '_codeon_menu_item_allergens', true);
        $is_spicy = get_post_meta($post->ID, '_codeon_menu_item_is_spicy', true);
        $is_vegetarian = get_post_meta($post->ID, '_codeon_menu_item_is_vegetarian', true);
        $is_vegan = get_post_meta($post->ID, '_codeon_menu_item_is_vegan', true);
        $is_gluten_free = get_post_meta($post->ID, '_codeon_menu_item_is_gluten_free', true);
        
        // Form alanlarını görüntüle
        ?>
        <div class="codeon-meta-box-container">
            <div class="codeon-meta-box-row">
                <label for="codeon_menu_item_description">
                    <?php _e('Detaylı Açıklama', 'codeon-restaurant-menu-builder'); ?>:
                </label>
                <textarea id="codeon_menu_item_description" name="codeon_menu_item_description" rows="4"><?php echo esc_textarea($description); ?></textarea>
                <p class="description"><?php _e('Menü öğesinin detaylı açıklaması.', 'codeon-restaurant-menu-builder'); ?></p>
            </div>

            <div class="codeon-meta-box-row">
                <label for="codeon_menu_item_ingredients">
                    <?php _e('İçerikler', 'codeon-restaurant-menu-builder'); ?>:
                </label>
                <textarea id="codeon_menu_item_ingredients" name="codeon_menu_item_ingredients" rows="3"><?php echo esc_textarea($ingredients); ?></textarea>
                <p class="description"><?php _e('Menü öğesinin içerdiği malzemeler. Her bir içeriği virgül ile ayırın.', 'codeon-restaurant-menu-builder'); ?></p>
            </div>

            <div class="codeon-meta-box-row">
                <label for="codeon_menu_item_allergens">
                    <?php _e('Alerjenler', 'codeon-restaurant-menu-builder'); ?>:
                </label>
                <textarea id="codeon_menu_item_allergens" name="codeon_menu_item_allergens" rows="2"><?php echo esc_textarea($allergens); ?></textarea>
                <p class="description"><?php _e('Menü öğesinin içerdiği alerjenler. Her bir alerjeni virgül ile ayırın.', 'codeon-restaurant-menu-builder'); ?></p>
            </div>

            <div class="codeon-meta-box-row">
                <div class="codeon-checkbox-group">
                    <label for="codeon_menu_item_is_spicy">
                        <input type="checkbox" id="codeon_menu_item_is_spicy" name="codeon_menu_item_is_spicy" value="1" <?php checked($is_spicy, '1'); ?> />
                        <?php _e('Acılı', 'codeon-restaurant-menu-builder'); ?>
                    </label>
                </div>

                <div class="codeon-checkbox-group">
                    <label for="codeon_menu_item_is_vegetarian">
                        <input type="checkbox" id="codeon_menu_item_is_vegetarian" name="codeon_menu_item_is_vegetarian" value="1" <?php checked($is_vegetarian, '1'); ?> />
                        <?php _e('Vejetaryen', 'codeon-restaurant-menu-builder'); ?>
                    </label>
                </div>

                <div class="codeon-checkbox-group">
                    <label for="codeon_menu_item_is_vegan">
                        <input type="checkbox" id="codeon_menu_item_is_vegan" name="codeon_menu_item_is_vegan" value="1" <?php checked($is_vegan, '1'); ?> />
                        <?php _e('Vegan', 'codeon-restaurant-menu-builder'); ?>
                    </label>
                </div>

                <div class="codeon-checkbox-group">
                    <label for="codeon_menu_item_is_gluten_free">
                        <input type="checkbox" id="codeon_menu_item_is_gluten_free" name="codeon_menu_item_is_gluten_free" value="1" <?php checked($is_gluten_free, '1'); ?> />
                        <?php _e('Glutensiz', 'codeon-restaurant-menu-builder'); ?>
                    </label>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Kategori açıklaması meta kutusunu görüntüle
     *
     * @since    1.0.0
     * @param    WP_Post    $post    Mevcut post
     */
    public function render_category_description_meta_box($post) {
        // Nonce alanı ekle
        wp_nonce_field('codeon_category_description_data', 'codeon_category_description_nonce');
        
        // Mevcut değerleri al
        $description = get_post_meta($post->ID, '_codeon_category_description', true);
        $icon = get_post_meta($post->ID, '_codeon_category_icon', true);
        
        // Form alanlarını görüntüle
        ?>
        <div class="codeon-meta-box-container">
            <div class="codeon-meta-box-row">
                <label for="codeon_category_description">
                    <?php _e('Kategori Açıklaması', 'codeon-restaurant-menu-builder'); ?>:
                </label>
                <textarea id="codeon_category_description" name="codeon_category_description" rows="4"><?php echo esc_textarea($description); ?></textarea>
                <p class="description"><?php _e('Menü kategorisinin kısa açıklaması.', 'codeon-restaurant-menu-builder'); ?></p>
            </div>

            <div class="codeon-meta-box-row">
                <label for="codeon_category_icon">
                    <?php _e('Kategori İkonu (CSS Sınıfı)', 'codeon-restaurant-menu-builder'); ?>:
                </label>
                <input type="text" id="codeon_category_icon" name="codeon_category_icon" value="<?php echo esc_attr($icon); ?>" />
                <p class="description"><?php _e('Font Awesome, Dashicons veya herhangi bir ikon seti için CSS sınıfı.', 'codeon-restaurant-menu-builder'); ?></p>
            </div>
        </div>
        <?php
    }

    /**
     * Meta kutuları verilerini kaydet
     *
     * @since    1.0.0
     * @param    int    $post_id    Post ID
     */
    public function save_meta_boxes($post_id) {
        // Otomatik kaydetme işlemi kontrolü
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Kullanıcı izinlerini kontrol et
        if (isset($_POST['post_type'])) {
            if ($_POST['post_type'] === 'codeon_menu_item') {
                if (!current_user_can('edit_page', $post_id)) {
                    return;
                }
            } elseif ($_POST['post_type'] === 'codeon_menu' || $_POST['post_type'] === 'codeon_menu_category') {
                if (!current_user_can('edit_post', $post_id)) {
                    return;
                }
            }
        }

        
        
        // Fiyat meta kutusu verilerini kaydet
        if (isset($_POST['codeon_menu_item_price_nonce']) && wp_verify_nonce($_POST['codeon_menu_item_price_nonce'], 'codeon_menu_item_price_data')) {
            // Fiyat
            if (isset($_POST['codeon_menu_item_price'])) {
                update_post_meta($post_id, '_codeon_menu_item_price', sanitize_text_field($_POST['codeon_menu_item_price']));
            }
            
            // Fiyat son eki
            if (isset($_POST['codeon_menu_item_price_postfix'])) {
                update_post_meta($post_id, '_codeon_menu_item_price_postfix', sanitize_text_field($_POST['codeon_menu_item_price_postfix']));
            }
            
            // İndirimli fiyat
            if (isset($_POST['codeon_menu_item_discount_price'])) {
                update_post_meta($post_id, '_codeon_menu_item_discount_price', sanitize_text_field($_POST['codeon_menu_item_discount_price']));
            }
            
            // Varyasyonlar
            $has_variations = isset($_POST['codeon_menu_item_has_variations']) ? '1' : '0';
            update_post_meta($post_id, '_codeon_menu_item_has_variations', $has_variations);
            
            if ($has_variations === '1' && isset($_POST['codeon_menu_item_variations'])) {
                $variations = array();
                
                foreach ($_POST['codeon_menu_item_variations'] as $variation) {
                    if (!empty($variation['name']) || !empty($variation['price'])) {
                        $variations[] = array(
                            'name' => sanitize_text_field($variation['name']),
                            'price' => sanitize_text_field($variation['price']),
                            'description' => sanitize_textarea_field($variation['description'])
                        );
                    }
                }
                
                update_post_meta($post_id, '_codeon_menu_item_variations', $variations);
            }
        }
        
        // Açıklama meta kutusu verilerini kaydet
        if (isset($_POST['codeon_menu_item_description_nonce']) && wp_verify_nonce($_POST['codeon_menu_item_description_nonce'], 'codeon_menu_item_description_data')) {
            // Açıklama
            if (isset($_POST['codeon_menu_item_description'])) {
                update_post_meta($post_id, '_codeon_menu_item_description', sanitize_textarea_field($_POST['codeon_menu_item_description']));
            }
            
            // İçerikler
            if (isset($_POST['codeon_menu_item_ingredients'])) {
                update_post_meta($post_id, '_codeon_menu_item_ingredients', sanitize_textarea_field($_POST['codeon_menu_item_ingredients']));
            }
            
            // Alerjenler
            if (isset($_POST['codeon_menu_item_allergens'])) {
                update_post_meta($post_id, '_codeon_menu_item_allergens', sanitize_textarea_field($_POST['codeon_menu_item_allergens']));
            }
            
            // Özellikler
            $is_spicy = isset($_POST['codeon_menu_item_is_spicy']) ? '1' : '0';
            update_post_meta($post_id, '_codeon_menu_item_is_spicy', $is_spicy);
            
            $is_vegetarian = isset($_POST['codeon_menu_item_is_vegetarian']) ? '1' : '0';
            update_post_meta($post_id, '_codeon_menu_item_is_vegetarian', $is_vegetarian);
            
            $is_vegan = isset($_POST['codeon_menu_item_is_vegan']) ? '1' : '0';
            update_post_meta($post_id, '_codeon_menu_item_is_vegan', $is_vegan);
            
            $is_gluten_free = isset($_POST['codeon_menu_item_is_gluten_free']) ? '1' : '0';
            update_post_meta($post_id, '_codeon_menu_item_is_gluten_free', $is_gluten_free);
        }
        
        // Menü yapılandırması meta kutusu verilerini kaydet
        if (isset($_POST['codeon_menu_structure_nonce']) && wp_verify_nonce($_POST['codeon_menu_structure_nonce'], 'codeon_menu_structure_data')) {
            if (isset($_POST['codeon_menu_structure'])) {
                $structure = array();
                
                foreach ($_POST['codeon_menu_structure'] as $section) {
                    if (!empty($section['title'])) {
                        $items = array();
                        
                        if (isset($section['items']) && is_array($section['items'])) {
                            foreach ($section['items'] as $item) {
                                if (!empty($item['id'])) {
                                    $items[] = array(
                                        'id' => absint($item['id'])
                                    );
                                }
                            }
                        }
                        
                        $structure[] = array(
                            'title' => sanitize_text_field($section['title']),
                            'description' => isset($section['description']) ? sanitize_textarea_field($section['description']) : '',
                            'items' => $items
                        );
                    }
                }
                
                update_post_meta($post_id, '_codeon_menu_structure', $structure);
            }
        }
        
        // Menü stilleri meta kutusu verilerini kaydet
        if (isset($_POST['codeon_menu_styles_nonce']) && wp_verify_nonce($_POST['codeon_menu_styles_nonce'], 'codeon_menu_styles_data')) {
            // Şablon
            if (isset($_POST['codeon_menu_template'])) {
                update_post_meta($post_id, '_codeon_menu_template', sanitize_text_field($_POST['codeon_menu_template']));
            }
            
            // Ana renk
            if (isset($_POST['codeon_menu_primary_color'])) {
                update_post_meta($post_id, '_codeon_menu_primary_color', sanitize_hex_color($_POST['codeon_menu_primary_color']));
            }
            
            // İkincil renk
            if (isset($_POST['codeon_menu_secondary_color'])) {
                update_post_meta($post_id, '_codeon_menu_secondary_color', sanitize_hex_color($_POST['codeon_menu_secondary_color']));
            }
            
            // Yazı tipi
            if (isset($_POST['codeon_menu_font_family'])) {
                update_post_meta($post_id, '_codeon_menu_font_family', sanitize_text_field($_POST['codeon_menu_font_family']));
            }
            
            // Para birimi sembolü
            if (isset($_POST['codeon_menu_currency_symbol'])) {
                update_post_meta($post_id, '_codeon_menu_currency_symbol', sanitize_text_field($_POST['codeon_menu_currency_symbol']));
            }
            
            // Menü öğe resimleri göster/gizle
            $show_images = isset($_POST['codeon_menu_show_images']) ? '1' : '0';
            update_post_meta($post_id, '_codeon_menu_show_images', $show_images);
        }
        
        // Kategori açıklaması meta kutusu verilerini kaydet
        if (isset($_POST['codeon_category_description_nonce']) && wp_verify_nonce($_POST['codeon_category_description_nonce'], 'codeon_category_description_data')) {
            // Açıklama
            if (isset($_POST['codeon_category_description'])) {
                update_post_meta($post_id, '_codeon_category_description', sanitize_textarea_field($_POST['codeon_category_description']));
            }
            
            // İkon
            if (isset($_POST['codeon_category_icon'])) {
                update_post_meta($post_id, '_codeon_category_icon', sanitize_text_field($_POST['codeon_category_icon']));
            }
        }
    }
}