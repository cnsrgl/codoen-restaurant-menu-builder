<?php
/**
 * Uluslararasılaştırma işlemlerini yöneten sınıf
 *
 * @since      1.0.0
 * @package    CodeOn_Restaurant_Menu
 */

class CodeOn_Restaurant_Menu_i18n {

    /**
     * Eklentinin metin alanı
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $domain    Eklentinin metin alanı
     */
    protected $domain;

    /**
     * Eklentinin metin alanını ayarla
     *
     * @since    1.0.0
     * @param    string    $domain    Eklentinin metin alanı
     */
    public function set_domain($domain) {
        $this->domain = $domain;
    }

    /**
     * Eklentinin çeviri dosyalarını yükle
     *
     * @since    1.0.0
     */
    public function load_plugin_textdomain() {
        load_plugin_textdomain(
            $this->domain,
            false,
            dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );
    }
}