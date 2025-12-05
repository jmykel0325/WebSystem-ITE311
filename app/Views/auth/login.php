<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-center align-items-center" style="min-height: calc(100vh - 140px);">
  <div class="row w-100" style="max-width: 960px;">
    <!-- Left: Login form -->
    <div class="col-lg-6 px-4 py-4 py-lg-5 bg-white rounded-start-4 border border-end-0">
      <h1 class="h4 mb-3">Sign in</h1>

      <?php if(session('error')): ?>
        <div class="alert alert-danger small"><?= session('error') ?></div>
      <?php endif; ?>
      <?php if(isset($validation)): ?>
        <div class="alert alert-danger small"><?= $validation->listErrors() ?></div>
      <?php endif; ?>

      <form method="post" action="<?= site_url('login') ?>" class="mt-3">
        <?= csrf_field() ?>
        <div class="mb-3">
          <label class="form-label small text-muted">Email</label>
          <input type="email" name="email" class="form-control" value="<?= old('email') ?>">
        </div>
        <div class="mb-2">
          <label class="form-label small text-muted">Password</label>
          <input type="password" name="password" class="form-control">
        </div>
        <div class="d-grid mt-3">
          <button class="btn btn-primary">Log In</button>
        </div>
      </form>

      <p class="mt-3 small text-muted">No account? <a href="<?= site_url('register') ?>">Register</a></p>
    </div>

    <!-- Right: Illustration / brand panel -->
    <div class="col-lg-6 d-none d-lg-flex align-items-center justify-content-center rounded-end-4" style="background: linear-gradient(135deg, #fe4f02, #f19361);">
      <div class="text-center text-white px-4">
        <h2 class="h4 fw-semibold mb-2">Welcome back to ITE311</h2>
        <p class="small mb-0">Access your courses, materials, and announcements in one simple learning portal.</p>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
