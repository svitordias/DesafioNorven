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
require_once plugin_dir_path(file: __FILE__); . 'includes/cpt-jobs.php';
require_once plugin_dir_path(file: __FILE__) . 'includes/meta-boxes.php';
require_once plugin_dir_path(file: __FILE__) . 'includes/shortcode-jobs.php';
require_once plugin_dir_path(file: __FILE__) . 'includes/job-application-form.php';

// Função de ativação
function norven_jobs_activate(): void {
    norven_jobs_register_cpt();
    flush_rewrite_rules();
}
register_activation_hook(file: __FILE__, callback: 'norven_jobs_activate');

// Função de desativação
function norven_jobs_deactivate(): void {
    flush_rewrite_rules();
}
register_deactivation_hook(file: __FILE__, callback: 'norven_jobs_deactivate');

require_once __DIR__ . '/vendor/autoload.php';

// Função de filtrar as vagas
function norven_jobs_filter(): void {
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

    $query = new WP_Query(query: $args);

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post(); ?>
            <div class="norven-job-item">
                <h3><?php the_title(); ?></h3>
                <p>📍 <?php echo get_post_meta(post_id: get_the_ID(), key: 'localizacao', single: true); ?></p>
                <p><strong>Tipo:</strong> <?php echo get_post_meta(post_id: get_the_ID(), key: 'tipo_contratacao', single: true); ?></p>
            </div>
        <?php endwhile;
    else :
        echo '<p>Nenhuma vaga encontrada.</p>';
    endif;
    wp_die();
}

add_action(hook_name: 'wp_ajax_norven_jobs_filter', callback: 'norven_jobs_filter');
add_action(hook_name: 'wp_ajax_nopriv_norven_jobs_filter', callback: 'norven_jobs_filter');

// Função de formulario
function norven_submit_application(): void {
    $vaga_id = $_POST['vaga_id'];
    $nome = sanitize_text_field(str: $_POST['nome']);
    $email = sanitize_email(email: $_POST['email']);

    if (!empty($_FILES['curriculo']['name'])) {
        $uploaded_file = wp_upload_bits(name: $_FILES['curriculo']['name'], deprecated: null, bits: file_get_contents(filename: $_FILES['curriculo']['tmp_name']));
        if (!empty($uploaded_file['error'])) {
            echo '<p style="color: red;">Erro ao fazer upload do currículo.</p>';
            wp_die();
        }
    }

    $mensagem = "Nova candidatura para a vaga " . get_the_title(post: $vaga_id) . "\n\nNome: $nome\nE-mail: $email\n\nCurrículo: " . $uploaded_file['url'];

    wp_mail(to: get_option(option: 'admin_email'), subject: 'Nova Candidatura Recebida', message: $mensagem);

    echo '<p style="color: green;">Candidatura enviada com sucesso!</p>';
    wp_die();
}

add_action(hook_name: 'wp_ajax_norven_submit_application', callback: 'norven_submit_application');
add_action(hook_name: 'wp_ajax_nopriv_norven_submit_application', callback: 'norven_submit_application');

// Style admin
function norven_jobs_admin_styles(): void {
    wp_enqueue_style(handle: 'norven-jobs-admin-style', src: plugin_dir_url(file: __FILE__) . 'assets/css/admin-style.css');
}
add_action(hook_name: 'admin_enqueue_scripts', callback: 'norven_jobs_admin_styles');

// Style public
function norven_jobs_frontend_styles(): void {
    wp_enqueue_style(handle: 'norven-jobs-public-style', src: plugin_dir_url(file: __FILE__) . 'assets/css/public-style.css');
}
add_action(hook_name: 'wp_enqueue_scripts', callback: 'norven_jobs_frontend_styles');