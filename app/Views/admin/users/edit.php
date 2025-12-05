<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h1 class="h4 mb-0"><i class="bi bi-person-gear me-2"></i>Edit User</h1>
  <a href="<?= site_url('admin/users') ?>" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
</div>

<div class="card">
  <div class="card-body">
    <form method="POST" action="<?= site_url('admin/users/update/' . (int)$user['id']) ?>">
      <?= csrf_field() ?>

      <div class="mb-3">
        <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="name" name="name" value="<?= old('name', $user['name']) ?>" required>
        <?php if (session()->getFlashdata('errors.name')): ?>
          <div class="text-danger small"><?= session()->getFlashdata('errors.name') ?></div>
        <?php endif; ?>
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
        <input type="email" class="form-control" id="email" name="email" value="<?= old('email', $user['email']) ?>" required>
        <?php if (session()->getFlashdata('errors.email')): ?>
          <div class="text-danger small"><?= session()->getFlashdata('errors.email') ?></div>
        <?php endif; ?>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" minlength="6" placeholder="Leave blank to keep current password">
        <?php if (session()->getFlashdata('errors.password')): ?>
          <div class="text-danger small"><?= session()->getFlashdata('errors.password') ?></div>
        <?php endif; ?>
      </div>

      <div class="mb-3">
        <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
        <?php if ((int) session('user_id') === (int) $user['id'] || (string) session('email') === (string) $user['email']): ?>
          <input type="text" class="form-control" value="<?= strtoupper(esc($user['role'])) ?>" disabled>
          <input type="hidden" name="role" value="<?= esc($user['role']) ?>">
          <div class="form-text text-muted">You cannot change your own role while logged in.</div>
        <?php else: ?>
          <select id="role" name="role" class="form-select" required>
            <option value="admin" <?= old('role', $user['role']) === 'admin' ? 'selected' : '' ?>>Admin</option>
            <option value="teacher" <?= old('role', $user['role']) === 'teacher' ? 'selected' : '' ?>>Teacher</option>
            <option value="student" <?= old('role', $user['role']) === 'student' ? 'selected' : '' ?>>Student</option>
          </select>
        <?php endif; ?>
        <?php if (session()->getFlashdata('errors.role')): ?>
          <div class="text-danger small"><?= session()->getFlashdata('errors.role') ?></div>
        <?php endif; ?>
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i>Update User</button>
        <a href="<?= site_url('admin/users') ?>" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>

<?= $this->endSection() ?>
