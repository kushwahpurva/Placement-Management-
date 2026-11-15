<?php
require_once 'config.php';
require_login();
$pdo = db();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';
    if ($action === 'save') {
        $id = (int) ($_POST['id'] ?? 0);
        $d = [(int) $_POST['company_id'], trim($_POST['job_title']), trim($_POST['skills']), (float) $_POST['min_cgpa'], trim($_POST['package']), $_POST['last_date']];
        if ($id) {
            $s = $pdo->prepare('UPDATE jobs SET company_id=?,job_title=?,skills=?,min_cgpa=?,package=?,last_date=? WHERE id=?');
            $s->execute([...$d, $id]);
            flash('Job opening updated.');
        } else {
            $s = $pdo->prepare('INSERT INTO jobs(company_id,job_title,skills,min_cgpa,package,last_date) VALUES(?,?,?,?,?,?)');
            $s->execute($d);
            flash('Job opening added.');
        }
    }
    if ($action === 'delete') {
        $s = $pdo->prepare('DELETE FROM jobs WHERE id=?');
        $s->execute([(int) $_POST['id']]);
        flash('Job opening deleted.');
    }
    header('Location: jobs.php');
    exit;
}
$edit = null;
if (isset($_GET['edit'])) {
    $s = $pdo->prepare('SELECT * FROM jobs WHERE id=?');
    $s->execute([(int) $_GET['edit']]);
    $edit = $s->fetch();
}
$companies = $pdo->query('SELECT id,company_name FROM companies ORDER BY company_name')->fetchAll();
$rows = $pdo->query('SELECT j.*,c.company_name FROM jobs j JOIN companies c ON c.id=j.company_id ORDER BY j.id DESC')->fetchAll();
$page_title = 'Job openings';
require 'partials_header.php'; ?>
<div class="section-heading">
    <div>
        <h2>Job opportunities</h2>
        <p>Publish and manage campus recruitment openings.</p>
    </div><span class="count-pill"><?= count($rows) ?> openings</span>
</div>
<div class="form-card">
    <div class="card-title">
        <h3><?= $edit ? 'Edit job opening' : 'Add a job opening' ?></h3><span>Enter role and eligibility details</span>
    </div>
    <?php if (!$companies): ?>
        <div class="alert warning">Add a company before creating a job opening.</div><?php endif; ?>
    <form method="post" class="form-grid"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input
            type="hidden" name="action" value="save"><input type="hidden" name="id" value="<?= e($edit['id'] ?? '') ?>">
        <label>Company<select name="company_id" required>
                <option value="">Select company</option><?php foreach ($companies as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= ((int) ($edit['company_id'] ?? 0) === (int) $c['id']) ? 'selected' : '' ?>>
                        <?= e($c['company_name']) ?></option><?php endforeach; ?>
            </select></label>
        <label>Job title<input name="job_title" required value="<?= e($edit['job_title'] ?? '') ?>"
                placeholder="e.g. Junior Web Developer"></label>
        <label>Required skills<input name="skills" required value="<?= e($edit['skills'] ?? '') ?>"
                placeholder="HTML, CSS, JavaScript"></label>
        <label>Minimum CGPA<input type="number" min="0" max="10" step=".01" name="min_cgpa" required
                value="<?= e($edit['min_cgpa'] ?? '') ?>" placeholder="e.g. 6.5"></label>
        <label>Package (LPA)<input name="package" required value="<?= e($edit['package'] ?? '') ?>"
                placeholder="e.g. 3.5 LPA"></label>
        <label>Last date to apply<input type="date" name="last_date" required
                value="<?= e($edit['last_date'] ?? '') ?>"></label>
        <div class="form-actions"><button class="btn primary"
                <?= $companies ? '' : 'disabled' ?>><?= $edit ? 'Update opening' : 'Publish opening' ?></button><?php if ($edit): ?><a
                    class="btn secondary" href="jobs.php">Cancel</a><?php endif; ?></div>
    </form>
</div>
<div class="section-heading">
    <div>
        <h2>Published openings</h2>
        <p>All job roles in the system.</p>
    </div>
</div>
<div class="table-card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Job role</th>
                    <th>Company</th>
                    <th>Skills</th>
                    <th>Min. CGPA</th>
                    <th>Package</th>
                    <th>Last date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $r): ?>
                    <tr>
                        <td><strong><?= e($r['job_title']) ?></strong></td>
                        <td><?= e($r['company_name']) ?></td>
                        <td><?= e($r['skills']) ?></td>
                        <td><?= number_format($r['min_cgpa'], 2) ?></td>
                        <td><span class="cgpa"><?= e($r['package']) ?></span></td>
                        <td><?= e(date('d M Y', strtotime($r['last_date']))) ?></td>
                        <td class="actions"><a class="action edit" href="?edit=<?= $r['id'] ?>">Edit</a>
                            <form method="post" onsubmit="return confirm('Delete this job opening?')"><input type="hidden"
                                    name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action"
                                    value="delete"><input type="hidden" name="id" value="<?= $r['id'] ?>"><button
                                    class="action delete">Delete</button></form>
                        </td>
                    </tr><?php endforeach; ?>
                <?php if (!$rows): ?>
                    <tr>
                        <td colspan="7" class="empty">No job openings found.</td>
                    </tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div><?php require 'partials_footer.php'; ?>