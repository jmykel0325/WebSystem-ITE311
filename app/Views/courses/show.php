<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0">
                        <i class="bi bi-book-half me-2"></i>
                        <?= esc($course['title'] ?? 'Course') ?>
                    </h4>
                    <?php if (!empty($course['course_number'])): ?>
                        <small class="text-white-50">Course Number: <?= esc($course['course_number']) ?></small>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <?php if (!empty($course['semester'])): ?>
                        <span class="badge bg-secondary me-2">
                            <?= $course['semester'] === 'first' ? 'First Semester' : 'Second Semester' ?>
                        </span>
                    <?php endif; ?>
                    <?php if (!empty($course['start_date']) || !empty($course['end_date'])): ?>
                        <small class="text-muted">
                            <?php if (!empty($course['start_date'])): ?>
                                <?= date('M d, Y', strtotime($course['start_date'])) ?>
                            <?php endif; ?>
                            <?php if (!empty($course['end_date'])): ?>
                                &ndash; <?= date('M d, Y', strtotime($course['end_date'])) ?>
                            <?php endif; ?>
                        </small>
                    <?php endif; ?>
                </div>

                <?php if (!empty($course['description'])): ?>
                    <h6 class="fw-semibold mb-2">Description</h6>
                    <p class="text-muted mb-3">
                        <?= esc($course['description']) ?>
                    </p>
                <?php else: ?>
                    <p class="text-muted mb-3">No description provided for this course.</p>
                <?php endif; ?>

                <?php if (!empty($course['days_pattern']) || !empty($course['duration_months'])): ?>
                    <div class="row g-3 mb-3">
                        <?php if (!empty($course['days_pattern'])): ?>
                            <div class="col-md-6">
                                <div class="small text-muted text-uppercase">Schedule</div>
                                <div>
                                    <span class="badge bg-light text-dark border me-1">
                                        <?= esc($course['days_pattern']) ?>
                                    </span>
                                    <?php if (!empty($course['start_time']) || !empty($course['end_time'])): ?>
                                        <br>
                                        <small class="text-muted">
                                            <?= !empty($course['start_time']) ? date('h:i A', strtotime($course['start_time'])) : '?' ?>
                                            &ndash;
                                            <?= !empty($course['end_time']) ? date('h:i A', strtotime($course['end_time'])) : '?' ?>
                                        </small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($course['duration_months'])): ?>
                            <div class="col-md-6">
                                <div class="small text-muted text-uppercase">Duration</div>
                                <div><?= (int)$course['duration_months'] ?> month(s)</div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
                    <a href="<?= site_url('courses') ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>
                        Back to Browse Courses
                    </a>
                    <a href="<?= site_url('materials/list/' . (int)($course['id'] ?? 0)) ?>" class="btn btn-primary">
                        <i class="bi bi-folder2-open me-1"></i>
                        View Materials
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
