<?php
if (!defined('ABSPATH')) {
    exit; // Impede acesso direto ao arquivo
}

// Registra o Custom Post Type "jobs"
function norven_jobs_register_cpt() {
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
add_action('init', 'norven_jobs_register_cpt');
