<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');?>
</div><!-- /.content-wrapper -->

<footer class="main-footer">
    <strong>Copyright &copy; <?php echo date("Y");?> <a href="http://autox.pro">CMS AutoX</a>.</strong> All rights reserved.
</footer>
<div class="control-sidebar-bg"></div>

</div><!-- ./wrapper -->
<script type="text/javascript">
    var reformalOptions = {
        project_id: 980760,
        project_host: "autox-pro.reformal.ru",
        tab_orientation: "bottom-right",
        tab_indent: "10px",
        tab_bg_color: "#000000",
        tab_border_color: "#FFFFFF",
        tab_image_url: "http://tab.reformal.ru/0J%252FRgNC10LTQu9C%252B0LbQtdC90LjRjw==/FFFFFF/3299ddc96cf2600fd0984ca7d9c32e9c/bottom-right/0/tab.png",
        tab_border_width: 0
    };

    (function() {
        var script = document.createElement('script');
        script.type = 'text/javascript'; script.async = true;
        script.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'media.reformal.ru/widgets/v3/reformal.js';
        document.getElementsByTagName('head')[0].appendChild(script);
    })();
</script><noscript><a href="http://reformal.ru"><img src="http://media.reformal.ru/reformal.png" /></a><a href="http://autox-pro.reformal.ru">Предложения</a></noscript>
<script src="<?php echo theme_url();?>admin/main.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="<?php echo theme_url();?>admin/bootstrap/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="<?php echo theme_url();?>admin/plugins/fastclick/fastclick.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo theme_url();?>admin/dist/js/app.min.js"></script>
<!-- Sparkline -->
<script src="<?php echo theme_url();?>admin/plugins/sparkline/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="<?php echo theme_url();?>admin/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?php echo theme_url();?>admin/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- SlimScroll 1.3.0 -->
<script src="<?php echo theme_url();?>admin/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- ChartJS 1.0.1 -->
<script src="<?php echo theme_url();?>admin/plugins/chartjs/Chart.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo theme_url();?>admin/dist/js/demo.js"></script>

<script src="<?php echo theme_url();?>admin/plugins/colorpicker/bootstrap-colorpicker.min.js"></script>
<script src="//cdn.ckeditor.com/4.5.9/full/ckeditor.js"></script>
<script type="text/javascript">
    $( document ).ready(function() {
        $('a[href="/<?php echo $this->uri->uri_string() ?>"]').parents('li').addClass('active');
    });
</script>
<?php if(ENVIRONMENT == 'development' || $this->input->get('debug_show')){
    $this->output->enable_profiler(TRUE);
}?>
</body>
</html>
