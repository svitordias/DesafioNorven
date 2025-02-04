<?php
if (!defined('ABSPATH')) {
    exit;
}

// Registra o Custom Post Type "Vagas"
function norven_jobs_register_cpt() {
    $labels = array(
        'name'               => 'Vagas',
        'singular_name'      => 'Vaga',
        'menu_name'          => 'Vagas',
        'add_new'            => 'Adicionar Nova',
        'add_new_item'       => 'Adicionar Nova Vaga',
        'edit_item'          => 'Editar Vaga',
        'new_item'           => 'Nova Vaga',
        'view_item'          => 'Ver Vaga',
        'all_items'          => 'Todas as Vagas',
        'search_items'       => 'Buscar Vagas',
        'not_found'          => 'Nenhuma vaga encontrada',
        'not_found_in_trash' => 'Nenhuma vaga encontrada na lixeira'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'menu_icon'          => 'dashicons-businessman',
        'supports'           => array('title', 'editor', 'custom-fields'),
        'has_archive'        => true,
        'rewrite'            => array('slug' => 'vagas'),
    );

    register_post_type('jobs', $args);
}
add_action('init', 'norven_jobs_register_cpt');