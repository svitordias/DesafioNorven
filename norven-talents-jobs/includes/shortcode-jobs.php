<?php
if (!defined('ABSPATH')) {
    exit; // Impede acesso direto ao arquivo
}

// Função para listar vagas no frontend com filtro
function norven_shortcode() {
    $args = array(
        'post_type'      => 'jobs',
        'posts_per_page' => 10,
        'post_status'    => 'publish',
    );

    // Verifica se há um filtro aplicado
    if (isset($_GET['tipo_contratacao']) && !empty($_GET['tipo_contratacao'])) {
        $args['meta_query'] = array(
            array(
                'key'     => '_norven_jobs_tipo',
                'value'   => sanitize_text_field($_GET['tipo_contratacao']),
                'compare' => '='
            )
        );
    }

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
add_shortcode('norven_jobs_list', 'norven_shortcode');

// Função para criar formulário de filtro
function norven_jobs_filter_form() {
    ob_start(); ?>
    <form id="job-filter-form" method="GET">
        <select name="tipo_contratacao">
            <option value="">Tipo de Contratação</option>
            <option value="Presencial">Presencial</option>
            <option value="Remoto">Remoto</option>
        </select>
        <button type="submit">Filtrar</button>
    </form>
    <div id="job-results"></div>
    <?php return ob_get_clean();
}
add_shortcode('norven_jobs_filter', 'norven_jobs_filter_form');
?>
