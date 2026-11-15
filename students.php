<?php
require_once 'config.php';
require_login();
$pdo = db();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';
    if ($action === 'save') {
        $id = (int) ($_POST['id'] ?? 0);
        $data = [trim($_POST['name']), trim($_POST['roll_no']), trim($_POST['email']), trim($_POST['phone']), trim($_POST['course']), (float) $_POST['cgpa']];
        if ($id) {
            $q = $pdo->prepare('UPDATE students SET name=?,roll_no=?,email=?,phone=?,course=?,cgpa=? WHERE id=?');
            $q->execute([...$data, $id]);
            flash('Student record updated.');
        } else {
            $q = $pdo->prepare('INSERT INTO students(name,roll_no,email,phone,course,cgpa) VALUES(?,?,?,?,?,?)');
            $q->execute($data);
            flash('Student added successfully.');
        }
    } elseif ($action === 'delete') {
        $q = $pdo->prepare('DELETE FROM students WHERE id=?');
        $q->execute([(int) $_POST['id']]);
        flash('Student deleted.');
    }
    header('Location: students.php');
    exit;
}
$edit = null;
if (isset($_GET['edit'])) {
    $q = $pdo->prepare('SELECT * FROM students WHERE id=?');
    $q->execute([(int) $_GET['edit']]);
    $edit = $q->fetch();
}
$search = trim($_GET['q'] ?? '');
$q = $pdo->prepare('SELECT * FROM students WHERE name LIKE ? OR roll_no LIKE ? OR course LIKE ? ORDER BY id DESC');
$like = "%$search%";
$q->execute([$like, $like, $like]);
$rows = $q->fetchAll();
$page_title = 'Student management';
require 'partials_header.php';
?>
<div class="section-heading">
    <div>
        <h2>Student directory</h2>
        <p>Add and maintain student placement records.</p>
    </div><span class="count-pill"><?= count($rows) ?> records</span>
</div>
<div class="form-card">
    <div class="card-title">
        <h3><?= $edit ? 'Edit student' : 'Add a student' ?></h3><span>All fields are required</span>
    </div>
    <form method="post" class="form-grid"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input
            type="hidden" name="action" value="save"><input type="hidden" name="id" value="<?= e($edit['id'] ?? '') ?>">
        <label>Full name<input name="name" required value="<?= e($edit['name'] ?? '') ?>"
                placeholder="e.g. Aditi Sharma"></label>
        <label>Roll number<input name="roll_no" required value="<?= e($edit['roll_no'] ?? '') ?>"
                placeholder="e.g. BCA2024-001"></label>
        <label>Email address<input type="email" name="email" required value="<?= e($edit['email'] ?? '') ?>"
                placeholder="student@example.com"></label>
        <label>Phone number<input name="phone" required value="<?= e($edit['phone'] ?? '') ?>"
                placeholder="10-digit number"></label>
        <label>Course<input name="course" required value="<?= e($edit['course'] ?? '') ?>"
                placeholder="e.g. BCA"></label>
        <label>CGPA<input type="number" name="cgpa" required min="0" max="10" step="0.01"
                value="<?= e($edit['cgpa'] ?? '') ?>" placeholder="e.g. 8.25"></label>
        <div class="form-actions"><button class="btn primary"
                type="submit"><?= $edit ? 'Update student' : 'Save student' ?></button><?php if ($edit): ?><a
                    class="btn secondary" href="students.php">Cancel</a><?php endif; ?></div>
    </form>
</div>
<div class="section-heading">
    <div>
        <h2>All students</h2>
        <p>Search by name, roll number or course.</p>
    </div>
    <form class="search-form"><input name="q" value="<?= e($search) ?>" placeholder="Search students..."><button
            class="btn secondary">Search</button></form>
</div>
<div class="table-card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Name / Roll no.</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Course</th>
                    <th>CGPA</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $r): ?>
                    <tr>
                        <td><strong><?= e($r['name']) ?></strong><small class="subcell"><?= e($r['roll_no']) ?></small></td>
                        <td><?= e($r['email']) ?></td>
                        <td><?= e($r['phone']) ?></td>
                        <td><?= e($r['course']) ?></td>
                        <td><span class="cgpa"><?= number_format($r['cgpa'], 2) ?></span></td>
                        <td class="actions"><a class="action edit" href="?edit=<?= $r['id'] ?>">Edit</a>
                            <form method="post" onsubmit="return confirm('Delete this student?')"><input type="hidden"
                                    name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action"
                                    value="delete"><input type="hidden" name="id" value="<?= $r['id'] ?>"><button
                                    class="action delete" type="submit">Delete</button></form>
                        </td>
                    </tr><?php endforeach; ?>
                <?php if (!$rows): ?>
                    <tr>
                        <td colspan="6" class="empty">No student records found.</td>
                    </tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require 'partials_footer.php'; ?>