<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<h1 class="h4 mb-3">My Courses</h1>
<div class="mb-3">
  <a href="#" class="btn btn-primary disabled">Create Course</a>
</div>
<table class="table table-striped">
  <thead>
    <tr>
      <th>Title</th>
      <th>Created</th>
    </tr>
  </thead>
  <tbody>
    <?php if (empty($courses)): ?>
      <tr><td colspan="2" class="text-muted">No courses yet.</td></tr>
    <?php else: foreach ($courses as $c): ?>
      <tr>
        <td><?= esc($c['title']) ?></td>
        <td><?= esc($c['created_at']) ?></td>
      </tr>
    <?php endforeach; endif; ?>
  </tbody>
</table>
<?= $this->endSection() ?>
