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

// if ( ! is_admin() ) { return; }
?>


<?php
function cookie_notice() {
    // If user hasn't accepted or rejected the cookie notice, display it
    if ($_COOKIE['cookie_accepted'] === 'true') {
         return;
    }
    // It will not display if the user is in admin-portal
    if (strpos($_SERVER['REQUEST_URI'], '/admin-portal') !== false) {
        return;
    }
?>
    <style>
        #cookie-notice{
            z-index: 1;
            position: fixed; 
            bottom: 0; 
            left: 0; 
            right: 0; 
            background-color: #fff; 
            color: #000; 
            padding: 10px; 
            text-align: center;
            box-shadow: 0 -1px 10px 0 rgb(172 171 171 / 30%);
        }
        #cookie-notice a{
            color: #000;
        }
        #cookie-notice button{
            background-color: #61a229;
            color: #fff;
            padding: 5px 10px;
            margin: 5px;
            border: 0;
        }
    </style>
    <div id="cookie-notice">
        <h5 class="cli_messagebar_head">VI TAR DITT PERSONVERN PÅ ALVOR</h5>
        <p>Vi bruker cookies for å gi deg en bedre opplevelse på våre nettsider. Ved hjelp av cookies kan vi gi deg sikker tilgang og enkel tilgang til nyttig informasjon. Cookies hjelper oss med å analysere besøk på nettsidene.</p>
        <form method="post">
            <button type="submit" id="accept-cookie" name="cookie_action" value="accept" >Jeg godtar</button>
            <button type="submit" id="reject-cookie" name="cookie_action" value="reject">Godtar ikke</button>
        </form>
    </div>
    <!--  Add JavaScript to hide the cookie notice when the user clicks on either button -->
    <script>
    document.getElementById("accept-cookie").addEventListener("click", function() {
        event.preventDefault(); // Prevent the form from submitting
        document.getElementById("cookie-notice").style.display = "none";        
        
    });
    document.getElementById("reject-cookie").addEventListener("click", function() {
        event.preventDefault(); // Prevent the form from submitting
        document.getElementById("cookie-notice").style.display = "none";
        
    });
    </script>
<?php    

}//end of function cookie_notice

add_action('wp_footer', 'cookie_notice');

function set_cookie() {
    if (isset($_POST['cookie_action'])) {
        if ($_POST['cookie_action'] === 'accept') {
            // Set a cookie when user clicks the accept button
            setcookie('cookie_accepted', 'true', time() + (86400 * 30), '/');
        } elseif ($_POST['cookie_action'] === 'reject') {
            // Set a cookie when user clicks the reject button
            setcookie('cookie_rejected', 'true', time() + (86400 * 30), '/');
        }
    }
}

add_action('init', 'set_cookie');
