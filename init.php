<?php
/**
 * Plugin Name: Iteket Cookie Plugin
 * Description: The plugin will enable a cookie consent banner with Accept and Reject options.
 * Author: Kamy Kommunikasjon
 * Author URI: https://www.kamy.no
 * Version: 1.0
 * Author: Your Name
 * License: GPL2
 * @package WordPress
 * @author Kamy Kommunikasjon
 * @since 1.0.0
 */


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
?>


<?php
function cookie_notice() {
    // If user hasn't accepted or rejected the cookie notice, display it
    if (isset($_COOKIE['cookie_accepted']) && $_COOKIE['cookie_accepted'] === 'true') {
         return;
    }
    // It will not display if the user is in admin-portal
    if (strpos($_SERVER['REQUEST_URI'], '/admin-portal') !== false) {
        return;
    }

    /**
    * Enqueue the cookie notice CSS styles
    */
    function iteket_enqueue_styles() {
        wp_enqueue_style( 'iteket-cookie-notice', plugin_dir_url( __FILE__ ) . 'css/iteket-cookie-notice.css',array(), '0.1' );
        // wp_enqueue_style( 'clean_theme', ADMIN_PORTAL_PLUGIN_URL . 'theme-assets/css/app.css', array(), '3.3.8' );
    }
    echo '------1212--'.plugin_dir_url( __FILE__ ) . 'css/iteket-cookie-notice.css' ;
    add_action( 'wp_enqueue_scripts', 'iteket_enqueue_styles' );
?>
    
    
    <div id="cookie-notice">
        <h5 class="cli_messagebar_head">VI TAR DITT PERSONVERN PÅ ALVOR</h5>
        <p>Vi bruker cookies for å gi deg en bedre opplevelse på våre nettsider. Ved hjelp av cookies kan vi gi deg sikker tilgang og enkel tilgang til nyttig informasjon. Cookies hjelper oss med å analysere besøk på nettsidene.</p>
        <form method="post" id="cookie-form">
            <button type="submit" id="accept-cookie" name="cookie_action" value="accept" >Jeg godtar</button>
            <button type="submit" id="reject-cookie" name="cookie_action" value="reject">Godtar ikke</button>
        </form>
    </div>
    
<?php    

}//end of function cookie_notice

add_action('wp_footer', 'cookie_notice');
function iteket_enqueue_scripts() {
    wp_enqueue_script( 'iteket-cookie-script', plugin_dir_url( __FILE__ ) . 'js/iteket-cookie-script.js', array( 'jquery' ), '1.0', true );
    wp_localize_script( 'iteket-cookie-script', 'iteket_cookie_script_vars', array(
        'ajax_url' => admin_url( 'admin-ajax.php' )
    ));
}
add_action( 'wp_enqueue_scripts', 'iteket_enqueue_scripts' );

function set_cookie() {
    error_log('Error: ' . print_r($_POST, true));
    if (isset($_POST['cookie_action'])) {
        if ($_POST['cookie_action'] === 'accept') {
            // Set a cookie when user clicks the accept button
            setcookie('cookie_accepted', 'true', time() + (86400 * 30), '/');
            // Send an AJAX request to update the cookie notice on the page
            echo '<script>
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "'.admin_url('admin-ajax.php').'");
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onload = function () {
                    if (xhr.readyState === xhr.DONE && xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.status === "success") {
                            document.getElementById("cookie-notice").style.display = "none";
                        }
                    }
                };
                xhr.send("action=update_cookie_notice&status=accepted");
            </script>';
        } elseif ($_POST['cookie_action'] === 'reject') {
            // Set a cookie when user clicks the reject button
            setcookie('cookie_rejected', 'true', time() + (86400 * 30), '/');
            // Send an AJAX request to update the cookie notice on the page
            echo '<script>
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "'.admin_url('admin-ajax.php').'");
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onload = function () {
                    if (xhr.readyState === xhr.DONE && xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.status === "success") {
                            document.getElementById("cookie-notice").style.display = "none";
                        }
                    }
                };
                xhr.send("action=update_cookie_notice&status=rejected");
            </script>';
        }
        exit;
    }
}


add_action('init', 'set_cookie');
