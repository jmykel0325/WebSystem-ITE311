<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">
                    <i class="bi bi-mortarboard me-2"></i>
                    Student Dashboard
                </h1>
                <p class="text-muted mb-0">Welcome back, <?= esc(session('name')) ?>!</p>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card text-bg-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <div class="fs-6">Enrolled Courses</div>
                                <div class="fs-3 fw-bold"><?= esc($stats['enrolled'] ?? 0) ?></div>
                            </div>
                            <div class="fs-1 opacity-75">
                                <i class="bi bi-book-half"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-bg-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <div class="fs-6">Quizzes Available</div>
                                <div class="fs-3 fw-bold"><?= esc($stats['quizzes'] ?? 0) ?></div>
                            </div>
                            <div class="fs-1 opacity-75">
                                <i class="bi bi-clipboard-check"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-lightning me-2"></i>
                    Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <a href="<?= site_url('student/enrollments') ?>" class="btn btn-primary w-100">
                            <i class="bi bi-plus-circle me-2"></i>
                            My Enrollments
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?= site_url('student/materials') ?>" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-folder2-open me-2"></i>
                            My Materials
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?= site_url('student/grades') ?>" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-graph-up me-2"></i>
                            View Grades
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enrolled Courses -->
        <?php if (!empty($enrolledCourses)): ?>
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-book-half me-2"></i>
                            My Enrolled Courses
                        </h5>
                        <span class="badge bg-primary"><?= count($enrolledCourses) ?> courses</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <?php foreach ($enrolledCourses as $course): ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <span class="badge bg-primary">
                                                <?= !empty($course['course_number']) ? esc($course['course_number']) : 'N/A' ?>
                                            </span>
                                            <small class="text-muted">
                                                <i class="bi bi-calendar-check"></i>
                                                <?= date('M d, Y', strtotime($course['enrollment_date'])) ?>
                                            </small>
                                        </div>
                                        <h6 class="card-title"><?= esc($course['title']) ?></h6>
                                        <p class="card-text text-muted small">
                                            <?= esc(substr($course['description'] ?? 'No description', 0, 80)) ?>
                                            <?= strlen($course['description'] ?? '') > 80 ? '...' : '' ?>
                                        </p>
                                        <?php if (!empty($course['teacher_name'])): ?>
                                            <p class="card-text small">
                                                <i class="bi bi-person-circle me-1"></i>
                                                <strong>Teacher:</strong> <?= esc($course['teacher_name']) ?>
                                            </p>
                                        <?php endif; ?>
                                        <div class="d-grid gap-2">
                                            <a href="<?= site_url('materials/list/' . $course['id']) ?>" 
                                               class="btn btn-sm btn-primary">
                                                <i class="bi bi-folder2-open me-1"></i>
                                                View Materials
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted"></i>
                    <h5 class="mt-3">No Enrolled Courses Yet</h5>
                    <p class="text-muted">Start your learning journey by enrolling in courses!</p>
                    <a href="<?= site_url('student/enrollments') ?>" class="btn btn-primary mt-3">
                        <i class="bi bi-plus-circle me-2"></i>
                        Browse Courses
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
