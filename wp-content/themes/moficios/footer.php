<footer>
    <div class="contenedor">
        <div class="footer-nav">
            <div class="div logo">
                <img src="<?php echo get_template_directory_uri(); ?>/img/logo-moficio-footer.svg" alt="">
            </div>
            <?php
                $footer = get_field('item_footer','options');
                if ($footer){
                    foreach ($footer as $fot) {
                ?>
                <div class="items-footer">
                    <h4><?php echo $fot['title']; ?></h4>
                    <?php
                        foreach ($fot['links'] as $k) {
                            ?>
                            <a href="<?php echo $k['link']; ?>"><?php echo $k['text']; ?></a>                            
                            <?php
                        }
                    ?>
                </div>
                <?php
                    }
                }
            ?>
        </div>
        <div class="derechos-autor">
            <div class="item-copy">© 2020 Todos los derechos reservados</div>
            <div class="rrss">
                <p>Siguenos</p>
                <?php
                    $social = get_field('social','options');
                    if ($social){
                        foreach ($social as $so) {
                    ?>
                    <a href="<?php echo $so['link']; ?>"><img src="<?php echo get_template_directory_uri(); ?>/img/icon-<?php echo $so['red_text']; ?>.svg" alt=""></a>
                    <?php
                        }
                    }
                ?>
            </div>
        </div>
    </div>
</footer>
<?php
wp_footer();
?>  
</body>
</html>