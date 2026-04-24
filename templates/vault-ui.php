<?php
/**
 * Front-end template for [io_vault] shortcode.
 * 
 * Variable in scope (from shortcode.php):
 *      $atts       - shortcode attributes
 *      $benchmarks - array of benchmark data
 * 
 * @package io-vault
 */

defined( 'ABSPATH' ) || exit;

$show_calc      = in_array( $atts['tab'], ['both', 'calculator'], true );
$show_vault     = in_array( $atts['tab'], ['both', 'benchmark'], true );
$show_tabs      = $show_calc && $show_vault;

$category_labels = [
    'conditioning'      => __('Conditioning', 'io-vault'),
    'stength'           => __('Strength', 'io-vault'),
    'gymnastics'        => __('Gymnastics', 'io-vault'),
    'mixed'             => __('Mixed Modal', 'io-vault'),
];

?>
<div class="io-vault-root" id="ioVaultRoot" data-show-calc="<?php echo $show_calc ? '1' : '0' ?>" data-show-vault="<?php echo $show_vault ? '1' : '0' ?>">
    <?php if ($show_tabs): ?>
    <!-- TAB NAV -->
    <nav class="io-tab-nav" role="tablist" aria-label="<?php esc_attr_e('Vault Sections', 'io-vault'); ?>">
        <button
            class="io-tab-btn io-tab-btn--active"
            role="tab"
            id="tab-btn-calc"
            aria-controls="tab-panel-calc"
            aria-selected="true"
            data-tab="calc"
        >
            <span class="io-tab-icon">!!</span>
            <span><?php esc_html_e('% Calculator', 'io-vault'); ?></span>
        </button>
        <button
            class="io-tab-btn"
            role="tab"
            id="tab-btn-vault"
            aria-controls="tab-panel-vault"
            aria-selected="false"
            data-tab="vault"
        >
            <span class="io-tab-icon">LOCK</span>
            <span><?php esc_html_e('Benchmark Vault', 'io-vault'); ?></span>
        </button>
    </nav>
    <?php endif; ?>

    <!-- Panel 1 - Percentage calculator -->
    <?php if ($show_calc) : ?>
    <section
        class="io-panel io-panel--calc<?php echo $show_tabs ? '' : 'io-panel--active'; ?>"
        id="tab-panel-calc"
        role="tabpanel"
        aria-labelledby="tab-btn-calc"
    >
        <header class="io-section-header">
            <h2 class="io-section-title">
                <span class="io-icon-badge">!!!</span>
                <?php esc_html_e( 'Percentage Calculator', 'io-vault' ); ?>
            </h2>
            <p class="io-section-sub"><?php esc_html_e('Enter your 1-Rep Max to generate your working weights.', 'io-vault'); ?></p>
        </header>
        <div class="io-calc-form">
            <div class="io-input-group">
                <label class="io-label" for="ioLiftName"><?php esc_html_e('Lift / Movement', 'io-vault'); ?></label>
                <div class="io-input-row">
                    <select class="io-select" id="ioLiftName" aria-label="<?php esc_attr_e('Select a lift', 'io-vault'); ?>">
                        <option value="Back Squat">Back Squat</option>
                        <option value="Front Squat">Front Squat</option>
                        <option value="Overhead Squat">Overhead Squat</option>
                        <option value="Deadlift">Deadlift</option>
                        <option value="Romanian Deadlift">Romanian Deadlift</option>
                        <option value="Clean">Clean</option>
                        <option value="Clean &amp; Jerk">Clean &amp; Jerk</option>
                        <option value="Snatch">Snatch</option>
                        <option value="Strict Press">Strict Press</option>
                        <option value="Push Press">Push Press</option>
                        <option value="Push Jerk">Push Jerk</option>
                        <option value="Bench Press">Bench Press</option>
                        <option value="Thruster">Thruster</option>
                        <option value="Cusrom...">Custom...</option>
                    </select>
                </div>
                <input
                    class="io-input io-input--custom-lift"
                    id="ioLiftCustom"
                    type="text"
                    placeholder=""<?php esc_attr_e('Enter lift name...', 'io-vault'); ?>
                    style="display:none"
                    aria-label="<?php esc_attr_e('Custom lift name', 'io-value'); ?>"
                >
            </div>

            <div class="io-input-group">
                <label class="io-label" for="io1RM"><?php esc_html_e('1-Rep Max', 'io-vault'); ?></label>
                <div class="io-input-row">
                    <input
                        class="io-input io-input--1rm"
                        id="io1RM"
                        type="number"
                        inputmode="decimal"
                        min="1"
                        max="9999"
                        placeholder="e.g. 255"
                        aria-descibedby="io1RM-hint"
                    >
                    <div class="io-unit-toggle" role="group" aria-label="<?php esc_attr_e('Weight unit', 'io-vault') ?>">
                        <button class="io-unit-btn io-unit-btn--active" data-unit="lb">lb</button>
                        <button class="io-unit-btn" data-unit="kg">kg</button>
                    </div>
                </div>
                <span id="io1RM-hint" class="io-hint"><?php esc_html_e('Round to nearest 5 or 2.5', 'io-vault'); ?></span>
            </div>
            <div class="io-input-group io-input-group--rounding">
                <label class="io-label"><?php esc_html_e('Rounding', 'io-vault'); ?></label>
                <div class="io-round-toggle" role="group">
                    <button class="io-round-btn io-round-btn--active" data-round="5">5</button>
                    <button class="io-round-btn" data-round="2.5">2.5</button>
                    <button class="io-round-btn" data-round="1">1</button>
                    <button class="io-round-btn" data-round="0">None</button>
                </div>
            </div>
        </div>

        <!-- Result table (hidden until input) -->
        <div class="io-result-wrap" id="ioResultWrap" aria-live="polite" aria-label="<?php esc_attr_e('Percentage table', 'io-table'); ?>">
            <div class="io-result-header">
                <span class="io-result-title" id="ioResultTitle"></span>
                <button class="io-copy-btn" id="ioCopyBtn" title="<?php esc_attr_e('Copy table', 'io-vault'); ?>">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/>
                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                    <span><?php esc_html_e('Copy', 'io-vault'); ?></span>
                </button>
            </div>
            <table>


            </table>
        </div>
    </section>
</div>
<?php