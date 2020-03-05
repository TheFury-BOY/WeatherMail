<?php
class MyPlugin_Newsletter_Widget extends WP_Widget
{   
    /**
     * widget
     */
    public function __construct()
    {
        parent::__construct('myplugin_newsletter', 'Newsletter', array('description' => 'Un formulaire d\'inscription à la newsletter.'));
    }
    
    public function widget($args, $instance)
    {
        echo $args['before_widget'];
        echo $args['before_title'];
        echo apply_filters('widget_title', $instance['title']);
        echo $args['after_title'];
        ?>
        <form action="" method="post">
            <p>
                <label for="myplugin_newsletter_email">Votre email :</label>
                <input id="myplugin_newsletter_email" name="myplugin_newsletter_email" type="email"/>
            </p>
            <input type="submit"/>
        </form>
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