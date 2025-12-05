<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row mb-3">
  <div class="col-12">
    <h1 class="h3 mb-2">Contact</h1>
    <p class="text-muted mb-0">Get in touch with the ITE311 LMS team.</p>
  </div>
</div>

<div class="row g-3">
  <div class="col-md-6">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <h5 class="card-title">Support</h5>
        <p class="small text-muted mb-2">
          For questions about courses, enrollments, or technical issues, use the contact details below.
        </p>
        <ul class="list-unstyled small mb-0">
          <li><i class="bi bi-envelope me-2"></i>Email: <a href="mailto:you@example.com">you@example.com</a></li>
          <li><i class="bi bi-telephone me-2"></i>Phone: 09xx xxx xxxx</li>
        </ul>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <h5 class="card-title">Office hours</h5>
        <ul class="small text-muted mb-0">
          <li>Monday &ndash; Friday: 8:00 AM &ndash; 5:00 PM</li>
          <li>Responses may take up to one working day.</li>
        </ul>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
