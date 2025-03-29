<?php
/**
 * CodeOn Restaurant Menu Builder - Ayarlar Sayfası
 *
 * @since      1.0.0
 * @package    CodeOn_Restaurant_Menu
 */

// Doğrudan erişimi engelle
if (!defined('WPINC')) {
    die;
}

// Ayarları kaydet
if (isset($_POST['codeon_save_settings']) && check_admin_referer('codeon_settings_nonce')) {
    // Genel ayarlar
    $currency_symbol = isset($_POST['codeon_currency_symbol']) ? sanitize_text_field($_POST['codeon_currency_symbol']) : '€';
    $default_template = isset($_POST['codeon_default_template']) ? sanitize_text_field($_POST['codeon_default_template']) : 'default';
    $show_images = isset($_POST['codeon_show_images']) ? '1' : '0';
    
    // Ayarları kaydet
    update_option('codeon_currency_symbol', $currency_symbol);
    update_option('codeon_default_template', $default_template);
    update_option('codeon_show_images', $show_images);
    
    // Renk ayarları
    $primary_color = isset($_POST['codeon_primary_color']) ? sanitize_hex_color($_POST['codeon_primary_color']) : '#000000';
    $secondary_color = isset($_POST['codeon_secondary_color']) ? sanitize_hex_color($_POST['codeon_secondary_color']) : '#333333';
    
    update_option('codeon_primary_color', $primary_color);
    update_option('codeon_secondary_color', $secondary_color);
    
    // Yazı tipi ayarları
    $font_family = isset($_POST['codeon_font_family']) ? sanitize_text_field($_POST['codeon_font_family']) : 'inherit';
    update_option('codeon_font_family', $font_family);
    
    // Başarı mesajı
    echo '<div class="notice notice-success is-dismissible"><p>' . __('Ayarlar başarıyla kaydedildi.', 'codeon-restaurant-menu-builder') . '</p></div>';
}

// Kaydedilmiş ayarları al
$currency_symbol = get_option('codeon_currency_symbol', '€');
$default_template = get_option('codeon_default_template', 'default');
$show_images = get_option('codeon_show_images', '0');
$primary_color = get_option('codeon_primary_color', '#000000');
$secondary_color = get_option('codeon_secondary_color', '#333333');
$font_family = get_option('codeon_font_family', 'inherit');
?>

<div class="wrap codeon-settings-page">
    <h1><?php _e('CodeOn Restaurant Menu Builder Ayarları', 'codeon-restaurant-menu-builder'); ?></h1>
    
    <div class="codeon-settings-description">
        <p><?php _e('Bu sayfada, eklentinin genel ayarlarını yapılandırabilirsiniz. Bu ayarlar, yeni oluşturulan tüm menüler için varsayılan değerler olarak kullanılacaktır. Her menü için ayrı ayrı özelleştirme yapabilirsiniz.', 'codeon-restaurant-menu-builder'); ?></p>
    </div>
    
    <form method="post" action="">
        <?php wp_nonce_field('codeon_settings_nonce'); ?>
        
        <div class="codeon-settings-container">
            <div class="codeon-settings-column">
                <div class="codeon-settings-card">
                    <h2><?php _e('Genel Ayarlar', 'codeon-restaurant-menu-builder'); ?></h2>
                    
                    <table class="form-table">
                        <tr>
                            <th scope="row"><?php _e('Para Birimi Sembolü', 'codeon-restaurant-menu-builder'); ?></th>
                            <td>
                                <input type="text" name="codeon_currency_symbol" value="<?php echo esc_attr($currency_symbol); ?>" class="regular-text" />
                                <p class="description"><?php _e('Menü öğelerinin fiyatlarında kullanılacak para birimi sembolü (ör. €, $, ₺, CHF).', 'codeon-restaurant-menu-builder'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Varsayılan Şablon', 'codeon-restaurant-menu-builder'); ?></th>
                            <td>
                                <select name="codeon_default_template">
                                    <option value="default" <?php selected($default_template, 'default'); ?>><?php _e('Varsayılan', 'codeon-restaurant-menu-builder'); ?></option>
                                    <option value="elegant" <?php selected($default_template, 'elegant'); ?>><?php _e('Zarif', 'codeon-restaurant-menu-builder'); ?></option>
                                    <option value="modern" <?php selected($default_template, 'modern'); ?>><?php _e('Modern', 'codeon-restaurant-menu-builder'); ?></option>
                                    <option value="classic" <?php selected($default_template, 'classic'); ?>><?php _e('Klasik', 'codeon-restaurant-menu-builder'); ?></option>
                                    <option value="bistro" <?php selected($default_template, 'bistro'); ?>><?php _e('Bistro', 'codeon-restaurant-menu-builder'); ?></option>
                                </select>
                                <p class="description"><?php _e('Menüler için kullanılacak varsayılan şablon.', 'codeon-restaurant-menu-builder'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Görselleri Göster', 'codeon-restaurant-menu-builder'); ?></th>
                            <td>
                                <label>
                                    <input type="checkbox" name="codeon_show_images" value="1" <?php checked($show_images, '1'); ?> />
                                    <?php _e('Varsayılan olarak menü öğe resimlerini göster', 'codeon-restaurant-menu-builder'); ?>
                                </label>
                                <p class="description"><?php _e('Bu ayar etkinleştirildiğinde, menü öğeleri için öne çıkan görseller gösterilecektir.', 'codeon-restaurant-menu-builder'); ?></p>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <div class="codeon-settings-card">
                    <h2><?php _e('Görünüm Ayarları', 'codeon-restaurant-menu-builder'); ?></h2>
                    
                    <table class="form-table">
                        <tr>
                            <th scope="row"><?php _e('Ana Renk', 'codeon-restaurant-menu-builder'); ?></th>
                            <td>
                                <input type="color" name="codeon_primary_color" value="<?php echo esc_attr($primary_color); ?>" class="color-picker" />
                                <p class="description"><?php _e('Başlıklar, fiyatlar ve vurgular için kullanılacak ana renk.', 'codeon-restaurant-menu-builder'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('İkincil Renk', 'codeon-restaurant-menu-builder'); ?></th>
                            <td>
                                <input type="color" name="codeon_secondary_color" value="<?php echo esc_attr($secondary_color); ?>" class="color-picker" />
                                <p class="description"><?php _e('Açıklamalar ve diğer ikincil metinler için kullanılacak renk.', 'codeon-restaurant-menu-builder'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Yazı Tipi', 'codeon-restaurant-menu-builder'); ?></th>
                            <td>
                                <select name="codeon_font_family">
                                    <option value="inherit" <?php selected($font_family, 'inherit'); ?>><?php _e('Varsayılan (Tema Yazı Tipi)', 'codeon-restaurant-menu-builder'); ?></option>
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
                                <p class="description"><?php _e('Menüler için kullanılacak varsayılan yazı tipi ailesi.', 'codeon-restaurant-menu-builder'); ?></p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="codeon-settings-column">
                <div class="codeon-settings-card">
                    <h2><?php _e('Önizleme', 'codeon-restaurant-menu-builder'); ?></h2>
                    
                    <div class="codeon-preview-container" id="codeon-preview">
                        <div class="codeon-preview-section-title" id="codeon-preview-section-title">
                            <?php _e('Menü Bölüm Başlığı', 'codeon-restaurant-menu-builder'); ?>
                        </div>
                        
                        <div class="codeon-preview-items">
                            <div class="codeon-preview-item">
                                <div class="codeon-preview-item-header">
                                    <div class="codeon-preview-item-title" id="codeon-preview-item-title">
                                        <?php _e('Menü Öğesi', 'codeon-restaurant-menu-builder'); ?>
                                    </div>
                                    <div class="codeon-preview-item-price" id="codeon-preview-item-price">
                                        <?php echo esc_html('12.50 ' . $currency_symbol); ?>
                                    </div>
                                </div>
                                <div class="codeon-preview-item-description" id="codeon-preview-item-description">
                                    <?php _e('Bu bir örnek menü öğesi açıklamasıdır. Burada yemeğin içeriği ve özellikleri hakkında bilgi verilir.', 'codeon-restaurant-menu-builder'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="codeon-settings-card">
                    <h2><?php _e('Kısa Kod Önizlemesi', 'codeon-restaurant-menu-builder'); ?></h2>
                    
                    <div class="codeon-shortcode-preview">
                        <p><?php _e('Aşağıdaki ayarlarla oluşturulacak kısa kod:', 'codeon-restaurant-menu-builder'); ?></p>
                        
                        <code id="codeon-shortcode-preview">[codeon_menu id="123" template="<?php echo esc_attr($default_template); ?>" image="<?php echo $show_images === '1' ? 'yes' : 'no'; ?>"]</code>
                        
                        <p class="description"><?php _e('Bu kısa kodu WordPress editöründe veya widget alanında kullanabilirsiniz.', 'codeon-restaurant-menu-builder'); ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <p class="submit">
            <input type="submit" name="codeon_save_settings" class="button button-primary" value="<?php _e('Ayarları Kaydet', 'codeon-restaurant-menu-builder'); ?>" />
        </p>
    </form>
</div>

<style>
    .codeon-settings-page {
        max-width: 1200px;
        margin-top: 20px;
    }
    
    .codeon-settings-description {
        margin-bottom: 20px;
    }
    
    .codeon-settings-container {
        display: flex;
        flex-wrap: wrap;
        margin-right: -15px;
        margin-left: -15px;
    }
    
    .codeon-settings-column {
        flex: 0 0 50%;
        max-width: 50%;
        padding-right: 15px;
        padding-left: 15px;
        box-sizing: border-box;
    }
    
    .codeon-settings-card {
        background-color: #fff;
        border: 1px solid #ccd0d4;
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 3px;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
    }
    
    .codeon-settings-card h2 {
        margin-top: 0;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }
    
    .color-picker {
        width: 100px;
        height: 30px;
        padding: 0;
        border: 1px solid #ddd;
        border-radius: 3px;
        cursor: pointer;
    }
    
    .codeon-preview-container {
        border: 1px solid #ddd;
        padding: 20px;
        background-color: #f9f9f9;
        margin-top: 10px;
    }
    
    .codeon-preview-section-title {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 15px;
        padding-bottom: 8px;
        border-bottom: 2px solid;
        color: var(--primary-color);
    }
    
    .codeon-preview-items {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    
    .codeon-preview-item {
        background-color: #fff;
        padding: 15px;
        border-radius: 5px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    
    .codeon-preview-item-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
    }
    
    .codeon-preview-item-title {
        font-weight: bold;
        font-size: 16px;
        color: var(--primary-color);
    }
    
    .codeon-preview-item-price {
        font-weight: bold;
        color: var(--primary-color);
    }
    
    .codeon-preview-item-description {
        font-size: 14px;
        color: var(--secondary-color);
    }
    
    .codeon-shortcode-preview {
        padding: 15px;
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 3px;
    }
    
    .codeon-shortcode-preview code {
        display: block;
        padding: 10px;
        margin: 10px 0;
        background-color: #fff;
        border: 1px solid #ddd;
        font-size: 14px;
    }
    
    @media (max-width: 782px) {
        .codeon-settings-column {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }
</style>

<script>
    jQuery(document).ready(function($) {
        // Önizleme için dinamik güncelleme
        function updatePreview() {
            var primaryColor = $('input[name="codeon_primary_color"]').val();
            var secondaryColor = $('input[name="codeon_secondary_color"]').val();
            var fontFamily = $('select[name="codeon_font_family"]').val();
            var currencySymbol = $('input[name="codeon_currency_symbol"]').val();
            var template = $('select[name="codeon_default_template"]').val();
            var showImages = $('input[name="codeon_show_images"]').is(':checked') ? 'yes' : 'no';
            
            // Stil güncelleme
            $('#codeon-preview-section-title, #codeon-preview-item-title, #codeon-preview-item-price').css('color', primaryColor);
            $('#codeon-preview-section-title').css('border-bottom-color', primaryColor);
            $('#codeon-preview-item-description').css('color', secondaryColor);
            $('#codeon-preview').css('font-family', fontFamily);
            
            // Para birimi güncelleme
            $('#codeon-preview-item-price').text('12.50 ' + currencySymbol);
            
            // Kısa kod önizlemesi güncelleme
            $('#codeon-shortcode-preview').text('[codeon_menu id="123" template="' + template + '" image="' + showImages + '"]');
        }
        
        // Renk seçici değiştiğinde
        $('input[name="codeon_primary_color"], input[name="codeon_secondary_color"]').on('input', updatePreview);
        
        // Diğer ayarlar değiştiğinde
        $('select[name="codeon_font_family"], input[name="codeon_currency_symbol"], select[name="codeon_default_template"], input[name="codeon_show_images"]').on('change', updatePreview);
        
        // Sayfa yüklendiğinde önizlemeyi başlat
        updatePreview();
        
        // CSS özelliklerini ayarla
        document.documentElement.style.setProperty('--primary-color', $('input[name="codeon_primary_color"]').val());
        document.documentElement.style.setProperty('--secondary-color', $('input[name="codeon_secondary_color"]').val());
    });
</script>