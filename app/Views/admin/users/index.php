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
  <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <strong>User List</strong>
    <div class="ms-auto" style="max-width: 260px; min-width: 200px;">
      <div class="input-group input-group-sm shadow-sm" style="border-radius: 999px; overflow: hidden;">
        <span class="input-group-text bg-white border-end-0">
          <i class="bi bi-search text-muted"></i>
        </span>
        <input
          type="text"
          id="adminUserSearch"
          class="form-control border-start-0"
          placeholder="Search name or email..."
          autocomplete="off"
          style="box-shadow: none;">
      </div>
    </div>
  </div>
  <div class="card-body table-responsive">
    <table class="table table-hover align-middle" id="adminUsersTable">
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
          <?php $row = 1; ?>
          <?php foreach ($users as $u): ?>
            <tr>
              <td><?= $row++ ?></td>
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
                    <a href="<?= site_url('admin/users/restore/' . (int)$u['id']) ?>" class="btn btn-sm btn-outline-success"
                       onclick="return confirm('Restore this deleted account?');">
                      <i class="bi bi-arrow-counterclockwise"></i> Restore
                    </a>
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

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
  var searchInput = document.getElementById('adminUserSearch');
  var table       = document.getElementById('adminUsersTable');
  if (!searchInput || !table) return;

  var tbody = table.querySelector('tbody');
  if (!tbody) return;

  searchInput.addEventListener('input', function () {
    var term = (this.value || '').toLowerCase().trim();
    var rows = tbody.querySelectorAll('tr');

    rows.forEach(function (row) {
      var nameCell  = row.querySelector('td:nth-child(2)');
      var emailCell = row.querySelector('td:nth-child(3)');

      var nameText  = (nameCell  ? nameCell.textContent  : '').toLowerCase().trim();
      var emailText = (emailCell ? emailCell.textContent : '').toLowerCase().trim();

      // Show all rows when search is empty; otherwise match if NAME or EMAIL starts with the term
      var match = !term || nameText.startsWith(term) || emailText.startsWith(term);
      row.style.display = match ? '' : 'none';
    });
  });
});
</script>
<?= $this->endSection() ?>
