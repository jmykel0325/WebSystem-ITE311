<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="container py-4">
  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
  <?php endif; ?>
  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
  <?php endif; ?>

  <h1 class="h4 mb-3">My Quizzes</h1>
  <div class="mb-3">
    <a href="<?= site_url('teacher/quizzes/create') ?>" class="btn btn-primary">Create Quiz</a>
  </div>

  <table class="table table-striped align-middle">
  <thead>
    <tr>
      <th>#</th>
      <th>Course</th>
      <th>Quiz Title</th>
      <th>Questions</th>
      <th style="width: 160px;">Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php if (empty($quizzes)): ?>
      <tr><td colspan="5" class="text-muted">No quizzes yet.</td></tr>
    <?php else: $i = 1; foreach ($quizzes as $q): ?>
      <tr>
        <td><?= $i++ ?></td>
        <td><?= esc($q['course_title']) ?></td>
        <td><?= esc($q['title']) ?></td>
        <td><?= (int)($q['question_count'] ?? 0) ?></td>
        <td>
          <div class="btn-group btn-group-sm" role="group">
            <a href="<?= site_url('teacher/quizzes/manage/' . (int)$q['any_id']) ?>" class="btn btn-outline-secondary">Questions</a>
            <a href="<?= site_url('teacher/quizzes/scores/' . (int)$q['any_id']) ?>" class="btn btn-outline-success">Scores</a>
            <a href="<?= site_url('teacher/quizzes/edit/' . (int)$q['any_id']) ?>" class="btn btn-outline-primary">Edit</a>
            <a href="<?= site_url('teacher/quizzes/delete/' . (int)$q['any_id']) ?>" class="btn btn-outline-danger" onclick="return confirm('Delete this quiz?');">Delete</a>
          </div>
        </td>
      </tr>
    <?php endforeach; endif; ?>
  </tbody>
  </table>
</div>
<?= $this->endSection() ?>
