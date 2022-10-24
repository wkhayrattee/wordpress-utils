<?php

namespace WkWordPressUtils;

use TOC\MarkupFixer;
use TOC\TocGenerator;

/** ****************************************************************
 * To load a template file within the current active theme directory
 *
 * It will FIRST check if the file exists within the child them.
 * Then on failure, it will attempt to load from the parent theme.
 *
 * Else, it will log error via error_log
 *
 * @param string $relative_file_name
 ** ****************************************************************/
function loadTemplate(string $relative_file_name): void
{
    if (file_exists(get_stylesheet_directory() . DIRECTORY_SEPARATOR . $relative_file_name)) { //load from child theme
        include get_stylesheet_directory() . DIRECTORY_SEPARATOR . $relative_file_name;
    } elseif (file_exists(get_template_directory() . DIRECTORY_SEPARATOR . $relative_file_name)) { //load from parent theme
        include get_template_directory() . DIRECTORY_SEPARATOR . $relative_file_name;
    } else {
        error_log('Cannot load file: ' . $relative_file_name);
    }
}

/** ****************************************************************
 * Handling addition of a custom Table of Contents
 *
 * @param string $content
 ** ****************************************************************/
/**
 * TOC builder
 *
 * @param string $content
 *
 * @return string
 */
function shapeTheTableOfContent(string $content): string
{
    $post_content = $content;
    if (empty($post_content) || is_null($post_content)) {
        return false;
    }

    // This ensures that all header tags have `id` attributes so they can be used as anchor links
    $fixer = new MarkupFixer();
    $post_content = $fixer->fix($post_content);

    /** @var TocGenerator $toc_content */
    $generator = new TocGenerator();
    $toc_content = '<div class="toc"><details open="open"><summary accesskey="c" title="(Alt + C)"><div class="details">Table of Contents</div></summary><div class="inner">';
    $toc_content .= $generator->getHtmlMenu($post_content);
    $toc_content .= '</div></details></div>';

    unset($fixer);
    unset($generator);

    //finally return the toc structure
    return $toc_content;
}
/**
 * Add IDs to all HTML headings which wil appear on the TOC
 *
 * @param string $content
 *
 * @return string
 */
function ensureHeadersHaveIdsToBeTocReady(string $content): string
{
    $post_content = $content;
    if (has_shortcode($post_content, TOC_SHORTCODE)) {
        // This ensures that all header tags have `id` attributes so they can be used as anchor links
        $fixer = new MarkupFixer();
        $post_content = $fixer->fix($post_content);

        unset($fixer);
    }

    return $post_content;
}

/** ****************************************************************
 * Handling addition of breadcrumbs
 ** ****************************************************************/
/**
 * To display breadcrumb programmatically
 * But also breadcrumb should be enabled via dashboard.
 *
 * Dependency:
 *  - SeoPress Pro
 */
function displayBreadcrumbUsingSeopressHelper(): void
{
    if (function_exists('seopress_display_breadcrumbs')) {
        echo '<div class="breadcrumb-div">';
        seopress_display_breadcrumbs();
        echo '</div>';
    }
}
