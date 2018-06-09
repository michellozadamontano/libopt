<div class="row">
    <div class="col-md-12">
        <div class="thumbnail">
            <table class="table table-light">
                <tr>
                    <td>
                        <form class="form-inline" action="<?php echo site_url('flujo_informativo/flujo_mensual_report')?>" method="post" target="_blank">
                            <div class="form-group">
                                <label for="exampleInputName2">Seleccione el Mes</label>
                                <select class="form-control chosen-select" name="month" id="month">
                                    <option value="1">Enero</option>
                                    <option value="2">Febrero</option>
                                    <option value="3">Marzo</option>
                                    <option value="4">Abril</option>
                                    <option value="5">Mayo</option>
                                    <option value="6">Junio</option>
                                    <option value="7">Julio</option>
                                    <option value="8">Agosto</option>
                                    <option value="9">Septiembre</option>
                                    <option value="10">Octubre</option>
                                    <option value="11">Noviembre</option>
                                    <option value="12">Diciembre</option>
                                </select>
                            </div>
                            <button type="submit" class="fa fa-file-pdf-o fa-2x btn btn-danger">Plan Mensual Pdf</button>

                        </form>


                    </td>
                    <td>
                        <form class="form-inline" action="<?php echo site_url('flujo_informativo/export_excel')?>" method="post" target="_blank">
                            <div class="form-group">
                                <label for="exampleInputName2">Seleccione el Mes</label>
                                <select class="form-control chosen-select" name="month" id="month">
                                    <option value="1">Enero</option>
                                    <option value="2">Febrero</option>
                                    <option value="3">Marzo</option>
                                    <option value="4">Abril</option>
                                    <option value="5">Mayo</option>
                                    <option value="6">Junio</option>
                                    <option value="7">Julio</option>
                                    <option value="8">Agosto</option>
                                    <option value="9">Septiembre</option>
                                    <option value="10">Octubre</option>
                                    <option value="11">Noviembre</option>
                                    <option value="12">Diciembre</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary fa fa-file-excel-o fa-3x">Plan Mensual Excel</button>

                        </form>

                    </td>
                    <td>
                        <a href="<?php echo site_url('flujo_informativo/plan_anual') ?>" class="btn btn-default fa fa-file-excel-o fa-3x">Plan Anual Excel</a>
                    </td>
                </tr>
            </table>
        </div>

    </div>
</div>