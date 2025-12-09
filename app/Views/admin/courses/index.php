<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                    <h4 class="mb-0">
                        <i class="bi bi-book-half me-2"></i>
                        Manage Courses
                    </h4>

                    <div class="d-flex align-items-center gap-2">
                        <form method="get" action="<?= site_url('admin/courses') ?>" class="d-flex align-items-center gap-2">
                            <label for="semesterFilter" class="mb-0 small me-1">Semester:</label>
                            <select id="semesterFilter" name="semester" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="" <?= empty($selectedSemester) ? 'selected' : '' ?>>All</option>
                                <option value="first" <?= ($selectedSemester ?? '') === 'first' ? 'selected' : '' ?>>First Semester</option>
                                <option value="second" <?= ($selectedSemester ?? '') === 'second' ? 'selected' : '' ?>>Second Semester</option>
                            </select>
                        </form>

                        <a href="<?= site_url('admin/courses/create') ?>" class="btn btn-light btn-sm">
                            <i class="bi bi-plus-circle me-2"></i>
                            Add New Course
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Success/Error Messages -->
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Courses Table -->
                <?php if (empty($courses)): ?>
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-inbox display-1"></i>
                        <h5 class="mt-3">No Courses Yet</h5>
                        <p>Start by creating your first course</p>
                        <a href="<?= site_url('admin/courses/create') ?>" class="btn btn-primary mt-3">
                            <i class="bi bi-plus-circle me-2"></i>
                            Create First Course
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Course Number</th>
                                    <th>Semester</th>
                                    <th>Schedule</th>
                                    <th>Course Title</th>
                                    <th>Teacher</th>
                                    <th class="text-center">Materials</th>
                                    <th class="text-center">Students</th>
                                    <th>Created</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($courses as $course): ?>
                                    <tr>
                                        <td>
                                            <strong class="badge bg-primary">
                                                <?= !empty($course['course_number']) ? esc($course['course_number']) : 'N/A' ?>
                                            </strong>
                                        </td>
                                        <td>
                                            <?php if (!empty($course['semester'])): ?>
                                                <span class="badge bg-secondary d-block mb-1">
                                                    <?= $course['semester'] === 'first' ? 'First Semester' : 'Second Semester' ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted d-block mb-1">N/A</span>
                                            <?php endif; ?>

                                            <?php if (!empty($course['start_date']) || !empty($course['end_date'])): ?>
                                                <small class="text-muted">
                                                    <?php if (!empty($course['start_date'])): ?>
                                                        <?= date('M d, Y', strtotime($course['start_date'])) ?>
                                                    <?php else: ?>
                                                        ?
                                                    <?php endif; ?>
                                                    &nbsp;–&nbsp;
                                                    <?php if (!empty($course['end_date'])): ?>
                                                        <?= date('M d, Y', strtotime($course['end_date'])) ?>
                                                    <?php else: ?>
                                                        ?
                                                    <?php endif; ?>
                                                </small>
                                            <?php else: ?>
                                                <small class="text-muted">N/A</small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($course['days_pattern'])): ?>
                                                <span class="badge bg-light text-dark border d-block mb-1">
                                                    <?= esc($course['days_pattern']) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted d-block mb-1">N/A</span>
                                            <?php endif; ?>

                                            <?php if (!empty($course['start_time']) || !empty($course['end_time'])): ?>
                                                <small class="text-muted">
                                                    <?= !empty($course['start_time']) ? date('h:i A', strtotime($course['start_time'])) : '?' ?>
                                                    &ndash;
                                                    <?= !empty($course['end_time']) ? date('h:i A', strtotime($course['end_time'])) : '?' ?>
                                                </small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong><?= esc($course['title']) ?></strong>
                                            <?php if (!empty($course['description'])): ?>
                                                <br>
                                                <small class="text-muted">
                                                    <?= esc(substr($course['description'], 0, 60)) ?>
                                                    <?= strlen($course['description']) > 60 ? '...' : '' ?>
                                                </small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($course['teacher_name']): ?>
                                                <i class="bi bi-person-circle me-1"></i>
                                                <?= esc($course['teacher_name']) ?>
                                                <br>
                                                <small class="text-muted"><?= esc($course['teacher_email']) ?></small>
                                            <?php else: ?>
                                                <span class="text-muted">No teacher assigned</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info">
                                                <?= $course['material_count'] ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <button type="button"
                                                    class="btn btn-outline-success btn-sm btn-view-students"
                                                    data-course-id="<?= (int)$course['id'] ?>"
                                                    data-course-title="<?= esc($course['title']) ?>">
                                                <?= $course['enrollment_count'] ?>
                                            </button>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <?= date('M d, Y', strtotime($course['created_at'])) ?>
                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <!-- Materials Button -->
                                                <a href="<?= site_url('admin/course/' . $course['id'] . '/upload') ?>" 
                                                   class="btn btn-sm btn-info"
                                                   title="Manage Materials">
                                                    <i class="bi bi-folder-plus"></i>
                                                </a>
                                                
                                                <!-- View Materials -->
                                                <a href="<?= site_url('materials/list/' . $course['id']) ?>" 
                                                   class="btn btn-sm btn-secondary"
                                                   title="View Materials">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                
                                                <!-- Edit Button -->
                                                <a href="<?= site_url('admin/courses/edit/' . $course['id']) ?>" 
                                                   class="btn btn-sm btn-primary"
                                                   title="Edit Course">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                
                                                <!-- Delete Button -->
                                                <a href="<?= site_url('admin/courses/delete/' . $course['id']) ?>" 
                                                   class="btn btn-sm btn-danger"
                                                   onclick="return confirm('Are you sure you want to delete this course? This will also delete all materials and enrollments!')"
                                                   title="Delete Course">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Summary -->
                    <div class="mt-3">
                        <p class="text-muted mb-0">
                            <i class="bi bi-info-circle me-2"></i>
                            Total Courses: <strong><?= count($courses) ?></strong>
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?php // Modal and scripts for viewing enrolled students ?>
<?= $this->section('scripts') ?>
<div class="modal fade" id="studentsModal" tabindex="-1" aria-labelledby="studentsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="studentsModalLabel">Enrolled Students</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="studentsModalBody">
          <p class="text-muted mb-0">Loading students...</p>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  var modalEl = document.getElementById('studentsModal');
  if (!modalEl) return;

  var studentsModal = new bootstrap.Modal(modalEl);
  var modalTitleEl = document.getElementById('studentsModalLabel');
  var modalBodyEl  = document.getElementById('studentsModalBody');

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

      fetch('<?= site_url('admin/courses/enrolled-students') ?>/' + courseId, {
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
        .then(function (res) { return res.json(); })
        .then(function (data) {
          if (!modalBodyEl) return;

          if (!data || data.ok !== true || !Array.isArray(data.students) || data.students.length === 0) {
            modalBodyEl.innerHTML = '<p class="text-muted mb-0">No students enrolled for this course.</p>';
            return;
          }

          var html = '<div class="table-responsive"><table class="table table-sm align-middle mb-0">' +
                     '<thead><tr><th>#</th><th>Name</th><th>Email</th><th>Enrolled</th><th class="text-end">Actions</th></tr></thead><tbody>';

          data.students.forEach(function (s, idx) {
            var enrolled = s.enrollment_date ? new Date(s.enrollment_date) : null;
            var enrolledStr = enrolled ? enrolled.toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: '2-digit' }) : 'N/A';

            html += '<tr>' +
                    '<td>' + (idx + 1) + '</td>' +
                    '<td>' + (s.name || '') + '</td>' +
                    '<td>' + (s.email || '') + '</td>' +
                    '<td><small class="text-muted">' + enrolledStr + '</small></td>' +
                    '<td class="text-end">' +
                      '<button type="button" class="btn btn-sm btn-outline-danger btn-unenroll" data-enrollment-id="' + (s.enrollment_id || '') + '">Unenroll</button>' +
                    '</td>' +
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
      // Attach "Unenroll" click handler (event delegation)
      modalBodyEl.addEventListener('click', function (e) {
        var target = e.target;
        if (!target.classList.contains('btn-unenroll')) return;

        var enrollmentId = target.getAttribute('data-enrollment-id');
        if (!enrollmentId) return;

        if (!confirm('Unenroll this student from the course?')) {
          return;
        }

        fetch('<?= site_url('admin/courses/unenroll-student') ?>/' + enrollmentId, {
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          }
        })
          .then(function (res) { return res.json(); })
          .then(function (data) {
            if (!data || data.ok !== true) {
              alert('Failed to unenroll student.');
              return;
            }

            // Remove row from modal table
            var row = target.closest('tr');
            if (row && row.parentNode) {
              row.parentNode.removeChild(row);
            }

            // Update numbering
            var rows = modalBodyEl.querySelectorAll('tbody tr');
            rows.forEach(function (r, index) {
              var cell = r.querySelector('td:first-child');
              if (cell) cell.textContent = index + 1;
            });

            // If table is empty, show empty message
            if (rows.length === 0) {
              modalBodyEl.innerHTML = '<p class="text-muted mb-0">No students enrolled for this course.</p>';
            }

            // Decrease count badge in main table
            var countBtn = document.querySelector('.btn-view-students[data-course-id="' + courseId + '"]');
            if (countBtn) {
              var current = parseInt(countBtn.textContent.trim(), 10);
              if (!isNaN(current) && current > 0) {
                countBtn.textContent = current - 1;
              }
            }
          })
          .catch(function () {
            alert('Failed to unenroll student. Please try again.');
          });
      });

    });
  });
});
</script>
<?= $this->endSection() ?>
