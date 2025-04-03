/**
 * JavaScript d'administration pour Codeon Restaurant Menu Builder
 */
(function($) {
    'use strict';
    
    // Variables
    var categoriesCounter = 0;
    var itemsCounter = 0;
    var variationCounter = 0;
    
    /**
     * Initialisation
     */
    function init() {
        // Initialiser les compteurs
        categoriesCounter = $('.crmb-category-item').length;
        itemsCounter = $('.crmb-item').length;
        variationCounter = $('.crmb-variation-item').length;
        
        // Gestionnaire d'onglets
        $('.crmb-tab-link').on('click', function(e) {
            e.preventDefault();
            var tab = $(this).data('tab');
            
            $('.crmb-tab-link').removeClass('active');
            $(this).addClass('active');
            
            $('.crmb-tab-content').removeClass('active');
            $('#crmb-tab-' + tab).addClass('active');
        });
        
        // Ajouter une catégorie
        $('.crmb-add-category').on('click', function(e) {
            e.preventDefault();
            addCategory();
        });
        
        // Ajouter un élément
        $(document).on('click', '.crmb-add-item', function(e) {
            e.preventDefault();
            var $category = $(this).closest('.crmb-category-item');
            addItem($category);
        });
        
        // Basculer l'affichage d'une catégorie
        $(document).on('click', '.crmb-toggle-category', function(e) {
            e.preventDefault();
            var $category = $(this).closest('.crmb-category-item');
            $category.find('.crmb-category-content').slideToggle();
            $(this).toggleClass('dashicons-arrow-down dashicons-arrow-up');
        });
        
        // Supprimer une catégorie
        $(document).on('click', '.crmb-remove-category', function(e) {
            e.preventDefault();
            if (confirm(crmb_vars.strings.confirm_delete)) {
                $(this).closest('.crmb-category-item').remove();
            }
        });
        
        // Supprimer un élément
        $(document).on('click', '.crmb-remove-item', function(e) {
            e.preventDefault();
            $(this).closest('.crmb-item').remove();
        });
        
        // Toggle des variations
        $(document).on('change', '.crmb-has-variations', function() {
            var $container = $(this).closest('.crmb-item-content').find('.crmb-variations-list');
            if ($(this).is(':checked')) {
                $container.slideDown();
            } else {
                $container.slideUp();
            }
        });

        // Ajouter une variation
        $(document).on('click', '.crmb-add-variation', function(e) {
            e.preventDefault();
            var template = wp.template('crmb-variation');
            var data = {
                index: ++variationCounter
            };
            
            $(this).siblings('.crmb-variations-items').append(template(data));
        });

        // Supprimer une variation
        $(document).on('click', '.crmb-remove-variation', function(e) {
            e.preventDefault();
            $(this).closest('.crmb-variation-item').remove();
        });
        
        // Gérer le tri des catégories
        $('.crmb-categories-container').sortable({
            handle: '.crmb-handle',
            axis: 'y',
            placeholder: 'crmb-sortable-placeholder',
            forcePlaceholderSize: true
        });
        
        // Gérer le tri des éléments
        $(document).on('mouseover', '.crmb-items-container', function() {
            if (!$(this).hasClass('ui-sortable')) {
                $(this).sortable({
                    handle: '.crmb-handle',
                    axis: 'y',
                    placeholder: 'crmb-sortable-placeholder',
                    forcePlaceholderSize: true
                });
            }
        });
        
        // Soumission du formulaire
        $('#crmb-menu-form').on('submit', function(e) {
            e.preventDefault();
            saveMenu();
        });
        
        // Supprimer un menu dans la liste
        $('.crmb-delete-menu').on('click', function(e) {
            e.preventDefault();
            var menuId = $(this).data('menu-id');
            deleteMenu(menuId);
        });
    }
    
    /**
     * Ajouter une nouvelle catégorie
     */
    function addCategory() {
        var template = wp.template('crmb-category');
        var data = {
            index: ++categoriesCounter
        };
        
        $('.crmb-categories-container').append(template(data));
    }
    
    /**
     * Ajouter un nouvel élément à une catégorie
     */
    function addItem($category) {
        var template = wp.template('crmb-item');
        var data = {
            index: ++itemsCounter
        };
        
        $category.find('.crmb-items-container').append(template(data));
    }
    
    /**
     * Enregistrer le menu
     */
    function saveMenu() {
        var $form = $('#crmb-menu-form');
        var menuId = $form.data('menu-id');
        var menuName = $('#crmb-menu-name').val();
        var menuDescription = $('#crmb-menu-description').val();
        
        // Récupérer les paramètres de style
        var settings = {
            header_background: $('#crmb-header-background').val(),
            header_text_color: $('#crmb-header-text-color').val(),
            item_name_color: $('#crmb-item-name-color').val(),
            item_description_color: $('#crmb-item-description-color').val(),
            item_price_color: $('#crmb-item-price-color').val(),
            layout_style: $('#crmb-layout-style').val(),
            divider_style: $('#crmb-divider-style').val()
        };
        
        // Récupérer les catégories et les éléments
        var categories = [];
        
        $('.crmb-category-item').each(function() {
            var $category = $(this);
            var categoryName = $category.find('.crmb-category-name').val();
            var items = [];
            
            $category.find('.crmb-item').each(function() {
                var $item = $(this);
                var hasVariations = $item.find('.crmb-has-variations').is(':checked');
                var variations = [];
                
                if (hasVariations) {
                    $item.find('.crmb-variation-item').each(function() {
                        var $variation = $(this);
                        variations.push({
                            name: $variation.find('.crmb-variation-name').val(),
                            price: $variation.find('.crmb-variation-price').val()
                        });
                    });
                }
                
                items.push({
                    name: $item.find('.crmb-item-name').val(),
                    description: $item.find('.crmb-item-description').val(),
                    price: $item.find('.crmb-item-price').val(),
                    price_suffix: $item.find('.crmb-item-price-suffix').val(),
                    has_variations: hasVariations,
                    variations: variations
                });
            });
            
            categories.push({
                name: categoryName,
                items: items
            });
        });
        
        // Afficher un indicateur de chargement
        $('.crmb-save-message').removeClass('success error').text('Enregistrement en cours...');
        $('.crmb-save-menu').prop('disabled', true);
        
        // Envoyer les données via AJAX
        $.ajax({
            url: crmb_vars.ajax_url,
            type: 'POST',
            data: {
                action: 'crmb_save_menu',
                nonce: crmb_vars.nonce,
                menu_id: menuId,
                menu_name: menuName,
                menu_description: menuDescription,
                header_background: settings.header_background,
                header_text_color: settings.header_text_color,
                item_name_color: settings.item_name_color,
                item_description_color: settings.item_description_color,
                item_price_color: settings.item_price_color,
                layout_style: settings.layout_style,
                divider_style: settings.divider_style,
                categories: categories
            },
            success: function(response) {
                if (response.success) {
                    $('.crmb-save-message').addClass('success').text(response.data.message);
                    
                    // Rediriger vers la liste des menus après un court délai
                    setTimeout(function() {
                        window.location.href = response.data.redirect;
                    }, 1000);
                } else {
                    $('.crmb-save-message').addClass('error').text(response.data.message);
                    $('.crmb-save-menu').prop('disabled', false);
                }
            },
            error: function() {
                $('.crmb-save-message').addClass('error').text(crmb_vars.strings.save_error);
                $('.crmb-save-menu').prop('disabled', false);
            }
        });
    }
    
    /**
     * Supprimer un menu
     */
    function deleteMenu(menuId) {
        if (!confirm(crmb_vars.strings.confirm_delete)) {
            return;
        }
        
        $.ajax({
            url: crmb_vars.ajax_url,
            type: 'POST',
            data: {
                action: 'crmb_delete_menu',
                nonce: crmb_vars.nonce,
                menu_id: menuId
            },
            success: function(response) {
                if (response.success) {
                    // Recharger la page pour afficher la liste mise à jour
                    window.location.reload();
                } else {
                    alert(response.data.message);
                }
            },
            error: function() {
                alert(crmb_vars.strings.delete_error);
            }
        });
    }
    
    // Initialiser quand le document est prêt
    $(document).ready(function() {
        init();
    });
    
})(jQuery);