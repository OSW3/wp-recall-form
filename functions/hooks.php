<?php

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) 
{
    echo "Hi there!<br>Do you want to plug me ?<br>";
	echo "If you looking for more about me, you can read at http://osw3.net/wordpress/plugins/please-plug-me/";
    exit;
}


/**
 * Hook Exemple
 * --
 * This is an exemple of a hook function.
 * We declare a function code : "RecallForm_Hook_AjaxUrl"
 * 
 * Declare this hook in config.json at :
 *      "hooks": {
 *          "RecallForm_Hook_AjaxUrl": "wp"
 *      }
 * 
 * This hook is call at each event "wp"
 */
if (!function_exists('RecallForm_Hook_AjaxUrl'))
{
    function RecallForm_Hook_AjaxUrl()
    {
        if (!is_admin())
        {
            wp_enqueue_script( 'script', get_template_directory_uri().'/assets/js/recall.js', array('jQuery'), '1.0', true );            
            wp_localize_script('script', 'RecallForm_AjaxUrl', admin_url( 'admin-ajax.php' ) );
        }
    }
} 


/**
 * Front Form Submission
 */

// Define AJAX action
add_action( 'wp_ajax_RecallForm_Submission', 'RecallForm_Submission' );
add_action( 'wp_ajax_nopriv_RecallForm_Submission', 'RecallForm_Submission' );


if (!function_exists('RecallForm_Submission'))
{
    function RecallForm_Submission() 
    {
        $post_id = null;

        // Define the post type
        // config.registers.posts[x].type
        $post_type = 'recall';
        $options = get_option('recall_form');

        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            // -- RETRIEVE AND CONTROL
            // --

            // retrieve Plugin config and Custom post Schema
            $config = (object) apply_filters('recall_form', false);
            $schema = isset($config->Schemas->CustomPosts)
                ? $config->Schemas->CustomPosts
                : [];
                
            // Add $_POST data to $_REQUEST[PluginNamespace]
            $_REQUEST[$config->Namespace] = $_POST;
                
            // Format responses
            $responses = PPM::responses([
                "config" => $config,
                "schema" => $schema[$post_type]
            ]);
            
            // check response validation
            $validate = PPM::validate([
                "config" => $config,
                "post_type" => $post_type,
                "responses" => $responses
            ]);
            

            // -- SAVE DATA
            // --

            if ($validate->isValid)
            {
                $title = $responses['recall_firstname']->value." ";
                $title.= $responses['recall_lastname']->value;

                $post_id = wp_insert_post([
                    'post_title'    => wp_strip_all_tags( $title ),
                    'post_content'  => "",
                    'post_type'     => $post_type,
                    'post_status'   => 'private',
                    'comment_status' => 'closed',
                    'ping_status' => 'closed', 
                ]); 
                update_post_meta( $post_id, "recall_firstname", $responses['recall_firstname']->value );
                update_post_meta( $post_id, "recall_lastname", $responses['recall_lastname']->value );
                update_post_meta( $post_id, "recall_prefix", $responses['recall_prefix']->value );
                update_post_meta( $post_id, "recall_phone", $responses['recall_phone']->value );
                update_post_meta( $post_id, "recall_date", $responses['recall_date']->value );
                update_post_meta( $post_id, "recall_time", $responses['recall_time']->value );
                update_post_meta( $post_id, "recall_message", $responses['recall_message']->value );
                update_post_meta( $post_id, "recall_isRead", "0" );
            }

            if (isset($_SESSION[$post_type])) 
            { 
                unset($_SESSION[$post_type]); 
            }


            // -- SEND NOTIFICATION
            // --

            // Header
            $headers  = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type: text/html; charset=".get_bloginfo('charset')."" . "\r\n";
            $headers .= "From: ". get_option('blogname') ." <". get_option('admin_email') .">" . "\r\n";

            // Subject
            $subject = "Demande de rappel depuis ".get_option('blogname');

            // Body
            $body = "<h3>Demande de rappel</h3>"."<br>";
            $body.= $responses['recall_firstname']->value." ".$responses['recall_lastname']->value."<br>";
            $body.= $responses['recall_date']->value." - ".$responses['recall_time']->value."<br>";
            $body.= $responses['recall_phone']->value."<br>";
            $body.= "<br>";
            $body.= $responses['recall_message']->value."<br>";

            // Send
            $to = explode("\n", $options['notification_to']);
            foreach ($to as $key => $value) 
            {
                $to[$key] = preg_replace("/\[\[admin_email\]\]/", get_option('admin_email'), $value);
                wp_mail($to[$key], $subject, $body, $headers);
            }
            // wp_mail("hello@osw3.net", $subject, $body, $headers);
        }


        // -- CALLBACK MESSAGE
        // --

        if ($post_id)
        {
            header("Content-type:application/json");
            echo json_encode(array(
                "state" => "success",
                "message" => $options['success_message']
            ));
        } 
        else 
        {
            header("Content-type:application/json");
            echo json_encode(array(
                "state" => "danger",
                "message" => $options['error_message']
            ));
        }
        exit;
    }
}