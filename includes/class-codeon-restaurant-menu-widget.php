<?php
/**
 * Widget Elementor pour Codeon Restaurant Menu Builder
 */
class Codeon_Restaurant_Menu_Widget extends \Elementor\Widget_Base {

    /**
     * Obtenir le nom du widget
     */
    public function get_name() {
        return 'codeon_restaurant_menu';
    }

    /**
     * Obtenir le titre du widget
     */
    public function get_title() {
        return __('Menu Restaurant', 'codeon-restaurant-menu-builder');
    }

    /**
     * Obtenir l'icône du widget
     */
    public function get_icon() {
        return 'eicon-menu-card';
    }

    /**
     * Obtenir les catégories du widget
     */
    public function get_categories() {
        return ['general', 'codeon-category'];
    }

    /**
     * Obtenir les mots-clés du widget
     */
    public function get_keywords() {
        return ['menu', 'restaurant', 'carte', 'nourriture', 'gastronomie', 'codeon'];
    }

    /**
     * Enregistrer les contrôles du widget
     */
    protected function _register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Contenu du Menu', 'codeon-restaurant-menu-builder'),
            ]
        );

        // Contrôle pour sélectionner un menu existant ou en créer un nouveau
        $this->add_control(
            'menu_source',
            [
                'label' => __('Source du Menu', 'codeon-restaurant-menu-builder'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'existing',
                'options' => [
                    'existing' => __('Menu Existant', 'codeon-restaurant-menu-builder'),
                    'new' => __('Nouveau Menu', 'codeon-restaurant-menu-builder'),
                ],
            ]
        );

        // Liste des menus existants
        $menu_options = $this->get_menu_options();
        $this->add_control(
            'menu_id',
            [
                'label' => __('Sélectionner un Menu', 'codeon-restaurant-menu-builder'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => count($menu_options) > 0 ? array_keys($menu_options)[0] : '',
                'options' => $menu_options,
                'condition' => [
                    'menu_source' => 'existing',
                ],
            ]
        );

        // Nouveau menu - nom
        $this->add_control(
            'new_menu_name',
            [
                'label' => __('Nom du Menu', 'codeon-restaurant-menu-builder'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Nouveau Menu', 'codeon-restaurant-menu-builder'),
                'condition' => [
                    'menu_source' => 'new',
                ],
            ]
        );

        // Nouveau menu - description
        $this->add_control(
            'new_menu_description',
            [
                'label' => __('Description', 'codeon-restaurant-menu-builder'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => '',
                'condition' => [
                    'menu_source' => 'new',
                ],
            ]
        );

        // Répéteur pour les catégories
        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'category_name',
            [
                'label' => __('Nom de la Catégorie', 'codeon-restaurant-menu-builder'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Nouvelle Catégorie', 'codeon-restaurant-menu-builder'),
            ]
        );

        // Répéteur pour les éléments à l'intérieur des catégories
        $item_repeater = new \Elementor\Repeater();

        $item_repeater->add_control(
            'item_name',
            [
                'label' => __('Nom du Plat', 'codeon-restaurant-menu-builder'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Nom du Plat', 'codeon-restaurant-menu-builder'),
            ]
        );

        $item_repeater->add_control(
            'item_description',
            [
                'label' => __('Description', 'codeon-restaurant-menu-builder'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => '',
            ]
        );

        $item_repeater->add_control(
            'item_price',
            [
                'label' => __('Prix', 'codeon-restaurant-menu-builder'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => '0',
                'min' => '0',
                'step' => '0.1',
            ]
        );

        $item_repeater->add_control(
            'item_price_suffix',
            [
                'label' => __('Suffixe de Prix', 'codeon-restaurant-menu-builder'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '.-',
                'description' => __('Exemple: ".-" ou "€"', 'codeon-restaurant-menu-builder'),
            ]
        );

        $item_repeater->add_control(
            'has_variations',
            [
                'label' => __('A des Variantes', 'codeon-restaurant-menu-builder'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Oui', 'codeon-restaurant-menu-builder'),
                'label_off' => __('Non', 'codeon-restaurant-menu-builder'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        // Variantes comme champs individuels
        $item_repeater->add_control(
            'variation1_name',
            [
                'label' => __('Variante 1 - Nom', 'codeon-restaurant-menu-builder'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('30cm', 'codeon-restaurant-menu-builder'),
                'condition' => [
                    'has_variations' => 'yes',
                ],
            ]
        );

        $item_repeater->add_control(
            'variation1_price',
            [
                'label' => __('Variante 1 - Prix', 'codeon-restaurant-menu-builder'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => '13',
                'min' => '0',
                'step' => '0.1',
                'condition' => [
                    'has_variations' => 'yes',
                ],
            ]
        );

        $item_repeater->add_control(
            'variation2_name',
            [
                'label' => __('Variante 2 - Nom', 'codeon-restaurant-menu-builder'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('40cm', 'codeon-restaurant-menu-builder'),
                'condition' => [
                    'has_variations' => 'yes',
                ],
            ]
        );

        $item_repeater->add_control(
            'variation2_price',
            [
                'label' => __('Variante 2 - Prix', 'codeon-restaurant-menu-builder'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => '18',
                'min' => '0',
                'step' => '0.1',
                'condition' => [
                    'has_variations' => 'yes',
                ],
            ]
        );

        $item_repeater->add_control(
            'variation3_name',
            [
                'label' => __('Variante 3 - Nom', 'codeon-restaurant-menu-builder'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('50cm', 'codeon-restaurant-menu-builder'),
                'condition' => [
                    'has_variations' => 'yes',
                ],
            ]
        );

        $item_repeater->add_control(
            'variation3_price',
            [
                'label' => __('Variante 3 - Prix', 'codeon-restaurant-menu-builder'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => '23',
                'min' => '0',
                'step' => '0.1',
                'condition' => [
                    'has_variations' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'items',
            [
                'label' => __('Plats', 'codeon-restaurant-menu-builder'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $item_repeater->get_controls(),
                'default' => [
                    [
                        'item_name' => __('Plat 1', 'codeon-restaurant-menu-builder'),
                        'item_description' => __('Description du plat', 'codeon-restaurant-menu-builder'),
                        'item_price' => '10',
                        'item_price_suffix' => '.-',
                        'has_variations' => 'no',
                    ],
                ],
                'title_field' => '{{{ item_name }}}',
                'prevent_empty' => false,
            ]
        );

        $this->add_control(
            'categories',
            [
                'label' => __('Catégories du Menu', 'codeon-restaurant-menu-builder'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'category_name' => __('Entrées', 'codeon-restaurant-menu-builder'),
                        'items' => [
                            [
                                'item_name' => __('Salade verte', 'codeon-restaurant-menu-builder'),
                                'item_price' => '6',
                                'item_price_suffix' => '.-',
                                'has_variations' => 'no',
                            ],
                        ],
                    ],
                ],
                'title_field' => '{{{ category_name }}}',
                'condition' => [
                    'menu_source' => 'new',
                ],
                'prevent_empty' => false,
            ]
        );

        $this->end_controls_section();

        // Section de style - En-tête des catégories
        $this->start_controls_section(
            'section_style_headers',
            [
                'label' => __('Style des En-têtes', 'codeon-restaurant-menu-builder'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'header_background',
            [
                'label' => __('Couleur de Fond', 'codeon-restaurant-menu-builder'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .crmb-category-header' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'header_text_color',
            [
                'label' => __('Couleur du Texte', 'codeon-restaurant-menu-builder'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .crmb-category-header' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'header_typography',
                'selector' => '{{WRAPPER}} .crmb-category-header',
            ]
        );

        $this->add_responsive_control(
            'header_padding',
            [
                'label' => __('Espacement Interne', 'codeon-restaurant-menu-builder'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .crmb-category-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => '10',
                    'right' => '15',
                    'bottom' => '10',
                    'left' => '15',
                    'unit' => 'px',
                    'isLinked' => false,
                ],
            ]
        );

        $this->end_controls_section();

        // Section de style - Éléments du menu
        $this->start_controls_section(
            'section_style_items',
            [
                'label' => __('Style des Éléments', 'codeon-restaurant-menu-builder'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'item_spacing',
            [
                'label' => __('Espacement entre les Éléments', 'codeon-restaurant-menu-builder'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .crmb-menu-item:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'item_name_color',
            [
                'label' => __('Couleur du Nom', 'codeon-restaurant-menu-builder'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .crmb-item-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'item_name_typography',
                'selector' => '{{WRAPPER}} .crmb-item-name',
            ]
        );

        $this->add_control(
            'item_description_color',
            [
                'label' => __('Couleur de la Description', 'codeon-restaurant-menu-builder'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#666666',
                'selectors' => [
                    '{{WRAPPER}} .crmb-item-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'item_description_typography',
                'selector' => '{{WRAPPER}} .crmb-item-description',
            ]
        );

        $this->add_control(
            'item_price_color',
            [
                'label' => __('Couleur du Prix', 'codeon-restaurant-menu-builder'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .crmb-item-price' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'item_price_typography',
                'selector' => '{{WRAPPER}} .crmb-item-price',
            ]
        );

        $this->end_controls_section();

        // Section de style - Mise en page
        $this->start_controls_section(
            'section_style_layout',
            [
                'label' => __('Mise en Page', 'codeon-restaurant-menu-builder'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'layout_style',
            [
                'label' => __('Style de Mise en Page', 'codeon-restaurant-menu-builder'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'dots',
                'options' => [
                    'dots' => __('Points de Séparation', 'codeon-restaurant-menu-builder'),
                    'line' => __('Ligne Continue', 'codeon-restaurant-menu-builder'),
                    'none' => __('Aucun', 'codeon-restaurant-menu-builder'),
                ],
            ]
        );

        $this->add_control(
            'separator_color',
            [
                'label' => __('Couleur du Séparateur', 'codeon-restaurant-menu-builder'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#CCCCCC',
                'selectors' => [
                    '{{WRAPPER}} .crmb-dots-separator' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .crmb-line-separator' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'layout_style!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'divider_style',
            [
                'label' => __('Style du Diviseur entre Catégories', 'codeon-restaurant-menu-builder'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'line',
                'options' => [
                    'line' => __('Ligne', 'codeon-restaurant-menu-builder'),
                    'dashed' => __('Tirets', 'codeon-restaurant-menu-builder'),
                    'dots' => __('Points', 'codeon-restaurant-menu-builder'),
                    'none' => __('Aucun', 'codeon-restaurant-menu-builder'),
                ],
            ]
        );

        $this->add_control(
            'divider_color',
            [
                'label' => __('Couleur du Diviseur', 'codeon-restaurant-menu-builder'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#CCCCCC',
                'selectors' => [
                    '{{WRAPPER}} .crmb-divider-line' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .crmb-divider-dashed' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .crmb-divider-dots' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'divider_style!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'menu_padding',
            [
                'label' => __('Espacement du Menu', 'codeon-restaurant-menu-builder'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .crmb-menu-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => '20',
                    'right' => '20',
                    'bottom' => '20',
                    'left' => '20',
                    'unit' => 'px',
                    'isLinked' => true,
                ],
            ]
        );

        $this->add_control(
            'menu_background',
            [
                'label' => __('Couleur de Fond du Menu', 'codeon-restaurant-menu-builder'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .crmb-menu-container' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Obtenir la liste des menus disponibles
     */
    private function get_menu_options() {
        $menu_builder = new Codeon_Restaurant_Menu_Builder();
        $menus = $menu_builder->get_menus();
        
        $options = [];
        if (!empty($menus)) {
            foreach ($menus as $menu) {
                $options[$menu->id] = $menu->name;
            }
        } else {
            $options[''] = __('Aucun menu disponible', 'codeon-restaurant-menu-builder');
        }
        
        return $options;
    }

    /**
     * Rendre le widget
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $menu_builder = new Codeon_Restaurant_Menu_Builder();
        
        // Créer un nouveau menu si nécessaire
        if ($settings['menu_source'] === 'new') {
            $menu_id = $menu_builder->add_menu(
                $settings['new_menu_name'],
                $settings['new_menu_description'],
                [
                    'header_background' => $settings['header_background'],
                    'header_text_color' => $settings['header_text_color'],
                    'item_name_color' => $settings['item_name_color'],
                    'item_description_color' => $settings['item_description_color'],
                    'item_price_color' => $settings['item_price_color'],
                    'layout_style' => $settings['layout_style'],
                    'divider_style' => $settings['divider_style'],
                ]
            );
            
            // Ajouter les catégories et les éléments
            global $wpdb;
            $table_categories = $wpdb->prefix . 'crmb_menu_categories';
            $table_items = $wpdb->prefix . 'crmb_menu_items';
            
            if (!empty($settings['categories'])) {
                foreach ($settings['categories'] as $index => $category) {
                    // Insérer la catégorie
                    $wpdb->insert(
                        $table_categories,
                        [
                            'menu_id' => $menu_id,
                            'name' => $category['category_name'],
                            'position' => $index,
                        ]
                    );
                    
                    $category_id = $wpdb->insert_id;
                    
                    // Insérer les éléments
                    if (!empty($category['items'])) {
                        foreach ($category['items'] as $item_index => $item) {
                            $item_data = [
                                'category_id' => $category_id,
                                'name' => $item['item_name'],
                                'description' => $item['item_description'],
                                'price' => $item['item_price'],
                                'price_suffix' => $item['item_price_suffix'],
                                'position' => $item_index,
                            ];
                            
                            // Varyasyonları işle
                            if (isset($item['has_variations']) && $item['has_variations'] === 'yes') {
                                $variations = [];
                                
                                // Sabit alanları kullanarak varyasyonları oluştur
                                if (!empty($item['variation1_name']) && isset($item['variation1_price'])) {
                                    $variations[] = [
                                        'name' => $item['variation1_name'],
                                        'price' => $item['variation1_price']
                                    ];
                                }
                                
                                if (!empty($item['variation2_name']) && isset($item['variation2_price'])) {
                                    $variations[] = [
                                        'name' => $item['variation2_name'],
                                        'price' => $item['variation2_price']
                                    ];
                                }
                                
                                if (!empty($item['variation3_name']) && isset($item['variation3_price'])) {
                                    $variations[] = [
                                        'name' => $item['variation3_name'],
                                        'price' => $item['variation3_price']
                                    ];
                                }
                                
                                if (!empty($variations)) {
                                    $item_data['variations'] = maybe_serialize($variations);
                                }
                            }
                            
                            $wpdb->insert($table_items, $item_data);
                        }
                    }
                }
            }
        } else {
            // Utiliser un menu existant
            $menu_id = $settings['menu_id'];
        }
        
        // Récupérer le menu complet
        $menu = $menu_builder->get_menu($menu_id);
        
        if (!$menu) {
            echo __('Menu introuvable ou mal configuré.', 'codeon-restaurant-menu-builder');
            return;
        }
        
        // Charger le modèle d'affichage
        $this->render_menu_template($menu, $settings);
    }

    /**
     * Rendre le template du menu
     */
    private function render_menu_template($menu, $settings) {
        $menu_settings = maybe_unserialize($menu->settings);
        
        // Mélanger les paramètres du menu avec ceux du widget
        $display_settings = array_merge(
            [
                'header_background' => '#000000',
                'header_text_color' => '#FFFFFF',
                'item_name_color' => '#000000',
                'item_description_color' => '#666666',
                'item_price_color' => '#000000',
                'layout_style' => 'dots',
                'divider_style' => 'line',
            ],
            is_array($menu_settings) ? $menu_settings : []
        );
        
        // Utiliser les paramètres du widget s'ils sont définis
        foreach ($display_settings as $key => $value) {
            if (isset($settings[$key]) && !empty($settings[$key])) {
                $display_settings[$key] = $settings[$key];
            }
        }
        
        ?>
        <div class="crmb-menu-container">
            <?php if (!empty($menu->name)) : ?>
                <h2 class="crmb-menu-title"><?php echo esc_html($menu->name); ?></h2>
            <?php endif; ?>
            
            <?php if (!empty($menu->description)) : ?>
                <div class="crmb-menu-description"><?php echo wp_kses_post($menu->description); ?></div>
            <?php endif; ?>
            
            <?php if (!empty($menu->categories)) : ?>
                <?php foreach ($menu->categories as $index => $category) : ?>
                    <div class="crmb-category">
                        <div class="crmb-category-header">
                            <?php echo esc_html($category->name); ?>
                        </div>
                        
                        <?php if (!empty($category->items)) : ?>
                            <div class="crmb-category-items">
                                <?php foreach ($category->items as $item) : ?>
                                    <div class="crmb-menu-item">
                                        <div class="crmb-item-header">
                                            <span class="crmb-item-name"><?php echo esc_html($item->name); ?></span>
                                            
                                            <?php if ($display_settings['layout_style'] !== 'none') : ?>
                                                <span class="crmb-separator crmb-<?php echo esc_attr($display_settings['layout_style']); ?>-separator"></span>
                                            <?php endif; ?>
                                            
                                            <span class="crmb-item-price">
                                                <?php echo esc_html(floatval($item->price) == intval($item->price) ? intval($item->price) : $item->price); 
                                                echo esc_html($item->price_suffix); ?>
                                            </span>
                                        </div>
                                        
                                        <?php if (!empty($item->description)) : ?>
                                            <div class="crmb-item-description">
                                                <?php echo esc_html($item->description); ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($item->variations)) : 
                                            $variations = maybe_unserialize($item->variations);
                                            if (is_array($variations) && !empty($variations)) : ?>
                                                <div class="crmb-item-variations">
                                                    <?php foreach ($variations as $variation) : ?>
                                                        <div class="crmb-variation">
                                                            <span class="crmb-variation-name"><?php echo esc_html($variation['name']); ?></span>
                                                            <?php if ($display_settings['layout_style'] !== 'none') : ?>
                                                                <span class="crmb-separator crmb-<?php echo esc_attr($display_settings['layout_style']); ?>-separator"></span>
                                                            <?php endif; ?>
                                                            <span class="crmb-variation-price">
                                                                <?php echo esc_html(floatval($variation['price']) == intval($variation['price']) ? intval($variation['price']) : $variation['price']); 
                                                                echo esc_html($item->price_suffix); ?>
                                                            </span>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                        <?php endif; ?>
                    </div>
                    <?php if ($index < count($menu->categories) - 1 && $display_settings['divider_style'] !== 'none') : ?>
                        <div class="crmb-divider crmb-divider-<?php echo esc_attr($display_settings['divider_style']); ?>"></div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Contenu du template pour l'éditeur
     */
    protected function content_template() {
        ?>
        <div class="crmb-menu-container">
            <# if ( settings.menu_source === 'new' && settings.new_menu_name ) { #>
                <h2 class="crmb-menu-title">{{{ settings.new_menu_name }}}</h2>
            <# } #>
            
            <# if ( settings.menu_source === 'new' && settings.new_menu_description ) { #>
                <div class="crmb-menu-description">{{{ settings.new_menu_description }}}</div>
            <# } #>
            
            <# if ( settings.menu_source === 'new' && settings.categories ) { #>
                <# _.each( settings.categories, function( category, index ) { #>
                    <div class="crmb-category">
                        <div class="crmb-category-header" style="background-color: {{ settings.header_background }}; color: {{ settings.header_text_color }};">
                            {{{ category.category_name }}}
                        </div>
                        
                        <# if ( category.items ) { #>
                            <div class="crmb-category-items">
                                <# _.each( category.items, function( item ) { #>
                                    <div class="crmb-menu-item">
                                        <div class="crmb-item-header">
                                            <span class="crmb-item-name" style="color: {{ settings.item_name_color }};">{{{ item.item_name }}}</span>
                                            
                                            <# if ( settings.layout_style !== 'none' ) { #>
                                                <span class="crmb-separator crmb-{{ settings.layout_style }}-separator"></span>
                                            <# } #>
                                            
                                            <span class="crmb-item-price" style="color: {{ settings.item_price_color }};">
                                                <# if(parseFloat(item.item_price) % 1 === 0) { #>
                                                    {{{ parseInt(item.item_price) + item.item_price_suffix }}}
                                                <# } else { #>
                                                    {{{ item.item_price + item.item_price_suffix }}}
                                                <# } #>
                                            </span>
                                        </div>
                                        
                                        <# if ( item.item_description ) { #>
                                            <div class="crmb-item-description" style="color: {{ settings.item_description_color }};">
                                                {{{ item.item_description }}}
                                            </div>
                                        <# } #>
                                        
                                        <# if ( item.has_variations === 'yes' ) { #>
                                            <div class="crmb-item-variations">
                                                <# if ( item.variation1_name && item.variation1_price !== undefined ) { #>
                                                    <div class="crmb-variation">
                                                        <span class="crmb-variation-name">{{{ item.variation1_name }}}</span>
                                                        <# if ( settings.layout_style !== 'none' ) { #>
                                                            <span class="crmb-separator crmb-{{ settings.layout_style }}-separator"></span>
                                                        <# } #>
                                                        <span class="crmb-variation-price">
                                                            <# if(parseFloat(item.variation1_price) % 1 === 0) { #>
                                                                {{{ parseInt(item.variation1_price) + item.item_price_suffix }}}
                                                            <# } else { #>
                                                                {{{ item.variation1_price + item.item_price_suffix }}}
                                                            <# } #>
                                                        </span>
                                                    </div>
                                                <# } #>
                                                
                                                <# if ( item.variation2_name && item.variation2_price !== undefined ) { #>
                                                    <div class="crmb-variation">
                                                        <span class="crmb-variation-name">{{{ item.variation2_name }}}</span>
                                                        <# if ( settings.layout_style !== 'none' ) { #>
                                                            <span class="crmb-separator crmb-{{ settings.layout_style }}-separator"></span>
                                                        <# } #>
                                                        <span class="crmb-variation-price">
                                                            <# if(parseFloat(item.variation2_price) % 1 === 0) { #>
                                                                {{{ parseInt(item.variation2_price) + item.item_price_suffix }}}
                                                            <# } else { #>
                                                                {{{ item.variation2_price + item.item_price_suffix }}}
                                                            <# } #>
                                                        </span>
                                                    </div>
                                                <# } #>
                                                
                                                <# if ( item.variation3_name && item.variation3_price !== undefined ) { #>
                                                    <div class="crmb-variation">
                                                        <span class="crmb-variation-name">{{{ item.variation3_name }}}</span>
                                                        <# if ( settings.layout_style !== 'none' ) { #>
                                                            <span class="crmb-separator crmb-{{ settings.layout_style }}-separator"></span>
                                                        <# } #>
                                                        <span class="crmb-variation-price">
                                                            <# if(parseFloat(item.variation3_price) % 1 === 0) { #>
                                                                {{{ parseInt(item.variation3_price) + item.item_price_suffix }}}
                                                            <# } else { #>
                                                                {{{ item.variation3_price + item.item_price_suffix }}}
                                                            <# } #>
                                                        </span>
                                                    </div>
                                                <# } #>
                                            </div>
                                        <# } #>
                                    </div>
                                <# }); #>
                            </div>
                        <# } #>
                    </div>
                    
                    <# if ( index < settings.categories.length - 1 && settings.divider_style !== 'none' ) { #>
                        <div class="crmb-divider crmb-divider-{{ settings.divider_style }}"></div>
                    <# } #>
                <# }); #>
            <# } else { #>
                <div><?php echo __('Aperçu du menu sélectionné sera affiché ici', 'codeon-restaurant-menu-builder'); ?></div>
            <# } #>
        </div>
        <?php
    }
}