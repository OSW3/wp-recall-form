<?php

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) 
{
    echo "Hi there!<br>Do you want to plug me ?<br>";
	echo "If you looking for more about me, you can read at http://osw3.net/wordpress/plugins/please-plug-me/";
    exit;
}

if (!function_exists('RecallForm_GetNewMessages'))
{
    function RecallForm_GetNewMessages()
    {
        return new WP_Query([
            'post_type' => 'recal',

            // 'meta_query' => [
            //     'relation' => 'OR',
            //     [
            //         'key' => 'isRead',
            //         'value' => '1',
            //         'compare' => '!='
            //     ],
            //     [
            //         'key' => 'isRead',
            //         'compare' => 'NOT EXISTS'
            //     ]
            // ]
        ]);
    }
}