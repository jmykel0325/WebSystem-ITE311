<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-12 mb-3">
    <a href="<?= site_url('teacher/dashboard') ?>" class="btn btn-sm btn-outline-primary rounded-pill d-inline-flex align-items-center gap-1 shadow-sm px-3">
      <i class="bi bi-arrow-left-circle"></i>
      <span>Back to Dashboard</span>
    </a>
  </div>

  <div class="col-lg-8 mb-3">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h1 class="h4 mb-0"><?= esc($course['title']) ?></h1>
            <?php if (!empty($course['course_number'])): ?>
              <span class="badge bg-primary mt-1"><?= esc($course['course_number']) ?></span>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <?php if (!empty($course['description'])): ?>
          <p class="text-muted mb-3"><?= esc($course['description']) ?></p>
        <?php endif; ?>

        <dl class="row mb-0 small text-muted">
          <dt class="col-sm-3">Semester</dt>
          <dd class="col-sm-9">
            <?php if (!empty($course['semester'])): ?>
              <?= $course['semester'] === 'first' ? 'First Semester' : 'Second Semester' ?>
            <?php else: ?>
              N/A
            <?php endif; ?>
          </dd>

          <dt class="col-sm-3">Schedule</dt>
          <dd class="col-sm-9">
            <?php if (!empty($course['days_pattern'])): ?>
              <span class="badge bg-light text-dark border me-1"><?= esc($course['days_pattern']) ?></span>
            <?php endif; ?>

            <?php if (!empty($course['start_date']) || !empty($course['end_date'])): ?>
              <?php if (!empty($course['start_date'])): ?>
                <?= date('M d, Y', strtotime($course['start_date'])) ?>
              <?php else: ?>
                ?
              <?php endif; ?>
              &ndash;
              <?php if (!empty($course['end_date'])): ?>
                <?= date('M d, Y', strtotime($course['end_date'])) ?>
              <?php else: ?>
                ?
              <?php endif; ?>
            <?php else: ?>
              <span class="text-muted">N/A</span>
            <?php endif; ?>

            <?php if (!empty($course['start_time']) || !empty($course['end_time'])): ?>
              <br>
              <small class="text-muted">
                <?= !empty($course['start_time']) ? date('h:i A', strtotime($course['start_time'])) : '?' ?>
                &ndash;
                <?= !empty($course['end_time']) ? date('h:i A', strtotime($course['end_time'])) : '?' ?>
              </small>
            <?php endif; ?>
          </dd>

          <dt class="col-sm-3">Students</dt>
          <dd class="col-sm-9">
            <button type="button"
                    class="btn btn-outline-info btn-sm btn-view-students"
                    data-course-id="<?= (int)$course['id'] ?>"
                    data-course-title="<?= esc($course['title']) ?>">
              <?= (int)$studentCount ?> enrolled
            </button>
          </dd>
        </dl>
      </div>
    </div>
  </div>

  <div class="col-lg-4 mb-3">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-header bg-white">
        <strong>Quick Actions</strong>
      </div>
      <div class="card-body">
        <div class="d-grid gap-2">
          <a href="<?= site_url('materials/list/' . $course['id']) ?>" class="btn btn-outline-secondary">
            <i class="bi bi-eye me-1"></i> View Materials
          </a>
          <a href="<?= site_url('admin/course/' . $course['id'] . '/upload') ?>" class="btn btn-outline-primary">
            <i class="bi bi-folder-plus me-1"></i> Manage Materials
          </a>
          <a href="<?= site_url('teacher/enrollments') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-people-check me-1"></i> Manage Enrollments
          </a>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 mb-3">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <strong>Materials</strong>
        <span class="badge bg-success"><?= (int)$materialCount ?> items</span>
      </div>
      <div class="card-body">
        <?php if (!empty($materials)): ?>
          <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>Title</th>
                  <th>Type</th>
                  <th>Uploaded</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($materials as $m): ?>
                  <tr>
                    <td><?= esc($m['title'] ?? $m['file_name'] ?? 'Material') ?></td>
                    <td><?= esc($m['type'] ?? pathinfo($m['file_path'] ?? '', PATHINFO_EXTENSION)) ?></td>
                    <td><small class="text-muted"><?= !empty($m['created_at']) ? date('M d, Y', strtotime($m['created_at'])) : '' ?></small></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <p class="text-muted mb-0">No materials uploaded yet.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<?= view('teacher/partials/students_modal_script', ['courseId' => $course['id'], 'courseTitle' => $course['title']]) ?>
<?= $this->endSection() ?>
