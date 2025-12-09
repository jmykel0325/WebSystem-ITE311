<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row">
  <div class="col-12">
    <!-- Welcome banner -->
    <div class="rounded-4 p-4 mb-4" style="background: linear-gradient(135deg, #fe4f02, #f19361); color:#ffffff;">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div>
          <h1 class="h4 mb-1">
            <i class="bi bi-mortarboard me-2"></i>Student Dashboard
          </h1>
          <p class="mb-0" style="opacity:.9;">Welcome back, <?= esc(session('name')) ?>! Continue your learning journey.</p>
        </div>
      </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
      <div class="col-md-6">
        <!-- Enrolled Courses - Carrot -->
        <div class="card border-0 rounded-4 h-100" style="background-color:#e67e22; color:#ffffff;">
          <div class="card-body d-flex align-items-center justify-content-between">
            <div>
              <div class="small text-uppercase opacity-75">Enrolled Courses</div>
              <div class="fs-3 fw-bold"><?= esc($stats['enrolled'] ?? 0) ?></div>
            </div>
            <div class="fs-1 opacity-75"><i class="bi bi-book-half"></i></div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <!-- Quizzes Available - Belize Hole -->
        <div class="card border-0 rounded-4 h-100" style="background-color:#2980b9; color:#ffffff;">
          <div class="card-body d-flex align-items-center justify-content-between">
            <div>
              <div class="small text-uppercase opacity-75">Quizzes Available</div>
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
      <div class="d-flex flex-wrap gap-2">
        <a href="<?= site_url('student/enrollments') ?>" class="btn btn-primary d-flex align-items-center">
          <i class="bi bi-plus-circle me-2"></i> My Enrollments
        </a>
        <a href="<?= site_url('student/materials') ?>" class="btn btn-outline-secondary d-flex align-items-center">
          <i class="bi bi-folder2-open me-2"></i> My Materials
        </a>
        <a href="<?= site_url('student/quizzes') ?>" class="btn btn-outline-success d-flex align-items-center">
          <i class="bi bi-clipboard-check me-2"></i> My Quizzes
        </a>
        <a href="<?= site_url('student/grades') ?>" class="btn btn-outline-secondary d-flex align-items-center">
          <i class="bi bi-graph-up me-2"></i> View Grades
        </a>
        <a href="<?= site_url('courses') ?>" class="btn btn-outline-primary d-flex align-items-center">
          <i class="bi bi-search me-2"></i> Browse Courses
        </a>
      </div>
    </div>

    <!-- Enrolled Courses -->
    <?php if (!empty($enrolledCourses)): ?>
      <div class="card">
        <div class="card-header">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
              <i class="bi bi-book-half me-2"></i>
              My Enrolled Courses
            </h5>
            <span class="badge bg-primary"><?= count($enrolledCourses) ?> courses</span>
          </div>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <?php foreach ($enrolledCourses as $course): ?>
              <div class="col-md-6 col-lg-4">
                <div class="card h-100 position-relative">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                      <span class="badge bg-primary">
                        <?= !empty($course['course_number']) ? esc($course['course_number']) : 'N/A' ?>
                      </span>
                      <small class="text-muted text-end">
                        <?php
                          $enrolledAt = strtotime($course['enrollment_date']);
                          $expiryTs   = null;
                          if (!empty($course['end_date'])) {
                              $expiryTs = strtotime($course['end_date']);
                          }
                          if (!$expiryTs) {
                              $expiryTs = strtotime('+4 months', $enrolledAt);
                          }
                        ?>
                        <div>
                          <i class="bi bi-calendar-check"></i>
                          Enrolled: <?= esc(date('M d, Y', $enrolledAt)) ?>
                        </div>
                        <div>
                          <i class="bi bi-hourglass-split"></i>
                          Expires: <?= esc(date('M d, Y', $expiryTs)) ?>
                        </div>
                      </small>
                    </div>
                    <h6 class="card-title mb-1"><?= esc($course['title']) ?></h6>
                    <p class="card-text text-muted small">
                      <?= esc(substr($course['description'] ?? 'No description', 0, 80)) ?>
                      <?= strlen($course['description'] ?? '') > 80 ? '...' : '' ?>
                    </p>

                    <p class="card-text small mb-1">
                      <i class="bi bi-calendar-week me-1"></i>
                      <?php if (!empty($course['days_pattern'])): ?>
                        <span class="badge bg-light text-dark border me-1">
                          <?= esc($course['days_pattern']) ?>
                        </span>
                      <?php else: ?>
                        <span class="text-muted">No days set</span>
                      <?php endif; ?>

                      <?php if (!empty($course['start_time']) || !empty($course['end_time'])): ?>
                        <br>
                        <small class="text-muted ms-4">
                          <?= !empty($course['start_time']) ? date('h:i A', strtotime($course['start_time'])) : '?' ?>
                          &ndash;
                          <?= !empty($course['end_time']) ? date('h:i A', strtotime($course['end_time'])) : '?' ?>
                        </small>
                      <?php endif; ?>
                    </p>

                    <?php if (!empty($course['teacher_name'])): ?>
                      <p class="card-text small mb-2">
                        <i class="bi bi-person-circle me-1"></i>
                        <strong>Teacher:</strong> <?= esc($course['teacher_name']) ?>
                      </p>
                    <?php endif; ?>
                    <div class="d-grid gap-2">
                      <a href="<?= site_url('materials/list/' . $course['id']) ?>" 
                         class="btn btn-sm btn-primary">
                        <i class="bi bi-folder2-open me-1"></i>
                        View Materials
                      </a>
                    </div>
                    <a href="<?= site_url('courses/show/' . $course['id']) ?>" class="stretched-link" aria-label="View course details"></a>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    <?php else: ?>
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted"></i>
                    <h5 class="mt-3">No Enrolled Courses Yet</h5>
                    <p class="text-muted">Start your learning journey by enrolling in courses!</p>
                    <a href="<?= site_url('courses') ?>" class="btn btn-primary mt-3">
                        <i class="bi bi-plus-circle me-2"></i>
                        Browse Courses
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
