<?php
if (!defined('ABSPATH')) {
    exit;
}

// Função para listar vagas no frontend
function norven_shortcode() {
    $args = array(
        'post_type'      => 'jobs',
        'posts_per_page' => 10,
        'post_status'    => 'publish',
    );

    $query = new WP_Query(query: $args);
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

// Função para criar formulário de contato
function norven_jobs_filter_form() {
    ob_start(); ?>
    <form id="job-filter-form">
        <select name="tipo_contratacao">
            <option value="">Tipo de Contratação</option>
            <option value="Remoto">Remoto</option>
            <option value="Presencial">Presencial</option>
            <option value="Híbrido">Híbrido</option>
        </select>

        <select name="localizacao">
            <option value="">Localização</option>
            <option value="São Paulo">São Paulo</option>
            <option value="Rio de Janeiro">Rio de Janeiro</option>
            <option value="Curitiba">Curitiba</option>
        </select>

        <button type="submit">Filtrar</button>
    </form>

    <div id="job-results"></div>

    <script>
        jQuery(document).ready(function($) {
            $('#job-filter-form').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '<?php echo admin_url("admin-ajax.php"); ?>',
                    data: $(this).serialize() + '&action=norven_jobs_filter',
                    success: function(response) {
                        $('#job-results').html(response);
                    }
                });
            });
        });
    </script>
    <?php return ob_get_clean();
}
add_shortcode('norven_jobs_filter', 'norven_jobs_filter_form');
