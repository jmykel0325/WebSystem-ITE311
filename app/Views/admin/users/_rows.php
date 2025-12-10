<?php if (!empty($users)): ?>
  <?php $row = 1; ?>
  <?php foreach ($users as $u): ?>
    <tr>
      <td><?= $row++ ?></td>
      <td><?= esc($u['name'] ?? ($u['first_name'] ?? '')) ?></td>
      <td><?= esc($u['email']) ?></td>
      <td>
        <?php if (($u['role'] ?? '') === 'deleted'): ?>
          <span class="badge bg-secondary text-uppercase">DELETED</span>
        <?php else: ?>
          <span class="badge bg-primary text-uppercase"><?= esc($u['role'] ?? 'user') ?></span>
        <?php endif; ?>
      </td>
      <td><?= !empty($u['created_at']) ? date('M d, Y', strtotime($u['created_at'])) : '-' ?></td>
      <td>
        <?php if ((int)session('user_id') !== (int)$u['id'] && (string)session('email') !== (string)$u['email']): ?>
          <?php if (($u['role'] ?? '') !== 'deleted'): ?>
            <a href="<?= site_url('admin/users/edit/' . (int)$u['id']) ?>" class="btn btn-sm btn-outline-primary me-1">
              <i class="bi bi-pencil-square"></i> Edit
            </a>
            <a href="<?= site_url('admin/users/delete/' . (int)$u['id']) ?>" class="btn btn-sm btn-outline-danger"
               onclick="return confirm('Are you sure you want to mark this account as deleted?');">
              <i class="bi bi-trash"></i> Delete
            </a>
          <?php else: ?>
            <a href="<?= site_url('admin/users/restore/' . (int)$u['id']) ?>" class="btn btn-sm btn-outline-success"
               onclick="return confirm('Restore this deleted account?');">
              <i class="bi bi-arrow-counterclockwise"></i> Restore
            </a>
          <?php endif; ?>
        <?php else: ?>
          <span class="badge bg-info text-dark">Current admin (in use)</span>
        <?php endif; ?>
      </td>
    </tr>
  <?php endforeach; ?>
<?php else: ?>
  <tr><td colspan="6" class="text-center text-muted">No users found.</td></tr>
<?php endif; ?>
