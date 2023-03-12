<?php
/*
Plugin Name: Login
Plugin URI: https://github.com/marouane216
Description: Plugin de login users personnalisé pour WordPress
Version: 1.0
Author: Marouane
Author URI: https://github.com/marouane216
*/

// Fonction pour afficher le formulaire de mon_plugin_shortcode_login
function mon_plugin_shortcode_login()
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

        .divSubmit {
            justify-content: center;
        }

        .Submit {
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

        .Submit:hover {
            color: aliceblue;
        }
    </style>
    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <div>
            <label class="labelForm" for="email">Email:</label>
            <input class="inputForm" type="email" name="email" id="email">
        </div>
        <div>
            <label class="labelForm" for="password">Password:</label>
            <input class="inputForm" type="password" name="password" id="password">
        </div>
        <div class="divSubmit">
            <input type="hidden" name="action" value="mon_plugin_login">
            <input class="Submit" type="submit" value="Envoyer">
        </div>
    </form>
    <?php
    return ob_get_clean();
}
add_shortcode('mon_plugin_form_login', 'mon_plugin_shortcode_login');

// Fonction pour login 
function mon_plugin_login()
{
    session_start();
    global $wpdb;
    $table_name = $wpdb->prefix . 'usersCreated';


    $email = $_POST['email'];
    $password = $_POST['password'];


    $results = $wpdb->get_results( "SELECT * FROM $table_name WHERE email = '$email'");

    $hashPassword = $results[0]->password;

    if (password_verify($password, $hashPassword)) {
        $_SESSION['user_id'] = $results[0]->id;
        $_SESSION['user_email'] = $results[0]->email;
        $_SESSION['user_name'] = $results[0]->userName;
        echo $_SESSION ['user_id'] . $_SESSION['user_email'].$_SESSION['user_name'] ;
        wp_redirect(home_url());
    } else {
        wp_redirect(home_url('/Login'));
    }
    
    
    exit;
}
add_action('admin_post_mon_plugin_login', 'mon_plugin_login');
?>