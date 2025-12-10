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
      <tbody id="adminUsersTbody">
        <?= $this->include('admin/users/_rows'); ?>
      </tbody>
    </table>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
  var searchInput = document.getElementById('adminUserSearch');
  var tbody       = document.getElementById('adminUsersTbody');
  if (!searchInput || !tbody) return;

  var searchUrl = '<?= site_url('admin/users/search') ?>';
  var debounceTimer = null;

  function performSearch() {
    var term = searchInput.value || '';
    var url  = searchUrl + '?q=' + encodeURIComponent(term);

    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
      .then(function (res) { return res.text(); })
      .then(function (html) {
        tbody.innerHTML = html;
      })
      .catch(function () {
        // On error, do nothing special for now – keep old rows
      });
  }

  searchInput.addEventListener('input', function () {
    if (debounceTimer) {
      clearTimeout(debounceTimer);
    }
    debounceTimer = setTimeout(performSearch, 250);
  });
});
</script>
<?= $this->endSection() ?>
