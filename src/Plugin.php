<?php

defined('ABSPATH') || exit;

class DLContentExpiryPlugin
{
    /**
     * Iniciamos el plugin
     * @return void
     * @author Daniel Lucia
     */
    public function init(): void
    {
        add_action('add_meta_boxes', [$this, 'addExpiryMetaBox']);
        add_action('save_post', [$this, 'saveExpiryMetaBox']);
        add_filter('the_content', [$this, 'maybeHideContent']);
        add_action('wp_enqueue_scripts', [$this, 'enqueueCountdownScript']);
    }

    /**
     * Añadimos metabox para mostrar la fecha.
     * @return void
     * @author Daniel Lucia
     */
    public function addExpiryMetaBox()
    {
        add_meta_box(
            'dl_content_expiry',
            __('Expiry date', 'dl-content-expiry'),
            [$this, 'renderExpiryMetaBox'],
            null,
            'side',
            'high'
        );
    }

    /**
     * Añadimos contenido al metabox
     * @param mixed $post
     * @return void
     * @author Daniel Lucia
     */
    public function renderExpiryMetaBox($post)
    {
        $expiry = get_post_meta($post->ID, '_dl_expiry_datetime', true);
        $enabled = get_post_meta($post->ID, '_dl_expiry_enabled', true);

        wp_nonce_field('dl_content_expiry_nonce', 'dl_content_expiry_nonce');

        echo '<p>';
            echo '<label for="dl_expiry_enabled">';
            echo '<input type="checkbox" id="dl_expiry_enabled" name="dl_expiry_enabled" value="1"' . checked($enabled, '1', false) . ' />';
            echo ' ' . esc_html__('Enable content expiry for this post', 'dl-content-expiry');
            echo '</label>';
        echo '</p>';

        echo '<p style="display:flex;flex-direction:column;gap:8px;">';
            echo '<label for="dl_expiry_datetime">' . esc_html__('Date and time of expiry:', 'dl-content-expiry') . '</label>';
            echo '<input type="datetime-local" id="dl_expiry_datetime" name="dl_expiry_datetime" value="' . esc_attr($expiry) . '" style="width:100%;" />';
        echo '</p>';

        echo '<p>';
            echo esc_html__('Set the date and time when this content will expire. After this date, the content will be hidden and a message will be displayed.', 'dl-content-expiry');
        echo '</p>';

        echo '<p>';
            echo '<small>';
                echo esc_html__('Current server time', 'dl-content-expiry') . ': <em>' . esc_html(current_time('Y-m-d H:i')) . '</em>';
            echo '</small>';
        echo '</p>';
    }

    /**
     * Guardamos el contenido del metabox
     * @param mixed $post_id
     * @return void
     * @author Daniel Lucia
     */
    public function saveExpiryMetaBox($post_id)
    {
        if (!isset($_POST['dl_content_expiry_nonce']) || !wp_verify_nonce($_POST['dl_content_expiry_nonce'], 'dl_content_expiry_nonce')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if (isset($_POST['dl_expiry_datetime'])) {
            update_post_meta($post_id, '_dl_expiry_datetime', sanitize_text_field($_POST['dl_expiry_datetime']));
        }

        $enabled = isset($_POST['dl_expiry_enabled']) ? '1' : '0';
        update_post_meta($post_id, '_dl_expiry_enabled', $enabled);
    }

    /**
     * Oculta el cnotenido del post si es necesario
     * @param mixed $content
     * @author Daniel Lucia
     */
    public function maybeHideContent($content)
    {
        if (!is_singular() || !in_the_loop() || !is_main_query()) {
            return $content;
        }

        global $post;
        $enabled = get_post_meta($post->ID, '_dl_expiry_enabled', true);
        if ($enabled !== '1') {
            return $content;
        }

        $expiry = get_post_meta($post->ID, '_dl_expiry_datetime', true);

        if (!$expiry) {
            return $content;
        }

        $expiry_ts = strtotime($expiry);
        $now = current_time('timestamp');

        if ($expiry_ts <= $now) {
            return '<div class="dl-expired-message"><strong>' . esc_html__('This content has expired.', 'dl-content-expiry') . '</strong></div>';
        } else {
            $countdown_id = 'dl-countdown-' . $post->ID;
            $html = '<div class="dl-countdown" id="' . esc_attr($countdown_id) . '" data-expiry="' . esc_attr($expiry_ts) . '" data-expired-text="' . esc_attr(__('This content has expired.', 'dl-content-expiry')) . '" data-label="' . esc_attr(__('Time left:', 'dl-content-expiry')) . '"></div>';

            add_action('wp_footer', function () use ($countdown_id) {
                echo '<script>window.dlCountdownIds = window.dlCountdownIds || [];window.dlCountdownIds.push("' . esc_js($countdown_id) . '");</script>';
            });

            return $html . $content;
        }
    }

    /**
     * Encolamos el script para el countdown
     * @return void
     * @author Daniel Lucia
     */
    public function enqueueCountdownScript()
    {
        wp_enqueue_script(
            'dl-content-expiry-countdown',
            plugin_dir_url(DL_CONTENT_EXPIRY_FILE) . 'assets/js/countdown.js',
            [],
            DL_CONTENT_EXPIRY_VERSION,
            true
        );

        wp_localize_script('dl-content-expiry-countdown', 'dlContentExpiry', [
            'expiredText' => __('This content has expired.', 'dl-content-expiry'),
            'label' => __('Time left:', 'dl-content-expiry'),
            'daysText' => __('days', 'dl-content-expiry'),
            'hoursText' => __('hours', 'dl-content-expiry'),
            'minutesText' => __('minutes', 'dl-content-expiry'),
            'secondsText' => __('seconds', 'dl-content-expiry')
        ]);

        wp_enqueue_style(
            'dl-content-expiry-countdown',
            plugin_dir_url(DL_CONTENT_EXPIRY_FILE) . 'assets/css/countdown.css',
            [],
            DL_CONTENT_EXPIRY_VERSION
        );
    }
}
