<?php

// Set as Read
update_post_meta($wp_post->ID, "recall_isRead", "1");

// User Name
$username = get_post_meta($wp_post->ID, 'recall_firstname', true)." ";
$username.= get_post_meta($wp_post->ID, 'recall_lastname', true);

// Phone
$phone = get_post_meta($wp_post->ID, 'recall_prefix', true)." ";
$phone.= get_post_meta($wp_post->ID, 'recall_phone', true);

// Date
$date = get_post_meta($wp_post->ID, 'recall_date', true)." ";
$time = get_post_meta($wp_post->ID, 'recall_time', true);


// Message 
$message = get_post_meta($wp_post->ID, 'recall_message', true);
if (empty($message))
{
    $message = "<i>".__("No message", $this->config->Namespace)."</i>";
}
?>

<!-- User Name -->
<div>
    <i class="dashicons dashicons-admin-users"></i> <?= $username ?> 
</div>
<hr>

<!-- Phone -->
<div>
    <i class="dashicons dashicons-phone"></i> <?= $phone ?> 
</div>
<hr>

<!-- Date -->
<div>
    <i class="dashicons dashicons-calendar-alt"></i> <?= $date ?> 
</div>
<hr>

<!-- Message -->
<div>
    <i class="dashicons dashicons-email"></i> <?= nl2br($message) ?> 
</div>