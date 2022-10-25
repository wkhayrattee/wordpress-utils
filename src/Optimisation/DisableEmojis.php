<?php
/**
 * Emojis are not necessary and add additional javascript calls to the page.
 * Removing them is considered an optimisation plus.
 *
 * @author Wasseem Khayrattee <hey@wk.contact>
 *
 * @github wkhayrattee
 */

namespace WkWordPressUtils\Optimisation;

class DisableEmojis
{
    /**
     * Heart of the process
     */
    public static function process(): void
    {
        add_action('init', [self::class, 'disable_emojis']);
    }

    /**
     * Disable the emoji's
     */
    public static function disable_emojis(): void
    {
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

        add_filter('tiny_mce_plugins', [self::class, 'disable_emojis_tinymce']);
        add_filter('wp_resource_hints', [self::class, 'disable_emojis_remove_dns_prefetch'], 10, 2);
    }

    /**
     * Filter function used to remove the tinymce emoji plugin.
     *
     * @param array $plugins
     *
     * @return array Difference between the two arrays
     */
    public static function disable_emojis_tinymce(array $plugins = []): array
    {
        return array_diff($plugins, ['wpemoji']);
    }

    /**
     * Remove emoji CDN hostname from DNS prefetching hints.
     *
     * @param array $urls URLs to print for resource hints.
     * @param string $relation_type The relation type the URLs are printed for.
     *
     * @return array Difference between the two arrays.
     */
    public static function disable_emojis_remove_dns_prefetch(array $urls, string $relation_type): array
    {
        if ('dns-prefetch' == $relation_type) {
            /** This filter is documented in wp-includes/formatting.php */
            $emoji_svg_url = apply_filters('emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/');
            $urls = array_diff($urls, [$emoji_svg_url]);
        }

        return $urls;
    }
}
