<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
  $name  = esc(session('name'));
  $email = esc(session('email'));
  $role  = esc(session('role'));
?>

<!-- Keep a friendly welcome, but no local logout button (navbar logout remains) -->
<div class="alert alert-success mb-4">
  Welcome, <?= $name ?>!
</div>

<h1 class="h4 mb-3">Dashboard</h1>

<div class="row g-4">
  <!-- Profile -->
  <div class="col-md-4">
    <div class="card h-100">
      <div class="card-header">My Profile</div>
      <div class="card-body">
        <p class="mb-1"><strong>Name:</strong> <?= $name ?></p>
        <p class="mb-1"><strong>Email:</strong> <?= $email ?></p>
        <p class="mb-0"><strong>Role:</strong> <?= $role ?></p>
      </div>
      <div class="card-footer text-muted small">Last login: just now</div>
    </div>
  </div>

  <!-- Quick Actions (example links – adjust to your app) -->
  <div class="col-md-4">
    <div class="card h-100">
      <div class="card-header">Quick Actions</div>
      <div class="card-body d-grid gap-2">
        <a href="<?= site_url('/') ?>" class="btn btn-primary">Go to Home</a>
        <a href="<?= site_url('about') ?>" class="btn btn-outline-secondary">About</a>
        <a href="<?= site_url('contact') ?>" class="btn btn-outline-secondary">Contact</a>
      </div>
    </div>
  </div>

  <!-- Announcements / Placeholder -->
  <div class="col-md-4">
    <div class="card h-100">
      <div class="card-header">Announcements</div>
      <div class="card-body">
        <p class="text-muted mb-2">No announcements yet.</p>
        <ul class="mb-0">
          <li class="text-muted">Stay tuned for upcoming updates.</li>
        </ul>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
