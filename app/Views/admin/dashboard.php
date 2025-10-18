<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="bi bi-shield-check me-2"></i>
                ADMIN DASHBOARD
            </h1>
        </div>

        <!-- Welcome Message -->
        <div class="alert alert-primary" role="alert">
            <h4 class="alert-heading">Welcome, <?= esc(session('name')) ?>!</h4>
            <p class="mb-0">Manage your university portal from this central dashboard.</p>
        </div>

        <!-- Summary Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card text-bg-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <div class="fs-6">Total Users</div>
                                <div class="fs-3 fw-bold"><?= esc($stats['users'] ?? 0) ?></div>
                            </div>
                            <div class="fs-1 opacity-75">
                                <i class="bi bi-people"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-bg-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <div class="fs-6">Total Courses</div>
                                <div class="fs-3 fw-bold"><?= esc($stats['courses'] ?? 0) ?></div>
                            </div>
                            <div class="fs-1 opacity-75">
                                <i class="bi bi-stack"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-bg-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <div class="fs-6">Total Lessons</div>
                                <div class="fs-3 fw-bold"><?= esc($stats['quizzes'] ?? 0) ?></div>
                            </div>
                            <div class="fs-1 opacity-75">
                                <i class="bi bi-book"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-bg-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <div class="fs-6">Enrollments</div>
                                <div class="fs-3 fw-bold"><?= esc($stats['enrollments'] ?? 0) ?></div>
                            </div>
                            <div class="fs-1 opacity-75">
                                <i class="bi bi-graph-up"></i>
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
                    <div class="col-md-6 col-lg-3">
                        <a href="<?= site_url('admin/announcements/create') ?>" class="btn btn-success w-100 d-flex align-items-center justify-content-center">
                            <i class="bi bi-plus-circle me-2"></i>
                            Create Announcement
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <a href="<?= site_url('announcements') ?>" class="btn btn-info w-100 d-flex align-items-center justify-content-center">
                            <i class="bi bi-eye me-2"></i>
                            View Announcements
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Actions -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-gear me-2"></i>
                    Admin Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6 col-lg-3">
                        <a href="#" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center">
                            <i class="bi bi-people me-2"></i>
                            Manage Users
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <a href="#" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center">
                            <i class="bi bi-file-text me-2"></i>
                            Manage Courses
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <a href="<?= site_url('admin/announcements') ?>" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center">
                            <i class="bi bi-megaphone me-2"></i>
                            Manage Announcements
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <a href="#" class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center">
                            <i class="bi bi-gear me-2"></i>
                            Site Settings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
