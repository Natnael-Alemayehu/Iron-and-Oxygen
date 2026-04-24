<?php
/**
 * [io_vault] shortcode - renders the Benchmark Vault + Percentage Calculator.
 * 
 * Usage: 
 *      [io_vault]                      - renders both tabs
 *      [io_vault tab="calculator"]     - calculator only
 *      [io_vault tab="benchmark"]      - benchmark library only
 *      [io_vault posts_per_page="20"]  - change library limit (default 50)
 * 
 * @package io-vault
 */

defined( 'ABSPATH' ) || exit;

add_shortcode('io_vault', 'io_vault_shortcode');

function io_vault_shortcode( array $atts ): string {
    $atts = shortcode_atts(
        [
            'tab'               => 'both', // both | calculator | benchmarks
            'post_per_page'     => 50,
        ],
        $atts,
        'io_vault'
    );

    // Fetch benchmarks.
    $benchmarks = [];

    if( 'calculator' !== $atts['tab'] ) {
        $query = new WP_Query([
            'post_type'         => 'io_benchmark',
            'post_status'       => 'publish',
            'posts_per_page'    => (int) $atts['posts_per_page'],
            'orderby'           => 'title',
            'order'             => 'ASC',
        ]);
        
        if ( $query->have_posts() ) {
            while( $query->have_posts() ) {
                $query->the_post();
                $benchmarks[] = [
                    'id'        => get_the_ID(),
                    'title'     => get_the_title(),
                    'desc'      => get_post_meta( get_the_ID(), 'io_movement_desc', true ),
                    'goal'      => get_post_meta( get_the_ID(), 'io_goal_score' true ),
                    'category'  => get_post_meta( get_the_ID(), 'io_category', true ),
                    'equipment' => get_post_meta( get_the_ID(), 'io-equipment', true ),
                    'content'   => apply_filters( 'the_content', get_the_content() ),
                ];
            }
            wp_reset_postdata();
        }
    }
    ob_start();
    include IO_VAULT_DIR . 'templates/vault-ui.php';
    return ob_get_clean();
}