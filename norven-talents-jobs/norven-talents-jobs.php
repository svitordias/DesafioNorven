<?php
/**
 * Plugin Name: Norven Cadastro de vagas
 * Description: Plugin para cadastro e exibição de vagas de emprego.
 * Version: 1.0
 * Author: Vitor Dias 
 * License: MIT
 */

if (!defined('ABSPATH')) {
    exit; // Impede acesso direto ao arquivo
}

// Inclui os arquivos necessários
require_once plugin_dir_path(__FILE__) . 'includes/cpt-jobs.php';
require_once plugin_dir_path(__FILE__) . 'includes/meta-boxes.php';
require_once plugin_dir_path(__FILE__) . 'includes/job-application-form.php';
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

// Função de filtrar as vagas
function norven_jobs_filter() {
    $tipo_contratacao = $_POST['tipo_contratacao'];
    $localizacao = $_POST['localizacao'];

    $args = [
        'post_type'      => 'jobs',
        'posts_per_page' => 10,
        'meta_query'     => [],
    ];

    if (!empty($tipo_contratacao)) {
        $args['meta_query'][] = [
            'key'     => 'tipo_contratacao',
            'value'   => $tipo_contratacao,
            'compare' => '='
        ];
    }

    if (!empty($localizacao)) {
        $args['meta_query'][] = [
            'key'     => 'localizacao',
            'value'   => $localizacao,
            'compare' => '='
        ];
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post(); ?>
            <div class="norven-job-item">
                <h3><?php the_title(); ?></h3>
                <p>📍 <?php echo get_post_meta(get_the_ID(), 'localizacao', true); ?></p>
                <p><strong>Tipo:</strong> <?php echo get_post_meta(get_the_ID(), 'tipo_contratacao', true); ?></p>
            </div>
        <?php endwhile;
    else :
        echo '<p>Nenhuma vaga encontrada.</p>';
    endif;
    wp_die();
}

add_action('wp_ajax_norven_jobs_filter', 'norven_jobs_filter');
add_action('wp_ajax_nopriv_norven_jobs_filter', 'norven_jobs_filter');

// Função de envio de candidatura
function norven_submit_application() {
    $vaga_id = $_POST['vaga_id'];
    $nome = sanitize_text_field($_POST['nome']);
    $email = sanitize_email($_POST['email']);

    if (!empty($_FILES['curriculo']['name'])) {
        $uploaded_file = wp_upload_bits($_FILES['curriculo']['name'], null, file_get_contents($_FILES['curriculo']['tmp_name']));
        if (!empty($uploaded_file['error'])) {
            echo '<p style="color: red;">Erro ao fazer upload do currículo.</p>';
            wp_die();
        }
    }

    $mensagem = "Nova candidatura para a vaga " . get_the_title($vaga_id) . "\n\nNome: $nome\nE-mail: $email\n\nCurrículo: " . $uploaded_file['url'];

    wp_mail(get_option('admin_email'), 'Nova Candidatura Recebida', $mensagem);

    echo '<p style="color: green;">Candidatura enviada com sucesso!</p>';
    wp_die();
}

add_action('wp_ajax_norven_submit_application', 'norven_submit_application');
add_action('wp_ajax_nopriv_norven_submit_application', 'norven_submit_application');

// Estilos do admin
function norven_jobs_admin_styles() {
    wp_enqueue_style('norven-jobs-admin-style', plugin_dir_url(__FILE__) . 'assets/css/admin-style.css');
}
add_action('admin_enqueue_scripts', 'norven_jobs_admin_styles');

// Estilos do frontend
function norven_jobs_frontend_styles() {
    wp_enqueue_style('norven-jobs-public-style', plugin_dir_url(__FILE__) . 'assets/css/public-style.css');
}
add_action('wp_enqueue_scripts', 'norven_jobs_frontend_styles');
