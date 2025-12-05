<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
  /** @var \CodeIgniter\Validation\Validation $validation */
  $validation = $validation ?? \Config\Services::validation();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h1 class="h4 mb-0"><i class="bi bi-person-plus me-2"></i>Add User</h1>
  <a href="<?= site_url('admin/users') ?>" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
</div>

<?php if (session()->getFlashdata('success')): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= esc(session()->getFlashdata('success')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?= esc(session()->getFlashdata('error')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>

<div class="card">
  <div class="card-body">
    <form method="POST" action="<?= site_url('admin/users/store') ?>">
      <?= csrf_field() ?>

      <div class="mb-3">
        <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
        <input
          type="text"
          class="form-control <?= $validation->getError('name') ? 'is-invalid' : '' ?>"
          id="name"
          name="name"
          value="<?= old('name') ?>"
          minlength="3"
          maxlength="50"
          required
        >
        <?php if ($validation->getError('name')): ?>
          <div class="invalid-feedback">
            <?= esc($validation->getError('name')) ?>
          </div>
        <?php endif; ?>
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
        <input
          type="email"
          class="form-control <?= $validation->getError('email') ? 'is-invalid' : '' ?>"
          id="email"
          name="email"
          value="<?= old('email') ?>"
          required
        >
        <?php if ($validation->getError('email')): ?>
          <div class="invalid-feedback">
            <?= esc($validation->getError('email')) ?>
          </div>
        <?php endif; ?>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
        <input
          type="password"
          class="form-control <?= $validation->getError('password') ? 'is-invalid' : '' ?>"
          id="password"
          name="password"
          minlength="6"
          maxlength="32"
          required
        >
        <?php if ($validation->getError('password')): ?>
          <div class="invalid-feedback">
            <?= esc($validation->getError('password')) ?>
          </div>
        <?php else: ?>
          <div class="form-text">6–32 characters.</div>
        <?php endif; ?>
      </div>

      <div class="mb-3">
        <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
        <select
          id="role"
          name="role"
          class="form-select <?= $validation->getError('role') ? 'is-invalid' : '' ?>"
          required
        >
          <option value="" disabled <?= old('role') ? '' : 'selected' ?>>Select role</option>
          <option value="admin" <?= old('role') === 'admin' ? 'selected' : '' ?>>Admin</option>
          <option value="teacher" <?= old('role') === 'teacher' ? 'selected' : '' ?>>Teacher</option>
          <option value="student" <?= old('role') === 'student' ? 'selected' : '' ?>>Student</option>
        </select>
        <?php if ($validation->getError('role')): ?>
          <div class="invalid-feedback d-block">
            <?= esc($validation->getError('role')) ?>
          </div>
        <?php endif; ?>
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i>Save User</button>
        <a href="<?= site_url('admin/users') ?>" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>

<?= $this->endSection() ?>
