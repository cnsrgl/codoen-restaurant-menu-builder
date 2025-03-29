<?php
/**
 * Özel post tiplerini tanımlayan sınıf
 *
 * @since      1.0.0
 * @package    CodeOn_Restaurant_Menu
 */

class CodeOn_Restaurant_Menu_Post_Types {

    /**
     * Özel post tiplerini kaydet
     *
     * @since    1.0.0
     */
    public function register_post_types() {
        // Menü post tipi
        $labels = array(
            'name'                  => _x('Menüler', 'Post Type General Name', 'codeon-restaurant-menu-builder'),
            'singular_name'         => _x('Menü', 'Post Type Singular Name', 'codeon-restaurant-menu-builder'),
            'menu_name'             => __('Restoran Menüleri', 'codeon-restaurant-menu-builder'),
            'name_admin_bar'        => __('Menü', 'codeon-restaurant-menu-builder'),
            'archives'              => __('Menü Arşivi', 'codeon-restaurant-menu-builder'),
            'attributes'            => __('Menü Özellikleri', 'codeon-restaurant-menu-builder'),
            'parent_item_colon'     => __('Üst Menü:', 'codeon-restaurant-menu-builder'),
            'all_items'             => __('Tüm Menüler', 'codeon-restaurant-menu-builder'),
            'add_new_item'          => __('Yeni Menü Ekle', 'codeon-restaurant-menu-builder'),
            'add_new'               => __('Yeni Ekle', 'codeon-restaurant-menu-builder'),
            'new_item'              => __('Yeni Menü', 'codeon-restaurant-menu-builder'),
            'edit_item'             => __('Menüyü Düzenle', 'codeon-restaurant-menu-builder'),
            'update_item'           => __('Menüyü Güncelle', 'codeon-restaurant-menu-builder'),
            'view_item'             => __('Menüyü Görüntüle', 'codeon-restaurant-menu-builder'),
            'view_items'            => __('Menüleri Görüntüle', 'codeon-restaurant-menu-builder'),
            'search_items'          => __('Menü Ara', 'codeon-restaurant-menu-builder'),
            'not_found'             => __('Bulunamadı', 'codeon-restaurant-menu-builder'),
            'not_found_in_trash'    => __('Çöp Kutusunda Bulunamadı', 'codeon-restaurant-menu-builder'),
            'featured_image'        => __('Öne Çıkan Görsel', 'codeon-restaurant-menu-builder'),
            'set_featured_image'    => __('Öne Çıkan Görseli Ayarla', 'codeon-restaurant-menu-builder'),
            'remove_featured_image' => __('Öne Çıkan Görseli Kaldır', 'codeon-restaurant-menu-builder'),
            'use_featured_image'    => __('Öne Çıkan Görsel Olarak Kullan', 'codeon-restaurant-menu-builder'),
            'insert_into_item'      => __('Menüye Ekle', 'codeon-restaurant-menu-builder'),
            'uploaded_to_this_item' => __('Bu Menüye Yüklendi', 'codeon-restaurant-menu-builder'),
            'items_list'            => __('Menü Listesi', 'codeon-restaurant-menu-builder'),
            'items_list_navigation' => __('Menü Listesi Navigasyonu', 'codeon-restaurant-menu-builder'),
            'filter_items_list'     => __('Menü Listesini Filtrele', 'codeon-restaurant-menu-builder'),
        );
        $args = array(
            'label'                 => __('Menü', 'codeon-restaurant-menu-builder'),
            'description'           => __('Restoran menüleri', 'codeon-restaurant-menu-builder'),
            'labels'                => $labels,
            'supports'              => array('title', 'editor', 'thumbnail', 'revisions'),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => false,
            'menu_position'         => 5,
            'menu_icon'             => 'dashicons-book-alt',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'page',
            'show_in_rest'          => true,
        );
        register_post_type('codeon_menu', $args);

        // Menü Öğesi post tipi
        $labels = array(
            'name'                  => _x('Menü Öğeleri', 'Post Type General Name', 'codeon-restaurant-menu-builder'),
            'singular_name'         => _x('Menü Öğesi', 'Post Type Singular Name', 'codeon-restaurant-menu-builder'),
            'menu_name'             => __('Menü Öğeleri', 'codeon-restaurant-menu-builder'),
            'name_admin_bar'        => __('Menü Öğesi', 'codeon-restaurant-menu-builder'),
            'archives'              => __('Öğe Arşivi', 'codeon-restaurant-menu-builder'),
            'attributes'            => __('Öğe Özellikleri', 'codeon-restaurant-menu-builder'),
            'parent_item_colon'     => __('Üst Öğe:', 'codeon-restaurant-menu-builder'),
            'all_items'             => __('Tüm Öğeler', 'codeon-restaurant-menu-builder'),
            'add_new_item'          => __('Yeni Öğe Ekle', 'codeon-restaurant-menu-builder'),
            'add_new'               => __('Yeni Ekle', 'codeon-restaurant-menu-builder'),
            'new_item'              => __('Yeni Öğe', 'codeon-restaurant-menu-builder'),
            'edit_item'             => __('Öğeyi Düzenle', 'codeon-restaurant-menu-builder'),
            'update_item'           => __('Öğeyi Güncelle', 'codeon-restaurant-menu-builder'),
            'view_item'             => __('Öğeyi Görüntüle', 'codeon-restaurant-menu-builder'),
            'view_items'            => __('Öğeleri Görüntüle', 'codeon-restaurant-menu-builder'),
            'search_items'          => __('Öğe Ara', 'codeon-restaurant-menu-builder'),
            'not_found'             => __('Bulunamadı', 'codeon-restaurant-menu-builder'),
            'not_found_in_trash'    => __('Çöp Kutusunda Bulunamadı', 'codeon-restaurant-menu-builder'),
            'featured_image'        => __('Öğe Görseli', 'codeon-restaurant-menu-builder'),
            'set_featured_image'    => __('Öğe Görselini Ayarla', 'codeon-restaurant-menu-builder'),
            'remove_featured_image' => __('Öğe Görselini Kaldır', 'codeon-restaurant-menu-builder'),
            'use_featured_image'    => __('Öğe Görseli Olarak Kullan', 'codeon-restaurant-menu-builder'),
            'insert_into_item'      => __('Öğeye Ekle', 'codeon-restaurant-menu-builder'),
            'uploaded_to_this_item' => __('Bu Öğeye Yüklendi', 'codeon-restaurant-menu-builder'),
            'items_list'            => __('Öğe Listesi', 'codeon-restaurant-menu-builder'),
            'items_list_navigation' => __('Öğe Listesi Navigasyonu', 'codeon-restaurant-menu-builder'),
            'filter_items_list'     => __('Öğe Listesini Filtrele', 'codeon-restaurant-menu-builder'),
        );
        $args = array(
            'label'                 => __('Menü Öğesi', 'codeon-restaurant-menu-builder'),
            'description'           => __('Menü öğeleri', 'codeon-restaurant-menu-builder'),
            'labels'                => $labels,
            'supports'              => array('title', 'editor', 'thumbnail', 'revisions'),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => false,
            'menu_position'         => 5,
            'menu_icon'             => 'dashicons-food',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => false,
            'can_export'            => true,
            'has_archive'           => false,
            'exclude_from_search'   => true,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
        );
        register_post_type('codeon_menu_item', $args);

        // Menü Kategorisi post tipi
        $labels = array(
            'name'                  => _x('Menü Kategorileri', 'Post Type General Name', 'codeon-restaurant-menu-builder'),
            'singular_name'         => _x('Menü Kategorisi', 'Post Type Singular Name', 'codeon-restaurant-menu-builder'),
            'menu_name'             => __('Menü Kategorileri', 'codeon-restaurant-menu-builder'),
            'name_admin_bar'        => __('Menü Kategorisi', 'codeon-restaurant-menu-builder'),
            'archives'              => __('Kategori Arşivi', 'codeon-restaurant-menu-builder'),
            'attributes'            => __('Kategori Özellikleri', 'codeon-restaurant-menu-builder'),
            'parent_item_colon'     => __('Üst Kategori:', 'codeon-restaurant-menu-builder'),
            'all_items'             => __('Tüm Kategoriler', 'codeon-restaurant-menu-builder'),
            'add_new_item'          => __('Yeni Kategori Ekle', 'codeon-restaurant-menu-builder'),
            'add_new'               => __('Yeni Ekle', 'codeon-restaurant-menu-builder'),
            'new_item'              => __('Yeni Kategori', 'codeon-restaurant-menu-builder'),
            'edit_item'             => __('Kategoriyi Düzenle', 'codeon-restaurant-menu-builder'),
            'update_item'           => __('Kategoriyi Güncelle', 'codeon-restaurant-menu-builder'),
            'view_item'             => __('Kategoriyi Görüntüle', 'codeon-restaurant-menu-builder'),
            'view_items'            => __('Kategorileri Görüntüle', 'codeon-restaurant-menu-builder'),
            'search_items'          => __('Kategori Ara', 'codeon-restaurant-menu-builder'),
            'not_found'             => __('Bulunamadı', 'codeon-restaurant-menu-builder'),
            'not_found_in_trash'    => __('Çöp Kutusunda Bulunamadı', 'codeon-restaurant-menu-builder'),
            'featured_image'        => __('Kategori Görseli', 'codeon-restaurant-menu-builder'),
            'set_featured_image'    => __('Kategori Görselini Ayarla', 'codeon-restaurant-menu-builder'),
            'remove_featured_image' => __('Kategori Görselini Kaldır', 'codeon-restaurant-menu-builder'),
            'use_featured_image'    => __('Kategori Görseli Olarak Kullan', 'codeon-restaurant-menu-builder'),
            'insert_into_item'      => __('Kategoriye Ekle', 'codeon-restaurant-menu-builder'),
            'uploaded_to_this_item' => __('Bu Kategoriye Yüklendi', 'codeon-restaurant-menu-builder'),
            'items_list'            => __('Kategori Listesi', 'codeon-restaurant-menu-builder'),
            'items_list_navigation' => __('Kategori Listesi Navigasyonu', 'codeon-restaurant-menu-builder'),
            'filter_items_list'     => __('Kategori Listesini Filtrele', 'codeon-restaurant-menu-builder'),
        );
        $args = array(
            'label'                 => __('Menü Kategorisi', 'codeon-restaurant-menu-builder'),
            'description'           => __('Menü kategorileri', 'codeon-restaurant-menu-builder'),
            'labels'                => $labels,
            'supports'              => array('title', 'editor', 'thumbnail'),
            'hierarchical'          => true,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => false,
            'menu_position'         => 5,
            'menu_icon'             => 'dashicons-category',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => false,
            'can_export'            => true,
            'has_archive'           => false,
            'exclude_from_search'   => true,
            'publicly_queryable'    => true,
            'capability_type'       => 'page',
            'show_in_rest'          => true,
        );
        register_post_type('codeon_menu_category', $args);
    }

    /**
     * Özel taksonomileri kaydet
     *
     * @since    1.0.0
     */
    public function register_taxonomies() {
        // Menü Tipi taksonomisi
        $labels = array(
            'name'                       => _x('Menü Tipleri', 'Taxonomy General Name', 'codeon-restaurant-menu-builder'),
            'singular_name'              => _x('Menü Tipi', 'Taxonomy Singular Name', 'codeon-restaurant-menu-builder'),
            'menu_name'                  => __('Menü Tipleri', 'codeon-restaurant-menu-builder'),
            'all_items'                  => __('Tüm Menü Tipleri', 'codeon-restaurant-menu-builder'),
            'parent_item'                => __('Üst Menü Tipi', 'codeon-restaurant-menu-builder'),
            'parent_item_colon'          => __('Üst Menü Tipi:', 'codeon-restaurant-menu-builder'),
            'new_item_name'              => __('Yeni Menü Tipi Adı', 'codeon-restaurant-menu-builder'),
            'add_new_item'               => __('Yeni Menü Tipi Ekle', 'codeon-restaurant-menu-builder'),
            'edit_item'                  => __('Menü Tipini Düzenle', 'codeon-restaurant-menu-builder'),
            'update_item'                => __('Menü Tipini Güncelle', 'codeon-restaurant-menu-builder'),
            'view_item'                  => __('Menü Tipini Görüntüle', 'codeon-restaurant-menu-builder'),
            'separate_items_with_commas' => __('Menü tiplerini virgülle ayırın', 'codeon-restaurant-menu-builder'),
            'add_or_remove_items'        => __('Menü Tipi Ekle veya Kaldır', 'codeon-restaurant-menu-builder'),
            'choose_from_most_used'      => __('En çok kullanılanlardan seçin', 'codeon-restaurant-menu-builder'),
            'popular_items'              => __('Popüler Menü Tipleri', 'codeon-restaurant-menu-builder'),
            'search_items'               => __('Menü Tipi Ara', 'codeon-restaurant-menu-builder'),
            'not_found'                  => __('Bulunamadı', 'codeon-restaurant-menu-builder'),
            'no_terms'                   => __('Menü Tipi Yok', 'codeon-restaurant-menu-builder'),
            'items_list'                 => __('Menü Tipi Listesi', 'codeon-restaurant-menu-builder'),
            'items_list_navigation'      => __('Menü Tipi Listesi Navigasyonu', 'codeon-restaurant-menu-builder'),
        );
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => true,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => false,
            'show_in_rest'               => true,
        );
        register_taxonomy('codeon_menu_type', array('codeon_menu'), $args);

        // Menü Öğe Tipi taksonomisi
        $labels = array(
            'name'                       => _x('Öğe Tipleri', 'Taxonomy General Name', 'codeon-restaurant-menu-builder'),
            'singular_name'              => _x('Öğe Tipi', 'Taxonomy Singular Name', 'codeon-restaurant-menu-builder'),
            'menu_name'                  => __('Öğe Tipleri', 'codeon-restaurant-menu-builder'),
            'all_items'                  => __('Tüm Öğe Tipleri', 'codeon-restaurant-menu-builder'),
            'parent_item'                => __('Üst Öğe Tipi', 'codeon-restaurant-menu-builder'),
            'parent_item_colon'          => __('Üst Öğe Tipi:', 'codeon-restaurant-menu-builder'),
            'new_item_name'              => __('Yeni Öğe Tipi Adı', 'codeon-restaurant-menu-builder'),
            'add_new_item'               => __('Yeni Öğe Tipi Ekle', 'codeon-restaurant-menu-builder'),
            'edit_item'                  => __('Öğe Tipini Düzenle', 'codeon-restaurant-menu-builder'),
            'update_item'                => __('Öğe Tipini Güncelle', 'codeon-restaurant-menu-builder'),
            'view_item'                  => __('Öğe Tipini Görüntüle', 'codeon-restaurant-menu-builder'),
            'separate_items_with_commas' => __('Öğe tiplerini virgülle ayırın', 'codeon-restaurant-menu-builder'),
            'add_or_remove_items'        => __('Öğe Tipi Ekle veya Kaldır', 'codeon-restaurant-menu-builder'),
            'choose_from_most_used'      => __('En çok kullanılanlardan seçin', 'codeon-restaurant-menu-builder'),
            'popular_items'              => __('Popüler Öğe Tipleri', 'codeon-restaurant-menu-builder'),
            'search_items'               => __('Öğe Tipi Ara', 'codeon-restaurant-menu-builder'),
            'not_found'                  => __('Bulunamadı', 'codeon-restaurant-menu-builder'),
            'no_terms'                   => __('Öğe Tipi Yok', 'codeon-restaurant-menu-builder'),
            'items_list'                 => __('Öğe Tipi Listesi', 'codeon-restaurant-menu-builder'),
            'items_list_navigation'      => __('Öğe Tipi Listesi Navigasyonu', 'codeon-restaurant-menu-builder'),
        );
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => true,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => false,
            'show_tagcloud'              => false,
            'show_in_rest'               => true,
        );
        register_taxonomy('codeon_item_type', array('codeon_menu_item'), $args);
    }
}