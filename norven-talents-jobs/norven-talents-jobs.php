<?php
/**
 * Plugin Name: Norven Talents Jobs Versão Final
 * Description: Plugin para cadastro e exibição de vagas de emprego.
 * Version: 1.3
 * Author: Vitor Dias
 */

// Evita acesso direto ao arquivo
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Sair se acessado diretamente
}

// Função de ativação do plugin
function ntj_activate() {
    // Código a ser executado na ativação do plugin
}
register_activation_hook(__FILE__, 'ntj_activate');

// Função para registrar o Custom Post Type
function ntj_register_job_post_type() {
    $labels = array(
        'name'               => 'Vagas',
        'singular_name'      => 'Vaga',
        'menu_name'          => 'Vagas',
        'name_admin_bar'     => 'Vaga',
        'add_new'            => 'Adicionar Nova',
        'add_new_item'       => 'Adicionar Nova Vaga',
        'new_item'           => 'Nova Vaga',
        'edit_item'          => 'Editar Vaga',
        'view_item'          => 'Ver Vaga',
        'all_items'          => 'Todas as Vagas',
        'search_items'       => 'Buscar Vagas',
        'not_found'          => 'Nenhuma vaga encontrada.',
        'not_found_in_trash' => 'Nenhuma vaga encontrada na lixeira.'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'jobs'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title', 'editor', 'thumbnail', 'custom-fields')
    );

    register_post_type('jobs', $args);
}
add_action('init', 'ntj_register_job_post_type'); // Registra o Custom Post Type

// Adiciona menu no painel do WordPress
function ntj_add_admin_menu() {
    add_menu_page(
        'Gerenciar Vagas',
        'Vagas',
        'manage_options',
        'ntj_manage_jobs',
        'ntj_manage_jobs_page',
        'dashicons-list-view',
        6
    );
}
add_action('admin_menu', 'ntj_add_admin_menu'); // Adiciona o menu de administração

// Função para exibir a página de gerenciamento de vagas
function ntj_manage_jobs_page() {
    // Lógica para listar, adicionar, editar e excluir vagas
    echo '<h1>Gerenciar Vagas</h1>';

    // Listar vagas
    $args = array(
        'post_type' => 'jobs',
        'posts_per_page' => -1,
    );
    $jobs = new WP_Query($args);

    if ($jobs->have_posts()) {
        echo '<table>';
        echo '<tr><th>Título</th><th>Ações</th></tr>';
        while ($jobs->have_posts()) {
            $jobs->the_post();
            echo '<tr>';
            echo '<td>' . get_the_title() . '</td>';
            echo '<td><a href="' . get_edit_post_link() . '">Editar</a> | <a href="?page=ntj_manage_jobs&delete=' . get_the_ID() . '" onclick="return confirm(\'Tem certeza que deseja excluir esta vaga?\');">Excluir</a></td>';
            echo '</tr>';
        }
        echo '</table>';
        wp_reset_postdata();
    } else {
        echo '<p>Nenhuma vaga encontrada.</p>';
    }

    // Formulário para adicionar nova vaga
    echo '<h2>Adicionar Nova Vaga</h2>';
    echo '<form method="post" action="">';
    echo '<input type="text" name="job_title" placeholder="Título da Vaga" required />';
    echo '<input type="submit" name="submit" value="Adicionar Vaga" />';
    echo '</form>';

    // Lógica para adicionar nova vaga
    if (isset($_POST['submit'])) {
        $new_job_title = sanitize_text_field($_POST['job_title']);
        $new_job_id = wp_insert_post(array(
            'post_title' => $new_job_title,
            'post_type' => 'jobs',
            'post_status' => 'publish',
        ));
        if ($new_job_id) {
            echo '<p>Vaga adicionada com sucesso!</p>';
        }
    }

    // Lógica para editar uma vaga
    if (isset($_GET['edit'])) {
        $job_id = intval($_GET['edit']);
        $job = get_post($job_id);
        if ($job) {
            echo '<h2>Editar Vaga: ' . get_the_title($job_id) . '</h2>';
            echo '<form method="post" action="">';
            echo '<input type="hidden" name="job_id" value="' . $job_id . '" />';
            echo '<input type="text" name="job_title" value="' . esc_attr($job->post_title) . '" required />';
            echo '<input type="submit" name="update" value="Atualizar Vaga" />';
            echo '</form>';
        }
    }

    // Lógica para atualizar a vaga
    if (isset($_POST['update'])) {
        $job_id = intval($_POST['job_id']);
        $updated_job_title = sanitize_text_field($_POST['job_title']);
        $updated_job = array(
            'ID' => $job_id,
            'post_title' => $updated_job_title,
        );
        wp_update_post($updated_job);
        echo '<p>Vaga atualizada com sucesso!</p>';
    }

    // Lógica para excluir uma vaga
    if (isset($_GET['delete'])) {
        $job_id = intval($_GET['delete']);
        wp_delete_post($job_id, true);
        echo '<p>Vaga excluída com sucesso!</p>';
    }
}
?>
