<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="bi bi-folder2-open me-2"></i>
                    My Course Materials
                </h4>
            </div>
            <div class="card-body">
                <!-- Welcome Message -->
                <div class="mb-4 p-3 rounded-3" style="background-color:#ff9361; color:#0e0e0e;">
                    <h5 class="alert-heading mb-1">
                        <i class="bi bi-info-circle me-2"></i>
                        Learning Resources
                    </h5>
                    <p class="mb-0" style="opacity:.9;">Access all learning materials from your enrolled courses</p>
                </div>

                <!-- Materials List -->
                <?php if (empty($materials)): ?>
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-inbox display-1"></i>
                        <h5 class="mt-3">No Materials Available</h5>
                        <p>You don't have any course materials yet. Enroll in courses to access learning resources.</p>
                        <a href="<?= site_url('student/enrollments') ?>" class="btn btn-primary mt-3">
                            <i class="bi bi-plus-circle me-2"></i>
                            Browse Courses
                        </a>
                    </div>
                <?php else: ?>
                    <!-- Group materials by course -->
                    <?php
                    $materialsByCourse = [];
                    foreach ($materials as $material) {
                        $courseTitle = $material['course_title'];
                        if (!isset($materialsByCourse[$courseTitle])) {
                            $materialsByCourse[$courseTitle] = [];
                        }
                        $materialsByCourse[$courseTitle][] = $material;
                    }
                    ?>

                    <?php foreach ($materialsByCourse as $courseTitle => $courseMaterials): ?>
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">
                                    <i class="bi bi-book me-2"></i>
                                    <?= esc($courseTitle) ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="list-group">
                                    <?php foreach ($courseMaterials as $material): ?>
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div class="flex-grow-1">
                                                <i class="bi bi-file-earmark-text text-primary me-2"></i>
                                                <strong><?= esc($material['file_name']) ?></strong>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="bi bi-clock me-1"></i>
                                                    Uploaded: <?= date('M d, Y', strtotime($material['created_at'])) ?>
                                                </small>
                                            </div>
                                            <div>
                                                <a href="<?= site_url('materials/download/' . $material['id']) ?>" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="bi bi-download me-1"></i>
                                                    Download
                                                </a>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <!-- Summary -->
                    <div class="mt-3">
                        <p class="text-muted mb-0">
                            <i class="bi bi-info-circle me-2"></i>
                            Total Materials: <strong><?= count($materials) ?></strong> from 
                            <strong><?= count($materialsByCourse) ?></strong> course(s)
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
