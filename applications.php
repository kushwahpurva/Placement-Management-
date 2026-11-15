<?php
require_once 'config.php';
require_login();
$pdo = db();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';
    if ($action === 'status') {
        $s = $pdo->prepare('UPDATE applications SET status=? WHERE id=?');
        $s->execute([$_POST['status'], (int) $_POST['id']]);
        flash('Application status updated.');
    } elseif ($action === 'delete') {
        $s = $pdo->prepare('DELETE FROM applications WHERE id=?');
        $s->execute([(int) $_POST['id']]);
        flash('Application deleted.');
    }
    header('Location: applications.php');
    exit;
}
$rows = $pdo->query("SELECT a.*,s.name AS student,s.roll_no,j.job_title,c.company_name FROM applications a JOIN students s ON s.id=a.student_id JOIN jobs j ON j.id=a.job_id JOIN companies c ON c.id=j.company_id ORDER BY a.id DESC")->fetchAll();
$page_title = 'Placement applications';
require 'partials_header.php'; ?>
<div class="section-heading">
    <div>
        <h2>Application tracking</h2>
        <p>Review applications and update each candidate's progress.</p>
    </div><span class="count-pill"><?= count($rows) ?> applications</span>
</div>
<div class="table-card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Job role</th>
                    <th>Company</th>
                    <th>Applied on</th>
                    <th>Current status</th>
                    <th>Update status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $r): ?>
                    <tr>
                        <td><strong><?= e($r['student']) ?></strong><small class="subcell"><?= e($r['roll_no']) ?></small></td>
                        <td><?= e($r['job_title']) ?></td>
                        <td><?= e($r['company_name']) ?></td>
                        <td><?= e(date('d M Y', strtotime($r['applied_on']))) ?></td>
                        <td><span class="badge <?= strtolower($r['status']) ?>"><?= e($r['status']) ?></span></td>
                        <td>
                            <div class="inline-actions">
                                <form method="post" class="status-form"><input type="hidden" name="csrf_token"
                                        value="<?= e(csrf_token()) ?>"><input type="hidden" name="action"
                                        value="status"><input type="hidden" name="id" value="<?= $r['id'] ?>"><select
                                        name="status">
                                        <option <?= $r['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                                        <option <?= $r['status'] === 'Shortlisted' ? 'selected' : '' ?>>Shortlisted</option>
                                        <option <?= $r['status'] === 'Selected' ? 'selected' : '' ?>>Selected</option>
                                        <option <?= $r['status'] === 'Rejected' ? 'selected' : '' ?>>Rejected</option>
                                    </select><button class="action edit">Save</button></form>
                                <form method="post" onsubmit="return confirm('Delete this application?')"><input
                                        type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden"
                                        name="action" value="delete"><input type="hidden" name="id"
                                        value="<?= $r['id'] ?>"><button class="action delete">Delete</button></form>
                            </div>
                        </td>
                    </tr><?php endforeach; ?>
                <?php if (!$rows): ?>
                    <tr>
                        <td colspan="6" class="empty">No applications found.</td>
                    </tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div><?php require 'partials_footer.php'; ?>