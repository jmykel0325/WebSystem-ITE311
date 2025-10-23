<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="bi bi-folder2-open me-2"></i>
                        Course Materials
                    </h4>
                    <?php if (session()->get('role') === 'admin' || session()->get('role') === 'teacher'): ?>
                        <a href="<?= site_url('admin/course/' . $course['id'] . '/upload') ?>" 
                           class="btn btn-light btn-sm">
                            <i class="bi bi-plus-circle me-2"></i>
                            Upload New Material
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <!-- Course Information -->
                <div class="alert alert-info mb-4">
                    <h5 class="alert-heading">
                        <i class="bi bi-book me-2"></i>
                        <?= esc($course['title']) ?>
                    </h5>
                    <?php if (isset($course['description'])): ?>
                        <p class="mb-0"><?= esc($course['description']) ?></p>
                    <?php endif; ?>
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

                <!-- Materials List -->
                <?php if (empty($materials)): ?>
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-inbox display-1"></i>
                        <h5 class="mt-3">No Materials Available</h5>
                        <p>There are no learning materials uploaded for this course yet.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th><i class="bi bi-file-earmark me-2"></i>File Name</th>
                                    <th><i class="bi bi-calendar me-2"></i>Upload Date</th>
                                    <th class="text-center"><i class="bi bi-gear me-2"></i>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($materials as $material): ?>
                                    <tr>
                                        <td>
                                            <i class="bi bi-file-earmark-text text-primary me-2"></i>
                                            <strong><?= esc($material['file_name']) ?></strong>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <?= date('M d, Y h:i A', strtotime($material['created_at'])) ?>
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="<?= site_url('materials/download/' . $material['id']) ?>" 
                                                   class="btn btn-sm btn-primary"
                                                   title="Download">
                                                    <i class="bi bi-download me-1"></i>
                                                    Download
                                                </a>
                                                
                                                <?php if (session()->get('role') === 'admin' || session()->get('role') === 'teacher'): ?>
                                                    <a href="<?= site_url('materials/delete/' . $material['id']) ?>" 
                                                       class="btn btn-sm btn-danger"
                                                       onclick="return confirm('Are you sure you want to delete this material?')"
                                                       title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Summary -->
                    <div class="mt-3">
                        <p class="text-muted mb-0">
                            <i class="bi bi-info-circle me-2"></i>
                            Total Materials: <strong><?= count($materials) ?></strong>
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
