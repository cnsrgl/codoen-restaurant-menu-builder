/**
 * CodeOn Restaurant Menu Builder
 * Genel görünüm JavaScript dosyası
 *
 * @since      1.0.0
 */

(function( $ ) {
    'use strict';

    /**
     * DOM hazır olduğunda çalışacak fonksiyonlar
     */
    $(document).ready(function() {
        // Menü öğesi hover etkileri
        initMenuItemEffects();
        
        // Menü filtreleme
        initMenuFiltering();
        
        // Görsel yüklemesi tamamlandığında masonry düzenleme
        initMenuLayout();
    });

    /**
     * Menü öğesi hover etkileri
     */
    function initMenuItemEffects() {
        $('.codeon-menu-item').hover(
            function() {
                $(this).addClass('hover');
            },
            function() {
                $(this).removeClass('hover');
            }
        );
    }

    /**
     * Menü filtreleme fonksiyonu
     */
    function initMenuFiltering() {
        // Filtreleme düğmeleri varsa
        if ($('.codeon-filter-buttons').length) {
            $('.codeon-filter-button').on('click', function() {
                // Aktif filtreleme düğmesini değiştir
                $('.codeon-filter-button').removeClass('active');
                $(this).addClass('active');
                
                var filter = $(this).data('filter');
                var $menuContainer = $(this).closest('.codeon-menu-container');
                
                // Tüm öğeleri göster (hepsi seçiliyse)
                if (filter === 'all') {
                    $menuContainer.find('.codeon-menu-item').show();
                } else {
                    // Önce tüm öğeleri gizle
                    $menuContainer.find('.codeon-menu-item').hide();
                    
                    // Seçilen filtreye uyan öğeleri göster
                    $menuContainer.find('.codeon-menu-item[data-category="' + filter + '"]').show();
                }
                
                // Boş bölümleri gizle
                $menuContainer.find('.codeon-menu-section').each(function() {
                    var $section = $(this);
                    var visibleItems = $section.find('.codeon-menu-item:visible').length;
                    
                    if (visibleItems === 0) {
                        $section.hide();
                    } else {
                        $section.show();
                    }
                });
            });
        }
    }

    /**
     * Menü düzeni (masonry, grid vb.)
     */
    function initMenuLayout() {
        // Görseller yüklendikten sonra masonry düzeni
        $('.codeon-menu-items').each(function() {
            var $menuItems = $(this);
            
            $menuItems.imagesLoaded(function() {
                // Masonry düzeni varsa
                if ($menuItems.hasClass('codeon-menu-masonry')) {
                    $menuItems.masonry({
                        itemSelector: '.codeon-menu-item',
                        columnWidth: '.codeon-menu-item',
                        percentPosition: true
                    });
                }
            });
        });
    }

})( jQuery );