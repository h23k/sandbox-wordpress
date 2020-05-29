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

// google/apiclient:^2.0
require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

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
      // echo 'const holidays = ' . $this->get_holidays_curl() . ';';
      // echo 'const holidays = ' . $this->get_holidays_gcal() . ';';
      echo 'myCalendar.setHoliday(holidays);';
      echo '</script>';
    }

    function set_array($holidays, $delimiter, $date, $str) {
      $ymd = preg_split($delimiter, $date);
      if (count($ymd) !== 3) {
        error_log($date);
        return $holidays;
      }
      $y = ltrim($ymd[0], '0');
      $m = ltrim($ymd[1], '0');
      $d = ltrim($ymd[2], '0');

      if (array_key_exists($y, $holidays)) {
        $months = $holidays[$y];
        if (array_key_exists($m, $months)) {
          $holidays[$y][$m][$d] = $str;
        } else {
          $holidays[$y][$m] = array(
            $d => $str
          );
        }
      } else {
        $holidays[$y] = array(
          $m => array(
            $d => $str
          )
        );
      }

      return $holidays;
    }

    function get_holidays() {
      $timeMin = new DateTime('-2 month');
      $timeMax = new DateTime('+1 month');

      $url = 'https://www8.cao.go.jp/chosei/shukujitsu/syukujitsu.csv';
      $file = new NoRewindIterator(new SplFileObject( $url ));

      $holidays = array();
      foreach ($file as $line_num => $line) {
        $line = preg_replace('/\R/', '', $line);
        $line = mb_convert_encoding($line, 'utf-8', 'sjis-win');

        $columns = preg_split('/,/', $line);
        if (count($columns) !== 2) {
          error_log($line);
          continue;
        }
        if (!strtotime($columns[0])) {
          error_log($columns[0]);
          continue;
        }
        $date = new DateTime($columns[0]);
        if ($date < $timeMin || $timeMax < $date) {
          continue;
        }

        $holidays = $this->set_array($holidays, '/\//', $columns[0], $columns[1]);
      }

      return json_encode($holidays, JSON_UNESCAPED_UNICODE);
    }

    // php curl
    function get_holidays_curl() {
      $timeMin = new DateTime('-2 month');
      $timeMax = new DateTime('+1 month');

      $url = 'https://www8.cao.go.jp/chosei/shukujitsu/syukujitsu.csv';
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $data = curl_exec($ch);
      // error_log(curl_errno($ch));
      // error_log(print_r(curl_getinfo($ch), true));
      curl_close($ch);

      $holidays = array();
      if (!empty($data)) {
        $data = mb_convert_encoding($data, 'utf-8', 'sjis-win');
        $data = preg_split('/\R/', $data);

        foreach ($data as $line) {
          $columns = preg_split('/,/', $line);
          if (count($columns) !== 2) {
            error_log($line);
            continue;
          }
          if (!strtotime($columns[0])) {
            error_log($columns[0]);
            continue;
          }
          $date = new DateTime($columns[0]);
          if ($date < $timeMin || $timeMax < $date) {
            continue;
          }

          $holidays = $this->set_array($holidays, '/\//', $columns[0], $columns[1]);
        }
      }

      return json_encode($holidays, JSON_UNESCAPED_UNICODE);
    }

    // Google Calendar API
    function get_holidays_gcal() {
      $timeMin = new DateTime('-2 month');
      $timeMax = new DateTime('+1 month');

      $credentials = plugin_dir_path(__FILE__) . 'credentials.json';
      $calendarId = 'ja.japanese#holiday@group.v.calendar.google.com';

      $client = new Google_Client();
      $client->setApplicationName('My WordPress');
      $client->setScopes(Google_Service_Calendar::CALENDAR_READONLY);
      $client->setAuthConfig($credentials);

      $service = new Google_Service_Calendar($client);
      $optParams = array(
          'orderBy' => 'startTime'
        , 'singleEvents' => true
        , 'timeMin' => $timeMin->format('c')
        , 'timeMax' => $timeMax->format('c')
      );
      $events = $service->events->listEvents($calendarId, $optParams);

      $holidays = array();
      foreach ($events->getItems() as $event) {
        $holidays = $this->set_array($holidays, '/-/', $event->start->date, $event->getSummary());
      }

      return json_encode($holidays, JSON_UNESCAPED_UNICODE);
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
