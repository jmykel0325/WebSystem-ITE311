<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<h1 class="h4 mb-3">Create an account</h1>

<?php if(isset($validation)): ?>
  <div class="alert alert-danger"><?= $validation->listErrors() ?></div>
<?php endif; ?>

<form method="post" action="<?= site_url('register') ?>">
  <?= csrf_field() ?>
  <div class="mb-3">
    <label class="form-label">Name</label>
    <input name="name" class="form-control" value="<?= old('name') ?>">
  </div>
  <div class="mb-3">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control" value="<?= old('email') ?>">
  </div>
  <div class="mb-3">
    <label class="form-label">Password</label>
    <input type="password" name="password" class="form-control">
  </div>
  <div class="mb-3">
    <label class="form-label">Confirm Password</label>
    <input type="password" name="password_confirm" class="form-control">
  </div>
  <button class="btn btn-primary">Register</button>
</form>

<p class="mt-3">Already have an account? <a href="<?= site_url('login') ?>">Login</a></p>
<?= $this->endSection() ?>
