<!doctype html>
<html lang="<?php bloginfo( 'language' ) ?>">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/507b78cca7.js" crossorigin="anonymous"></script>
    <?php
        $entry = get_query_var('ENTRY');
        load_assets(['main', $entry]);
        wp_head();             
    ?>
</head>
<body>
    <header>
        <div class="contenedor">
            <a href="<?php echo site_url(); ?>">
                <img src="<?php echo get_template_directory_uri(); ?>/img/logo_moficio.svg" alt="">
            </a>
            <div class="register-or-login">
                <div class="login">
                    <?php
                        if ( is_user_logged_in() ) {
                            ?>                            
                            <div class="item-perfil">
                                <p>Tu</p>
                                <img src="<?php echo get_template_directory_uri(); ?>/img/icon/img-perfil.png" alt="">
                            </div>             
                            <?php
                        } else {
                            ?>
                            <p><a href="javascript:void(0)">Registrate</a> o<strong><a href="javascript:void(0)"> ingresa</a> </strong></p>
                            <div class="loginAa">                        
                                <?php do_action('facebook_login_button');?>
                            </div>
                            <?php
                        }
                    ?>
                </div>
                <a href="usuario.html">
                    <a href="javascript:void(0)" class="oficio">Ofrece tu oficio</a>
                </a>
            </div>
        </div>
    </header>
<!-- add oficio -->
    <div class="header_contact">
        <h2>Ofrece tu oficio</h2>
        <div class="header_contact__ru">            
            <?php echo do_shortcode('[contact-form-7 id="5" title="Contact form 1"]'); ?>
        </div>
    </div>
    <div class="header_contact__bull"></div>