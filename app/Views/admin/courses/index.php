<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="bi bi-book-half me-2"></i>
                        Manage Courses
                    </h4>
                    <a href="<?= site_url('admin/courses/create') ?>" class="btn btn-light btn-sm">
                        <i class="bi bi-plus-circle me-2"></i>
                        Add New Course
                    </a>
                </div>
            </div>
            <div class="card-body">
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

                <!-- Courses Table -->
                <?php if (empty($courses)): ?>
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-inbox display-1"></i>
                        <h5 class="mt-3">No Courses Yet</h5>
                        <p>Start by creating your first course</p>
                        <a href="<?= site_url('admin/courses/create') ?>" class="btn btn-primary mt-3">
                            <i class="bi bi-plus-circle me-2"></i>
                            Create First Course
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Course Number</th>
                                    <th>Course Title</th>
                                    <th>Teacher</th>
                                    <th class="text-center">Materials</th>
                                    <th class="text-center">Students</th>
                                    <th>Created</th>
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
                                                    <?= esc(substr($course['description'], 0, 60)) ?>
                                                    <?= strlen($course['description']) > 60 ? '...' : '' ?>
                                                </small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($course['teacher_name']): ?>
                                                <i class="bi bi-person-circle me-1"></i>
                                                <?= esc($course['teacher_name']) ?>
                                                <br>
                                                <small class="text-muted"><?= esc($course['teacher_email']) ?></small>
                                            <?php else: ?>
                                                <span class="text-muted">No teacher assigned</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info">
                                                <?= $course['material_count'] ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success">
                                                <?= $course['enrollment_count'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <?= date('M d, Y', strtotime($course['created_at'])) ?>
                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <!-- Materials Button -->
                                                <a href="<?= site_url('admin/course/' . $course['id'] . '/upload') ?>" 
                                                   class="btn btn-sm btn-info"
                                                   title="Manage Materials">
                                                    <i class="bi bi-folder-plus"></i>
                                                </a>
                                                
                                                <!-- View Materials -->
                                                <a href="<?= site_url('materials/list/' . $course['id']) ?>" 
                                                   class="btn btn-sm btn-secondary"
                                                   title="View Materials">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                
                                                <!-- Edit Button -->
                                                <a href="<?= site_url('admin/courses/edit/' . $course['id']) ?>" 
                                                   class="btn btn-sm btn-primary"
                                                   title="Edit Course">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                
                                                <!-- Delete Button -->
                                                <a href="<?= site_url('admin/courses/delete/' . $course['id']) ?>" 
                                                   class="btn btn-sm btn-danger"
                                                   onclick="return confirm('Are you sure you want to delete this course? This will also delete all materials and enrollments!')"
                                                   title="Delete Course">
                                                    <i class="bi bi-trash"></i>
                                                </a>
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
                            Total Courses: <strong><?= count($courses) ?></strong>
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
