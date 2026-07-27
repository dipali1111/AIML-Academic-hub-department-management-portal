<<<<<<< HEAD
﻿<?php
=======
<?php
>>>>>>> b6df4ad04a8aa0ab8bde74ecb2f0ff952f1c32f4
require_once __DIR__ . '/../includes/auth.php';
require_role(['admin','hod','faculty','tpo']);
require_once __DIR__ . '/../includes/functions.php';

$role = $_SESSION['role'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    verify_csrf();
    if ($_POST['action'] === 'add' || $_POST['action'] === 'edit') {
        $title = trim($_POST['title']);
        $area = trim($_POST['area']);
        $faculty_id = (int)$_POST['faculty_id'];
        $funding_agency = trim($_POST['funding_agency']);
        $amount = (float)$_POST['amount'];
        $status = enum_val($_POST['status'], ['proposed','ongoing','completed']);
        $year = (int)$_POST['year'];
        if ($_POST['action'] === 'add') {
            if (safe_exec("INSERT INTO research (title,area,faculty_id,funding_agency,amount,status,year) VALUES (?,?,?,?,?,?,?)",
                [$title,$area,$faculty_id,$funding_agency,$amount,$status,$year])) {
                flash('Research added.');
            }
        } else {
            if (safe_exec("UPDATE research SET title=?,area=?,faculty_id=?,funding_agency=?,amount=?,status=?,year=? WHERE id=?",
                [$title,$area,$faculty_id,$funding_agency,$amount,$status,$year,(int)$_POST['id']])) {
                flash('Research updated.');
            }
        }
    } elseif ($_POST['action'] === 'delete') {
        if (safe_exec("DELETE FROM research WHERE id=?", [(int)$_POST['id']])) {
            flash('Research deleted.', 'danger');
        }
    }
    redirect('modules/research.php');
}

$edit = null;
if (isset($_GET['edit'])) {
    $edit = $pdo->prepare("SELECT * FROM research WHERE id=?");
    $edit->execute([(int)$_GET['edit']]);
    $edit = $edit->fetch();
}
$faculty = $pdo->query("SELECT id,full_name FROM faculty ORDER BY full_name")->fetchAll();
$research = $pdo->query("SELECT r.*, f.full_name AS faculty_name FROM research r LEFT JOIN faculty f ON r.faculty_id=f.id ORDER BY r.year DESC")->fetchAll();
?>
<?php
$active = 'research';
$page_title = 'AIML AcademicHub';
require_once __DIR__ . '/../includes/layout.php';
?>
<h2>Research</h2>
<?php show_flash(); ?>

<div class="card">
    <h3><?= $edit ? 'Edit Research' : 'Add Research' ?></h3>
        <form method="post" class="grid grid-3">
        <input type="hidden" name="action" value="<?= $edit ? 'edit' : 'add' ?>">
        <?= csrf_field() ?>
        <?php if ($edit): ?><input type="hidden" name="id" value="<?= $edit['id'] ?>"><?php endif; ?>
        <div class="form-group" style="grid-column:1/-1"><label>Title</label><input name="title" value="<?= e($edit['title'] ?? '') ?>" required></div>
        <div class="form-group"><label>Area</label><input name="area" value="<?= e($edit['area'] ?? '') ?>"></div>
        <div class="form-group"><label>Faculty</label>
            <select name="faculty_id">
                <?php foreach ($faculty as $f): ?><option value="<?= $f['id'] ?>" <?= ($edit['faculty_id']??0)==$f['id']?'selected':'' ?>><?= e($f['full_name']) ?></option><?php endforeach; ?>
            </select>
        </div>
        <div class="form-group"><label>Year</label><input type="number" name="year" value="<?= e($edit['year'] ?? date('Y')) ?>"></div>
        <div class="form-group"><label>Funding Agency</label><input name="funding_agency" value="<?= e($edit['funding_agency'] ?? '') ?>"></div>
        <div class="form-group"><label>Amount (INR)</label><input type="number" step="0.01" name="amount" value="<?= e($edit['amount'] ?? '0') ?>"></div>
        <div class="form-group"><label>Status</label>
            <select name="status">
                <option value="proposed" <?= ($edit['status']??'')==='proposed'?'selected':'' ?>>Proposed</option>
                <option value="ongoing" <?= ($edit['status']??'')==='ongoing'?'selected':'' ?>>Ongoing</option>
                <option value="completed" <?= ($edit['status']??'')==='completed'?'selected':'' ?>>Completed</option>
            </select>
        </div>
        <div class="form-group" style="grid-column:1/-1"><button class="btn btn-primary" type="submit"><?= $edit ? 'Update' : 'Add Research' ?></button>
        <?php if ($edit): ?><a href="<?= base_url() ?>/modules/research.php" class="btn btn-warning">Cancel</a><?php endif; ?></div>
    </form>
</div>

<div class="card">
    <h3>All Research (<?= count($research) ?>)</h3>
<<<<<<< HEAD
    <table>
=======
    <div class="table-wrap"><table>
>>>>>>> b6df4ad04a8aa0ab8bde74ecb2f0ff952f1c32f4
        <tr><th>Title</th><th>Area</th><th>Faculty</th><th>Agency</th><th>Amount</th><th>Year</th><th>Status</th><th>Actions</th></tr>
        <?php foreach ($research as $r): ?>
        <tr>
            <td><?= e($r['title']) ?></td>
            <td><?= e($r['area']) ?></td>
            <td><?= e($r['faculty_name']) ?></td>
            <td><?= e($r['funding_agency']) ?></td>
            <td>&#8377;<?= number_format($r['amount'],2) ?></td>
            <td><?= $r['year'] ?></td>
            <td><span class="badge badge-<?= $r['status']==='completed'?'success':($r['status']==='ongoing'?'info':'warning') ?>"><?= ucfirst($r['status']) ?></span></td>
            <td>
                <a href="?edit=<?= $r['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                <form method="post" style="display:inline">
    <input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $r['id'] ?>"><?= csrf_field() ?>
    <button type="submit" class="btn btn-sm btn-danger btn-delete" data-title="Delete?" data-msg="Delete?">Delete</button>
</form>
            </td>
        </tr>
        <?php endforeach; ?>
<<<<<<< HEAD
    </table>
=======
    </table></div>
>>>>>>> b6df4ad04a8aa0ab8bde74ecb2f0ff952f1c32f4
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>




