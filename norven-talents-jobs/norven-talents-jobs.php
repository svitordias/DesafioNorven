<?php
/**
 * Plugin Name: Norven Cadastro de vagas
 * Description: Plugin para cadastro e exibição de vagas de emprego.
 * Version: 1.0
 * Author: Vitor Dias 
 * License: MIT
 */

if (!defined(constant_name: 'ABSPATH')) {
    exit; // Impede acesso direto ao arquivo
}

// Inclui os arquivos necessários
require_once plugin_dir_path(__FILE__) . 'includes/cpt-jobs.php';
require_once plugin_dir_path(__FILE__) . 'includes/meta-boxes.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcode-jobs.php';

// Função de ativação
function norven_jobs_activate() {
    norven_jobs_register_cpt();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'norven_jobs_activate');

// Função de desativação
function norven_jobs_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'norven_jobs_deactivate');

require_once __DIR__ . '/vendor/autoload.php';