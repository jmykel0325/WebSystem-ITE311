<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="bi bi-cloud-upload me-2"></i>
                    Upload Material
                </h4>
            </div>
            <div class="card-body">
                <!-- Course Information -->
                <div class="alert alert-info">
                    <h5 class="alert-heading">
                        <i class="bi bi-book me-2"></i>
                        <?= esc($course['title']) ?>
                    </h5>
                    <p class="mb-0">Upload learning materials for this course</p>
                </div>

                <!-- Success/Error Messages -->
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <ul class="mb-0">
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Upload Form -->
                <?= form_open_multipart('admin/course/' . $course['id'] . '/upload', ['class' => 'needs-validation', 'novalidate' => '']) ?>
                    
                    <div class="mb-4">
                        <label for="material_file" class="form-label fw-bold">
                            <i class="bi bi-file-earmark-arrow-up me-2"></i>
                            Select File to Upload
                        </label>
                        <input type="file" 
                               class="form-control form-control-lg" 
                               id="material_file" 
                               name="material_file" 
                               required
                               accept=".pdf,.ppt">
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>
                            Allowed file types: PDF, PPT (Max size: 10MB)
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="<?= site_url('materials/list/' . $course['id']) ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-upload me-2"></i>
                            Upload Material
                        </button>
                    </div>

                <?= form_close() ?>
            </div>
        </div>

        <!-- Existing Materials -->
        <div class="card shadow-sm mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-files me-2"></i>
                    Existing Materials
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <?php
                    $materialModel = new \App\Models\MaterialModel();
                    $materials = $materialModel->getMaterialsByCourse($course['id']);
                    ?>
                    
                    <?php if (empty($materials)): ?>
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-inbox display-4"></i>
                            <p class="mt-2">No materials uploaded yet</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($materials as $material): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="bi bi-file-earmark-text me-2"></i>
                                    <strong><?= esc($material['file_name']) ?></strong>
                                    <br>
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i>
                                        Uploaded: <?= date('M d, Y h:i A', strtotime($material['created_at'])) ?>
                                    </small>
                                </div>
                                <div class="btn-group" role="group">
                                    <a href="<?= site_url('materials/download/' . $material['id']) ?>" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-download"></i>
                                    </a>
                                    <a href="<?= site_url('materials/delete/' . $material['id']) ?>" 
                                       class="btn btn-sm btn-outline-danger"
                                       onclick="return confirm('Are you sure you want to delete this material?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Bootstrap form validation
(function() {
    'use strict';
    var forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();
</script>

<?= $this->endSection() ?>
