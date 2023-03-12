<?php
/*
Plugin Name: Register
Plugin URI: https://github.com/pluginsWordpress/registerLogin/blob/main/register.php
Description: Plugin de register users personnalisé pour WordPress
Version: 1.0
Author: Marouane
Author URI: https://github.com/marouane216
*/

// Fonction d'activation du plugin
function mon_plugin_activation()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'usersCreated';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        nom varchar(255) NOT NULL,
        prenom varchar(255) NOT NULL,
        email varchar(255) NOT NULL,
        password varchar(255) NOT NULL,
        userName varchar(255) NOT NULL,
        date datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'mon_plugin_activation');

// Fonction de désactivation du plugin
function mon_plugin_desactivation()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'usersCreated';

    $wpdb->query("DROP TABLE IF EXISTS $table_name");
}
register_deactivation_hook(__FILE__, 'mon_plugin_desactivation');

// Fonction pour afficher le formulaire de register
function mon_plugin_shortcode_register()
{
    ob_start();
    ?>
    <style>
        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
            width: 50%;
            margin: 0 25%;
        }

        form div {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
        }

        .Add_Nom_PrenomSubmit {
            background-color: #0d6efd;
            color: black;
            font-size: 1rem;
            width: 6rem;
            display: flex;
            justify-content: center;
            border: 1px solid;
            border-radius: 7px;
            cursor: pointer;
        }
        .Add_Nom_PrenomSubmit:hover{
            color: aliceblue;
        }
    </style>
    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <div>
            <label class="labelForm" for="username">Username:</label>
            <input class="inputForm" type="text" name="username" id="username">
        </div>
        <div>
            <label class="labelForm" for="email">Email:</label>
            <input class="inputForm" type="email" name="email" id="email">
        </div>
        <div>
            <label class="labelForm" for="password">Password:</label>
            <input class="inputForm" type="password" name="password" id="password">
        </div>
        <div id="Nom">

        </div>
        <div id="Prenom">

        </div>
        <div>
            <span class="Add_Nom_PrenomSubmit" id="btnNom" onclick="Add_Nom()">Add Nom</span>
            <span class="Add_Nom_PrenomSubmit" id="btnPrenom" onclick="Add_Prenom()">Add Prenom</span>
            <input type="hidden" name="action" value="mon_plugin_register">
            <input class="Add_Nom_PrenomSubmit" type="submit" value="Envoyer">
        </div>
    </form>
    <script>
        var divNom = document.getElementById('Nom');
        var divPrenom = document.getElementById('Prenom');
        var btnNom = document.getElementById('btnNom');
        var btnPrenom = document.getElementById('btnPrenom');
        var form;
        function Add_Nom() {
            form = `<label class="labelForm" for="nom">Nom:</label>
                        <input class="inputForm" type="text" name="nom">`;
            divNom.innerHTML = form;
            btnNom.style.display = 'none';
        }
        function Add_Prenom() {
            form = `<label class="labelForm" for="prenom">Prenom:</label>
                        <input class="inputForm" type="text" name="prenom"> `;
            divPrenom.innerHTML = form;
            btnPrenom.style.display = 'none';
        }
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('mon_plugin_form_register', 'mon_plugin_shortcode_register');

// Fonction pour enregistrer user dans la base de données
function mon_plugin_register()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'usersCreated';


    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password = password_hash($password, PASSWORD_DEFAULT);


    if (isset($_POST['nom'])) {
        $nom = $_POST['nom'];
    } else {
        $nom = ' ';
    }
    if (isset($_POST['prenom'])) {
        $prenom = $_POST['prenom'];
    } else {
        $prenom = ' ';
    }

    $wpdb->insert(
        $table_name,
        array(
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'username' => $username,
            'password' => $password
        )
    );

    wp_redirect(home_url('/Login'));
    exit;
}
add_action('admin_post_mon_plugin_register', 'mon_plugin_register');
