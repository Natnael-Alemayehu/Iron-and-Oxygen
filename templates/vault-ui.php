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
            <table class="io-pct-table" id="ioPctTable">
                <thead>
                    <tr>
                        <th scope="col"><?php esc_html_e('%', 'io-vault'); ?></th>
                        <th scope="col" id="ioTableWeightHeader"><?php esc_html_e('Weight', 'io-vault'); ?></th>
                        <th scope="col"><?php esc_html_e('Target Zone', 'io-vault'); ?></th>
                    </tr>
                </thead>
                <tbody id="ioPctTableBody"></tbody>
            </table>
        </div>
    </section>
    <?php endif; ?>
    <!-- Panel 2 - Benchmark Vault -->
    <?php if($show_vault) : ?>
    <section
        class="io-panel io-panel--vault<?php echo ! $show_tabs ? ' io-panel--active' : ''; ?>"
        id="tab-panel-vault"
        role="tabpanel"
        aria-labelledby="tab-btn-vault"
    >
        <header class="io-section-header">
            <h2 class="io-section-title">
                <span class="io-icon-badge">LOCK</span>
                <?php esc_html_e('Benchmark Vault', 'io-vault'); ?>
            </h2>
            <p class="io-section-sub"><?php esc_html_e('Standard wordouts &amp; goal scores.', 'io-vault'); ?></p>
        </header>

        <?php if( ! empty( $benchmarks ) ) : ?>

        <!-- Search + Filter -->
        <div class="io-search-bar">
            <div class="io-search-wrap">
                <svg class="io-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"></svg>
                <input 
                    class="io-input io-input--search"
                    id="ioSearch"
                    type="search"
                    placeholder="<?php esc_attr_e('Search benchmark...', 'io-vault'); ?>"
                    aria-label="<?php esc_attr_e('Search benchmarks', 'io-vault'); ?>"
                >
            </div>
            <select class="io-select io-select--filter" id="ioCategoryFilter" aria-label="<?php esc_attr_e('Filter by category', 'io-vault'); ?>">
                <option value="<?php echo esc_attr( $val ); ?>"><?php echo esc_html( $label ); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="io-benchmark-count" id="ioBenchmarkCount" aria-live="polite">
            <?php
            printf(
                /* translators: %d = number of benchmarks */
                esc_html(_n('%d Benchmark', '%d Benchmarks', count($benchmarks), 'io-vault') ),
                count( $benchmarks )
            );
            ?>
        </div>

        <!-- Benchmark cards -->
        <ul class="io-benchmark-list" id="ioBenchmarkList" role="list">
            <?php foreach ($benchmarks as $bm) :
                $cat_slug   = esc_attr($bm['category']);
                $cat_label  = $category_labels[ $bm['category'] ] ?? '';
                $equip      = $bm['equipment'] ? array_map('trim', explode(',', $bm['equipment'])) : [];    
            ?>
            <li
                class="io-benchmark-card"
                data-title="<?php echo esc_attr(strtolower($bm['title'])) ?>"
                data-category="<?php echo $cat_slug; ?>"
                data-search="<?php echo esc_attr(strtolower($bm['title'] . ' ' . $bm['desc'] . ' ' . $bm['equipment'])); ?>"
            >
                <button
                    class="io-card-trigger"
                    aria-expanded="false"
                    aria-contrils="bm-body-<?php echo esc_attr($bm['id']); ?>"
                >
                    <span class="io-card-title"><?php echo esc_html($bm['title']); ?></span>
                    <span class="io-card-meta">
                        <?php if( $cat_label ) : ?>
                            <span class="io-badge io-badge--<?php echo $cat_slug ?>"><?php echo esc_html($cat_label); ?></span>
                        <?php endif; ?>
                        <?php if( $bm['goal'] ): ?>
                            <span class="io-goal-pill">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14" /></svg>
                                <?php echo esc_html( $bm['goal'] ); ?>
                            </span>
                        <?php endif ?>
                    </span>
                    <span class="io-cheveron" aria-hidden="true">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                    </span>
                </button>

                <div
                    class="io-card-body"
                    id="bm-body-<?php echo esc_attr( $bm['id'] ); ?>"
                    hidden
                >
                    <?php if ( $bm['desc'] ); ?>
                    <div class="io-desc">
                        <h4 class="io-sub-heading"><?php esc_html_e('Movement Descripiton', 'io-vault'); ?></h4>
                        <p><?php echo n12br( esc_html( $bm['desc'] ) ); ?></p>
                    </div>
                    <?php endif; ?>

                    <?php if( ! empty( $bm['content'] ) ): ?>
                        <div class="io-wp-content">
                            <?php echo wp_kses_post( $bm['content'] ); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ( ! empty( $equip ) ) : ?>
                        <div class="io-setup">
                            <h4 class="io-sub-heading"><?php esc_html_e('Equipment', 'io-vault'); ?></h4>
                            <ul class="io-equip-list">
                                <?php foreach ( $equip as $item ) : ?>
                                    <li><?php echo esc_html( $item ); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if( $bm['goal'] ): ?>
                        <div class="io-goal-block">
                            <span class="io-goal-label"><?php esc_html_e('Goal Score', 'io-vault');?></span>
                            <span class="io-goal-value"><?php echo esc_html( $bm['goal'] ); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
        
        <p class="io-empty-msg" id="ioBenchmarkEmpty" hidden>
            <?php esc_html_e( 'No benchmarks match your search.', 'io-vault' ); ?>
        </p>

        <?php else: ?>
        <div class="io-empty-state">
            <p> <?php esc_html_e('No benchmark have been published yet. Add some via the WordPress admin -> Benchmark menu.', 'io-vault'); ?> </p>
        </div>
        <?php endif; ?>
        
    </section>
    <?php endif; ?>
    
</div> <!-- /.io-vault-root -->
<?php