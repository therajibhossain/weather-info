<?php

namespace WeatherInfo;

use WeatherInfo\Config;

// The widget class
class Widget extends \WP_Widget
{
    // Main constructor
    public function __construct()
    {
        parent::__construct(
            'weather_info_widget',
            __('Weather Info', 'text_domain'),
            array(
                'customize_selective_refresh' => true,
            )
        );
    }

    // The widget form (for the backend )
    public function form($instance)
    {
        // Set widget defaults
        $defaults = array(
            'title' => '',
            'text' => '',
        );

        // Parse current settings with defaults
        extract(wp_parse_args(( array )$instance, $defaults));

        $fields = '<p>';
        $fields .= $this->field(array('label', 'title', '', 'Widget Title'));
        $fields .= $this->field(array('text', 'title', $title));
        $fields .= '</p>';
        echo $fields;
    }

    private function field($args = array(), $select_options = array())
    {
        //array(type, name, value, label)
        $args = Config::sanitize_data($args);
        $id = $this->get_field_id($args[1]);
        $value = $args[2];
        $name = ($this->get_field_name($args[1]));
        $label = isset($args[3]) ? $args[3] : $args[1];
        $field = '';
        $common_attr = "name='$name' id='$id' class='widefat'";
        switch ($args[0]) {
            case "label":
                $field = "<label for='$id'>$label</label>";
                break;
            case "text":
                $field = "<input type='text' value='$value' $common_attr/>";
                break;
            case "textarea":
                $field = "<textarea $common_attr>$value</textarea>";
                break;
            case "checkbox":
                $checked = isset($value) && $value == 1 ? 'checked' : '';
                $field = "<input type='checkbox' $common_attr value='1' $checked/>";
                break;
            case "select":
                $select_options = Config::sanitize_data($select_options);
                $field = "<select $common_attr>";

                // Loop through options and add each one to the select dropdown
                foreach ($select_options as $key => $name) {
                    $field .= '<option value="' . esc_attr($key) . '" id="' . esc_attr($key) . '" ' . selected($value, $key, false) . '>' . $name . '</option>';
                }
                $field .= "</select>";
            default:

        }
        return $field;
    }

    // Update widget settings
    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = isset($new_instance['title']) ? wp_strip_all_tags($new_instance['title']) : '';
        $instance['text'] = isset($new_instance['text']) ? wp_strip_all_tags($new_instance['text']) : '';
        $instance['textarea'] = isset($new_instance['textarea']) ? wp_kses_post($new_instance['textarea']) : '';
        $instance['checkbox'] = isset($new_instance['checkbox']) ? 1 : false;
        $instance['select'] = isset($new_instance['select']) ? wp_strip_all_tags($new_instance['select']) : '';
        return $instance;
    }

    // Display the widget
    public function widget($args, $instance)
    {
        wp_enqueue_style("openweathermap-widget-right", GWW_STYLES . "openweathermap-widget-right.min.css");

        extract($args);
        // Check the widget options
        $title = isset($instance['title']) ? apply_filters('widget_title', $instance['title']) : '';
        $text = isset($instance['text']) ? $instance['text'] : '';

        // WordPress core before_widget hook (always include )
        $widget = $before_widget;

        // Display the widget
        $widget .= '<div class="widget-text wp_widget_plugin_box">';

        // Display widget title if defined
        if ($title) {
            $widget .= $before_title . $title . $after_title;
        }

        $widget .= $this->weather_info($instance);
        $widget .= '</div>';

        // WordPress core after_widget hook (always include )
        $widget .= $after_widget;
        echo $widget;
    }

    private function weather_info($instance)
    {
        $loc = Config::visitor_location();
        $region = Config::sanitize_data($loc['region_name'] . ", " . $loc['country_code']);
        $weather = new Front();
        $weather = $weather->index();
        $weather_current = $weather['current'];
        ob_start();
        ?>
        <div class="container-custom-card">
            <div id="openweathermap-widget-15" class="container-widget container-widget--15">
                <div id="container-openweathermap-widget-15">
                    <div class="widget-right weather-right--type1 widget-right--brown">
                        <div class="widget-right__header widget-right__header--brown">
                            <div class="widget-right__layout">
                                <div>
                                    <h2 class="widget-right__title"><?php echo $region; ?></h2>
                                    <p class="widget-right__description"><?php echo $weather_current['weather'][0]['description']; ?></p>
                                </div>
                            </div>
                            <img src="http://openweathermap.org/themes/openweathermap/assets/vendor/owm/img/widgets/<?php echo $weather_current['weather'][0]['icon']; ?>.png"
                                 width="128" height="128" alt="Weather in <?php echo $region; ?>"
                                 class="weather-right__icon weather-right__icon--type1">
                        </div>
                        <div class="weather-right weather-right--brown">
                            <div class="weather-right__layout">
                                <div class="weather-right__temperature"><?php echo $weather_current['temp']; ?>
                                    <span>°C</span></div>
                                <div class="weather-right__weather">
                                    <div class="weather-right-card">
                                        <table class="weather-right__table">
                                            <tbody>
                                            <tr class="weather-right__items">
                                                <th colspan="2" class="weather-right__item">Details</th>
                                            </tr>
                                            <tr class="weather-right__items">
                                                <td class="weather-right__item">Feels like</td>
                                                <td class="weather-right__item weather-right__feels"><?php echo $weather_current['feels_like']; ?>
                                                    <span>°C</span>
                                                </td>
                                            </tr>
                                            <tr class="weather-right__items">
                                                <td class="weather-right__item">Wind</td>
                                                <td class="weather-right__item weather-right__wind-speed"><?php echo $weather_current['wind_speed']; ?>
                                                    m/s
                                                </td>
                                            </tr>
                                            <tr class="weather-right-card__items">
                                                <td class="weather-right__item">Humidity</td>
                                                <td class="weather-right__item weather-right__humidity"><?php echo $weather_current['humidity']; ?>
                                                    %
                                                </td>
                                            </tr>
                                            <tr class="weather-right-card__items">
                                                <td class="weather-right__item">Pressure</td>
                                                <td class="weather-right__item weather-right__pressure"><?php echo $weather_current['pressure']; ?>
                                                    hPa
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="widget-right__footer widget-right__footer--brown">
                            <div class="widget-right__layout">
                                <div class="widget-right__date"><?php echo date("H:i F d", $weather_current['dt']); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}
//https://medium.com/@andrewchandev/weather-api-47a44354b54b
//https://erikflowers.github.io/weather-icons/

//https://gist.github.com/tbranyen/62d974681dea8ee0caa1
var dict = {
    '01d': 'wi-day-sunny',
    '02d': 'wi-day-cloudy',
    '03d': 'wi-cloud',
    '04d': 'wi-cloudy',
    '09d': 'wi-showers',
    '10d': 'wi-day-rain-mix',
    '11d': 'wi-thunderstorm',
    '13d': 'wi-snow',
    '50d': 'wi-fog',
    '01n': 'wi-night-clear',
    '02n': 'wi-night-alt-cloudy',
    '03n': 'wi-night-alt-cloudy-high',
    '04n': 'wi-cloudy',
    '09n': 'wi-night-alt-sprinkle',
    '10n': 'wi-night-alt-showers',
    '11n': 'wi-night-alt-thunderstorm',
    '13n': 'wi-night-alt-snow',
    '50n': 'wi-night-fog'
  };