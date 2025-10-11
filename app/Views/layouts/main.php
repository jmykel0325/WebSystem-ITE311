<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title><?= esc($title ?? 'ITE311 LMS') ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Bootstrap 5.3 + Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
  <!-- Theme -->
  <link href="<?= base_url('assets/css/theme.css') ?>" rel="stylesheet">
  <?= csrf_meta() ?>
</head>
<body>

  <!-- Modern Navbar -->
  <nav class="navbar navbar-expand-lg navbar-modern sticky-top">
    <div class="container">
      <a class="navbar-brand fw-bold" href="<?= site_url('/') ?>">
        <i class="bi bi-mortarboard"></i> ITE311
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="topNav">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link <?= url_is('/') ? 'active' : '' ?>" href="<?= site_url('/') ?>">Home</a></li>
          <li class="nav-item"><a class="nav-link <?= url_is('about') ? 'active' : '' ?>" href="<?= site_url('about') ?>">About</a></li>
          <li class="nav-item"><a class="nav-link <?= url_is('contact') ? 'active' : '' ?>" href="<?= site_url('contact') ?>">Contact</a></li>

          <?php if (session('isLoggedIn')): ?>
            <li class="nav-item"><a class="nav-link <?= url_is('dashboard') ? 'active' : '' ?>" href="<?= site_url('dashboard') ?>">Dashboard</a></li>

            <?php if (session('role') === 'student'): ?>
              <li class="nav-item"><a class="nav-link <?= url_is('student/enrollments') ? 'active' : '' ?>" href="<?= site_url('student/enrollments') ?>">My Enrollments</a></li>
            <?php endif; ?>

            <?php if (session('role') === 'teacher'): ?>
              <li class="nav-item"><a class="nav-link <?= url_is('teacher/courses*') ? 'active' : '' ?>" href="<?= site_url('teacher/courses') ?>">My Courses</a></li>
            <?php endif; ?>

            <?php if (session('role') === 'admin'): ?>
              <li class="nav-item"><a class="nav-link <?= url_is('admin/*') ? 'active' : '' ?>" href="<?= site_url('admin') ?>">Admin</a></li>
            <?php endif; ?>
          <?php endif; ?>
        </ul>

        <ul class="navbar-nav">
          <?php if (session('isLoggedIn')): ?>
            <li class="nav-item">
              <span class="nav-link">Hi, <strong><?= esc(session('name')) ?></strong></span>
            </li>
                <li class="nav-item">
                  <a class="logout-btn" href="<?= site_url('logout') ?>" title="Logout">
                    <i class="bi bi-power"></i>
                    <span>Logout</span>
                  </a>
                </li>
          <?php else: ?>
            <li class="nav-item">
              <a class="btn btn-primary btn-sm" href="<?= site_url('login') ?>">
                <i class="bi bi-box-arrow-in-right"></i> Login
              </a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Page header slot (optional) -->
  <?php if (isset($header)): ?>
    <header class="py-4 bg-white border-bottom">
      <div class="container">
        <?= $header ?>
      </div>
    </header>
  <?php endif; ?>

  <!-- Main content -->
  <main class="py-4">
    <div class="container">
      <?= $this->renderSection('content') ?>
    </div>
  </main>

  <!-- Toast container -->
  <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1080">
    <div id="appToast" class="toast toast-modern align-items-center text-bg-primary border-0" role="alert"
         aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body" id="appToastBody">Action completed.</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script>
    // Fallback jQuery if CDN fails
    if (typeof jQuery === 'undefined') {
      document.write('<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"><\/script>');
    }
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function showToast(message, type = 'primary') {
      const toast = document.getElementById('appToast');
      toast.className = 'toast toast-modern align-items-center text-bg-'+type+' border-0';
      document.getElementById('appToastBody').innerText = message;
      new bootstrap.Toast(toast, { delay: 2500 }).show();
    }
  </script>
</body>
</html>