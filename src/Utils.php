<?php

namespace WkWordPressUtils;

class Utils
{
    /**
     * Get the root domain URL of the WordPress site.
     *
     * This function retrieves the root domain URL without any subfolder paths.
     * It parses the site_url to extract the scheme (http/https) and the host.
     *
     * Note:
     * - It assumes WordPress is installed directly under the domain, not nested deeper.
     * - If WordPress is installed in a subdomain, e.g., sub.domain.com/subfolder,
     *   it returns sub.domain.com.
     * - It doesn't specifically extract the main domain from potential subdomains.
     *
     * @return string The root domain URL without subfolders.
     */
    public static function get_root_domain_url()
    {
        $site_url = site_url();
        $parsed_url = parse_url($site_url);
        $scheme = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
        $host = $parsed_url['host'] ?? '';

        return $scheme . $host;
    }
}
