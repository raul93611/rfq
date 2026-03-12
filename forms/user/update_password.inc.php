<input type="hidden" name="id_user" value="<?= $id_user; ?>">

<div class="card-body user-form">
  <div id="errors" class="mb-3"></div>

  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="password">New Password</label>
      <input type="password" class="form-control" id="password" name="password" placeholder="Min. 6 characters" required>
    </div>
    <div class="form-group col-md-6">
      <label for="password-confirmation">Confirm Password</label>
      <input type="password" class="form-control" id="password-confirmation" name="password-confirmation" placeholder="Repeat password" required>
    </div>
  </div>
</div>

<div class="card-footer d-flex justify-content-end" style="background:transparent;border-top:1px solid #f0f2f5;gap:8px;">
  <a class="btn btn-secondary btn-sm" href="<?= USERS; ?>">
    <i class="fas fa-times mr-1"></i> Cancel
  </a>
  <button type="submit" class="btn btn-primary btn-sm" name="update_password">
    <i class="fas fa-check mr-1"></i> Update Password
  </button>
</div>
