<?php
if (!defined('ABSPATH')) {
    exit;
}

// Adiciona metaboxes para os campos personalizados
function norven_jobs_add_meta_boxes() {
    add_meta_box('norven_jobs_meta', 'Detalhes da Vaga', 'norven_jobs_meta_callback', 'jobs', 'normal', 'high');
}
add_action('add_meta_boxes', 'norven_jobs_add_meta_boxes');

function norven_jobs_meta_callback($post) {
    $localizacao = get_post_meta($post->ID, '_norven_jobs_localizacao', true);
    $tipo = get_post_meta($post->ID, '_norven_jobs_tipo', true);
    $salario = get_post_meta($post->ID, '_norven_jobs_salario', true);
    ?>

    <label for="norven_jobs_localizacao">Localização:</label>
    <input type="text" name="norven_jobs_localizacao" value="<?php echo esc_attr($localizacao); ?>" />

    <label for="norven_jobs_tipo">Tipo de Contratação:</label>
    <select name="norven_jobs_tipo">
        <option value="Presencial" <?php selected($tipo, 'Presencial'); ?>>Presencial</option>
        <option value="Remoto" <?php selected($tipo, 'Remoto'); ?>>Remoto</option>
    </select>

    <label for="norven_jobs_salario">Salário (Opcional):</label>
    <input type="text" name="norven_jobs_salario" value="<?php echo esc_attr($salario); ?>" />

    <?php
}

// Salva os dados dos campos personalizados
function norven_jobs_save_meta($post_id) {
    if (array_key_exists('norven_jobs_localizacao', $_POST)) {
        update_post_meta($post_id, '_norven_jobs_localizacao', sanitize_text_field($_POST['norven_jobs_localizacao']));
    }
    if (array_key_exists('norven_jobs_tipo', $_POST)) {
        update_post_meta($post_id, '_norven_jobs_tipo', sanitize_text_field($_POST['norven_jobs_tipo']));
    }
    if (array_key_exists('norven_jobs_salario', $_POST)) {
        update_post_meta($post_id, '_norven_jobs_salario', sanitize_text_field($_POST['norven_jobs_salario']));
    }
}
add_action('save_post', 'norven_jobs_save_meta');