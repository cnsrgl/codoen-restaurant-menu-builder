/**
 * CodeOn Restaurant Menu Builder
 * Yönetici JavaScript
 *
 * @since      1.0.0
 */

(function($) {
    'use strict';

    // DOM hazır olduğunda
    $(document).ready(function() {
        // Renk seçici
        if ($.fn.wpColorPicker) {
            $('.color-picker').wpColorPicker();
        }

        // Meta kutusu özellikleri
        initMetaBoxes();
    });

    /**
     * Meta kutuları başlat
     */
    function initMetaBoxes() {
        // Varyasyon göster/gizle
        $('#codeon_menu_item_has_variations').on('change', function() {
            if ($(this).is(':checked')) {
                $('#codeon_variations_container').removeClass('hidden');
            } else {
                $('#codeon_variations_container').addClass('hidden');
            }
        });

        // Yeni varyasyon ekle
        $('.add-variation').on('click', function() {
            var index = $('.codeon-variation-item').length;
            var template = $('#variation-template').html();
            template = template.replace(/\{index\}/g, index);
            $('.codeon-variations-list').append(template);
        });

        // Varyasyon kaldır
        $(document).on('click', '.remove-variation', function() {
            $(this).closest('.codeon-variation-item').remove();
        });
    }

})(jQuery);