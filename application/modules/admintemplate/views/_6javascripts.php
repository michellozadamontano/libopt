<!--[if lt IE 9]>
<script src="<?php echo base_url('assets/global/plugins/respond.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/global/plugins/excanvas.min.js'); ?>"></script> 
<![endif]-->
<!-- BEGIN CORE PLUGINS -->
<script src="<?php echo base_url('js/jquery-1.11.0.js')?>"></script>
<!--<script src="<?php echo base_url('assets/global/plugins/jquery.min.js'); ?>" type="text/javascript"></script>-->
<script src="<?php echo base_url('assets/global/plugins/bootstrap/js/bootstrap.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/js.cookie.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/jquery.blockui.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/uniform/jquery.uniform.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-toastr/toastr.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/counterup/jquery.waypoints.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/counterup/jquery.counterup.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/jquery-nestable/jquery.nestable.js'); ?>" type="text/javascript"></script>

<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="<?php echo base_url('assets/global/scripts/app.min.js'); ?>" type="text/javascript"></script>




<script src="<?php echo base_url('assets/global/plugins/jquery-ui/jquery-ui.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/jquery-multi-select/js/jquery.multi-select.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/bootstrap-selectsplitter/bootstrap-selectsplitter.min.js'); ?>" type="text/javascript"></script>


<!-- END CORE PLUGINS -->
<!-- END THEME GLOBAL SCRIPTS -->
<!-- BEGIN THEME LAYOUT SCRIPTS -->
<script src="<?php echo base_url('assets/layouts/layouts/scripts/layout.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/layouts/layouts/scripts/demo.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/layouts/global/scripts/quick-sidebar.min.js'); ?>" type="text/javascript"></script>
<!-- END THEME LAYOUT SCRIPTS -->

<script type="text/javascript" src="<?php echo base_url('assets/global/plugins/select2/js/select2.full.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/pages/scripts/components-select2.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/pages/scripts/portlet-draggable.min.js'); ?>"></script>


<script src="<?= base_url();?>js/zunpt.js"></script>
<script type="text/javascript" src="<?= base_url();?>js/jquery.quicksearch.js"></script>
<!--<script src="<?= base_url();?>js/jquery.ui.datepicker-es.js"></script>
<script src="<?php echo base_url('js/moment.min.js');?>"></script>
<script src="<?= base_url();?>js/fullcalendar.min.js"></script>
<script src="<?= base_url();?>js/es.js"></script>
<script src="<?= base_url();?>js/calendar.js"></script>-->
<script src="<?php echo base_url('jquery-ui/ui/jquery-ui.js')?>"></script>
<!--<script src="<?php echo base_url('jquery-ui/ui/jquery.ui.datepicker.js')?>"></script>-->
<script src="<?php echo base_url('js/jquery.ui.datepicker-es.js')?>"></script>
<script src="<?php echo base_url('js/jquery.maskedinput.js')?>"></script>
<script src="<?php echo base_url('js/jquery.validate.js')?>"></script>
<script src="<?php echo base_url('js/ion.rangeSlider.min.js')?>"></script>
<script src="<?php echo base_url('js/formularios.js')?>"></script>
<script src="<?php echo base_url('chosen/chosen.jquery.js')?>" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo base_url('chosen/docsupport/prism.js')?>" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo base_url('js/jquery-ui.multidatespicker.js')?>"></script>

<script>
    var base_url="<?php echo base_url();?>";
    var current_url="<?php echo current_url();?>";

</script>
<script type="text/javascript">
    var config = {
        '.chosen-select'           : {},
        '.chosen-select-deselect'  : {allow_single_deselect:true},
        '.chosen-select-no-single' : {disable_search_threshold:10},
        '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
        '.chosen-select-width'     : {width:"95%"}
    };
    for (var selector in config) {
        if (config.hasOwnProperty(selector)) {
            $(selector).chosen(config[selector]);
        }
    }
</script>



<script>
    $(document).ready(function() {
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "positionClass": "toast-top-center",
            "onclick": null,
            "showDuration": "1000",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        <?php
            echo 'var infosms = "' . $this->session->flashdata('message') . '";';
        ?>

        if (infosms) {
            toastr['info'](infosms, "Informacion:");
        }
    });

    // $('.BSswitch').bootstrapSwitch();

</script>
<!--<script src="<?php echo base_url('assets/pages/scripts/ui-nestable.js'); ?>" type="text/javascript"></script>-->

