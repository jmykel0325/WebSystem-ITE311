<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>
<div class="container mt-4">
    <h1 class="mb-3">Quiz Scores</h1>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= esc(session()->getFlashdata('error')); ?>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= esc(session()->getFlashdata('success')); ?>
        </div>
    <?php endif; ?>

    <div class="mb-3">
        <p class="mb-1"><strong>Course:</strong> <?= esc($quiz['course_title'] ?? ''); ?></p>
        <p class="mb-1"><strong>Lesson:</strong> <?= esc($quiz['lesson_title'] ?? ''); ?></p>
        <p class="mb-1"><strong>Quiz Title:</strong> <?= esc($quiz['title'] ?? ''); ?></p>
    </div>

    <?php if (empty($scores)): ?>
        <div class="alert alert-info">No scores recorded for this quiz yet.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Student Name</th>
                        <th>Email</th>
                        <th>Score (%)</th>
                        <th>Submitted At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach ($scores as $row): ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= esc($row['student_name'] ?? ''); ?></td>
                            <td><?= esc($row['student_email'] ?? ''); ?></td>
                            <td><?= esc($row['score']); ?></td>
                            <td><?= esc($row['created_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <a href="<?= site_url('teacher/quizzes'); ?>" class="btn btn-secondary mt-3">Back to Quizzes</a>
</div>
<?= $this->endSection(); ?>
