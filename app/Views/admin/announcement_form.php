<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="bi bi-megaphone me-2"></i>
                <?= $announcement ? 'Edit Announcement' : 'Create Announcement' ?>
            </h1>
            <a href="<?= site_url('admin/announcements') ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                Back to Announcements
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="<?= site_url($announcement ? 'admin/announcements/update/' . $announcement['id'] : 'admin/announcements/store') ?>">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control" 
                               id="title" 
                               name="title" 
                               value="<?= old('title', $announcement['title'] ?? '') ?>"
                               required>
                        <?php if (session()->getFlashdata('errors.title')): ?>
                            <div class="text-danger small"><?= session()->getFlashdata('errors.title') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Content <span class="text-danger">*</span></label>
                        <textarea class="form-control" 
                                  id="content" 
                                  name="content" 
                                  rows="8" 
                                  required><?= old('content', $announcement['content'] ?? '') ?></textarea>
                        <?php if (session()->getFlashdata('errors.content')): ?>
                            <div class="text-danger small"><?= session()->getFlashdata('errors.content') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="is_active" 
                                   name="is_active" 
                                   value="1"
                                   <?= old('is_active', $announcement['is_active'] ?? '1') ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_active">
                                Active (visible to users)
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>
                            <?= $announcement ? 'Update Announcement' : 'Create Announcement' ?>
                        </button>
                        <a href="<?= site_url('admin/announcements') ?>" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
