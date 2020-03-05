<?php
class MyPlugin_Weather_Widget extends WP_Widget
{   
    /**
     * widget
     */
    public function __construct()
    {
        parent::__construct('myplugin_weather', 'Weather', array('description' => 'Un Widget qui vous donne la météo.'));    
    }
    
    public function widget($args, $instance)
    {
        
        echo $args['before_widget'];
        echo $args['before_title'];
        echo apply_filters('widget_title', $instance['title']);
        echo $args['after_title'];
        ?>
        <section id="app">
            <h1>
                <span id="ville">Marseille</span>
                <span class="tooltip">Tapez une autre ville si vous le souhaitez</span>
            </h1>
            <i class="wi wi-day-rain"></i>
            <h2>
                <span id="temperature">25</span> °C (<span id="conditions">Ciel Dégagé</span>)
            </h2>
        </section>
        <?php
        echo $args['after_widget'];
    }

    public function form($instance)
    {
        //permet la modification des paramètre du plugin(utilise les méthode de WP_Widget)
        $title = isset($instance['title']) ? $instance['title'] : '';
        ?>
        <p>
            <label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo  $title; ?>" />
        </p>
        <?php
    }
}


function widget_enqueue_header() 
{
    wp_enqueue_script( 'weather', plugin_dir_url( __FILE__ ) . 'js/weather.js' );

    wp_enqueue_style( 'weather', plugin_dir_url( __FILE__ ) . 'css/weather.css' );
    
    wp_enqueue_style( 'weather-icons', plugin_dir_url( __FILE__ ) . 'lib/weather-icons/css/weather-icons.min.css' );
}
    
    add_action('wp_enqueue_scripts', 'widget_enqueue_header');

