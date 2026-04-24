<?php
/**
 * Meta boxes for the Benchmark CPT.
 * 
 * Fields:
 *      io_movement_desc    - Textarea: detailed movement description / rx standards
 *      io_goal_score       - Text:     goal time, rounds, or reps (e.g. "sub 10:00")
 *      io_category         - Select:   Conditioning | strength | Gymnastics | Mixed
 *      io_equipment        - Text:     comma-separated equipment list
 * 
 * @package io-vault
 */

defined( 'ABSPATH' ) || exit;

/* Register */
add_action( 'add_meta_boxes', function() {
    add_meta_box(
        'io_benchmark_details',
        __('Benchmark Details', 'io-vault'),
        'io_benchmark_meta_box_html',
        'io_benchmark',
        'normal',
        'high'
    );
} );

/* Render */
function io_benchmark_meta_box_html( WP_Post $post ): void {
    wp_nonce_field( 'io_save_benchmark_meta', 'io_benchmark_nonce' );

    $desc       = get_post_meta( $post->ID, 'io_movement_desc', true );
    $goal       = get_post_meta( $post->ID, 'io_goal_score', true );
    $category   = get_post_meta( $post->ID, 'io_category', true );
    $equipment  = get_post_meta( $post->ID, 'io_equipment', true );
    $category   = [
        ''              => __('- Select Category -', 'io-value'),
        'conditioning'  => __('Conditioning', 'io-value'),
        'strength'      => __('Strength', 'io-value'),
        'gymnastics'    => __('Gymnastics', 'io-value'),
        'mixed'         => __('Mixed Modal', 'io-value'),
    ];
    ?>
    <style>
        .io-meta-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        .io-meta-grid label { display: block; font-weight: 600; margin-bottom: 4px; }
        .io-meta-grid input, 
        .io-meta-grid select,
        .io-meta-grid textarea { width: 100%; }
        .io-meta-full { grid-column: 1/-1; }
    </style>

    <div class="io-meta-grid">
        <div>
            <label for="io_goal_score"><?php esc_html_e('Goal Time / Score', 'io-vault'); ?></label>
            <input
                type="text"
                id="io_goal_score"
                name="io_goal_score"
                value="<?php echo esc_attr( $goal ); ?>"    
                placeholder="e.g. Sub 10:00 / 150+ reps"
            >
        </div>

        <div>
            <label for="io_category"><?php esc_html_e('Category', 'io-vault'); ?></label>
            <select id="io_category" name="io_category">
                <?php foreach ($categories as $val => $label) : ?>
                    <option value="<?php echo esc_attr($val); ?>" <?php selected($category, $val); ?>>
                        <?php echo esc_html( $label ); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="io-meta-full">
            <label for="io_equipment"><?php esc_html_e('Equipment (comma-separated)', 'io-vault') ?></label>
            <input
                type="text"
                id="io_equipment"
                name="io_equipment"
                value="<?php echo esc_attr( $equipment ); ?>"
                placeholder="e.g. Barbell, Pull-up Bar, Box"
            >
        </div>

        <div class="io-meta-full">
            <label for="io_movement_desc"><?php esc_html_e('Movement Description / Rx Standards', 'io-vault') ?></label>
            <textarea
                id="io_movement_desc"
                name="io_movement_desc"
                rows="6"
                placeholder="Describe the workout structure, rep schemas, Rx weights, and any scaling notes..."
            ><?php echo esc_textarea( $desc ); ?></textarea>
        </div>
    </div>
    <?php
}

/* Save */
add_action('save_post_io_benchmark', function( int $post_id ){
    // Nonce check
    if (
        ! isset($_POST['io_benchmark_nonce']) ||
        ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['io_benchmark_nonce'] ) ), 'io_save_benchmark_meta' )
    ) {
        return;
    }

    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
        return;
    }

    if (! current_user_can('edit_post', $post_id) ) {
        return;
    }

    $fields = [
        'io_movement_desc'      => 'sanitize_textarea_field',
        'io_goal_score'         => 'sanitize_text_field',
        'io_category'           => 'sanitize_text_field',
        'io_equipment'          => 'sanitize_text_field',
    ];

    foreach ( $fields as $key => $sanitizer ) {
        if ( isset( $_POST[$key] ) ) {
            update_post_meta($post_id, $key, $sanitizer(wp_unslash($_POST[$key] ) ) );
        } else {
            delete_post_meta( $post_id, $key );
        }
    }
} );