<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row">
  <div class="col-12">
    <!-- Welcome banner -->
    <div class="rounded-4 p-4 mb-4" style="background: linear-gradient(135deg, #fe4f02, #f19361); color:#ffffff;">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div>
          <h1 class="h4 mb-1">Teacher Dashboard</h1>
          <p class="mb-0" style="opacity:.9;">Welcome back, <?= esc(session('name')) ?>! Manage your courses and students here.</p>
        </div>
      </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
      <div class="col-md-6">
        <!-- My Courses - Carrot -->
        <div class="card border-0 rounded-4 h-100" style="background-color:#e67e22; color:#ffffff;">
          <div class="card-body d-flex align-items-center justify-content-between">
            <div>
              <div class="small text-uppercase opacity-75">My Courses</div>
              <div class="fs-3 fw-bold"><?= esc($stats['my_courses'] ?? 0) ?></div>
            </div>
            <div class="fs-1 opacity-75"><i class="bi bi-book-half"></i></div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <!-- My Quizzes - Belize Hole -->
        <div class="card border-0 rounded-4 h-100" style="background-color:#2980b9; color:#ffffff;">
          <div class="card-body d-flex align-items-center justify-content-between">
            <div>
              <div class="small text-uppercase opacity-75">My Quizzes</div>
              <div class="fs-3 fw-bold"><?= esc($stats['quizzes'] ?? 0) ?></div>
            </div>
            <div class="fs-1 opacity-75"><i class="bi bi-clipboard-check"></i></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="mb-4">
      <div class="d-flex align-items-center mb-2">
        <i class="bi bi-lightning me-2 text-success"></i>
        <span class="fw-semibold">Quick Actions</span>
      </div>
      <div class="d-flex flex-wrap gap-2 align-items-center">
        <a href="<?= site_url('teacher/courses') ?>" class="btn btn-primary d-flex align-items-center">
          <i class="bi bi-book-half me-2"></i> My Courses
        </a>
        <a href="<?= site_url('teacher/quizzes') ?>" class="btn btn-outline-secondary d-flex align-items-center">
          <i class="bi bi-clipboard-check me-2"></i> My Quizzes
        </a>
        <a href="<?= site_url('teacher/enrollments') ?>" class="btn btn-outline-secondary d-flex align-items-center">
          <i class="bi bi-people-check me-2"></i> Manage Enrollments
        </a>
        <a href="<?= site_url('teacher/announcements') ?>" class="btn btn-outline-secondary d-flex align-items-center">
          <i class="bi bi-megaphone me-2"></i> Announcements
        </a>

        <!-- Search bar for filtering My Courses -->
        <div class="ms-auto" style="max-width: 260px; min-width: 200px;">
          <div class="input-group input-group-sm shadow-sm" style="border-radius: 999px; overflow: hidden;">
            <span class="input-group-text bg-white border-end-0">
              <i class="bi bi-search text-muted"></i>
            </span>
            <input
              type="text"
              id="teacherDashboardCourseSearch"
              class="form-control border-start-0"
              placeholder="Search course title..."
              autocomplete="off"
              style="box-shadow: none;">
          </div>
        </div>
      </div>
    </div>

        <!-- My Courses -->
        <?php if (!empty($courses)): ?>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-book-half me-2"></i>
                        My Courses
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Course Code</th>
                                    <th>Course Title</th>
                                    <th class="text-center">Students</th>
                                    <th class="text-center">Materials</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($courses as $course): ?>
                                    <tr class="teacher-course-row" data-course-url="<?= site_url('teacher/courses/show/' . $course['id']) ?>">
                                        <td>
                                            <a href="<?= site_url('teacher/courses/show/' . $course['id']) ?>" class="badge bg-primary text-decoration-none">
                                                <?= !empty($course['course_number']) ? esc($course['course_number']) : 'N/A' ?>
                                            </a>
                                        </td>
                                        <td>
                                            <strong><?= esc($course['title']) ?></strong>
                                            <?php if (!empty($course['description'])): ?>
                                                <br>
                                                <small class="text-muted">
                                                    <?= esc(substr($course['description'], 0, 50)) ?>
                                                    <?= strlen($course['description']) > 50 ? '...' : '' ?>
                                                </small>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <button type="button"
                                                    class="btn btn-outline-info btn-sm btn-view-students"
                                                    data-course-id="<?= (int)$course['id'] ?>"
                                                    data-course-title="<?= esc($course['title']) ?>">
                                                <?= $course['student_count'] ?>
                                            </button>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success"><?= $course['material_count'] ?></span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="<?= site_url('admin/course/' . $course['id'] . '/upload') ?>" 
                                                   class="btn btn-outline-primary"
                                                   title="Upload Materials">
                                                    <i class="bi bi-folder-plus"></i>
                                                </a>
                                                <a href="<?= site_url('materials/list/' . $course['id']) ?>" 
                                                   class="btn btn-outline-secondary"
                                                   title="View Materials">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted"></i>
                    <h5 class="mt-3">No Courses Assigned Yet</h5>
                    <p class="text-muted">Contact the administrator to get courses assigned to you.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<div class="modal fade" id="teacherStudentsModal" tabindex="-1" aria-labelledby="teacherStudentsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="teacherStudentsModalLabel">Enrolled Students</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="teacherStudentsModalBody">
          <p class="text-muted mb-0">Loading students...</p>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  var modalEl = document.getElementById('teacherStudentsModal');
  if (!modalEl) return;

  var studentsModal = new bootstrap.Modal(modalEl);
  var modalTitleEl = document.getElementById('teacherStudentsModalLabel');
  var modalBodyEl  = document.getElementById('teacherStudentsModalBody');

  document.querySelectorAll('.btn-view-students').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var courseId    = this.getAttribute('data-course-id');
      var courseTitle = this.getAttribute('data-course-title') || 'Course';

      if (modalTitleEl) {
        modalTitleEl.textContent = 'Enrolled Students - ' + courseTitle;
      }
      if (modalBodyEl) {
        modalBodyEl.innerHTML = '<p class="text-muted mb-0">Loading students...</p>';
      }

      fetch('<?= site_url('teacher/courses/students') ?>/' + courseId, {
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
        .then(function (res) { return res.json(); })
        .then(function (data) {
          if (!modalBodyEl) return;

          if (!data || data.ok !== true || !Array.isArray(data.students) || data.students.length === 0) {
            modalBodyEl.innerHTML = '<p class="text-muted mb-0">No approved students enrolled for this course.</p>';
            return;
          }

          var html = '<div class="table-responsive"><table class="table table-sm align-middle mb-0">' +
                     '<thead><tr><th>#</th><th>Name</th><th>Email</th><th>Enrolled</th></tr></thead><tbody>';

          data.students.forEach(function (s, idx) {
            var enrolled = s.enrollment_date ? new Date(s.enrollment_date) : null;
            var enrolledStr = enrolled ? enrolled.toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: '2-digit' }) : 'N/A';

            html += '<tr>' +
                    '<td>' + (idx + 1) + '</td>' +
                    '<td>' + (s.name || '') + '</td>' +
                    '<td>' + (s.email || '') + '</td>' +
                    '<td><small class="text-muted">' + enrolledStr + '</small></td>' +
                    '</tr>';
          });

          html += '</tbody></table></div>';
          modalBodyEl.innerHTML = html;
        })
        .catch(function () {
          if (!modalBodyEl) return;
          modalBodyEl.innerHTML = '<p class="text-danger mb-0">Failed to load students. Please try again.</p>';
        });

      studentsModal.show();
    });
  });

  // Make entire My Courses row clickable (except on buttons/links)
  document.querySelectorAll('.teacher-course-row').forEach(function (row) {
    row.addEventListener('click', function (e) {
      var target = e.target;
      if (target.closest('a, button')) {
        return; // let normal links/buttons work
      }
      var url = this.getAttribute('data-course-url');
      if (url) {
        window.location.href = url;
      }
    });
  });

  // Search/filter courses in the dashboard table
  var searchInput = document.getElementById('teacherDashboardCourseSearch');
  if (searchInput) {
    searchInput.addEventListener('input', function () {
      var term = (this.value || '').toLowerCase().trim();
      var rows = document.querySelectorAll('.teacher-course-row');

      rows.forEach(function (row) {
        // Course Title is in the 2nd column
        var titleEl = row.querySelector('td:nth-child(2)');
        var titleText = (titleEl ? titleEl.textContent : '').toLowerCase().trim();

        // Show all when input is empty, otherwise match titles that START with the term
        var match = !term || titleText.startsWith(term);
        row.style.display = match ? '' : 'none';
      });
    });
  }
});
</script>
<?= $this->endSection() ?>
