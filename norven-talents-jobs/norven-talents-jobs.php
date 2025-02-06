<?php
/**
 * Plugin Name: Norven Talents Jobs Versão 2
 * Description: Plugin para cadastro de vagas de emprego.
 * Version: 1.1
 * Author: Vitor Dias
 */

// Evita acesso direto ao arquivo
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Sair se acessado diretamente
}

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
        'rewrite'            => array( 'slug' => 'vaga' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor' ),
        'register_meta_box_cb' => 'ntj_add_custom_fields'
    );

    register_post_type( 'vaga', $args );
}
add_action( 'init', 'ntj_register_job_post_type' );

// Função para adicionar campos personalizados
function ntj_add_custom_fields() {
    add_meta_box(
        'ntj_job_details',
        'Detalhes da Vaga',
        'ntj_render_job_details',
        'vaga',
        'normal',
        'high'
    );
}

function ntj_render_job_details( $post ) {
    // Adicione campos personalizados aqui
    // Exemplo: Título da Vaga, Descrição, Requisitos, Localização, Tipo de Contratação, Salário
    // Utilize a função get_post_meta() para recuperar os valores
}

// Shortcode para exibir as vagas
function ntj_job_shortcode() {
    // Lógica para exibir as vagas cadastradas
}
add_shortcode( 'norven_jobs', 'ntj_job_shortcode' );

// Endpoint REST API
add_action( 'rest_api_init', function () {
    register_rest_route( 'ntj/v1', '/jobs', array(
        'methods' => 'GET',
        'callback' => 'ntj_get_jobs',
    ));
});

function ntj_get_jobs() {
    // Lógica para retornar as vagas em formato JSON
}

// Enfileirar scripts e estilos
function ntj_enqueue_scripts() {
    wp_enqueue_style( 'ntj-style', plugins_url( 'assets/css/public-style.css', __FILE__ ) );
    wp_enqueue_script( 'ntj-script', plugins_url( 'assets/js/public-script.js', __FILE__ ), array('jquery'), null, true );
}
add_action( 'wp_enqueue_scripts', 'ntj_enqueue_scripts' );
?>
