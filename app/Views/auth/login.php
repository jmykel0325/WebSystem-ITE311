<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<h1 class="h4 mb-3">Sign in</h1>

<?php if(session('error')): ?>
  <div class="alert alert-danger"><?= session('error') ?></div>
<?php endif; ?>
<?php if(isset($validation)): ?>
  <div class="alert alert-danger"><?= $validation->listErrors() ?></div>
<?php endif; ?>

<form method="post" action="<?= site_url('login') ?>">
  <?= csrf_field() ?>
  <div class="mb-3">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control" value="<?= old('email') ?>">
  </div>
  <div class="mb-3">
    <label class="form-label">Password</label>
    <input type="password" name="password" class="form-control">
  </div>
  <button class="btn btn-primary">Log In</button>
</form>

<p class="mt-3">No account? <a href="<?= site_url('register') ?>">Register</a></p>
<?= $this->endSection() ?>
