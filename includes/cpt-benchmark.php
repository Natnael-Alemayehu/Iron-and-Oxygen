<?php
/**
 * Registers the "Benchmark" Custom Post Type.
 * 
 * @package io-vault
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'io_register_benchmark_cpt' );

function io_register_benchmark_cpt(): void {
    $labels = [
        'name'              => __('Benchmarks', 'io-vault'),
        'singular_name'     => __('Benchmark', 'io-vault'),
        'add_new'           => __('Add Benchmark', 'io-vault'),
        'add_new_item'      => __('Add New Benchmark', 'io-vault'),
        'edit_item'         => __('Edit Benchmark', 'io-vault'),
        'new_item'          => __('New Benchmark', 'io-vault'),
        'view_item'         => __('View Benchmark', 'io-vault'),
        'search_items'      => __('Search Benchmarks', 'io-vault'),
        'not_found'         => __('No Benchmarks Found', 'io-vault'),
        'menu_name'         => __('Benchmarks', 'io-vault')
    ];

    $args = [
        'labels'            => $labels,
        'public'            => true,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'menu_icon'         => 'dashicons-performance',
        'menu_position'     => 25,
        'supports'          => [ 'title', 'editor', 'thumbnail', 'revisions' ],
        'has_archive'       => false,
        'rewrite'           => [ 'slug' => 'benchmark' ],
        'show_in_rest'      => true, // Gutenberg support
        'capability_type'   => 'post',
    ];

    registered_post_type( 'io_benchmark', $args );
}

// Flush rewrite rules only on activation.
register_activation_hook(
    IO_VAULT_DIR . 'iron-oxygen-vault.php',
    function () {
        io_register_benchmark_cpt();
        flush_rewrite_rules();
    }
);

register_deactivation_hook(
    IO_VAULT_DIR . 'iron-oxygen-vault.php',
    'flush_rewrite_rules'
)