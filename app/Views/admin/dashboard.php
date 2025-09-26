<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h1 class="h4 mb-4">Admin Dashboard</h1>
<div class="row g-3">
  <div class="col-md-3">
    <div class="card text-bg-primary">
      <div class="card-body">
        <div class="fs-6">Users</div>
        <div class="fs-3 fw-bold"><?= esc($stats['users'] ?? 0) ?></div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-bg-success">
      <div class="card-body">
        <div class="fs-6">Courses</div>
        <div class="fs-3 fw-bold"><?= esc($stats['courses'] ?? 0) ?></div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-bg-warning">
      <div class="card-body">
        <div class="fs-6">Quizzes</div>
        <div class="fs-3 fw-bold"><?= esc($stats['quizzes'] ?? 0) ?></div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-bg-danger">
      <div class="card-body">
        <div class="fs-6">Enrollments</div>
        <div class="fs-3 fw-bold"><?= esc($stats['enrollments'] ?? 0) ?></div>
      </div>
    </div>
  </div>
</div>

<div class="mt-4 d-flex gap-3">
  <a href="#" class="btn btn-outline-primary">Manage Users</a>
  <a href="#" class="btn btn-outline-secondary">Manage Courses</a>
</div>

<?= $this->endSection() ?>
