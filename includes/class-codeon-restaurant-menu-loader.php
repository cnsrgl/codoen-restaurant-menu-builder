<?php
/**
 * Kancaları ve filtreleri tutan sınıf
 *
 * @since      1.0.0
 * @package    CodeOn_Restaurant_Menu
 */

class CodeOn_Restaurant_Menu_Loader {

    /**
     * Kaydedilen tüm eylem kancalarını tutar
     *
     * @since    1.0.0
     * @access   protected
     * @var      array    $actions    Kaydedilen işlemler
     */
    protected $actions;

    /**
     * Kaydedilen tüm filtreleri tutar
     *
     * @since    1.0.0
     * @access   protected
     * @var      array    $filters    Kaydedilen filtreler
     */
    protected $filters;

    /**
     * Kaydedilen tüm kısa kodları tutar
     *
     * @since    1.0.0
     * @access   protected
     * @var      array    $shortcodes    Kaydedilen kısa kodlar
     */
    protected $shortcodes;

    /**
     * Koleksiyonları başlat
     *
     * @since    1.0.0
     */
    public function __construct() {
        $this->actions = array();
        $this->filters = array();
        $this->shortcodes = array();
    }

    /**
     * Bir eylem kancası ekle
     *
     * @since    1.0.0
     * @param    string    $hook             Kanca adı
     * @param    object    $component        Kancayı kaydeden nesne
     * @param    string    $callback         Geri çağırma metodu
     * @param    int       $priority         Öncelik değeri
     * @param    int       $accepted_args    Kabul edilen argüman sayısı
     */
    public function add_action($hook, $component, $callback, $priority = 10, $accepted_args = 1) {
        $this->actions = $this->add($this->actions, $hook, $component, $callback, $priority, $accepted_args);
    }

    /**
     * Bir filtre ekle
     *
     * @since    1.0.0
     * @param    string    $hook             Kanca adı
     * @param    object    $component        Kancayı kaydeden nesne
     * @param    string    $callback         Geri çağırma metodu
     * @param    int       $priority         Öncelik değeri
     * @param    int       $accepted_args    Kabul edilen argüman sayısı
     */
    public function add_filter($hook, $component, $callback, $priority = 10, $accepted_args = 1) {
        $this->filters = $this->add($this->filters, $hook, $component, $callback, $priority, $accepted_args);
    }

    /**
     * Bir kısa kod ekle
     *
     * @since    1.0.0
     * @param    string    $tag              Kısa kod etiketi
     * @param    object    $component        Kısa kodu kaydeden nesne
     * @param    string    $callback         Geri çağırma metodu
     */
    public function add_shortcode($tag, $component, $callback) {
        $this->shortcodes = $this->add_shortcode_internal($this->shortcodes, $tag, $component, $callback);
    }

    /**
     * Koleksiyona yeni bir kanca ve geri çağırma ekle
     *
     * @since    1.0.0
     * @access   private
     * @param    array     $hooks            Kancaların tutulduğu dizi
     * @param    string    $hook             Kanca adı
     * @param    object    $component        Kancayı kaydeden nesne
     * @param    string    $callback         Geri çağırma metodu
     * @param    int       $priority         Öncelik değeri
     * @param    int       $accepted_args    Kabul edilen argüman sayısı
     * @return   array                       Kancaların bulunduğu dizi
     */
    private function add($hooks, $hook, $component, $callback, $priority, $accepted_args) {
        $hooks[] = array(
            'hook'          => $hook,
            'component'     => $component,
            'callback'      => $callback,
            'priority'      => $priority,
            'accepted_args' => $accepted_args
        );

        return $hooks;
    }

    /**
     * Koleksiyona yeni bir kısa kod ekle
     *
     * @since    1.0.0
     * @access   private
     * @param    array     $shortcodes       Kısa kodların tutulduğu dizi
     * @param    string    $tag              Kısa kod etiketi
     * @param    object    $component        Kısa kodu kaydeden nesne
     * @param    string    $callback         Geri çağırma metodu
     * @return   array                       Kısa kodların bulunduğu dizi
     */
    private function add_shortcode_internal($shortcodes, $tag, $component, $callback) {
        $shortcodes[] = array(
            'tag'           => $tag,
            'component'     => $component,
            'callback'      => $callback
        );

        return $shortcodes;
    }

    /**
     * Kaydedilen tüm eylem ve filtreleri WordPress'e kaydet
     *
     * @since    1.0.0
     */
    public function run() {
        foreach ($this->filters as $hook) {
            add_filter($hook['hook'], array($hook['component'], $hook['callback']), $hook['priority'], $hook['accepted_args']);
        }

        foreach ($this->actions as $hook) {
            add_action($hook['hook'], array($hook['component'], $hook['callback']), $hook['priority'], $hook['accepted_args']);
        }

        foreach ($this->shortcodes as $shortcode) {
            add_shortcode($shortcode['tag'], array($shortcode['component'], $shortcode['callback']));
        }
    }
}