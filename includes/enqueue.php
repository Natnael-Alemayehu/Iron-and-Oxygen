<?php
/**
 * Enqueue plugin styles and scripts - only on pages that contain the shortcode.
 * 
 * @package io-vault
 */

defined ( 'ABSPATH' ) || exit;

add_action( 'wp_enqueue_scripts', 'io_vault_enqueue' );

function io_vault_enqueue(): void {
    global $post;

    // Only load when the shortcode is present on the page.
    if( ! is_a($post, 'WP_Post') || !has_shortcode($post->post_content, 'io-vault') ) {
        return;
    }

    wp_enqueue_style(
        'io-vault-styles',
        IO_VAULT_URL . 'assets/css/vault.css',
        [],
        IO_VAULT_VERSION
    );

    wp_enqueue_script(
        'io-vault-script',
        IO_VAULT_URL . 'assets/js/vault.js',
        [],
        IO_VAULT_VERSION,
        true    // footer
    );

    // Pass data to JS (no AJAX needed for calculator - pure client-side).
    wp_localized_script(
        'io-vault-script',
        'ioVaultData',
        [
            'ajaxUrl'   => admin_url('admin-ajax.php'),
            'nonce'     => wp_create_nonce('io_vault_nonce'),
        ]
    );
}