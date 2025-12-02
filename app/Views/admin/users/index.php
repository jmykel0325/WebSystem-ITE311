<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h1 class="h4 mb-0"><i class="bi bi-people me-2"></i>Manage Users</h1>
  <a href="<?= site_url('admin/dashboard') ?>" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
</div>

<div class="card">
  <div class="card-header">
    <strong>User List</strong>
  </div>
  <div class="card-body table-responsive">
    <table class="table table-hover align-middle">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Email</th>
          <th>Role</th>
          <th>Created</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($users)): ?>
          <?php foreach ($users as $u): ?>
            <tr>
              <td><?= (int)$u['id'] ?></td>
              <td><?= esc($u['name'] ?? ($u['first_name'] ?? '')) ?></td>
              <td><?= esc($u['email']) ?></td>
              <td><span class="badge bg-primary text-uppercase"><?= esc($u['role'] ?? 'user') ?></span></td>
              <td><?= !empty($u['created_at']) ? date('M d, Y', strtotime($u['created_at'])) : '-' ?></td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="5" class="text-center text-muted">No users found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?= $this->endSection() ?>
