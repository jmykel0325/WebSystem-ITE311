<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="bi bi-megaphone me-2"></i>
                Manage Announcements
            </h1>
            <a href="<?= site_url('admin/announcements/create') ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>
                Create Announcement
            </a>
        </div>

        <!-- Success Message -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($announcements)): ?>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Content</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($announcements as $announcement): ?>
                                    <tr>
                                        <td>
                                            <strong><?= esc($announcement['title']) ?></strong>
                                        </td>
                                        <td>
                                            <?= esc(substr($announcement['content'], 0, 100)) ?>
                                            <?php if (strlen($announcement['content']) > 100): ?>
                                                <span class="text-muted">...</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <?= date('M j, Y g:i A', strtotime($announcement['created_at'])) ?>
                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="<?= site_url('admin/announcements/edit/' . $announcement['id']) ?>" 
                                                   class="btn btn-outline-primary">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="<?= site_url('admin/announcements/delete/' . $announcement['id']) ?>" 
                                                   class="btn btn-outline-danger"
                                                   onclick="return confirm('Are you sure you want to delete this announcement?')">
                                                    <i class="bi bi-trash"></i>
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
                    <div class="text-muted">
                        <i class="bi bi-megaphone display-1 text-muted"></i>
                        <h4 class="mt-3 text-muted">No announcements yet</h4>
                        <p class="text-muted">Create your first announcement to get started.</p>
                        <a href="<?= site_url('admin/announcements/create') ?>" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i>
                            Create First Announcement
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
