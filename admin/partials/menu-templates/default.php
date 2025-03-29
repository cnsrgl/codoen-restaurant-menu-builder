<?php
/**
 * CodeOn Restaurant Menu Builder - Varsayılan Menü Şablonu
 *
 * @since      1.0.0
 * @package    CodeOn_Restaurant_Menu
 */

// Doğrudan erişimi engelle
if (!defined('WPINC')) {
    die;
}
?>

<div class="codeon-menu-container codeon-menu-<?php echo $menu_id; ?> codeon-theme-<?php echo esc_attr($template); ?>">
    <?php if ($atts['title'] === 'yes' && !empty($menu->post_title)) : ?>
        <h2 class="codeon-menu-title"><?php echo esc_html($menu->post_title); ?></h2>
    <?php endif; ?>
    
    <?php if ($atts['description'] === 'yes' && !empty($menu->post_content)) : ?>
        <div class="codeon-menu-description"><?php echo wp_kses_post($menu->post_content); ?></div>
    <?php endif; ?>
    
    <?php if (!empty($structure)) : ?>
        <?php foreach ($structure as $section) : ?>
            <div class="codeon-menu-section">
                <?php if (!empty($section['title'])) : ?>
                    <h3 class="codeon-section-title"><?php echo esc_html($section['title']); ?></h3>
                <?php endif; ?>
                
                <?php if (!empty($section['description'])) : ?>
                    <div class="codeon-section-description"><?php echo wp_kses_post($section['description']); ?></div>
                <?php endif; ?>
                
                <?php if (!empty($section['items'])) : ?>
                    <div class="codeon-menu-items">
                        <?php foreach ($section['items'] as $item) : ?>
                            <?php 
                            $item_id = $item['id'];
                            $item_post = get_post($item_id);
                            
                            if ($item_post && $item_post->post_type === 'codeon_menu_item') :
                                // Menü öğesi meta verileri
                                $price = get_post_meta($item_id, '_codeon_menu_item_price', true);
                                $price_postfix = get_post_meta($item_id, '_codeon_menu_item_price_postfix', true);
                                $discount_price = get_post_meta($item_id, '_codeon_menu_item_discount_price', true);
                                $description = get_post_meta($item_id, '_codeon_menu_item_description', true);
                                $is_spicy = get_post_meta($item_id, '_codeon_menu_item_is_spicy', true);
                                $is_vegetarian = get_post_meta($item_id, '_codeon_menu_item_is_vegetarian', true);
                                $is_vegan = get_post_meta($item_id, '_codeon_menu_item_is_vegan', true);
                                $is_gluten_free = get_post_meta($item_id, '_codeon_menu_item_is_gluten_free', true);
                                
                                // Görsel URL
                                $image_url = '';
                                if ($show_images === '1' && has_post_thumbnail($item_id)) {
                                    $image_url = get_the_post_thumbnail_url($item_id, 'medium');
                                }
                            ?>
                                <div class="codeon-menu-item">
                                    <?php if (!empty($image_url)) : ?>
                                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($item_post->post_title); ?>" class="codeon-item-image" />
                                    <?php endif; ?>
                                    
                                    <div class="codeon-item-header">
                                        <h4 class="codeon-item-title"><?php echo esc_html($item_post->post_title); ?></h4>
                                        
                                        <?php if (!empty($price)) : ?>
                                            <div class="codeon-item-price">
                                                <?php if (!empty($discount_price)) : ?>
                                                    <span class="codeon-regular-price"><?php echo esc_html($discount_price . ' ' . $price_postfix); ?></span>
                                                    <span class="codeon-discount-price"><?php echo esc_html($price . ' ' . $price_postfix); ?></span>
                                                <?php else : ?>
                                                    <?php echo esc_html($price . ' ' . $price_postfix); ?>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php if (!empty($description)) : ?>
                                        <div class="codeon-item-description"><?php echo wp_kses_post($description); ?></div>
                                    <?php endif; ?>
                                    
                                    <?php 
                                    // Özellik rozetleri
                                    $has_features = $is_spicy || $is_vegetarian || $is_vegan || $is_gluten_free;
                                    
                                    if ($has_features) : ?>
                                        <div class="codeon-item-features">
                                            <?php if ($is_spicy) : ?>
                                                <span class="codeon-feature-badge codeon-badge-spicy"><?php _e('Acılı', 'codeon-restaurant-menu-builder'); ?></span>
                                            <?php endif; ?>
                                            
                                            <?php if ($is_vegetarian) : ?>
                                                <span class="codeon-feature-badge codeon-badge-vegetarian"><?php _e('Vejetaryen', 'codeon-restaurant-menu-builder'); ?></span>
                                            <?php endif; ?>
                                            
                                            <?php if ($is_vegan) : ?>
                                                <span class="codeon-feature-badge codeon-badge-vegan"><?php _e('Vegan', 'codeon-restaurant-menu-builder'); ?></span>
                                            <?php endif; ?>
                                            
                                            <?php if ($is_gluten_free) : ?>
                                                <span class="codeon-feature-badge codeon-badge-gluten-free"><?php _e('Glutensiz', 'codeon-restaurant-menu-builder'); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php
                                    // Varyasyonlar
                                    $has_variations = get_post_meta($item_id, '_codeon_menu_item_has_variations', true);
                                    $variations = get_post_meta($item_id, '_codeon_menu_item_variations', true);
                                    
                                    if ($has_variations && !empty($variations)) : ?>
                                        <div class="codeon-item-variations">
                                            <?php foreach ($variations as $variation) : ?>
                                                <div class="codeon-variation-item">
                                                    <div class="codeon-variation-name"><?php echo esc_html($variation['name']); ?></div>
                                                    <div class="codeon-variation-price"><?php echo esc_html($variation['price'] . ' ' . $price_postfix); ?></div>
                                                </div>
                                                <?php if (!empty($variation['description'])) : ?>
                                                    <div class="codeon-variation-description"><?php echo wp_kses_post($variation['description']); ?></div>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>