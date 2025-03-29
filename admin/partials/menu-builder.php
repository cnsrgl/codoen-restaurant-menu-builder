<?php
/**
 * CodeOn Restaurant Menu Builder - Menü Oluşturucu
 *
 * @since      1.0.0
 * @package    CodeOn_Restaurant_Menu
 */

// Doğrudan erişimi engelle
if (!defined('WPINC')) {
    die;
}

global $post;

// Mevcut menü yapısını al
$structure = get_post_meta($post->ID, '_codeon_menu_structure', true);

// Şablonları ve stilleri al
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

// Yapı boşsa, boş bir bölüm oluştur
if (empty($structure)) {
    $structure = array(
        array(
            'title' => '',
            'description' => '',
            'items' => array()
        )
    );
}
?>

<div class="codeon-admin-header">
    <h2><?php _e('Menü Yapılandırması', 'codeon-restaurant-menu-builder'); ?></h2>
    <p><?php _e('Aşağıdaki alanları kullanarak menünüzü oluşturun. Her bölüm bir menü kategorisini temsil eder (örn. Başlangıçlar, Ana Yemekler, Tatlılar). Her bölüme dilediğiniz kadar menü öğesi ekleyebilirsiniz.', 'codeon-restaurant-menu-builder'); ?></p>
</div>

<div class="codeon-menu-builder">
    <div class="codeon-menu-builder-controls">
        <button type="button" class="button button-primary add-section">
            <span class="dashicons dashicons-plus-alt"></span> <?php _e('Bölüm Ekle', 'codeon-restaurant-menu-builder'); ?>
        </button>
        
        <span class="spinner"></span>
    </div>
    
    <div class="codeon-menu-structure">
        <?php foreach ($structure as $section_index => $section) : ?>
            <div class="codeon-menu-section" data-index="<?php echo $section_index; ?>">
                <div class="codeon-section-header">
                    <h3 class="codeon-section-title">
                        <span class="codeon-drag-handle dashicons dashicons-menu"></span>
                        <input type="text" name="codeon_menu_structure[<?php echo $section_index; ?>][title]" value="<?php echo esc_attr($section['title']); ?>" placeholder="<?php _e('Bölüm Başlığı', 'codeon-restaurant-menu-builder'); ?>" />
                        <button type="button" class="button-link codeon-remove-section">
                            <span class="dashicons dashicons-trash"></span>
                        </button>
                    </h3>
                    <div class="codeon-section-description">
                        <textarea name="codeon_menu_structure[<?php echo $section_index; ?>][description]" placeholder="<?php _e('Bölüm Açıklaması (İsteğe Bağlı)', 'codeon-restaurant-menu-builder'); ?>"><?php echo esc_textarea($section['description']); ?></textarea>
                    </div>
                </div>
                
                <div class="codeon-section-items">
                    <?php if (!empty($section['items'])) : ?>
                        <?php foreach ($section['items'] as $item_index => $item) : ?>
                            <div class="codeon-menu-item" data-index="<?php echo $item_index; ?>">
                                <span class="codeon-drag-handle dashicons dashicons-menu"></span>
                                <div class="codeon-item-select">
                                    <select name="codeon_menu_structure[<?php echo $section_index; ?>][items][<?php echo $item_index; ?>][id]">
                                        <option value=""><?php _e('-- Öğe Seçin --', 'codeon-restaurant-menu-builder'); ?></option>
                                        <?php
                                        $menu_items = get_posts(array(
                                            'post_type' => 'codeon_menu_item',
                                            'numberposts' => -1,
                                            'orderby' => 'title',
                                            'order' => 'ASC'
                                        ));
                                        
                                        foreach ($menu_items as $menu_item) {
                                            $price = get_post_meta($menu_item->ID, '_codeon_menu_item_price', true);
                                            $price_postfix = get_post_meta($menu_item->ID, '_codeon_menu_item_price_postfix', true);
                                            echo '<option value="' . $menu_item->ID . '"' . selected($item['id'], $menu_item->ID, false) . '>' . $menu_item->post_title . ' (' . $price . ' ' . $price_postfix . ')</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <button type="button" class="button-link codeon-remove-item">
                                    <span class="dashicons dashicons-no"></span>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    
                    <button type="button" class="button add-item">
                        <span class="dashicons dashicons-plus"></span> <?php _e('Öğe Ekle', 'codeon-restaurant-menu-builder'); ?>
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <div class="codeon-menu-preview">
        <h3><?php _e('Menü Önizlemesi', 'codeon-restaurant-menu-builder'); ?></h3>
        <p><?php _e('Menünüzü kaydettikten sonra, aşağıdaki kısa kodu kullanarak WordPress içeriğinize ekleyebilirsiniz:', 'codeon-restaurant-menu-builder'); ?></p>
        <code>[codeon_menu id="<?php echo $post->ID; ?>"]</code>
        
        <div class="codeon-shortcode-parameters">
            <p><?php _e('Ek parametreler:', 'codeon-restaurant-menu-builder'); ?></p>
            <ul>
                <li><code>template="<?php echo esc_attr($template); ?>"</code> - <?php _e('Şablon seçimi (default, elegant, modern, classic, bistro)', 'codeon-restaurant-menu-builder'); ?></li>
                <li><code>title="yes|no"</code> - <?php _e('Menü başlığını göster/gizle', 'codeon-restaurant-menu-builder'); ?></li>
                <li><code>description="yes|no"</code> - <?php _e('Menü açıklamasını göster/gizle', 'codeon-restaurant-menu-builder'); ?></li>
                <li><code>image="yes|no"</code> - <?php _e('Öğe görsellerini göster/gizle', 'codeon-restaurant-menu-builder'); ?></li>
            </ul>
        </div>
    </div>
</div>

<!-- Şablonlar -->
<script type="text/template" id="section-template">
    <div class="codeon-menu-section" data-index="{index}">
        <div class="codeon-section-header">
            <h3 class="codeon-section-title">
                <span class="codeon-drag-handle dashicons dashicons-menu"></span>
                <input type="text" name="codeon_menu_structure[{index}][title]" value="" placeholder="<?php _e('Bölüm Başlığı', 'codeon-restaurant-menu-builder'); ?>" />
                <button type="button" class="button-link codeon-remove-section">
                    <span class="dashicons dashicons-trash"></span>
                </button>
            </h3>
            <div class="codeon-section-description">
                <textarea name="codeon_menu_structure[{index}][description]" placeholder="<?php _e('Bölüm Açıklaması (İsteğe Bağlı)', 'codeon-restaurant-menu-builder'); ?>"></textarea>
            </div>
        </div>
        
        <div class="codeon-section-items">
            <button type="button" class="button add-item"><?php _e('Öğe Ekle', 'codeon-restaurant-menu-builder'); ?></button>
        </div>
    </div>
</script>

<script type="text/template" id="item-template">
    <div class="codeon-menu-item" data-index="{itemIndex}">
        <span class="codeon-drag-handle dashicons dashicons-menu"></span>
        <div class="codeon-item-select">
            <select name="codeon_menu_structure[{sectionIndex}][items][{itemIndex}][id]">
                <option value=""><?php _e('-- Öğe Seçin --', 'codeon-restaurant-menu-builder'); ?></option>
                {itemOptions}
            </select>
        </div>
        <button type="button" class="button-link codeon-remove-item">
            <span class="dashicons dashicons-no"></span>
        </button>
    </div>
</script>

<script type="text/template" id="variation-template">
    <div class="codeon-variation-item">
        <div class="codeon-meta-box-row">
            <label><?php _e('Varyasyon Adı', 'codeon-restaurant-menu-builder'); ?>:</label>
            <input type="text" name="codeon_menu_item_variations[{index}][name]" value="" />
        </div>
        <div class="codeon-meta-box-row">
            <label><?php _e('Fiyat', 'codeon-restaurant-menu-builder'); ?>:</label>
            <input type="text" name="codeon_menu_item_variations[{index}][price]" value="" />
        </div>
        <div class="codeon-meta-box-row">
            <label><?php _e('Açıklama', 'codeon-restaurant-menu-builder'); ?>:</label>
            <textarea name="codeon_menu_item_variations[{index}][description]"></textarea>
        </div>
        <button type="button" class="button remove-variation"><?php _e('Kaldır', 'codeon-restaurant-menu-builder'); ?></button>
    </div>
</script>