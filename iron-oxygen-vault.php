<?php
/**
 * Plugin Name:         Iron & Oxygen - Benchmark Vault & Percentage Calculator
 * Description:         Benchmark Workout Library (CPT) + 1-Rep Max Percentage Calculator for the member portal. Embed with [io_vault].
 * Version:             1.0.0
 * Requires at least:   6.0
 * Requires PHP:        8.0
 * Text Doamin:         io-vault
 */

defined ( 'ABSPATH' ) || exit;

define( 'IO_VAULT_VERSION', '1.0.0' );
define( 'IO_VAULT_DIR', plugin_dir_path(__FILE__) );
define( 'IO_VAULT_URL', plugin_dir_url(__FILE__) );

// Autoload Includes
require_once IO_VAULT_DIR . 'includes/cpt-benchmark.php';
require_once IO_VAULT_DIR . 'includes/meta-boxes.php';
require_once IO_VAULT_DIR . 'includes/shortcode.php';
require_once IO_VAULT_DIR . 'includes/enqueue.php'