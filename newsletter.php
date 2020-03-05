<?php
class MyPlugin_Newsletter 
{

    public function __construct()
    {
        //connecte la methode save_email à l'affichage
        //wp_loaded correspond à l’instant où l’application est chargée et s’apprête à effectuer le rendu du thème pour la page demandée.
        add_action('wp_loaded', array($this, 'save_email'));

        //Impératif à l'initialisation du système d'adm (ev admin_init)
        add_action('admin_init', array($this, 'register_settings'));

        //Déclare le widget et le rend visible dans la liste proposée par WordPress
        add_action('widgets_init', function()
        {
            register_widget('MyPlugin_Newsletter_Widget');
        });

        //ajout de prio 20 pour que la fonc soit executée après elle du menu parent
        add_action('admin_menu', array($this, 'add_admin_menu'), 20);

    }

    public function add_admin_menu()
    {
        //permet l'ajout d'un sous-menu methode add_submenu_page
        $hook = add_submenu_page('myplugin', 'Newsletter', 'Newsletter', 'manage_options', 'myplugin_newsletter', array($this, 'menu_html'));

        //ajout de l'action send_newsletter (tracking)
        add_action('load-'.$hook, array($this, 'process_action'));
    }

    public static function install()
    {
        //effectue toute les actions nécessaire lors de l'activation du plugin
        global $wpdb;

        //$wpdb->prefix contient le préfixe donné à l'instalation de Wordpress
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}myplugin_newsletter_email (id INT AUTO_INCREMENT PRIMARY KEY, email VARCHAR(255) NOT NULL);");
    }

    public static function uninstall()
    {
        global $wpdb;

        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}myplugin_newsletter_email;");
    }

    public function save_email()
    {
        // vérifie la présence de la données dans la base, utilise la méthode get_row
        if (isset($_POST['myplugin_newsletter_email']) && !empty($_POST['myplugin_newsletter_email'])) {
        
            global $wpdb;
            $email = $_POST['myplugin_newsletter_email'];

            //vérifie le format de l'email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailErr = "Invalid email format";
            } else {
                $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}myplugin_newsletter_email WHERE email = '$email'");
            
                //insert les données si la requète précédente ne récupère aucun résultat.
                if (is_null($row)) {
                    $wpdb->insert("{$wpdb->prefix}myplugin_newsletter_email", array('email' => $email));
                }
            }
        }
    }

    public function menu_html()
    {
        //fonction d'affichage du sous-menu
        echo '<h1>'.get_admin_page_title().'</h1>';
        ?>
        <form method="post" action="options.php">
            <?php 
                //définit le champs des options
                settings_fields('myplugin_newsletter_settings');
            ?>
            <?php
                //active l'affichage de la section argument: id de la page
                do_settings_sections('myplugin_newsletter_settings') 
            ?>

            <?php submit_button(); ?>
        </form>
        <form method="post" action="">
            <!--Le formulaire contient simplement un champ caché qui sera envoyé en POST afin de demander l’envoi des emails. Au clic sur le bouton, la page actuelle sera rafraîchie avec le paramètre send_newsletter présent dans la requête.-->
            <input type="hidden" name="send_newsletter" value="1"/>
            <?php submit_button('Envoyer la newsletter') ?>
        </form>
        <?php
        /*
        aucune valeur n'est sauvegardée dans la base de données (le champ apparaît vide lorsque vous revenez sur la page). Ceci est du au fait que nous n'avons pas encore autorisé WordPress à enregistrer la valeur de l'option myplugin_newsleter_sender. 
        */
    }

    public function register_settings()
    {
        register_setting('myplugin_newsletter_settings', 'myplugin_newsletter_sender');
        register_setting('myplugin_newsletter_settings', 'myplugin_newsletter_object');
        register_setting('myplugin_newsletter_settings', 'myplugin_newsletter_content');

        /*
        Permet de nouveau champs dans le formulaire sans devoir modifier la méthode de rendu, en ajoutant juste une nouvelle section

        Les paramètres de cette fonction sont l'identifiant de la section, son titre, une fonction appelée au début du rendu de la section et enfin la page sur laquelle devra s'afficher la section.
        */
        add_settings_section('myplugin_newsletter_section', 'Newsletter parameters', array($this, 'section_html'), 'myplugin_newsletter_settings');
        add_settings_field('myplugin_newsletter_sender', 'Expéditeur', array($this, 'sender_html'), 'myplugin_newsletter_settings', 'myplugin_newsletter_section');
        add_settings_field('myplugin_newsletter_object', 'Objet', array($this, 'object_html'), 'myplugin_newsletter_settings', 'myplugin_newsletter_section');
        add_settings_field('myplugin_newsletter_content', 'Contenu', array($this, 'content_html'), 'myplugin_newsletter_settings', 'myplugin_newsletter_section');
    }

    public function section_html()
    {
        //méthode section_html() affiche une description de la section
        echo '<h4>Renseignez les paramètres d\'envoi de la newsletter.</h4><br/>';
    }

    public function sender_html()
    {
        ?>
        <input type="text" name="myplugin_newsletter_sender" value="<?php echo get_option('myplugin_newsletter_sender')?>"/>
        <?php
    }

    public function object_html()
    {   
        ?>
        <input type="text" name="myplugin_newsletter_object" value="<?php echo get_option('myplugin_newsletter_object')?>"/>
        <?php
    }

    public function content_html()
    {   
        ?>
        <textarea name="myplugin_newsletter_content"><?php echo get_option('myplugin_newsletter_content')?></textarea>
        <?php
    }

    public function process_action()
    {
        //permet de vérifier la présence du paramètre send_newsletter avant d’appeler la méthode d’envoi.
        if (isset($_POST['send_newsletter'])) {
        $this->send_newsletter();
        }
    }

    public function send_newsletter()
    {
        /* 
        récupére les paramètres de configuration choisis ainsi que la liste des emails, puis appelle la fonction wp_mail() qui permet de construire un email.
        */
        global $wpdb;
        $recipients = $wpdb->get_results("SELECT email FROM {$wpdb->prefix}myplugin_newsletter_email");
        $object = get_option('myplugin_newsletter_object', 'Newsletter');
        $content = get_option('myplugin_newsletter_content', 'Mon contenu');
        $sender = get_option('myplugin_newsletter_sender', 'no-reply@example.com');
        $header = array('From: '.$sender);

        foreach ($recipients as $_recipient) {
            $result = wp_mail($_recipient->email, $object, $content, $header);
        }
    }
}