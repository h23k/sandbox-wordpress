<?php
/**
 * @package My First Plugin
 * @version 0.0.1
 */
/*
Plugin Name: My First Plugin
Plugin URI: http://wordpress.org/plugins/myfirstplugin/
Description: This is my first plugin.
Version: 0.0.1
Author: h23k
Author URI: https://localhost/
License: GPL2
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2020 h23k.
*/

if (!class_exists('My_First_Plugin')) {
  Class My_First_Plugin {
    public function get_hello() {
      return 'hello';
    }

    public function create_myfirstplugin_menu() {
      add_menu_page(
          'My First Plugin'
        , '私のカレンダー'
        , 'manage_options'
        , 'myfirstplugin_setting'
        , array($this, 'create_setting_page')
        , ''
        , 65
      );
    }
    public function create_setting_page() {
      echo '<h1 class="wp-heading-inline">私のカレンダーの設定</h1>';
    }

    public function output_style_script_tag() {
      wp_enqueue_style('plugin-myfirstplugin-style', plugins_url('/css/mycalendar.css', __FILE__));
      wp_enqueue_script('plugin-myfirstplugin-script', plugins_url('/js/mycalendar.js', __FILE__));
    }
    public function output_inline_script() {
      echo '<script type="text/javascript">';
      echo 'myCalendar.sayHello();';
      echo 'const holidays = ' . $this->get_holidays() . ';';
      echo 'myCalendar.setHoliday(holidays);';
      echo '</script>';
    }

    function get_holidays() {
      $url = 'https://www8.cao.go.jp/chosei/shukujitsu/syukujitsu.csv';
      $ca = plugin_dir_path(__FILE__) . 'cacert.pem';

      $ch = curl_init($url);
      // curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
      curl_setopt($ch, CURLOPT_CAINFO, $ca);
      // $result = curl_exec($ch);
      // error_log(curl_errno($ch));
      // error_log(print_r(curl_getinfo($ch), true));
      $result = 1;
      curl_close($ch);

      $str = '';
      if (!empty($result)) {
        // curlで取れないので。。。
        $str = '"2020": {"4": {"29": "昭和の日"}, "5": {"3": "憲法記念日", "4": "みどりの日", "5": "こどもの日", "6": "振替休日"}}';
      }
      return '{' . $str . '}';
    }
  }
  $MFP = new My_First_Plugin();
  // Add admin menu(私のカレンダー)
  add_action('admin_menu', array($MFP, 'create_myfirstplugin_menu'));
  // Add style and script tag
  add_action('wp_enqueue_scripts', array($MFP, 'output_style_script_tag'));
  add_action('wp_print_footer_scripts', array($MFP, 'output_inline_script'));

  // say hello
  function myfirstplugin_say_hello() {
    $MFP = new My_First_Plugin();
    echo $MFP->get_hello() . ', myfirstplugin';
  }
}
