<?php
require_once 'config.php';
?>
  <a href='<?= $client->createAuthUrl() ?>' style="text-decoration: none">
    <div id="g_id_onload" data-client_id="386998533472-mcsvj2g5oj0um90rh05vipr0051ap2e8.apps.googleusercontent.com" data-context="signin" data-ux_mode="redirect" data-login_uri="http://localhost/project1-fall2024/client/php-google-login/google-login.php" data-auto_prompt="false">
    </div>

    <div style="width: 300px; margin-top: 20px; height: 50px; margin-left: 50px;" class="g_id_signin" data-type="standard" data-shape="pill" data-theme="outline" data-text="signin_with" data-size="medium" data-logo_alignment="left" data-width="200px">
    </div>
  </a>

<?php
// }
?>
<script src="https://accounts.google.com/gsi/client" async></script>