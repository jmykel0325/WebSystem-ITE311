<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-12 mb-3">
    <h1 class="h4 mb-1">My Courses</h1>
    <p class="text-muted small mb-0">View and manage the courses you are currently handling.</p>
  </div>

  <div class="col-12">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
          <strong>Courses Assigned</strong>
          <span class="badge bg-primary ms-2"><?= count($courses ?? []) ?> total</span>
        </div>
        <div style="max-width: 260px;" class="ms-auto">
          <input
            type="text"
            id="teacherCoursesSearch"
            class="form-control form-control-sm"
            placeholder="Search by course title..."
            autocomplete="off">
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive mb-0">
          <table class="table table-hover align-middle mb-0" id="teacherCoursesTable">
            <thead class="table-light">
              <tr>
                <th>Title</th>
                <th>Created</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($courses)): ?>
                <tr><td colspan="2" class="text-muted">No courses yet.</td></tr>
              <?php else: foreach ($courses as $c): ?>
                <tr class="teacher-course-row" data-course-url="<?= site_url('teacher/courses/show/' . (int)$c['id']) ?>">
                  <td class="course-title">
                    <a href="<?= site_url('teacher/courses/show/' . (int)$c['id']) ?>" class="text-decoration-none text-dark fw-semibold">
                      <?= esc($c['title']) ?>
                    </a>
                  </td>
                  <td class="course-created">
                    <small class="text-muted"><?= esc($c['created_at']) ?></small>
                  </td>
                </tr>
              <?php endforeach; endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  (function () {
    const input = document.getElementById('teacherCoursesSearch');
    const table = document.getElementById('teacherCoursesTable');
    if (!input || !table) return;

    const tbody = table.querySelector('tbody');
    if (!tbody) return;

    // Match behavior of student Browse Courses: filter by TITLE only, starts-with
    input.addEventListener('input', function () {
      const term = this.value.toLowerCase().trim();
      const rows = tbody.querySelectorAll('tr');

      rows.forEach(function (row) {
        const title = (row.querySelector('.course-title')?.textContent || '').toLowerCase().trim();

        const match = !term || title.startsWith(term);
        row.style.display = match ? '' : 'none';
      });
    });

    // Make row clickable (except when clicking links/buttons)
    tbody.addEventListener('click', function (e) {
      const target = e.target;
      if (target.closest('a, button')) {
        return;
      }
      const row = target.closest('.teacher-course-row');
      if (!row) return;
      const url = row.getAttribute('data-course-url');
      if (url) {
        window.location.href = url;
      }
    });
  })();
</script>
<?= $this->endSection() ?>
