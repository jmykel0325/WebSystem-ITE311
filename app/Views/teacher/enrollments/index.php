<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-end mb-3">
  <div>
    <div class="kicker">Teacher</div>
    <h1 class="page-title mb-0">Manage Enrollments</h1>
  </div>
</div>

<div class="row g-4">
  <div class="col-lg-6">
    <div class="card h-100">
      <div class="card-header bg-white">
        <strong>Pending Requests</strong>
      </div>
      <div class="card-body">
        <?php if (!empty($pending)): ?>
          <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
              <thead>
                <tr>
                  <th>Student</th>
                  <th>Course</th>
                  <th>Requested At</th>
                  <th class="text-end">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($pending as $row): ?>
                  <tr>
                    <td><?= esc($row['student_name'] ?? 'Unknown') ?></td>
                    <td><?= esc($row['course_title'] ?? 'Unknown course') ?></td>
                    <td>
                      <?php if (!empty($row['enrollment_date'])): ?>
                        <?= esc(date('M d, Y', strtotime($row['enrollment_date']))) ?>
                      <?php endif; ?>
                    </td>
                    <td class="text-end">
                      <a href="<?= site_url('teacher/enrollments/approve/' . (int)$row['id']) ?>" class="btn btn-success btn-sm">
                        Approve
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <div class="empty-state">
            <i class="bi bi-inboxes mb-2" style="font-size: 2rem;"></i>
            <div class="fw-semibold">No pending requests</div>
            <div class="text-muted">Students have not requested enrollment yet.</div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="col-lg-6">
    <div class="card h-100">
      <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <strong>Approved Students</strong>
        <?php if (!empty($approvedCourses)): ?>
          <div class="d-flex align-items-center gap-2">
            <label for="courseFilter" class="small text-muted mb-0">Course:</label>
            <select id="courseFilter" class="form-select form-select-sm" style="min-width: 160px;">
              <option value="">All</option>
              <?php foreach ($approvedCourses as $course): ?>
                <option value="<?= (int)$course['id'] ?>"><?= esc($course['title']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        <?php endif; ?>
      </div>
      <div class="card-body">
        <?php if (!empty($approved)): ?>
          <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
              <thead>
                <tr>
                  <th>Student</th>
                  <th>Course</th>
                  <th>Enrolled At</th>
                  <th class="text-end">Action</th>
                </tr>
              </thead>
              <tbody id="approvedTableBody">
                <?php foreach ($approved as $row): ?>
                  <tr data-course-id="<?= (int)($row['course_id'] ?? 0) ?>">
                    <td><?= esc($row['student_name'] ?? 'Unknown') ?></td>
                    <td><?= esc($row['course_title'] ?? 'Unknown course') ?></td>
                    <td>
                      <?php if (!empty($row['enrollment_date'])): ?>
                        <?= esc(date('M d, Y', strtotime($row['enrollment_date']))) ?>
                      <?php endif; ?>
                    </td>
                    <td class="text-end">
                      <?php if (($row['status'] ?? 'approved') === 'approved'): ?>
                        <a href="<?= site_url('teacher/enrollments/unenroll/' . (int)$row['id']) ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Unenroll this student from the course?');">
                          Unenroll
                        </a>
                      <?php else: ?>
                        <a href="<?= site_url('teacher/enrollments/reenroll/' . (int)$row['id']) ?>" class="btn btn-outline-success btn-sm" onclick="return confirm('Re-enroll this student to the course?');">
                          Re-enroll
                        </a>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <div class="empty-state">
            <i class="bi bi-emoji-smile mb-2" style="font-size: 2rem;"></i>
            <div class="fw-semibold">No approved enrollments yet</div>
            <div class="text-muted">Approved students will appear here.</div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
  var filter = document.getElementById('courseFilter');
  var tbody  = document.getElementById('approvedTableBody');
  if (!filter || !tbody) return;

  filter.addEventListener('change', function () {
    var value = this.value;
    var rows  = tbody.querySelectorAll('tr');
    rows.forEach(function (row) {
      var cid = row.getAttribute('data-course-id') || '';
      if (!value || value === cid) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  });
});
</script>
<?= $this->endSection() ?>
