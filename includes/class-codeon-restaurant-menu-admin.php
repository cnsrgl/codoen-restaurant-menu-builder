<?php
/**
 * Classe d'administration pour Codeon Restaurant Menu Builder
 */
class Codeon_Restaurant_Menu_Admin {

    /**
     * Constructeur
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('wp_ajax_crmb_save_menu', array($this, 'ajax_save_menu'));
        add_action('wp_ajax_crmb_delete_menu', array($this, 'ajax_delete_menu'));
        add_action('wp_ajax_crmb_get_menu', array($this, 'ajax_get_menu'));
        add_action('elementor/elements/categories_registered', array($this, 'add_elementor_category'));
    }

    /**
     * Ajouter une catégorie Elementor
     */
    public function add_elementor_category($elements_manager) {
        $elements_manager->add_category(
            'codeon-category',
            [
                'title' => __('Codeon', 'codeon-restaurant-menu-builder'),
                'icon' => 'fa fa-plug',
            ]
        );
    }

    /**
     * Ajouter le menu d'administration
     */
    public function add_admin_menu() {
        add_menu_page(
            __('Menu Restaurant', 'codeon-restaurant-menu-builder'),
            __('Menu Restaurant', 'codeon-restaurant-menu-builder'),
            'manage_options',
            'codeon-restaurant-menu',
            array($this, 'display_admin_page'),
            'dashicons-food',
            30
        );

        add_submenu_page(
            'codeon-restaurant-menu',
            __('Tous les Menus', 'codeon-restaurant-menu-builder'),
            __('Tous les Menus', 'codeon-restaurant-menu-builder'),
            'manage_options',
            'codeon-restaurant-menu',
            array($this, 'display_admin_page')
        );

        add_submenu_page(
            'codeon-restaurant-menu',
            __('Ajouter un Menu', 'codeon-restaurant-menu-builder'),
            __('Ajouter un Menu', 'codeon-restaurant-menu-builder'),
            'manage_options',
            'codeon-restaurant-menu-add',
            array($this, 'display_add_menu_page')
        );
    }

    /**
     * Enregistrer les scripts et styles pour l'admin
     */
    public function enqueue_admin_scripts($hook) {
        // Ne charger les scripts que sur les pages du plugin
        if (!strstr($hook, 'codeon-restaurant-menu')) {
            return;
        }

        wp_enqueue_style('crmb-admin-css', CRMB_PLUGIN_URL . 'assets/css/codeon-restaurant-menu-admin.css', array(), CRMB_VERSION);
        wp_enqueue_script('crmb-admin-js', CRMB_PLUGIN_URL . 'assets/js/codeon-restaurant-menu-admin.js', array('jquery', 'jquery-ui-sortable'), CRMB_VERSION, true);

        // Ajouter des variables pour les scripts
        wp_localize_script('crmb-admin-js', 'crmb_vars', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('crmb_nonce'),
            'strings' => array(
                'confirm_delete' => __('Êtes-vous sûr de vouloir supprimer ce menu ?', 'codeon-restaurant-menu-builder'),
                'save_success' => __('Menu enregistré avec succès !', 'codeon-restaurant-menu-builder'),
                'save_error' => __('Erreur lors de l\'enregistrement du menu.', 'codeon-restaurant-menu-builder'),
                'delete_success' => __('Menu supprimé avec succès !', 'codeon-restaurant-menu-builder'),
                'delete_error' => __('Erreur lors de la suppression du menu.', 'codeon-restaurant-menu-builder'),
            ),
        ));
    }

    /**
     * Afficher la page d'administration principale
     */
    public function display_admin_page() {
        $menu_builder = new Codeon_Restaurant_Menu_Builder();
        $menus = $menu_builder->get_menus();
        
        ?>
        <div class="wrap crmb-admin">
            <h1 class="wp-heading-inline"><?php echo __('Menus de Restaurant', 'codeon-restaurant-menu-builder'); ?></h1>
            <a href="<?php echo admin_url('admin.php?page=codeon-restaurant-menu-add'); ?>" class="page-title-action"><?php echo __('Ajouter un Menu', 'codeon-restaurant-menu-builder'); ?></a>
            
            <div class="crmb-admin-header">
                <div class="crmb-logo">
                    <img src="<?php echo CRMB_PLUGIN_URL; ?>assets/img/codeon-logo.png" alt="Codeon" />
                </div>
                <p class="crmb-description"><?php echo __('Créez et gérez facilement des menus de restaurant pour votre site WordPress.', 'codeon-restaurant-menu-builder'); ?></p>
            </div>
            
            <?php if (empty($menus)) : ?>
                <div class="crmb-no-menus">
                    <p><?php echo __('Aucun menu n\'a été créé pour le moment.', 'codeon-restaurant-menu-builder'); ?></p>
                    <a href="<?php echo admin_url('admin.php?page=codeon-restaurant-menu-add'); ?>" class="button button-primary"><?php echo __('Créer un Menu', 'codeon-restaurant-menu-builder'); ?></a>
                </div>
            <?php else : ?>
                <div class="crmb-menus-table-wrap">
                    <table class="wp-list-table widefat fixed striped crmb-menus-table">
                        <thead>
                            <tr>
                                <th scope="col"><?php echo __('Nom', 'codeon-restaurant-menu-builder'); ?></th>
                                <th scope="col"><?php echo __('Description', 'codeon-restaurant-menu-builder'); ?></th>
                                <th scope="col"><?php echo __('Date de Création', 'codeon-restaurant-menu-builder'); ?></th>
                                <th scope="col"><?php echo __('Dernière Modification', 'codeon-restaurant-menu-builder'); ?></th>
                                <th scope="col"><?php echo __('Actions', 'codeon-restaurant-menu-builder'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($menus as $menu) : ?>
                                <tr>
                                    <td><?php echo esc_html($menu->name); ?></td>
                                    <td><?php echo !empty($menu->description) ? esc_html(wp_trim_words($menu->description, 10)) : '—'; ?></td>
                                    <td><?php echo date_i18n(get_option('date_format'), strtotime($menu->date_created)); ?></td>
                                    <td><?php echo date_i18n(get_option('date_format'), strtotime($menu->date_modified)); ?></td>
                                    <td class="crmb-actions">
                                        <a href="<?php echo admin_url('admin.php?page=codeon-restaurant-menu-add&menu_id=' . $menu->id); ?>" class="button button-small"><?php echo __('Modifier', 'codeon-restaurant-menu-builder'); ?></a>
                                        <a href="#" class="button button-small crmb-delete-menu" data-menu-id="<?php echo $menu->id; ?>"><?php echo __('Supprimer', 'codeon-restaurant-menu-builder'); ?></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
            
            <div class="crmb-admin-footer">
                <p><?php echo __('Codeon Restaurant Menu Builder - Version', 'codeon-restaurant-menu-builder'); ?> <?php echo CRMB_VERSION; ?></p>
                <p><?php echo __('Développé par', 'codeon-restaurant-menu-builder'); ?> <a href="https://codeon.ch" target="_blank">Codeon</a></p>
            </div>
        </div>
        <?php
    }

    /**
     * Afficher la page d'ajout ou de modification de menu
     */
    public function display_add_menu_page() {
        $menu_id = isset($_GET['menu_id']) ? intval($_GET['menu_id']) : 0;
        $menu_builder = new Codeon_Restaurant_Menu_Builder();
        $menu = $menu_id ? $menu_builder->get_menu($menu_id) : null;
        
        $menu_name = $menu ? $menu->name : '';
        $menu_description = $menu ? $menu->description : '';
        $menu_settings = $menu ? maybe_unserialize($menu->settings) : array();
        
        $default_settings = array(
            'header_background' => '#000000',
            'header_text_color' => '#FFFFFF',
            'item_name_color' => '#000000',
            'item_description_color' => '#666666',
            'item_price_color' => '#000000',
            'layout_style' => 'dots',
            'divider_style' => 'line',
        );
        
        $settings = array_merge($default_settings, is_array($menu_settings) ? $menu_settings : array());
        
        ?>
        <div class="wrap crmb-admin crmb-menu-editor">
            <h1 class="wp-heading-inline">
                <?php echo $menu_id ? __('Modifier le Menu', 'codeon-restaurant-menu-builder') : __('Ajouter un Menu', 'codeon-restaurant-menu-builder'); ?>
            </h1>
            
            <div class="crmb-admin-header">
                <div class="crmb-logo">
                    <img src="<?php echo CRMB_PLUGIN_URL; ?>assets/img/codeon-logo.png" alt="Codeon" />
                </div>
            </div>
            
            <form id="crmb-menu-form" data-menu-id="<?php echo $menu_id; ?>">
                <div class="crmb-menu-form-container">
                    <div class="crmb-menu-form-header">
                        <div class="crmb-field-group">
                            <label for="crmb-menu-name"><?php echo __('Nom du Menu', 'codeon-restaurant-menu-builder'); ?></label>
                            <input type="text" id="crmb-menu-name" name="menu_name" value="<?php echo esc_attr($menu_name); ?>" required />
                        </div>
                        
                        <div class="crmb-field-group">
                            <label for="crmb-menu-description"><?php echo __('Description', 'codeon-restaurant-menu-builder'); ?></label>
                            <textarea id="crmb-menu-description" name="menu_description"><?php echo esc_textarea($menu_description); ?></textarea>
                        </div>
                    </div>
                    
                    <div class="crmb-tabs">
                        <div class="crmb-tab-nav">
                            <a href="#" class="crmb-tab-link active" data-tab="categories"><?php echo __('Catégories & Plats', 'codeon-restaurant-menu-builder'); ?></a>
                            <a href="#" class="crmb-tab-link" data-tab="styles"><?php echo __('Style', 'codeon-restaurant-menu-builder'); ?></a>
                        </div>
                        
                        <div class="crmb-tab-content active" id="crmb-tab-categories">
                            <div class="crmb-categories-container">
                                <?php if ($menu && !empty($menu->categories)) : ?>
                                    <?php foreach ($menu->categories as $category) : ?>
                                        <div class="crmb-category-item" data-category-id="<?php echo $category->id; ?>">
                                            <div class="crmb-category-header">
                                                <div class="crmb-category-title">
                                                    <span class="crmb-handle dashicons dashicons-menu"></span>
                                                    <input type="text" class="crmb-category-name" name="category_name" value="<?php echo esc_attr($category->name); ?>" />
                                                </div>
                                                <div class="crmb-category-actions">
                                                    <a href="#" class="crmb-toggle-category dashicons dashicons-arrow-down"></a>
                                                    <a href="#" class="crmb-remove-category dashicons dashicons-trash"></a>
                                                </div>
                                            </div>
                                            
                                            <div class="crmb-category-content">
                                                <div class="crmb-items-container">
                                                    <?php if (!empty($category->items)) : ?>
                                                        <?php foreach ($category->items as $item) : ?>
                                                            <div class="crmb-item" data-item-id="<?php echo $item->id; ?>">
                                                                <div class="crmb-item-header">
                                                                    <span class="crmb-handle dashicons dashicons-menu"></span>
                                                                    <input type="text" class="crmb-item-name" name="item_name" value="<?php echo esc_attr($item->name); ?>" placeholder="<?php echo __('Nom du plat', 'codeon-restaurant-menu-builder'); ?>" />
                                                                    <input type="text" class="crmb-item-price" name="item_price" value="<?php echo esc_attr($item->price); ?>" placeholder="<?php echo __('Prix', 'codeon-restaurant-menu-builder'); ?>" />
                                                                    <input type="text" class="crmb-item-price-suffix" name="item_price_suffix" value="<?php echo esc_attr($item->price_suffix); ?>" placeholder="<?php echo __('Suffixe', 'codeon-restaurant-menu-builder'); ?>" />
                                                                    <a href="#" class="crmb-remove-item dashicons dashicons-trash"></a>
                                                                </div>
                                                                <div class="crmb-item-content">
                                                                    <textarea class="crmb-item-description" name="item_description" placeholder="<?php echo __('Description du plat', 'codeon-restaurant-menu-builder'); ?>"><?php echo esc_textarea($item->description); ?></textarea>
                                                                    
                                                                    <?php 
                                                                    $has_variations = !empty($item->variations);
                                                                    $variations = $has_variations ? maybe_unserialize($item->variations) : array();
                                                                    ?>
                                                                    
                                                                    <div class="crmb-item-variations-container">
                                                                        <label class="crmb-variations-toggle">
                                                                            <input type="checkbox" class="crmb-has-variations" name="has_variations" <?php checked($has_variations); ?> />
                                                                            <?php echo __('A des variantes (ex: tailles différentes)', 'codeon-restaurant-menu-builder'); ?>
                                                                        </label>
                                                                        
                                                                        <div class="crmb-variations-list" style="<?php echo $has_variations ? '' : 'display: none;'; ?>">
                                                                            <div class="crmb-variations-items">
                                                                                <?php if ($has_variations && is_array($variations)) : ?>
                                                                                    <?php foreach ($variations as $variation) : ?>
                                                                                        <div class="crmb-variation-item">
                                                                                            <input type="text" class="crmb-variation-name" name="variation_name" value="<?php echo esc_attr($variation['name']); ?>" placeholder="<?php echo __('Nom (ex: 30cm)', 'codeon-restaurant-menu-builder'); ?>" />
                                                                                            <input type="text" class="crmb-variation-price" name="variation_price" value="<?php echo esc_attr($variation['price']); ?>" placeholder="<?php echo __('Prix', 'codeon-restaurant-menu-builder'); ?>" />
                                                                                            <a href="#" class="crmb-remove-variation dashicons dashicons-trash"></a>
                                                                                        </div>
                                                                                    <?php endforeach; ?>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                            <button type="button" class="button crmb-add-variation"><?php echo __('Ajouter une Variante', 'codeon-restaurant-menu-builder'); ?></button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <div class="crmb-add-item-container">
                                                    <button type="button" class="button crmb-add-item"><?php echo __('Ajouter un Plat', 'codeon-restaurant-menu-builder'); ?></button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            
                            <div class="crmb-add-category-container">
                                <button type="button" class="button button-primary crmb-add-category"><?php echo __('Ajouter une Catégorie', 'codeon-restaurant-menu-builder'); ?></button>
                            </div>
                        </div>
                        
                        <div class="crmb-tab-content" id="crmb-tab-styles">
                            <div class="crmb-styles-container">
                                <h3><?php echo __('En-têtes des Catégories', 'codeon-restaurant-menu-builder'); ?></h3>
                                <div class="crmb-style-fields-group">
                                    <div class="crmb-field-group">
                                        <label for="crmb-header-background"><?php echo __('Couleur de Fond', 'codeon-restaurant-menu-builder'); ?></label>
                                        <input type="color" id="crmb-header-background" name="header_background" value="<?php echo esc_attr($settings['header_background']); ?>" />
                                    </div>
                                    
                                    <div class="crmb-field-group">
                                        <label for="crmb-header-text-color"><?php echo __('Couleur du Texte', 'codeon-restaurant-menu-builder'); ?></label>
                                        <input type="color" id="crmb-header-text-color" name="header_text_color" value="<?php echo esc_attr($settings['header_text_color']); ?>" />
                                    </div>
                                </div>
                                
                                <h3><?php echo __('Éléments du Menu', 'codeon-restaurant-menu-builder'); ?></h3>
                                <div class="crmb-style-fields-group">
                                    <div class="crmb-field-group">
                                        <label for="crmb-item-name-color"><?php echo __('Couleur du Nom', 'codeon-restaurant-menu-builder'); ?></label>
                                        <input type="color" id="crmb-item-name-color" name="item_name_color" value="<?php echo esc_attr($settings['item_name_color']); ?>" />
                                    </div>
                                    
                                    <div class="crmb-field-group">
                                        <label for="crmb-item-description-color"><?php echo __('Couleur de la Description', 'codeon-restaurant-menu-builder'); ?></label>
                                        <input type="color" id="crmb-item-description-color" name="item_description_color" value="<?php echo esc_attr($settings['item_description_color']); ?>" />
                                    </div>
                                    
                                    <div class="crmb-field-group">
                                        <label for="crmb-item-price-color"><?php echo __('Couleur du Prix', 'codeon-restaurant-menu-builder'); ?></label>
                                        <input type="color" id="crmb-item-price-color" name="item_price_color" value="<?php echo esc_attr($settings['item_price_color']); ?>" />
                                    </div>
                                </div>
                                
                                <h3><?php echo __('Mise en Page', 'codeon-restaurant-menu-builder'); ?></h3>
                                <div class="crmb-style-fields-group">
                                    <div class="crmb-field-group">
                                        <label for="crmb-layout-style"><?php echo __('Style de Mise en Page', 'codeon-restaurant-menu-builder'); ?></label>
                                        <select id="crmb-layout-style" name="layout_style">
                                            <option value="dots" <?php selected($settings['layout_style'], 'dots'); ?>><?php echo __('Points de Séparation', 'codeon-restaurant-menu-builder'); ?></option>
                                            <option value="line" <?php selected($settings['layout_style'], 'line'); ?>><?php echo __('Ligne Continue', 'codeon-restaurant-menu-builder'); ?></option>
                                            <option value="none" <?php selected($settings['layout_style'], 'none'); ?>><?php echo __('Aucun', 'codeon-restaurant-menu-builder'); ?></option>
                                        </select>
                                    </div>
                                    
                                    <div class="crmb-field-group">
                                        <label for="crmb-divider-style"><?php echo __('Style du Diviseur entre Catégories', 'codeon-restaurant-menu-builder'); ?></label>
                                        <select id="crmb-divider-style" name="divider_style">
                                            <option value="line" <?php selected($settings['divider_style'], 'line'); ?>><?php echo __('Ligne', 'codeon-restaurant-menu-builder'); ?></option>
                                            <option value="dashed" <?php selected($settings['divider_style'], 'dashed'); ?>><?php echo __('Tirets', 'codeon-restaurant-menu-builder'); ?></option>
                                            <option value="dots" <?php selected($settings['divider_style'], 'dots'); ?>><?php echo __('Points', 'codeon-restaurant-menu-builder'); ?></option>
                                            <option value="none" <?php selected($settings['divider_style'], 'none'); ?>><?php echo __('Aucun', 'codeon-restaurant-menu-builder'); ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="crmb-menu-form-footer">
                        <button type="submit" class="button button-primary crmb-save-menu"><?php echo __('Enregistrer le Menu', 'codeon-restaurant-menu-builder'); ?></button>
                        <a href="<?php echo admin_url('admin.php?page=codeon-restaurant-menu'); ?>" class="button"><?php echo __('Annuler', 'codeon-restaurant-menu-builder'); ?></a>
                        
                        <div class="crmb-save-message"></div>
                    </div>
                </div>
            </form>
            
            <div class="crmb-admin-footer">
                <p><?php echo __('Codeon Restaurant Menu Builder - Version', 'codeon-restaurant-menu-builder'); ?> <?php echo CRMB_VERSION; ?></p>
                <p><?php echo __('Développé par', 'codeon-restaurant-menu-builder'); ?> <a href="https://codeon.ch" target="_blank">Codeon</a></p>
            </div>
        </div>
        
        <!-- Templates pour JavaScript -->
        <script type="text/html" id="tmpl-crmb-category">
            <div class="crmb-category-item" data-category-id="new_{{data.index}}">
                <div class="crmb-category-header">
                    <div class="crmb-category-title">
                        <span class="crmb-handle dashicons dashicons-menu"></span>
                        <input type="text" class="crmb-category-name" name="category_name" value="<?php echo __('Nouvelle Catégorie', 'codeon-restaurant-menu-builder'); ?>" />
                    </div>
                    <div class="crmb-category-actions">
                        <a href="#" class="crmb-toggle-category dashicons dashicons-arrow-down"></a>
                        <a href="#" class="crmb-remove-category dashicons dashicons-trash"></a>
                    </div>
                </div>
                
                <div class="crmb-category-content">
                    <div class="crmb-items-container"></div>
                    
                    <div class="crmb-add-item-container">
                        <button type="button" class="button crmb-add-item"><?php echo __('Ajouter un Plat', 'codeon-restaurant-menu-builder'); ?></button>
                    </div>
                </div>
            </div>
        </script>
        
        <script type="text/html" id="tmpl-crmb-item">
            <div class="crmb-item" data-item-id="new_{{data.index}}">
                <div class="crmb-item-header">
                    <span class="crmb-handle dashicons dashicons-menu"></span>
                    <input type="text" class="crmb-item-name" name="item_name" value="<?php echo __('Nouveau Plat', 'codeon-restaurant-menu-builder'); ?>" placeholder="<?php echo __('Nom du plat', 'codeon-restaurant-menu-builder'); ?>" />
                    <input type="text" class="crmb-item-price" name="item_price" value="0" placeholder="<?php echo __('Prix', 'codeon-restaurant-menu-builder'); ?>" />
                    <input type="text" class="crmb-item-price-suffix" name="item_price_suffix" value=".-" placeholder="<?php echo __('Suffixe', 'codeon-restaurant-menu-builder'); ?>" />
                    <a href="#" class="crmb-remove-item dashicons dashicons-trash"></a>
                </div>
                <div class="crmb-item-content">
                    <textarea class="crmb-item-description" name="item_description" placeholder="<?php echo __('Description du plat', 'codeon-restaurant-menu-builder'); ?>"></textarea>
                    
                    <div class="crmb-item-variations-container">
                        <label class="crmb-variations-toggle">
                            <input type="checkbox" class="crmb-has-variations" name="has_variations" />
                            <?php echo __('A des variantes (ex: tailles différentes)', 'codeon-restaurant-menu-builder'); ?>
                        </label>
                        
                        <div class="crmb-variations-list" style="display: none;">
                            <div class="crmb-variations-items"></div>
                            <button type="button" class="button crmb-add-variation"><?php echo __('Ajouter une Variante', 'codeon-restaurant-menu-builder'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </script>
        
        <script type="text/html" id="tmpl-crmb-variation">
            <div class="crmb-variation-item">
                <input type="text" class="crmb-variation-name" name="variation_name" value="<?php echo __('Nouvelle Variante', 'codeon-restaurant-menu-builder'); ?>" placeholder="<?php echo __('Nom (ex: 30cm)', 'codeon-restaurant-menu-builder'); ?>" />
                <input type="text" class="crmb-variation-price" name="variation_price" value="0" placeholder="<?php echo __('Prix', 'codeon-restaurant-menu-builder'); ?>" />
                <a href="#" class="crmb-remove-variation dashicons dashicons-trash"></a>
            </div>
        </script>
        <?php
    }

    /**
     * AJAX: Enregistrer un menu
     */
    public function ajax_save_menu() {
        check_ajax_referer('crmb_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permission non accordée.', 'codeon-restaurant-menu-builder')));
            return;
        }
        
        $menu_id = isset($_POST['menu_id']) ? intval($_POST['menu_id']) : 0;
        $menu_name = isset($_POST['menu_name']) ? sanitize_text_field($_POST['menu_name']) : '';
        $menu_description = isset($_POST['menu_description']) ? wp_kses_post($_POST['menu_description']) : '';
        
        // Récupérer les paramètres de style
        $settings = array(
            'header_background' => isset($_POST['header_background']) ? sanitize_hex_color($_POST['header_background']) : '#000000',
            'header_text_color' => isset($_POST['header_text_color']) ? sanitize_hex_color($_POST['header_text_color']) : '#FFFFFF',
            'item_name_color' => isset($_POST['item_name_color']) ? sanitize_hex_color($_POST['item_name_color']) : '#000000',
            'item_description_color' => isset($_POST['item_description_color']) ? sanitize_hex_color($_POST['item_description_color']) : '#666666',
            'item_price_color' => isset($_POST['item_price_color']) ? sanitize_hex_color($_POST['item_price_color']) : '#000000',
            'layout_style' => isset($_POST['layout_style']) ? sanitize_text_field($_POST['layout_style']) : 'dots',
            'divider_style' => isset($_POST['divider_style']) ? sanitize_text_field($_POST['divider_style']) : 'line',
        );
        
        // Récupérer les catégories et les éléments
        $categories = isset($_POST['categories']) ? $_POST['categories'] : array();
        
        $menu_builder = new Codeon_Restaurant_Menu_Builder();
        
        // Commencer une transaction
        global $wpdb;
        $wpdb->query('START TRANSACTION');
        
        try {
            // Créer ou mettre à jour le menu
            if ($menu_id > 0) {
                $menu_builder->update_menu($menu_id, array(
                    'name' => $menu_name,
                    'description' => $menu_description,
                    'settings' => $settings,
                ));
            } else {
                $menu_id = $menu_builder->add_menu($menu_name, $menu_description, $settings);
            }
            
            // Supprimer les anciennes catégories et éléments pour ce menu
            $table_categories = $wpdb->prefix . 'crmb_menu_categories';
            $table_items = $wpdb->prefix . 'crmb_menu_items';
            
            // Récupérer toutes les catégories existantes
            $existing_categories = $wpdb->get_results($wpdb->prepare("SELECT id FROM $table_categories WHERE menu_id = %d", $menu_id));
            
            // Supprimer tous les éléments de ces catégories
            foreach ($existing_categories as $category) {
                $wpdb->delete($table_items, array('category_id' => $category->id));
            }
            
            // Supprimer les catégories
            $wpdb->delete($table_categories, array('menu_id' => $menu_id));
            
            // Ajouter les nouvelles catégories et éléments
            foreach ($categories as $index => $category) {
                // Insérer la catégorie
                $wpdb->insert(
                    $table_categories,
                    array(
                        'menu_id' => $menu_id,
                        'name' => sanitize_text_field($category['name']),
                        'position' => $index,
                    )
                );
                
                $category_id = $wpdb->insert_id;
                
                // Insérer les éléments
                if (!empty($category['items'])) {
                    foreach ($category['items'] as $item_index => $item) {
                        $item_data = array(
                            'category_id' => $category_id,
                            'name' => sanitize_text_field($item['name']),
                            'description' => wp_kses_post($item['description']),
                            'price' => floatval($item['price']),
                            'price_suffix' => sanitize_text_field($item['price_suffix']),
                            'position' => $item_index,
                        );
                        
                        // Ajouter les variations si présentes
                        if (isset($item['has_variations']) && $item['has_variations'] && !empty($item['variations'])) {
                            $variations = array();
                            foreach ($item['variations'] as $variation) {
                                $variations[] = array(
                                    'name' => sanitize_text_field($variation['name']),
                                    'price' => floatval($variation['price'])
                                );
                            }
                            $item_data['variations'] = maybe_serialize($variations);
                        }
                        
                        $wpdb->insert($table_items, $item_data);
                    }
                }
            }
            
            // Valider la transaction
            $wpdb->query('COMMIT');
            
            wp_send_json_success(array(
                'message' => __('Menu enregistré avec succès !', 'codeon-restaurant-menu-builder'),
                'menu_id' => $menu_id,
                'redirect' => admin_url('admin.php?page=codeon-restaurant-menu'),
            ));
            
        } catch (Exception $e) {
            // Annuler la transaction en cas d'erreur
            $wpdb->query('ROLLBACK');
            
            wp_send_json_error(array(
                'message' => __('Erreur lors de l\'enregistrement du menu : ', 'codeon-restaurant-menu-builder') . $e->getMessage(),
            ));
        }
    }

    /**
     * AJAX: Supprimer un menu
     */
    public function ajax_delete_menu() {
        check_ajax_referer('crmb_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permission non accordée.', 'codeon-restaurant-menu-builder')));
            return;
        }
        
        $menu_id = isset($_POST['menu_id']) ? intval($_POST['menu_id']) : 0;
        
        if ($menu_id <= 0) {
            wp_send_json_error(array('message' => __('ID de menu invalide.', 'codeon-restaurant-menu-builder')));
            return;
        }
        
        $menu_builder = new Codeon_Restaurant_Menu_Builder();
        $result = $menu_builder->delete_menu($menu_id);
        
        if ($result) {
            wp_send_json_success(array('message' => __('Menu supprimé avec succès !', 'codeon-restaurant-menu-builder')));
        } else {
            wp_send_json_error(array('message' => __('Erreur lors de la suppression du menu.', 'codeon-restaurant-menu-builder')));
        }
    }

    /**
     * AJAX: Récupérer un menu
     */
    public function ajax_get_menu() {
        check_ajax_referer('crmb_nonce', 'nonce');
        
        $menu_id = isset($_GET['menu_id']) ? intval($_GET['menu_id']) : 0;
        
        if ($menu_id <= 0) {
            wp_send_json_error(array('message' => __('ID de menu invalide.', 'codeon-restaurant-menu-builder')));
            return;
        }
        
        $menu_builder = new Codeon_Restaurant_Menu_Builder();
        $menu = $menu_builder->get_menu($menu_id);
        
        if ($menu) {
            wp_send_json_success(array('menu' => $menu));
        } else {
            wp_send_json_error(array('message' => __('Menu introuvable.', 'codeon-restaurant-menu-builder')));
        }
    }
}