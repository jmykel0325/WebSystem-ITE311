<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?= esc($title ?? 'ITE311') ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php $isLoggedIn = session('isLoggedIn') ?? false; $role = session('role') ?? null; ?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="<?= site_url('/') ?>">ITE311</a>
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
    <div id="nav" class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link <?= url_is('/')?'active':'' ?>" href="<?= site_url('/') ?>">Home</a></li>
        <li class="nav-item"><a class="nav-link <?= url_is('about')?'active':'' ?>" href="<?= site_url('about') ?>">About</a></li>
        <li class="nav-item"><a class="nav-link <?= url_is('contact')?'active':'' ?>" href="<?= site_url('contact') ?>">Contact</a></li>
        <?php if ($isLoggedIn): ?>
          <li class="nav-item"><a class="nav-link <?= url_is('dashboard')?'active':'' ?>" href="<?= site_url('dashboard') ?>">Dashboard</a></li>

          <?php if ($role === 'admin'): ?>
            <li class="nav-item"><a class="nav-link <?= url_is('admin/dashboard')?'active':'' ?>" href="<?= site_url('admin/dashboard') ?>">Admin</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Users</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Courses</a></li>
          <?php elseif ($role === 'teacher'): ?>
            <li class="nav-item"><a class="nav-link <?= url_is('teacher/courses')?'active':'' ?>" href="<?= site_url('teacher/courses') ?>">My Courses</a></li>
            <li class="nav-item"><a class="nav-link <?= url_is('teacher/quizzes')?'active':'' ?>" href="<?= site_url('teacher/quizzes') ?>">Quizzes</a></li>
          <?php elseif ($role === 'student'): ?>
            <li class="nav-item"><a class="nav-link <?= url_is('student/enrollments')?'active':'' ?>" href="<?= site_url('student/enrollments') ?>">My Enrollments</a></li>
            <li class="nav-item"><a class="nav-link <?= url_is('student/grades')?'active':'' ?>" href="<?= site_url('student/grades') ?>">Grades</a></li>
          <?php endif; ?>

          <li class="nav-item"><a class="nav-link" href="<?= site_url('logout') ?>">Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link <?= url_is('register')?'active':'' ?>" href="<?= site_url('register') ?>">Register</a></li>
          <li class="nav-item"><a class="nav-link <?= url_is('login')?'active':'' ?>" href="<?= site_url('login') ?>">Login</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<main class="container py-4">
  <?php if(session('success')): ?><div class="alert alert-success"><?= session('success') ?></div><?php endif; ?>
  <?php if(session('error')):   ?><div class="alert alert-danger"><?= session('error')   ?></div><?php endif; ?>
  <?= $this->renderSection('content') ?>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>