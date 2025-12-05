<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row">
  <div class="col-12">
    <!-- Welcome banner -->
    <div class="rounded-4 p-4 mb-4" style="background: linear-gradient(135deg, #fe4f02, #f19361); color:#ffffff;">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div>
          <h1 class="h4 mb-1">Welcome back, <?= esc(session('name')) ?>!</h1>
          <p class="mb-0" style="opacity:.9;">Manage your LMS from this centralized admin dashboard.</p>
        </div>
        <div class="small" style="opacity:.9;">
          <?= esc(date('F j, Y')) ?>
        </div>
      </div>
    </div>

    <!-- Stat cards -->
    <div class="row g-3 mb-4">
      <div class="col-md-3">
        <!-- Total Users - Green Sea -->
        <div class="card border-0 rounded-4 h-100" style="background-color:#16a085; color:#ffffff;">
          <div class="card-body d-flex align-items-center justify-content-between">
            <div>
              <div class="small text-uppercase opacity-75">Total Users</div>
              <div class="fs-3 fw-bold"><?= esc($stats['users'] ?? 0) ?></div>
            </div>
            <div class="fs-1 opacity-75"><i class="bi bi-people"></i></div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <!-- Total Courses - Nephritis -->
        <div class="card border-0 rounded-4 h-100" style="background-color:#27ae60; color:#ffffff;">
          <div class="card-body d-flex align-items-center justify-content-between">
            <div>
              <div class="small text-uppercase opacity-75">Total Courses</div>
              <div class="fs-3 fw-bold"><?= esc($stats['courses'] ?? 0) ?></div>
            </div>
            <div class="fs-1 opacity-75"><i class="bi bi-stack"></i></div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <!-- Total Lessons - Belize Hole -->
        <div class="card border-0 rounded-4 h-100" style="background-color:#2980b9; color:#ffffff;">
          <div class="card-body d-flex align-items-center justify-content-between">
            <div>
              <div class="small text-uppercase opacity-75">Total Lessons</div>
              <div class="fs-3 fw-bold"><?= esc($stats['quizzes'] ?? 0) ?></div>
            </div>
            <div class="fs-1 opacity-75"><i class="bi bi-book"></i></div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <!-- Enrollments - Wisteria -->
        <div class="card border-0 rounded-4 h-100" style="background-color:#8e44ad; color:#ffffff;">
          <div class="card-body d-flex align-items-center justify-content-between">
            <div>
              <div class="small text-uppercase opacity-75">Enrollments</div>
              <div class="fs-3 fw-bold"><?= esc($stats['enrollments'] ?? 0) ?></div>
            </div>
            <div class="fs-1 opacity-75"><i class="bi bi-graph-up"></i></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick actions -->
    <div class="mb-4">
      <div class="d-flex align-items-center mb-2">
        <i class="bi bi-lightning me-2 text-success"></i>
        <span class="fw-semibold">Quick Actions</span>
      </div>
      <div class="d-flex flex-wrap gap-2">
        <a href="<?= site_url('admin/announcements/create') ?>" class="btn btn-success d-flex align-items-center">
          <i class="bi bi-plus-circle me-2"></i> Create Announcement
        </a>
        <a href="<?= site_url('announcements') ?>" class="btn btn-primary d-flex align-items-center">
          <i class="bi bi-eye me-2"></i> View Announcements
        </a>
        <a href="<?= site_url('admin/courses/create') ?>" class="btn btn-outline-primary d-flex align-items-center">
          <i class="bi bi-book-plus me-2"></i> Add Course
        </a>
      </div>
    </div>

    <!-- Admin actions -->
    <div class="mb-3 fw-semibold"><i class="bi bi-gear me-2"></i>Admin Actions</div>
    <div class="row g-3">
      <div class="col-md-6 col-lg-3">
        <div class="card border-0 rounded-4 h-100">
          <div class="card-body d-flex align-items-center">
            <div class="me-3 rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
              <i class="bi bi-people text-primary"></i>
            </div>
            <div>
              <a href="<?= site_url('admin/users') ?>" class="stretched-link text-decoration-none text-dark fw-semibold d-block">Manage Users</a>
              <small class="text-muted">Add, update, and remove users.</small>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="card border-0 rounded-4 h-100">
          <div class="card-body d-flex align-items-center">
            <div class="me-3 rounded-circle bg-success-subtle d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
              <i class="bi bi-book-half text-success"></i>
            </div>
            <div>
              <a href="<?= site_url('admin/courses') ?>" class="stretched-link text-decoration-none text-dark fw-semibold d-block">Manage Courses</a>
              <small class="text-muted">Organize and edit course content.</small>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="card border-0 rounded-4 h-100">
          <div class="card-body d-flex align-items-center">
            <div class="me-3 rounded-circle bg-warning-subtle d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
              <i class="bi bi-megaphone text-warning"></i>
            </div>
            <div>
              <a href="<?= site_url('admin/announcements') ?>" class="stretched-link text-decoration-none text-dark fw-semibold d-block">Manage Announcements</a>
              <small class="text-muted">Post and update news items.</small>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="card border-0 rounded-4 h-100">
          <div class="card-body d-flex align-items-center">
            <div class="me-3 rounded-circle bg-secondary-subtle d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
              <i class="bi bi-gear text-secondary"></i>
            </div>
            <div>
              <a href="#" class="text-decoration-none text-dark fw-semibold d-block">Site Settings</a>
              <small class="text-muted">Configure system preferences.</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
