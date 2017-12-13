<?php 
// the query
$query = RecallForm_GetNewMessages();

?>

<?php if (count($query->posts) > 0): ?>
    <ul class="widget-get-in-touch widget-list">
    <?php foreach($query->posts as $post): ?>
    <?php
        $message_link = add_query_arg( array(
            'post' => $post->ID,
            'action' => 'edit',
        ), admin_url('post.php') );
    ?>
        <li class="widget-list-item wp-clearfix">
            <div class="item-header wp-clearfix">
                <div class="sender">
                    <a href="<?= $message_link ?>">
                        <?= get_post_meta($post->ID, 'recall_firstname', true) ?> <?= get_post_meta($post->ID, 'recall_lastname', true) ?> <small>(<?= get_post_meta($post->ID, 'recall_phone', true) ?>)</small>
                    </a>
                </div>
                <div class="date">
                    <?= PPM::date("D d M Y H:i", $post->post_date) ?>
                </div>
            </div>
        </li>
    <?php endforeach; ?>
    </ul>
<?php else: ?>
    <?= __("There is no new message.", $this->config->Namespace); ?>
<?php endif; ?>