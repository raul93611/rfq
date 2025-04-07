<input type="hidden" name="id_user" value="<?php echo $id_user; ?>">

<div class="card-body">
  <div id="errors"></div>

  <div class="form-group">
    <label for="password">Password:</label>
    <input
      type="password"
      class="form-control form-control-sm"
      id="password"
      name="password"
      placeholder="Enter new password"
      required>
    <small class="form-text text-muted">
      Choose a strong password with at least 8 characters, including letters and numbers.
    </small>
  </div>

  <div class="form-group">
    <label for="password-confirmation">Password Confirmation:</label>
    <input
      type="password"
      class="form-control form-control-sm"
      id="password-confirmation"
      name="password-confirmation"
      placeholder="Confirm new password"
      required>
    <small class="form-text text-muted">
      Re-enter your password to confirm it matches.
    </small>
  </div>
</div>

<div class="card-footer">
  <a class="btn btn-secondary" id="go_back" href="<?php echo USERS; ?>">
    <i class="fa fa-reply"></i> Back
  </a>
  <button
    type="submit"
    class="btn btn-success"
    name="update_password">
    <i class="fa fa-check"></i> Save
  </button>
</div>