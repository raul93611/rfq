<input type="hidden" name="id_user" value="<?php echo $id_user; ?>">
<div class="card-body">
  <div id="errors"></div>
  <div class="form-group">
    <label for="password1">Password:</label>
    <input type="password" class="form-control form-control-sm" id="password" name="password" placeholder="Password">
  </div>
  <div class="form-group">
    <label for="password-confirmation">Password confirmation:</label>
    <input type="password" class="form-control form-control-sm" id="password-confirmation" name="password-confirmation" placeholder="Password">
  </div>
</div>
<div class="card-footer">
  <a class="btn btn-primary" id="go_back" href="<?php echo USERS; ?>"><i class="fa fa-reply"></i></a>
  <button type="submit" class="btn btn-success" name="update_password"><i class="fa fa-check"></i> Save</button>
</div>