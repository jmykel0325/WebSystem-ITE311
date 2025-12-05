<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h1 class="h4 mb-0"><i class="bi bi-people me-2"></i>Manage Users</h1>
  <div class="d-flex gap-2">
    <a href="<?= site_url('admin/users/create') ?>" class="btn btn-primary"><i class="bi bi-person-plus"></i> Add User</a>
    <a href="<?= site_url('admin/dashboard') ?>" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
  </div>
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
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($users)): ?>
          <?php foreach ($users as $u): ?>
            <tr>
              <td><?= (int)$u['id'] ?></td>
              <td><?= esc($u['name'] ?? ($u['first_name'] ?? '')) ?></td>
              <td><?= esc($u['email']) ?></td>
              <td>
                <?php if (($u['role'] ?? '') === 'deleted'): ?>
                  <span class="badge bg-secondary text-uppercase">DELETED</span>
                <?php else: ?>
                  <span class="badge bg-primary text-uppercase"><?= esc($u['role'] ?? 'user') ?></span>
                <?php endif; ?>
              </td>
              <td><?= !empty($u['created_at']) ? date('M d, Y', strtotime($u['created_at'])) : '-' ?></td>
              <td>
                <?php if ((int)session('user_id') !== (int)$u['id'] && (string)session('email') !== (string)$u['email']): ?>
                  <?php if (($u['role'] ?? '') !== 'deleted'): ?>
                    <a href="<?= site_url('admin/users/edit/' . (int)$u['id']) ?>" class="btn btn-sm btn-outline-primary me-1">
                      <i class="bi bi-pencil-square"></i> Edit
                    </a>
                    <a href="<?= site_url('admin/users/delete/' . (int)$u['id']) ?>" class="btn btn-sm btn-outline-danger"
                       onclick="return confirm('Are you sure you want to mark this account as deleted?');">
                      <i class="bi bi-trash"></i> Delete
                    </a>
                  <?php else: ?>
                    <span class="text-muted small">Deleted</span>
                  <?php endif; ?>
                <?php else: ?>
                  <span class="badge bg-info text-dark small">Current admin (in use)</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="6" class="text-center text-muted">No users found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?= $this->endSection() ?>
