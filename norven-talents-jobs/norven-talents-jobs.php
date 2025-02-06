<?php
/**
 * Plugin Name: Norven Talents Jobs
 * Description: Plugin para cadastro de vagas de emprego.
 * Version: 1.0
 * Author: Seu Nome
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
    echo '<h1>Gerenciar Vagas</h1>';
    // Aqui você pode adicionar a lógica para listar, adicionar, editar e excluir vagas
}
?>
