<?php
if (!defined('ABSPATH')) {
    exit;
}

// Função para listar vagas no frontend
function norven_jobs_shortcode() {
    $args = array(
        'post_type'      => 'jobs',
        'posts_per_page' => 10,
        'post_status'    => 'publish',
    );

    $query = new WP_Query($args);
    $output = '<div class="norven-jobs-list">';

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $localizacao = get_post_meta(get_the_ID(), '_norven_jobs_localizacao', true);
            $tipo = get_post_meta(get_the_ID(), '_norven_jobs_tipo', true);
            $output .= '<div class="norven-job">';
            $output .= '<h3>' . get_the_title() . '</h3>';
            $output .= '<p><strong>Localização:</strong> ' . esc_html($localizacao) . '</p>';
            $output .= '<p><strong>Tipo:</strong> ' . esc_html($tipo) . '</p>';
            $output .= '</div>';
        }
        wp_reset_postdata();
    } else {
        $output .= '<p>Nenhuma vaga encontrada.</p>';
    }

    $output .= '</div>';
    return $output;
}
add_shortcode('norven_jobs', 'norven_jobs_shortcode');

//