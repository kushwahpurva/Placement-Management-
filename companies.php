<?php
require_once 'config.php';
require_login();
$pdo = db();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';
    if ($action === 'save') {
        $id = (int) ($_POST['id'] ?? 0);
        $d = [trim($_POST['company_name']), trim($_POST['hr_name']), trim($_POST['hr_email']), trim($_POST['location']), trim($_POST['website'])];
        if ($id) {
            $s = $pdo->prepare('UPDATE companies SET company_name=?,hr_name=?,hr_email=?,location=?,website=? WHERE id=?');
            $s->execute([...$d, $id]);
            flash('Company updated.');
        } else {
            $s = $pdo->prepare('INSERT INTO companies(company_name,hr_name,hr_email,location,website) VALUES(?,?,?,?,?)');
            $s->execute($d);
            flash('Company added.');
        }
    }
    if ($action === 'delete') {
        $s = $pdo->prepare('DELETE FROM companies WHERE id=?');
        $s->execute([(int) $_POST['id']]);
        flash('Company deleted.');
    }
    header('Location: companies.php');
    exit;
}
$edit = null;
if (isset($_GET['edit'])) {
    $s = $pdo->prepare('SELECT * FROM companies WHERE id=?');
    $s->execute([(int) $_GET['edit']]);
    $edit = $s->fetch();
}
$rows = $pdo->query('SELECT * FROM companies ORDER BY id DESC')->fetchAll();
$page_title = 'Company management';
require 'partials_header.php'; ?>
<div class="section-heading">
    <div>
        <h2>Company partners</h2>
        <p>Maintain employer and HR contact information.</p>
    </div><span class="count-pill"><?= count($rows) ?> companies</span>
</div>
<div class="form-card">
    <div class="card-title">
        <h3><?= $edit ? 'Edit company' : 'Add a company' ?></h3><span>Company information</span>
    </div>
    <form method="post" class="form-grid"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input
            type="hidden" name="action" value="save"><input type="hidden" name="id" value="<?= e($edit['id'] ?? '') ?>">
        <label>Company name<input name="company_name" required value="<?= e($edit['company_name'] ?? '') ?>"
                placeholder="e.g. TechNova Solutions"></label><label>HR contact name<input name="hr_name"
                value="<?= e($edit['hr_name'] ?? '') ?>" placeholder="HR representative"></label><label>HR email<input
                type="email" name="hr_email" value="<?= e($edit['hr_email'] ?? '') ?>"
                placeholder="hr@company.com"></label><label>Location<input name="location" required
                value="<?= e($edit['location'] ?? '') ?>" placeholder="City"></label><label class="span-two">Website<input
                name="website" value="<?= e($edit['website'] ?? '') ?>" placeholder="https://company.com"></label>
        <div class="form-actions"><button
                class="btn primary"><?= $edit ? 'Update company' : 'Save company' ?></button><?php if ($edit): ?><a
                    class="btn secondary" href="companies.php">Cancel</a><?php endif; ?></div>
    </form>
</div>
<div class="section-heading">
    <div>
        <h2>Company directory</h2>
        <p>Registered placement partners.</p>
    </div>
</div>
<div class="table-card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Company</th>
                    <th>HR contact</th>
                    <th>Email</th>
                    <th>Location</th>
                    <th>Website</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $r): ?>
                    <tr>
                        <td><strong><?= e($r['company_name']) ?></strong></td>
                        <td><?= e($r['hr_name']) ?></td>
                        <td><?= e($r['hr_email']) ?></td>
                        <td><?= e($r['location']) ?></td>
                        <td><?php if ($r['website']): ?><a class="table-link" href="<?= e($r['website']) ?>" target="_blank"
                                    rel="noopener">Visit ↗</a><?php else: ?>—<?php endif; ?></td>
                        <td class="actions"><a class="action edit" href="?edit=<?= $r['id'] ?>">Edit</a>
                            <form method="post"
                                onsubmit="return confirm('Delete this company? Related jobs may also be deleted.')"><input
                                    type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden"
                                    name="action" value="delete"><input type="hidden" name="id"
                                    value="<?= $r['id'] ?>"><button class="action delete">Delete</button></form>
                        </td>
                    </tr><?php endforeach; ?>
                <?php if (!$rows): ?>
                    <tr>
                        <td colspan="6" class="empty">No companies found.</td>
                    </tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div><?php require 'partials_footer.php'; ?>