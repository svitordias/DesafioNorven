<?php
if (!defined(constant_name: 'ABSPATH')) {
    exit;
}

function norven_jobs_application_form():bool {
    ob_start(); ?>
    <form id="job-application-form" enctype="multipart/form-data">
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
                    url: '<?php echo admin_url(path: "admin-ajax.php"); ?>',
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

add_shortcode(tag: 'norven_job_application', callback: 'norven_jobs_application_form');