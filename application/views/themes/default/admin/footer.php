<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');?>
</div><!-- /.content-wrapper -->

<footer class="main-footer">
    <strong>Copyright &copy; <?php echo date("Y");?> <a href="http://cms.autoxcatalog.com">CMS AutoX</a>.</strong> All rights reserved.
</footer>
<div class="control-sidebar-bg"></div>

</div><!-- ./wrapper -->

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
</body>
</html>
