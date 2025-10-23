<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">
                    <i class="bi bi-person-badge me-2"></i>
                    Teacher Dashboard
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
                                <div class="fs-6">My Courses</div>
                                <div class="fs-3 fw-bold"><?= esc($stats['my_courses'] ?? 0) ?></div>
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
                                <div class="fs-6">My Quizzes</div>
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
                        <a href="<?= site_url('teacher/courses') ?>" class="btn btn-primary w-100">
                            <i class="bi bi-book-half me-2"></i>
                            My Courses
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?= site_url('teacher/quizzes') ?>" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-clipboard-check me-2"></i>
                            My Quizzes
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?= site_url('teacher/announcements') ?>" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-megaphone me-2"></i>
                            Announcements
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- My Courses -->
        <?php if (!empty($courses)): ?>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-book-half me-2"></i>
                        My Courses
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Course Code</th>
                                    <th>Course Title</th>
                                    <th class="text-center">Students</th>
                                    <th class="text-center">Materials</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($courses as $course): ?>
                                    <tr>
                                        <td>
                                            <strong class="badge bg-primary">
                                                <?= !empty($course['course_number']) ? esc($course['course_number']) : 'N/A' ?>
                                            </strong>
                                        </td>
                                        <td>
                                            <strong><?= esc($course['title']) ?></strong>
                                            <?php if (!empty($course['description'])): ?>
                                                <br>
                                                <small class="text-muted">
                                                    <?= esc(substr($course['description'], 0, 50)) ?>
                                                    <?= strlen($course['description']) > 50 ? '...' : '' ?>
                                                </small>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info"><?= $course['student_count'] ?></span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success"><?= $course['material_count'] ?></span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="<?= site_url('admin/course/' . $course['id'] . '/upload') ?>" 
                                                   class="btn btn-outline-primary"
                                                   title="Upload Materials">
                                                    <i class="bi bi-folder-plus"></i>
                                                </a>
                                                <a href="<?= site_url('materials/list/' . $course['id']) ?>" 
                                                   class="btn btn-outline-secondary"
                                                   title="View Materials">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted"></i>
                    <h5 class="mt-3">No Courses Assigned Yet</h5>
                    <p class="text-muted">Contact the administrator to get courses assigned to you.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
