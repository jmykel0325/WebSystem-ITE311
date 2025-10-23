<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php $role = esc($role ?? 'student'); ?>

<div class="d-flex justify-content-between align-items-end mb-4">
  <div>
    <div class="kicker"><?= esc(ucfirst(session('role'))) ?> Dashboard</div>
    <h1 class="page-title mb-0">Welcome, <?= esc(session('name')) ?>!</h1>
  </div>
</div>

<?php if ($role === 'admin'): ?>
  <div class="row g-4">
    <div class="col-md-4">
      <div class="card p-3">
        <div class="d-flex align-items-center">
          <div class="me-3 text-primary"><i class="bi bi-people" style="font-size:1.6rem"></i></div>
          <div>
            <div class="text-muted">Total Users</div>
            <div class="h4 mb-0"><?= esc($stats['users'] ?? 0) ?></div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card p-3">
        <div class="d-flex align-items-center">
          <div class="me-3 text-success"><i class="bi bi-collection" style="font-size:1.6rem"></i></div>
          <div>
            <div class="text-muted">Total Courses</div>
            <div class="h4 mb-0"><?= esc($stats['courses'] ?? 0) ?></div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card p-3">
        <div class="d-flex align-items-center">
          <div class="me-3 text-warning"><i class="bi bi-book" style="font-size:1.6rem"></i></div>
          <div>
            <div class="text-muted">Total Lessons</div>
            <div class="h4 mb-0"><?= esc($stats['lessons'] ?? 0) ?></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  

<?php elseif ($role === 'teacher'): ?>
  <div class="row g-4">
    <div class="col-md-4">
      <div class="card p-3">
        <div class="d-flex align-items-center">
          <div class="me-3 text-primary"><i class="bi bi-collection" style="font-size:1.6rem"></i></div>
          <div>
            <div class="text-muted">My Courses</div>
            <div class="h4 mb-0"><?= esc($stats['my_courses'] ?? 0) ?></div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card p-3">
        <div class="d-flex align-items-center">
          <div class="me-3 text-success"><i class="bi bi-clipboard-check" style="font-size:1.6rem"></i></div>
          <div>
            <div class="text-muted">Quizzes</div>
            <div class="h4 mb-0"><?= esc($stats['quizzes'] ?? 0) ?></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="row g-4 mt-3">
    <div class="col-12">
      <div class="card">
        <div class="card-header bg-white">
          <strong>Teacher Shortcuts</strong>
        </div>
        <div class="card-body d-grid gap-2">
          <a class="btn btn-primary" href="<?= site_url('teacher/courses') ?>">
            <i class="bi bi-collection"></i> My Courses
          </a>
          <a class="btn btn-outline-secondary" href="<?= site_url('teacher/quizzes') ?>">
            <i class="bi bi-clipboard-check"></i> My Quizzes
          </a>
          <a class="btn btn-outline-secondary" href="#">
            <i class="bi bi-check2-square"></i> Grade Submissions
          </a>
        </div>
      </div>
    </div>
  </div>

<?php else: ?>
  <div class="row g-4">
    <div class="col-md-4">
      <div class="card p-3">
        <div class="d-flex align-items-center">
          <div class="me-3 text-primary"><i class="bi bi-collection" style="font-size:1.6rem"></i></div>
          <div>
            <div class="text-muted">Enrolled Courses</div>
            <div class="h4 mb-0"><?= esc($stats['enrolled'] ?? 0) ?></div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card p-3">
        <div class="d-flex align-items-center">
          <div class="me-3 text-success"><i class="bi bi-clipboard-check" style="font-size:1.6rem"></i></div>
          <div>
            <div class="text-muted">Quizzes Assigned</div>
            <div class="h4 mb-0"><?= esc($stats['quizzes'] ?? 0) ?></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Enrolled Courses Section -->
  <?php if (!empty($enrolledCourses)): ?>
  <div class="row g-4 mt-3">
    <div class="col-12">
      <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
          <strong>My Enrolled Courses</strong>
          <span class="badge text-bg-light"><?= count($enrolledCourses) ?> courses</span>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <?php foreach ($enrolledCourses as $course): ?>
              <div class="col-md-6 col-lg-4">
                <div class="p-3 border rounded-3 h-100">
                  <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="fw-semibold mb-1"><?= esc($course['title']) ?></h6>
                    <span class="badge badge-soft">
                      <i class="bi bi-calendar-check"></i>
                      <?= esc(date('M d', strtotime($course['enrollment_date']))) ?>
                    </span>
                  </div>
                  <p class="text-muted small mb-2"><?= esc($course['description'] ?? 'No description available') ?></p>
                  <div class="d-flex gap-2">
                    <a href="#" class="btn btn-outline-primary btn-sm flex-fill">
                      <i class="bi bi-play-circle"></i> Continue Learning
                    </a>
                    <a href="#" class="btn btn-outline-secondary btn-sm">
                      <i class="bi bi-info-circle"></i>
                    </a>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>
  
  <div class="row g-4 mt-3">
    <div class="col-12">
      <div class="card">
        <div class="card-header bg-white">
          <strong>Student Shortcuts</strong>
        </div>
        <div class="card-body d-grid gap-2">
          <a class="btn btn-primary" href="<?= site_url('student/enrollments') ?>">
            <i class="bi bi-plus-circle"></i> My Enrollments
          </a>
          <a class="btn btn-outline-secondary" href="<?= site_url('student/grades') ?>">
            <i class="bi bi-graph-up"></i> View Grades
          </a>
          <a class="btn btn-outline-secondary" href="#">
            <i class="bi bi-calendar-event"></i> Upcoming Deadlines
          </a>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>

<?= $this->endSection() ?>
