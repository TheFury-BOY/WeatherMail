<?php
/*
Plugin Name: MyPlugin
Plugin URI:
Description: My first plugin on Wordpress
Version: 0.1
Author: TheFury-BOY
Author URI: http://github.com/TheFury-BOY
Licence: MIT
*/

class MyPlugin 
{

    public function __construct()
    {

        //add_filter('wp-title', 'myplugin_modify_page_title', 20);
        //Permet d'inclure d'autre fichier dans le plugin
        include_once plugin_dir_path( __FILE__ ).'/page_title.php';
        new MyPlugin_Page_Title();
        
        include_once plugin_dir_path( __FILE__ ).'/newsletter.php';
        new MyPlugin_Newsletter();
        
        include_once plugin_dir_path( __FILE__ ).'/newsletter_widget.php';
        new MyPlugin_Newsletter_Widget();

        include_once plugin_dir_path( __FILE__ ).'/recent.php';
        new MyPlugin_Recent();

        include_once plugin_dir_path( __FILE__ ).'/weather.php';
        new MyPlugin_Weather();

        include_once plugin_dir_path( __FILE__ ).'/weather_widget.php';
        new MyPlugin_Weather_Widget();

        //permet d’ajouter une fonction à appeler lors de l’activation d’un plugin particulier
        register_activation_hook(__FILE__, array('MyPlugin_Newsletter', 'install'));
        //méthode install() est statique, c'est le nom de la classe qui est donc utilisé

        //fonctionne de la même façon que l'installation
        register_uninstall_hook(__FILE__, array('Zero_Newsletter', 'uninstall'));

        //crée élément de premier niveau qui apparaitra dans le menu d'adm 
        //l'ajout se fait lors du chargement des menus de WP
        add_action('admin_menu', array($this, 'add_admin_menu'));
    }

    public function add_admin_menu()
    {
        //La création d'un menu s'effectue avec  la fonction add_menu_page()
        add_menu_page('MyPlugin', 'Myplugin', 'manage_options', 'myplugin', array($this, 'menu_html'));

        //permet de corrigé le doublon dans les sous-menu
        add_submenu_page('myplugin', 'Apercu', 'Apercu', 'manage_options', 'myplugin', array($this, 'menu_html'));
    }

    public function menu_html()
    {
        echo '<h1>'.get_admin_page_title().'</h1>';
        echo '<p>Bienvenue sur la page d\'accueil du plugin</p>';
    }
}

//Bien penser à instancier son plugin pour le rendre fonctionnel
new MyPlugin();