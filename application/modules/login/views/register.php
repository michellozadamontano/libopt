<?php
defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="col-md-7">

    <?php echo form_open(base_url('login/register'),['class' => "form-horizontal"]) ?>
        <div class="form-group">
            <label for="inputfullname" class="col-sm-2 control-label">Full Name</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="inputfullname" name="fullname" placeholder="Full Name">
            </div>
        </div>
        <div class="form-group">
            <label for="inputusername" class="col-sm-2 control-label">User Name</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="inputusername" name="username" placeholder="User Name">
            </div>
        </div>

        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
            <div class="col-sm-10">
                <input type="email" class="form-control" id="inputEmail3" name="email" placeholder="Email">
            </div>
        </div>
        <div class="form-group">
            <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" id="inputPassword3" name="password" placeholder="Password">
            </div>
        </div>
        <div class="form-group">
            <label for="rol" class="col-sm-2 control-label">Rol</label>
            <div class="col-sm-10">
                <select name="rol" id="rol" class="form-control">
                    <?php foreach($query->result() as $row):?>
                    <option value="<?php echo $row->id ?>"><?php echo $row->description ?></option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="cargos" class="col-sm-2 control-label">Cargo</label>
            <div class="col-sm-10">
             <?php echo Modules::run('cargos/_draw_dropdown') ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <div class="checkbox">
                    <label>
                        <input type="checkbox"> Remember me
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default">Crear</button>
            </div>
        </div>
    </form>
</div>