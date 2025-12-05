<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-center align-items-center" style="min-height: calc(100vh - 140px);">
  <div class="text-center px-3 px-md-4" style="max-width: 760px;">
    <h1 class="display-5 fw-bold mb-3 text-dark">Welcome to ITE311 Learning Portal</h1>
    <p class="lead text-muted mb-3">
      The ITE311 Learning Management System (LMS) is your official online learning space designed to support both
      students and teachers. Our goal is to make learning more accessible, organized, and flexible through a single
      digital platform.
    </p>
    <p class="text-muted mb-2">
      Students can easily access their subjects, class materials, assignments, quizzes, and announcements&mdash;anytime,
      anywhere.
    </p>
    <p class="text-muted mb-2">
      Teachers can manage classes, upload lessons, monitor student progress, and share course updates with ease.
    </p>
    <p class="text-muted mb-0">
      This LMS is part of our ongoing commitment to provide technology-enhanced education that empowers every learner
      in our institution.
    </p>
  </div>
</div>

<?= $this->endSection() ?>
