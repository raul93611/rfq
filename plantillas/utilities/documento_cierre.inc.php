<!--*************************************************ALERT MODAL***************************************************************-->
<div class="modal fade" id="alert_delete_system" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title text-danger"><i class="fa fa-exclamation-triangle"></i> ALERT</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="alert_delete_body">
        <h4 class="text-center text-danger">Do you want to continue the action?</h4>
      </div>
      <div class="modal-footer">
        <a href="#" class="btn btn-success" id="continue_button"><i class="fas fa-check"></i> Continue</a>
      </div>
    </div>
  </div>
</div>

<div class="modal modal-danger fade" id="error_alert">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title text-danger"><i class="fa fa-exclamation-triangle"></i> ALERT</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="form_uncompleted_body">
        <h4 class="text-center text-danger">Code must be fill out</h4>
      </div>
    </div>
  </div>
</div>

<footer class="main-footer">
    <strong>Copyright &copy; 2021.</strong>
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0
    </div>
</footer>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.3.2/dist/chart.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.2.1/js/plugins/piexif.min.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.2.1/js/plugins/sortable.min.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.2.1/js/fileinput.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.2.1/themes/explorer-fa/theme.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script src="<?php echo DIST; ?>js/adminlte.min.js"></script>
<script src="<?php echo RUTA_JS; ?>main.js"></script>
</body>
</html>
