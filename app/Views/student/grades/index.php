<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<h1 class="h4 mb-3">My Grades</h1>
<table class="table table-striped">
  <thead>
    <tr>
      <th>Course</th>
      <th>Quiz</th>
      <th>Score</th>
    </tr>
  </thead>
  <tbody>
    <?php if (empty($grades)): ?>
      <tr><td colspan="3" class="text-muted">No grades yet.</td></tr>
    <?php else: foreach ($grades as $g): ?>
      <tr>
        <td><?= esc($g['course_title']) ?></td>
        <td><?= esc('Quiz #' . $g['quiz_id']) ?></td>
        <td><?= esc($g['score']) ?></td>
      </tr>
    <?php endforeach; endif; ?>
  </tbody>
</table>
<?= $this->endSection() ?>
