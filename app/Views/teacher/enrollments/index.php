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
      <div class="card-header bg-white">
        <strong>Approved Students</strong>
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
              <tbody>
                <?php foreach ($approved as $row): ?>
                  <tr>
                    <td><?= esc($row['student_name'] ?? 'Unknown') ?></td>
                    <td><?= esc($row['course_title'] ?? 'Unknown course') ?></td>
                    <td>
                      <?php if (!empty($row['enrollment_date'])): ?>
                        <?= esc(date('M d, Y', strtotime($row['enrollment_date']))) ?>
                      <?php endif; ?>
                    </td>
                    <td class="text-end">
                      <a href="<?= site_url('teacher/enrollments/unenroll/' . (int)$row['id']) ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Unenroll this student from the course?');">
                        Unenroll
                      </a>
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
