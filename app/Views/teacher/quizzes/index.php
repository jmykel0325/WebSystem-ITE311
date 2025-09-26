<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<h1 class="h4 mb-3">My Quizzes</h1>
<div class="mb-3">
  <a href="#" class="btn btn-primary disabled">Create Quiz</a>
</div>
<table class="table table-striped">
  <thead>
    <tr>
      <th>ID</th>
      <th>Course</th>
      <th>Lesson</th>
      <th>Question</th>
    </tr>
  </thead>
  <tbody>
    <?php if (empty($quizzes)): ?>
      <tr><td colspan="4" class="text-muted">No quizzes yet.</td></tr>
    <?php else: foreach ($quizzes as $q): ?>
      <tr>
        <td><?= esc($q['id']) ?></td>
        <td><?= esc($q['course_title']) ?></td>
        <td><?= esc($q['lesson_title']) ?></td>
        <td><?= esc($q['question']) ?></td>
      </tr>
    <?php endforeach; endif; ?>
  </tbody>
</table>
<?= $this->endSection() ?>
