/**
 * CodeOn Restaurant Menu Builder
 * Menü Oluşturucu JavaScript
 *
 * @since      1.0.0
 */

(function($) {
    'use strict';

    // DOM hazır olduğunda
    $(document).ready(function() {
        // Sürükle ve bırak özellikleri
        initSortable();
        
        // Bölüm ekleme/kaldırma
        initSectionControls();
        
        // Öğe ekleme/kaldırma
        initItemControls();
    });

    /**
     * Sürükle-bırak özelliklerini başlat
     */
    function initSortable() {
        // Bölümleri sürükle-bırak
        $('.codeon-menu-structure').sortable({
            handle: '.codeon-drag-handle',
            placeholder: 'codeon-sortable-placeholder',
            update: function(event, ui) {
                reindexSections();
            }
        });
        
        // Öğeleri sürükle-bırak
        $('.codeon-section-items').sortable({
            handle: '.codeon-drag-handle',
            placeholder: 'codeon-sortable-placeholder',
            items: '.codeon-menu-item',
            update: function(event, ui) {
                reindexItems($(this).closest('.codeon-menu-section'));
            }
        });
    }
    
    /**
     * Bölüm kontrol düğmelerini başlat
     */
    function initSectionControls() {
        // Yeni bölüm ekle
        $('.add-section').on('click', function() {
            var sectionIndex = $('.codeon-menu-section').length;
            var template = $('#section-template').html();
            template = template.replace(/\{index\}/g, sectionIndex);
            $('.codeon-menu-structure').append(template);
            
            // Yeni bölüm için sürükle-bırak başlat
            $('.codeon-menu-structure').sortable('refresh');
            $('.codeon-section-items').sortable({
                handle: '.codeon-drag-handle',
                placeholder: 'codeon-sortable-placeholder',
                items: '.codeon-menu-item',
                update: function(event, ui) {
                    reindexItems($(this).closest('.codeon-menu-section'));
                }
            });
        });
        
        // Bölüm kaldır
        $(document).on('click', '.codeon-remove-section', function() {
            $(this).closest('.codeon-menu-section').remove();
            reindexSections();
        });
    }
    
    /**
     * Öğe kontrol düğmelerini başlat
     */
    function initItemControls() {
        // Yeni öğe ekle
        $(document).on('click', '.add-item', function() {
            var $section = $(this).closest('.codeon-menu-section');
            var sectionIndex = $section.data('index');
            var itemIndex = $section.find('.codeon-menu-item').length;
            
            // AJAX ile mevcut menü öğelerini al
            $.ajax({
                url: codeon_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'codeon_get_menu_items',
                    security: codeon_ajax.nonce
                },
                success: function(response) {
                    if (response.success) {
                        var itemsHtml = '';
                        $.each(response.data.items, function(i, item) {
                            itemsHtml += '<option value="' + item.id + '">' + item.title + ' (' + item.price + ' ' + item.price_postfix + ')</option>';
                        });
                        
                        var template = $('#item-template').html();
                        template = template.replace(/\{sectionIndex\}/g, sectionIndex);
                        template = template.replace(/\{itemIndex\}/g, itemIndex);
                        template = template.replace(/\{itemOptions\}/g, itemsHtml);
                        
                        $section.find('.add-item').before(template);
                        $section.find('.codeon-section-items').sortable('refresh');
                    } else {
                        alert(response.data.message);
                    }
                },
                error: function() {
                    alert('Menü öğeleri yüklenirken bir hata oluştu.');
                }
            });
        });
        
        // Öğe kaldır
        $(document).on('click', '.codeon-remove-item', function() {
            var $section = $(this).closest('.codeon-menu-section');
            $(this).closest('.codeon-menu-item').remove();
            reindexItems($section);
        });
    }
    
    /**
     * Bölüm indekslerini güncelle
     */
    function reindexSections() {
        $('.codeon-menu-section').each(function(sectionIndex) {
            var $section = $(this);
            $section.attr('data-index', sectionIndex);
            
            // Başlık ve açıklama alanlarını güncelle
            $section.find('.codeon-section-title input').attr('name', 'codeon_menu_structure[' + sectionIndex + '][title]');
            $section.find('.codeon-section-description textarea').attr('name', 'codeon_menu_structure[' + sectionIndex + '][description]');
            
            // Öğeleri güncelle
            reindexItems($section);
        });
    }
    
    /**
     * Öğe indekslerini güncelle
     */
    function reindexItems($section) {
        var sectionIndex = $section.data('index');
        $section.find('.codeon-menu-item').each(function(itemIndex) {
            var $item = $(this);
            $item.attr('data-index', itemIndex);
            
            // Öğe seçimini güncelle
            $item.find('select').attr('name', 'codeon_menu_structure[' + sectionIndex + '][items][' + itemIndex + '][id]');
        });
    }

})(jQuery);