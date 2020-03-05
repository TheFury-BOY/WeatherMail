<?php
class MyPlugin_Weather 
{

    public function __construct()
    {


        //Déclare le widget et le rend visible dans la liste proposée par WordPress
        add_action('widgets_init', function()
        {
            register_widget('MyPlugin_Weather_Widget');
        });

        //ajout de prio 20 pour que la fonc soit executée après elle du menu parent
        add_action('admin_menu', array($this, 'add_admin_menu'), 20);

    }

    public function add_admin_menu()
    {
        //permet l'ajout d'un sous-menu methode add_submenu_page
        $hook = add_submenu_page('myplugin', 'Weather', 'Weather', 'manage_options', 'myplugin_weather', array($this, 'menu_html'));

    }

    public function menu_html()
    {
        //fonction d'affichage du sous-menu
        echo '<h1>'.get_admin_page_title().'</h1>';
    }
}