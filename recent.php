<?php
class MyPlugin_Recent
{
    public function __construct()
    {
        add_shortcode('myplugin_recent_articles', array($this, 'recent_html'));
    }

    //initialiser des valeurs par défauts pour les params du shortcode
    public function recent_html($atts, $content)
    {
        $atts = shortcode_atts(array('numberposts' => 5, 'order' => 'DESC'), $atts);
        $posts = get_posts($atts);

        $html = array();
        $html[] = $content;
        $html[] = '<ul>';
        foreach ($posts as $post) {
            $html[] = '<li><a href="'.get_permalink($post).'">'.$post->post_title.'</a></li>';
        }
        $html[] = '</ul>';

        echo implode('', $html);
    }
}