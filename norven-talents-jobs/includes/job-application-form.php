<?php
if (!defined('ABSPATH')) {
    exit; // Impede acesso direto ao arquivo
}

// Função para exibir o formulário de candidatura
function norven_jobs_application_form() {
    ob_start(); ?>
    <form id="job-application-form" enctype="multipart/form-data" method="POST">
        <input type="text" name="nome" placeholder="Seu Nome" required>
        <input type="email" name="email" placeholder="Seu E-mail" required>
        <input type="file" name="curriculo" required>
        <input type="hidden" name="vaga_id" value="<?php echo get_the_ID(); ?>">
        <button type="submit">Enviar Candidatura</button>
    </form>

    <div id="application-message"></div>

    <script>
        jQuery(document).ready(function($) {
            $('#job-application-form').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                formData.append('action', 'norven_submit_application');

                $.ajax({
                    type: 'POST',
                    url: '<?php echo admin_url("admin-ajax.php"); ?>',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#application-message').html(response);
                    }
                });
            });
        });
    </script>
    <?php return ob_get_clean(); 
}

// Adiciona o shortcode para o formulário de candidatura
add_shortcode('norven_job_application', 'norven_jobs_application_form');

// Lógica para processar a candidatura
function norven_submit_application() {
    // Verifica se os dados foram enviados
    if (isset($_POST['nome']) && isset($_POST['email']) && isset($_FILES['curriculo'])) {
        $nome = sanitize_text_field($_POST['nome']);
        $email = sanitize_email($_POST['email']);
        $vaga_id = intval($_POST['vaga_id']);

        // Processa o arquivo de currículo
        $uploaded_file = $_FILES['curriculo'];
        // Aqui você pode adicionar a lógica para mover o arquivo para o diretório desejado

        // Envia uma resposta ao usuário
        wp_send_json_success('Candidatura enviada com sucesso!');
    } else {
        wp_send_json_error('Erro ao enviar a candidatura.');
    }
}
add_action('wp_ajax_norven_submit_application', 'norven_submit_application');
add_action('wp_ajax_nopriv_norven_submit_application', 'norven_submit_application');
?>
