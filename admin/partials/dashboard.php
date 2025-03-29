<?php
/**
 * CodeOn Restaurant Menu Builder - Yönetici Kontrol Paneli
 *
 * @since      1.0.0
 * @package    CodeOn_Restaurant_Menu
 */

// Doğrudan erişimi engelle
if (!defined('WPINC')) {
    die;
}

// Menü ve öğe sayılarını al
$menus_count = wp_count_posts('codeon_menu');
$items_count = wp_count_posts('codeon_menu_item');
$categories_count = wp_count_posts('codeon_menu_category');

// Son eklenen menüleri al
$recent_menus = get_posts(array(
    'post_type' => 'codeon_menu',
    'posts_per_page' => 5,
    'orderby' => 'date',
    'order' => 'DESC'
));

// Son eklenen öğeleri al
$recent_items = get_posts(array(
    'post_type' => 'codeon_menu_item',
    'posts_per_page' => 5,
    'orderby' => 'date',
    'order' => 'DESC'
));
?>

<div class="wrap codeon-admin-dashboard">
    <h1><?php _e('CodeOn Restaurant Menu Builder', 'codeon-restaurant-menu-builder'); ?></h1>
    
    <div class="codeon-welcome-panel">
        <h2><?php _e('Hoş Geldiniz!', 'codeon-restaurant-menu-builder'); ?></h2>
        <p><?php _e('CodeOn Restaurant Menu Builder ile profesyonel ve özelleştirilebilir restoran menüleri oluşturabilirsiniz. WordPress sitenize menülerinizi eklemek için aşağıdaki adımları izleyin:', 'codeon-restaurant-menu-builder'); ?></p>
        
        <ol class="codeon-quick-start">
            <li><?php _e('<strong>Menü Öğeleri Oluşturun:</strong> Her bir yemek veya içecek için menü öğeleri ekleyin. Fiyatlar, açıklamalar ve özel özellikler (vejetaryen, glutensiz vb.) ekleyebilirsiniz.', 'codeon-restaurant-menu-builder'); ?></li>
            <li><?php _e('<strong>Menü Kategorileri Oluşturun (İsteğe Bağlı):</strong> Yemeklerinizi gruplandırmak için kategorileri kullanabilirsiniz (ör. Başlangıçlar, Ana Yemekler, Tatlılar).', 'codeon-restaurant-menu-builder'); ?></li>
            <li><?php _e('<strong>Menü Oluşturun:</strong> Menü öğelerinizi bir araya getirerek menü oluşturun. İstediğiniz yapıyı oluşturmak için sürükle ve bırak özelliğini kullanın.', 'codeon-restaurant-menu-builder'); ?></li>
            <li><?php _e('<strong>Sitenize Ekleyin:</strong> Oluşturduğunuz menüyü kısa kod veya blok kullanarak sitenize ekleyin.', 'codeon-restaurant-menu-builder'); ?></li>
        </ol>
    </div>
    
    <div class="codeon-admin-columns">
        <div class="codeon-admin-column">
            <div class="codeon-admin-card">
                <h2><?php _e('İstatistikler', 'codeon-restaurant-menu-builder'); ?></h2>
                
                <ul class="codeon-stats">
                    <li>
                        <span class="codeon-stat-label"><?php _e('Toplam Menü', 'codeon-restaurant-menu-builder'); ?></span>
                        <span class="codeon-stat-value"><?php echo isset($menus_count->publish) ? $menus_count->publish : 0; ?></span>
                    </li>
                    <li>
                        <span class="codeon-stat-label"><?php _e('Toplam Menü Öğesi', 'codeon-restaurant-menu-builder'); ?></span>
                        <span class="codeon-stat-value"><?php echo isset($items_count->publish) ? $items_count->publish : 0; ?></span>
                    </li>
                    <li>
                        <span class="codeon-stat-label"><?php _e('Toplam Kategori', 'codeon-restaurant-menu-builder'); ?></span>
                        <span class="codeon-stat-value"><?php echo isset($categories_count->publish) ? $categories_count->publish : 0; ?></span>
                    </li>
                </ul>
                
                <div class="codeon-action-buttons">
                    <a href="<?php echo admin_url('post-new.php?post_type=codeon_menu'); ?>" class="button button-primary"><?php _e('Yeni Menü Ekle', 'codeon-restaurant-menu-builder'); ?></a>
                    <a href="<?php echo admin_url('post-new.php?post_type=codeon_menu_item'); ?>" class="button"><?php _e('Yeni Öğe Ekle', 'codeon-restaurant-menu-builder'); ?></a>
                </div>
            </div>
            
            <div class="codeon-admin-card">
                <h2><?php _e('Hızlı Kısa Kod Kılavuzu', 'codeon-restaurant-menu-builder'); ?></h2>
                
                <div class="codeon-shortcode-guide">
                    <p><?php _e('Aşağıdaki kısa kodları kullanarak menülerinizi sitenize ekleyebilirsiniz:', 'codeon-restaurant-menu-builder'); ?></p>
                    
                    <div class="codeon-shortcode-example">
                        <code>[codeon_menu id="123"]</code>
                        <p><?php _e('Belirtilen ID\'ye sahip menüyü görüntüler.', 'codeon-restaurant-menu-builder'); ?></p>
                    </div>
                    
                    <div class="codeon-shortcode-example">
                        <code>[codeon_menu id="123" title="no" description="no"]</code>
                        <p><?php _e('Menü başlığını ve açıklamasını gizleyerek, yalnızca menü öğelerini görüntüler.', 'codeon-restaurant-menu-builder'); ?></p>
                    </div>
                    
                    <div class="codeon-shortcode-example">
                        <code>[codeon_menu id="123" template="elegant"]</code>
                        <p><?php _e('Belirtilen şablonu kullanarak menüyü görüntüler. Kullanılabilir şablonlar: default, elegant, modern, classic, bistro', 'codeon-restaurant-menu-builder'); ?></p>
                    </div>
                    
                    <div class="codeon-shortcode-example">
                        <code>[codeon_menu_item id="456"]</code>
                        <p><?php _e('Belirtilen ID\'ye sahip tek bir menü öğesini görüntüler.', 'codeon-restaurant-menu-builder'); ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="codeon-admin-column">
            <div class="codeon-admin-card">
                <h2><?php _e('Son Eklenen Menüler', 'codeon-restaurant-menu-builder'); ?></h2>
                
                <?php if (!empty($recent_menus)) : ?>
                    <ul class="codeon-recent-items">
                        <?php foreach ($recent_menus as $menu) : ?>
                            <li>
                                <a href="<?php echo get_edit_post_link($menu->ID); ?>"><?php echo $menu->post_title; ?></a>
                                <span class="codeon-item-meta">
                                    <?php echo get_the_date('', $menu->ID); ?>
                                    <a href="<?php echo get_permalink($menu->ID); ?>" target="_blank" class="codeon-view-link"><?php _e('Görüntüle', 'codeon-restaurant-menu-builder'); ?></a>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    
                    <a href="<?php echo admin_url('edit.php?post_type=codeon_menu'); ?>" class="button"><?php _e('Tüm Menüleri Görüntüle', 'codeon-restaurant-menu-builder'); ?></a>
                <?php else : ?>
                    <p><?php _e('Henüz menü bulunmuyor.', 'codeon-restaurant-menu-builder'); ?></p>
                    <a href="<?php echo admin_url('post-new.php?post_type=codeon_menu'); ?>" class="button button-primary"><?php _e('İlk Menünü Oluştur', 'codeon-restaurant-menu-builder'); ?></a>
                <?php endif; ?>
            </div>
            
            <div class="codeon-admin-card">
                <h2><?php _e('Son Eklenen Menü Öğeleri', 'codeon-restaurant-menu-builder'); ?></h2>
                
                <?php if (!empty($recent_items)) : ?>
                    <ul class="codeon-recent-items">
                        <?php foreach ($recent_items as $item) : ?>
                            <?php 
                            $price = get_post_meta($item->ID, '_codeon_menu_item_price', true);
                            $price_postfix = get_post_meta($item->ID, '_codeon_menu_item_price_postfix', true);
                            ?>
                            <li>
                                <a href="<?php echo get_edit_post_link($item->ID); ?>"><?php echo $item->post_title; ?></a>
                                <span class="codeon-item-meta">
                                    <?php if (!empty($price)) : ?>
                                        <span class="codeon-item-price"><?php echo esc_html($price . ' ' . $price_postfix); ?></span>
                                    <?php endif; ?>
                                    <?php echo get_the_date('', $item->ID); ?>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    
                    <a href="<?php echo admin_url('edit.php?post_type=codeon_menu_item'); ?>" class="button"><?php _e('Tüm Öğeleri Görüntüle', 'codeon-restaurant-menu-builder'); ?></a>
                <?php else : ?>
                    <p><?php _e('Henüz menü öğesi bulunmuyor.', 'codeon-restaurant-menu-builder'); ?></p>
                    <a href="<?php echo admin_url('post-new.php?post_type=codeon_menu_item'); ?>" class="button button-primary"><?php _e('İlk Menü Öğeni Oluştur', 'codeon-restaurant-menu-builder'); ?></a>
                <?php endif; ?>
            </div>
            
            <div class="codeon-admin-card">
                <h2><?php _e('Destek ve Yardım', 'codeon-restaurant-menu-builder'); ?></h2>
                
                <p><?php _e('CodeOn Restaurant Menu Builder eklentisi hakkında yardıma mı ihtiyacınız var?', 'codeon-restaurant-menu-builder'); ?></p>
                
                <ul class="codeon-support-links">
                    <li><a href="https://codeon.ch/docs/restaurant-menu-builder/" target="_blank"><?php _e('Dokümantasyon', 'codeon-restaurant-menu-builder'); ?></a></li>
                    <li><a href="https://codeon.ch/support/" target="_blank"><?php _e('Destek Forumu', 'codeon-restaurant-menu-builder'); ?></a></li>
                    <li><a href="https://codeon.ch/contact/" target="_blank"><?php _e('İletişim', 'codeon-restaurant-menu-builder'); ?></a></li>
                </ul>
                
                <p class="codeon-version"><?php _e('Versiyon', 'codeon-restaurant-menu-builder'); ?>: <?php echo CODEON_RESTAURANT_MENU_VERSION; ?></p>
            </div>
        </div>
    </div>
</div>

<style>
    .codeon-admin-dashboard {
        max-width: 1200px;
        margin-top: 20px;
    }
    
    .codeon-welcome-panel {
        background-color: #fff;
        border: 1px solid #ccd0d4;
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 3px;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
    }
    
    .codeon-welcome-panel h2 {
        margin-top: 0;
        color: #23282d;
        font-size: 21px;
    }
    
    .codeon-quick-start {
        margin-left: 15px;
    }
    
    .codeon-quick-start li {
        margin-bottom: 10px;
    }
    
    .codeon-admin-columns {
        display: flex;
        flex-wrap: wrap;
        margin-right: -15px;
        margin-left: -15px;
    }
    
    .codeon-admin-column {
        flex: 0 0 50%;
        max-width: 50%;
        padding-right: 15px;
        padding-left: 15px;
        box-sizing: border-box;
    }
    
    .codeon-admin-card {
        background-color: #fff;
        border: 1px solid #ccd0d4;
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 3px;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
    }
    
    .codeon-admin-card h2 {
        margin-top: 0;
        margin-bottom: 15px;
        color: #23282d;
        font-size: 16px;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
    }
    
    .codeon-stats {
        margin: 0;
        padding: 0;
        list-style: none;
    }
    
    .codeon-stats li {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .codeon-stat-value {
        font-weight: bold;
        color: #0073aa;
    }
    
    .codeon-action-buttons {
        margin-top: 15px;
    }
    
    .codeon-shortcode-guide {
        font-size: 14px;
    }
    
    .codeon-shortcode-example {
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px dotted #eee;
    }
    
    .codeon-shortcode-example code {
        display: inline-block;
        padding: 3px 5px;
        background-color: #f0f0f0;
        margin-bottom: 5px;
    }
    
    .codeon-shortcode-example p {
        margin: 5px 0 0 0;
        color: #666;
    }
    
    .codeon-recent-items {
        margin: 0;
        padding: 0;
        list-style: none;
    }
    
    .codeon-recent-items li {
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .codeon-item-meta {
        display: block;
        font-size: 12px;
        color: #666;
    }
    
    .codeon-view-link {
        margin-left: 10px;
    }
    
    .codeon-support-links {
        margin: 10px 0;
        padding: 0 0 0 15px;
    }
    
    .codeon-support-links li {
        margin-bottom: 8px;
    }
    
    .codeon-version {
        margin-top: 15px;
        color: #666;
        font-size: 12px;
        text-align: right;
    }
    
    @media (max-width: 782px) {
        .codeon-admin-column {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }
</style>