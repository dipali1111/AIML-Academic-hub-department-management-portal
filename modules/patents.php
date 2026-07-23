<?php
require_once __DIR__ . '/../includes/auth.php';
require_role(['admin','hod','faculty','tpo']);
require_once __DIR__ . '/../includes/functions.php';

$role = $_SESSION['role'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    verify_csrf();
    if ($_POST['action'] === 'add' || $_POST['action'] === 'edit') {
        $title = trim($_POST['title']);
        $inventors = trim($_POST['inventors']);
        $application_no = trim($_POST['application_no']);
        $status = enum_val($_POST['status'], ['filed','granted','pending']);
        $filed_date = valid_date($_POST['filed_date'] ?? '');
        $faculty_id = (int)$_POST['faculty_id'];
        if ($_POST['action'] === 'add') {
            if (safe_exec("INSERT INTO patents (title,inventors,application_no,status,filed_date,faculty_id) VALUES (?,?,?,?,?,?)",
                [$title,$inventors,$application_no,$status,$filed_date,$faculty_id])) {
                flash('Patent added.');
            }
        } else {
            if (safe_exec("UPDATE patents SET title=?,inventors=?,application_no=?,status=?,filed_date=?,faculty_id=? WHERE id=?",
                [$title,$inventors,$application_no,$status,$filed_date,$faculty_id,(int)$_POST['id']])) {
                flash('Patent updated.');
            }
        }
    } elseif ($_POST['action'] === 'delete') {
        if (safe_exec("DELETE FROM patents WHERE id=?", [(int)$_POST['id']])) {
            flash('Patent deleted.', 'danger');
        }
    }
    redirect('modules/patents.php');
}

$edit = null;
if (isset($_GET['edit'])) {
    $edit = $pdo->prepare("SELECT * FROM patents WHERE id=?");
    $edit->execute([(int)$_GET['edit']]);
    $edit = $edit->fetch();
}
$faculty = $pdo->query("SELECT id,full_name FROM faculty ORDER BY full_name")->fetchAll();
$patents = $pdo->query("SELECT p.*, f.full_name AS faculty_name FROM patents p LEFT JOIN faculty f ON p.faculty_id=f.id ORDER BY p.filed_date DESC")->fetchAll();
?>
<?php
$active = 'patents';
$page_title = 'AIML AcademicHub';
require_once __DIR__ . '/../includes/layout.php';
?>
<h2>Patents</h2>
<?php show_flash(); ?>

<div class="card">
    <h3><?= $edit ? 'Edit Patent' : 'Add Patent' ?></h3>
        <form method="post" class="grid grid-2">
        <input type="hidden" name="action" value="<?= $edit ? 'edit' : 'add' ?>">
        <?= csrf_field() ?>
        <?php if ($edit): ?><input type="hidden" name="id" value="<?= $edit['id'] ?>"><?php endif; ?>
        <div class="form-group" style="grid-column:1/-1"><label>Title</label><input name="title" value="<?= e($edit['title'] ?? '') ?>" required></div>
        <div class="form-group"><label>Inventors</label><input name="inventors" value="<?= e($edit['inventors'] ?? '') ?>"></div>
        <div class="form-group"><label>Application No</label><input name="application_no" value="<?= e($edit['application_no'] ?? '') ?>"></div>
        <div class="form-group"><label>Filed Date</label><input type="date" name="filed_date" value="<?= e($edit['filed_date'] ?? '') ?>"></div>
        <div class="form-group"><label>Status</label>
            <select name="status">
                <option value="filed" <?= ($edit['status']??'')==='filed'?'selected':'' ?>>Filed</option>
                <option value="granted" <?= ($edit['status']??'')==='granted'?'selected':'' ?>>Granted</option>
                <option value="pending" <?= ($edit['status']??'')==='pending'?'selected':'' ?>>Pending</option>
            </select>
        </div>
        <div class="form-group"><label>Faculty</label>
            <select name="faculty_id">
                <?php foreach ($faculty as $f): ?><option value="<?= $f['id'] ?>" <?= ($edit['faculty_id']??0)==$f['id']?'selected':'' ?>><?= e($f['full_name']) ?></option><?php endforeach; ?>
            </select>
        </div>
        <div class="form-group" style="grid-column:1/-1"><button class="btn btn-primary" type="submit"><?= $edit ? 'Update' : 'Add Patent' ?></button>
        <?php if ($edit): ?><a href="<?= base_url() ?>/modules/patents.php" class="btn btn-warning">Cancel</a><?php endif; ?></div>
    </form>
</div>

<div class="card">
    <h3>All Patents (<?= count($patents) ?>)</h3>
    <table>
        <tr><th>Title</th><th>Inventors</th><th>App No</th><th>Filed</th><th>Status</th><th>Faculty</th><th>Actions</th></tr>
        <?php foreach ($patents as $p): ?>
        <tr>
            <td><?= e($p['title']) ?></td>
            <td><?= e($p['inventors']) ?></td>
            <td><?= e($p['application_no']) ?></td>
            <td><?= $p['filed_date'] ?></td>
            <td><span class="badge badge-<?= $p['status']==='granted'?'success':($p['status']==='pending'?'warning':'info') ?>"><?= ucfirst($p['status']) ?></span></td>
            <td><?= e($p['faculty_name']) ?></td>
            <td>
                <a href="?edit=<?= $p['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                <form method="post" style="display:inline">
    <input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $p['id'] ?>"><?= csrf_field() ?>
    <button type="submit" class="btn btn-sm btn-danger btn-delete" data-title="Delete?" data-msg="Delete?">Delete</button>
</form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>




