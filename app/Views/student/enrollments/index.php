<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<h1 class="h4 mb-3">My Enrollments</h1>

<?php if(session('success')): ?><div class="alert alert-success"><?= esc(session('success')) ?></div><?php endif; ?>
<?php if(session('error')): ?><div class="alert alert-danger"><?= esc(session('error')) ?></div><?php endif; ?>

<h2 class="h5">Enrolled Courses</h2>
<table class="table table-striped">
  <thead><tr><th>Course</th><th>Enrolled At</th></tr></thead>
  <tbody>
    <?php if (empty($enrollments)): ?>
      <tr><td colspan="2" class="text-muted">No enrollments yet.</td></tr>
    <?php else: foreach ($enrollments as $e): ?>
      <tr>
        <td><?= esc($e['course_title']) ?></td>
        <td><?= esc($e['enrolled_at']) ?></td>
      </tr>
    <?php endforeach; endif; ?>
  </tbody>
</table>

<h2 class="h5 mt-4">Available Courses</h2>
<table class="table table-hover">
  <thead><tr><th>Title</th><th>Description</th><th></th></tr></thead>
  <tbody>
    <?php if (empty($available)): ?>
      <tr><td colspan="3" class="text-muted">No available courses.</td></tr>
    <?php else: foreach ($available as $c): ?>
      <tr>
        <td><?= esc($c['title']) ?></td>
        <td><?= esc($c['description']) ?></td>
        <td>
          <form method="post" action="<?= site_url('student/enrollments/enroll') ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="course_id" value="<?= esc($c['id']) ?>">
            <button class="btn btn-sm btn-primary" type="submit">Enroll</button>
          </form>
        </td>
      </tr>
    <?php endforeach; endif; ?>
  </tbody>
</table>
<?= $this->endSection() ?>
