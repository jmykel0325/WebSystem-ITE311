<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row mb-3">
  <div class="col-12">
    <h1 class="h3 mb-2">About ITE311 LMS</h1>
    <p class="text-muted mb-0">
      This project is a lightweight learning management system built for the ITE311 Web Systems course.
      It is designed to practice real-world web development concepts using PHP and CodeIgniter 4.
    </p>
  </div>
</div>

<div class="row g-3">
  <div class="col-md-6">
    <div class="card h-100 border-0 shadow-sm">
      <div class="card-body">
        <h5 class="card-title">What you can do</h5>
        <ul class="small text-muted mb-0">
          <li>Admins manage courses, semesters, schedules, and user accounts.</li>
          <li>Teachers handle materials, announcements, and student enrollments.</li>
          <li>Students enroll in subjects and access learning resources in one place.</li>
        </ul>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card h-100 border-0 shadow-sm">
      <div class="card-body">
        <h5 class="card-title">Technologies used</h5>
        <ul class="small text-muted mb-0">
          <li>PHP 8 with <strong>CodeIgniter 4</strong> MVC framework.</li>
          <li><strong>Bootstrap 5</strong> for responsive layout and components.</li>
          <li>MySQL database for courses, users, enrollments, and materials.</li>
        </ul>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
