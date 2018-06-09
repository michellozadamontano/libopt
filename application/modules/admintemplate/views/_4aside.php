<!-- BEGIN SIDEBAR -->
<div class="page-sidebar-wrapper">
<!-- BEGIN SIDEBAR -->
<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
<!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
<div class="page-sidebar navbar-collapse collapse">
<!-- BEGIN SIDEBAR MENU -->
<!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
<!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
<!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
<!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
<!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
<ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true"
    data-slide-speed="200" style="padding-top: 20px">
<!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
<li class="sidebar-toggler-wrapper hide">
    <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
    <div class="sidebar-toggler"></div>
    <!-- END SIDEBAR TOGGLER BUTTON -->
</li>
<!-- DOC: To remove the search box from the sidebar you just need to completely remove the below "sidebar-search-wrapper" LI element -->

<!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
<!-- DOC: Apply "sidebar-search-bordered" class the below search form to have bordered search box -->
<!-- DOC: Apply "sidebar-search-bordered sidebar-search-solid" class the below search form to have bordered & solid search box -->

<!-- END RESPONSIVE QUICK SEARCH FORM -->

<?php $mes = $this->session->userdata('mes');
$year = $this->session->userdata('ano');
?>

<li class="nav-item start <?php if (($menuactivo == 'welcome') || ($menuactivo == 'update_plan') || ($menuactivo == 'tareasprincipales')) {
    echo ' open active';
}?>">
    <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-calendar"></i>
        <span class="title">Plan Trabajo</span>
        <span class="arrow"></span>
    </a>
    <ul class="sub-menu">

        <li class="nav-item start  <?php if ($menuactivo == 'welcome') {
            echo ' open active';
        }?> ">
            <a href="<?php echo site_url('welcome/index/' . $mes . '/' . $year); ?>"
               class="nav-link ">
                <i class="icon-calendar"></i>
                <span class="title">Plan Trabajo</span>
            </a>
        </li>

        <li class="nav-item start  <?php if ($menuactivo == 'tareasprincipales') {
            echo ' open active';
        }?> ">
            <a href="<?php echo site_url('tareasprincipales/index/' . $mes . '/' . $year); ?>"
               class="nav-link ">
                <i class="fa fa-tasks fa-3x"></i>
                <span class="title">Tareas principales </span>
            </a>
        </li>

    </ul>
</li>

<li class="nav-item start  <?php if (($menuactivo == 'predefinidas') || ($menuactivo == 'predefinida_fecha') || ($menuactivo == 'predefinidas_dias_fecha') || ($menuactivo == 'vacaciones') || ($menuactivo == 'predate')) {
    echo ' open active';
}?>">
    <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-pencil"></i>
        <span class="title">Tareas Predefinidas</span>
        <span class="arrow"></span>
    </a>
    <ul class="sub-menu">

        <li class="nav-item start <?php if ($menuactivo == 'predefinidas') {
            echo ' open active';
        }?>">
            <a href="<?php echo site_url('predefinidas'); ?>"
               class="nav-link ">
                <i class="icon-pencil"></i>
                <span class="title">Tareas por d&iacute;as</span>
            </a>
        </li>
        <li class="nav-item start <?php if ($menuactivo == 'predefinida_fecha') {
            echo ' open active';
        }?>">
            <a href="<?php echo site_url('predefinida_fecha'); ?>"
               class="nav-link ">
                <i class="icon-calendar"></i>
                <span class="title">Tareas por fechas</span>
            </a>
        </li>
        <li class="nav-item start <?php if ($menuactivo == 'predefinidas_dias_fecha') {
            echo ' open active';
        }?>">
            <a href="<?php echo site_url('predefinidas_dias_fecha'); ?>"
               class="nav-link ">
                <i class="icon-calendar"></i>
                <span class="title">Tareas seg&uacute;n d&iacute;as</span>
            </a>
        </li>
        <li class="nav-item start <?php if ($menuactivo == 'vacaciones') {
            echo ' open active';
        }?>">
            <a href="<?php echo site_url('vacaciones'); ?>"
               class="nav-link ">
                <i class="icon-plane"></i>
                <span class="title">Vacaciones</span>
            </a>
        </li>
        <li class="nav-item start <?php if ($menuactivo == 'predate') {
            echo ' open active';
        }?>">
            <a href="<?php echo site_url('predate'); ?>"
               class="nav-link ">
                <i class="icon-calendar"></i>
                <span class="title">Plan Anual</span>
            </a>
        </li>
    </ul>
</li>
<li class="nav-item start  <?php if (($menuactivo == 'grupo_participantes') || ($menuactivo == 'participantes') || ($menuactivo == 'categoria_flujo') || ($menuactivo == 'flujo_informativo')||($menuactivo == 'flujo_mes_report')||($menuactivo == 'import')) {
    echo ' open active';
}?>">
    <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-notebook"></i>
        <span class="title">Plan Anual Empresa</span>
        <span class="arrow"></span>
    </a>
    <ul class="sub-menu">

        <li class="nav-item start <?php if ($menuactivo == 'grupo_participantes') {
            echo ' open active';
        }?>">
            <a href="<?php echo site_url('grupo_participantes'); ?>"
               class="nav-link ">
                <i class="icon-users"></i>
                <span class="title">Grupo Participantes</span>
            </a>
        </li>
        <li class="nav-item start <?php if ($menuactivo == 'participantes') {
            echo ' open active';
        }?>">
            <a href="<?php echo site_url('participantes'); ?>"
               class="nav-link ">
                <i class="icon-user-follow"></i>
                <span class="title">Participantes</span>
            </a>
        </li>
        <li class="nav-item start <?php if ($menuactivo == 'categoria_flujo') {
            echo ' open active';
        }?>">
            <a href="<?php echo site_url('categoria_flujo'); ?>"
               class="nav-link ">
                <i class="icon-layers"></i>
                <span class="title">Categoria Tarea</span>
            </a>
        </li>
        <li class="nav-item start <?php if ($menuactivo == 'import') {
            echo ' open active';
        }?>">
            <a href="<?php echo site_url('flujo_informativo/import_excel'); ?>"
               class="nav-link ">
                <i class="fa fa-arrow-circle-o-up"></i>
                <span class="title">Importar Tareas</span>
            </a>
        </li>
        <li class="nav-item start <?php if ($menuactivo == 'flujo_informativo') {
            echo ' open active';
        }?>">
            <a href="<?php echo site_url('flujo_informativo'); ?>"
               class="nav-link ">
                <i class="icon-book-open"></i>
                <span class="title">Flujo de Actividades</span>
            </a>
        </li>
        <li class="nav-item start <?php if ($menuactivo == 'flujo_mes_report') {
            echo ' open active';
        }?>">
            <a href="<?php echo site_url('flujo_informativo/show_view_report'); ?>"
               class="nav-link ">
                <i class="icon-doc"></i>
                <span class="title">Reportes Osde</span>

            </a>
        </li>
    </ul>
</li>


<li class="nav-item start  <?php if (($menuactivo == 'diasvencidos') || ($menuactivo == 'tareas_incumplidas') || ($menuactivo == 'nuevas_tareas') || ($menuactivo == 'modelo') || ($menuactivo == 'analisis') || ($menuactivo == 'reportes')) {
    echo ' open active';
}?>">
    <a href="javascript:;" class="nav-link nav-toggle">
        <i class="fa fa-question fa-8x"></i>
        <span class="title">Cumplimiento</span>
        <span class="arrow"></span>
    </a>
    <ul class="sub-menu">
        <li class="nav-item start  <?php if ($menuactivo == 'diasvencidos') {
            echo ' open active';
        }?> ">
            <a href="<?php echo site_url('pt/get_dias_vencidos'); ?>"
               class="nav-link ">
                <i class="fa fa-thumbs-down fa-3x"></i>
                <span class="title">Mes actual </span>
            </a>
        </li>

        <!--<li class="nav-item start  <?php if ($menuactivo == 'tareas_incumplidas') {
            echo ' open active';
        }?> ">
                        <a href="<?php echo site_url('tareas_incumplidas'); ?>"
                           class="nav-link ">
                            <i class="fa fa-thumbs-down fa-3x"></i>
                            <span class="title">Mes anterior </span>
                        </a>
                    </li>-->
        <li class="nav-item start  <?php if ($menuactivo == 'nuevas_tareas') {
            echo ' open active';
        }?> ">
            <a href="<?php echo site_url('nuevas_tareas'); ?>"
               class="nav-link ">
                <i class="fa fa-tasks fa-3x"></i>
                <span class="title">Tareas extras  </span>
            </a>
        </li>
        <li class="nav-item start  <?php if ($menuactivo == 'analisis') {
            echo ' open active';
        }?> ">
            <a href="<?php echo site_url('analisis'); ?>"
               class="nav-link ">
                <i class="fa fa-exclamation fa-3x"></i>
                <span class="title">An&aacute;lisis </span>
            </a>
        </li>
        <li class="nav-item start  <?php if ($menuactivo == 'modelo') {
            echo ' open active';
        }?> ">
            <a href="<?php echo site_url('tareas_incumplidas/generarmodelo'); ?>"
               class="nav-link ">
                <i class="fa fa-file-excel-o fa-3x"></i>
                <span class="title">Vista Previa Modelo 2</span>
            </a>
        </li>
        <li class="nav-item start  <?php if ($menuactivo == 'reportes') {
            echo ' open active';
        }?> ">
            <a href="<?php echo site_url('tareas_incumplidas/reportes'); ?>"
               class="nav-link ">
                <i class="fa fa-file-pdf-o fa-3x"></i>
                <span class="title">Reportes</span>
            </a>
        </li>
    </ul>
</li>


    <li class="nav-item start  <?php if (($menuactivo == 'pt_anterior') ) {
        echo ' open active';
    }?>">
        <a href="javascript:;" class="nav-link nav-toggle">
            <i class="fa fa-calendar-check-o"></i>
            <span class="title">Planes Anteriores</span>
            <span class="arrow"></span>
        </a>
        <ul class="sub-menu">
            <li class="nav-item start <?php if ($menuactivo == 'pt_anterior') {
                echo ' open active';
            }?>">
                <a href="<?php echo site_url('welcome/pt_anteriores'); ?>"
                   class="nav-link ">
                    <i class="fa fa-calendar-plus-o"></i>
                    <span class="title">Mis planes anteriores</span>
                </a>
            </li>
        </ul>
    </li>

<li class="nav-item start  <?php if (($menuactivo == 'subordinados') || ($menuactivo == 'update_plan')) {
    echo ' open active';
}?>">
    <a href="javascript:;" class="nav-link nav-toggle">
        <i class="fa fa-group"></i>
        <span class="title">Subordinados</span>
        <span class="arrow"></span>
    </a>
    <ul class="sub-menu">
        <li class="nav-item start <?php if ($menuactivo == 'subordinados') {
            echo ' open active';
        }?>">
            <a href="<?php echo site_url('users/subordinados'); ?>"
               class="nav-link ">
                <i class="fa fa-group"></i>
                <span class="title">Mis subordinados</span>
            </a>
        </li>
        <li class="nav-item start <?php if ($menuactivo == 'update_plan') {
            echo ' open active';
        }?>">
            <a href="<?php echo site_url('pt/show_calendar_subor'); ?>"
               class="nav-link ">
                <i class="fa fa-tasks"></i>
                <span class="title">Asignar Tareas</span>
            </a>
        </li>
        <li class="nav-item start <?php if ($menuactivo == 'aprobacionpt') {
            echo ' open active';
        }?>">
            <a href="<?php echo site_url('aprobacionpt/subordinados'); ?>"
               class="nav-link ">
                <i class="fa fa-group"></i>
                <span class="title">Aprobación PT</span>
            </a>
        </li>
    </ul>
</li>

<!--
                <li class="nav-item start <?php if ($menuactivo == 'cargos') {
    echo ' open active';
}?>">
                    <a href="javascript:;" class="nav-link nav-toggle">
                        <i class="icon-settings"></i>
                        <span class="title">Cargos</span>
                        <span class="arrow"></span>
                    </a>
                    <ul class="sub-menu">
-->
<li class="nav-item start <?php if ($menuactivo == 'cargos') {
    echo ' open active';
}?>">
    <a href="<?php echo site_url('cargos'); ?>"
       class="nav-link ">
        <i class="icon-settings"></i>
        <span class="title">Cargos</span>
    </a>
</li>
<!--  </ul>
</li>-->
<li class="nav-item start <?php if (($menuactivo == 'grupo') || ($menuactivo == 'actividades')) {
    echo ' open active';
}?>">
    <a href="javascript:;" class="nav-link nav-toggle">
        <i class="fa fa-object-group"></i><i class="fa fa-tasks"></i>
        <span class="title">Grupos y Actividades</span>
        <span class="arrow"></span>
    </a>
    <ul class="sub-menu">

        <li class="nav-item start <?php if ($menuactivo == 'grupo') {
            echo ' open active';
        }?>">
            <a href="<?php echo site_url('grupo'); ?>"
               class="nav-link ">
                <i class="fa fa-object-group fa-4x"></i>
                <span class="title">Grupo</span>
            </a>
        </li>
        <li class="nav-item start <?php if ($menuactivo == 'actividades') {
            echo ' open active';
        }?>">
            <a href="<?php echo site_url('actividades'); ?>"
               class="nav-link ">
                <i class="fa fa-tasks fa-4x"></i>
                <span class="title">Actividades</span>
            </a>
        </li>
    </ul>
</li>

<!--
                <li class="nav-item start <?php if (($menuactivo == 'users') || ($menuactivo == 'adminrol')) {
    echo ' open active';
}?>">
                    <a href="javascript:;" class="nav-link nav-toggle">
                        <i class="icon-users"></i>
                        <span class="title">Usuarios</span>
                        <span class="arrow"></span>
                    </a>
                    <ul class="sub-menu">
                        -->

<?php
$isadmin = Modules::run('auth/isadmin', array());
$isdirectivo = Modules::run('auth/isdirectivo');

if ( ($isadmin) ||($isdirectivo)) {
    ?>
    <li class="nav-item start <?php if ($menuactivo == 'users') {
        echo ' open active';
    }?>">
        <a href="<?php echo site_url('users'); ?>"
           class="nav-link ">
            <i class="icon-user-following"></i>
            <span class="title">Usuarios</span>
        </a>
    </li>
<?php } ?>
<!--</ul>
</li>-->

</ul>
<!-- END SIDEBAR MENU -->
<!-- END SIDEBAR MENU -->
</div>
<!-- END SIDEBAR -->
</div>
<!-- END SIDEBAR -->