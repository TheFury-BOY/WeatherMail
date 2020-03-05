<?php
class MyPlugin_Weather
{
    public function __construct()
    {
        add_shortcode('myplugin_weather', array($this, 'weather_html'));
    }

    //initialiser des valeurs par défauts pour les params du shortcode
    public function weather_html($atts, $content)
    {
        $atts = shortcode_atts(array('numberposts' => 5, 'order' => 'DESC'), $atts);
        $posts = get_posts($atts);

        $html = array();
        $html[] = $content;
        $html[] = '<ul>';
    
        $html[] = '<li><a href="'.get_permalink($post).'">'.$post->post_title.'</a></li>';
        $html[] = '</ul>';
        /* 
        Ligne 1 : on crée un nouvel objet de type  XMLHttpRequest  qui correspond à notre objet AJAX. C'est grâce à lui qu'on va créer et envoyer notre requête ;

        Ligne 2 : on demande à ouvrir une connexion vers un service web. C'est ici que l'on précise quelle méthode HTTP on souhaite, ainsi que l'URL du service web ;

        Ligne 3 : on envoie finalement la requête au service web. 
        */
        $html[] = '<script>
            var request = new XMLHttpRequest();
            request.open("GET", "http://url-service-web.com/api/users");
            request.send();
        </script>';

        echo implode('', $html);
    }
}