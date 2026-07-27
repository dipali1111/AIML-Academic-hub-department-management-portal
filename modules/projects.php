<<<<<<< HEAD
﻿<?php
=======
<?php
>>>>>>> b6df4ad04a8aa0ab8bde74ecb2f0ff952f1c32f4
require_once __DIR__ . '/../includes/auth.php';
require_role(['admin','hod','faculty','student','tpo']);
require_once __DIR__ . '/../includes/functions.php';

$role = $_SESSION['role'];
$user = current_user();
$where = '';
$whereParams = [];
if ($role === 'student') {
    $sidStmt = $pdo->prepare("SELECT id FROM students WHERE user_id=?");
    $sidStmt->execute([$user['id']]);
    $sid = $sidStmt->fetch()['id'] ?? 0;
    $where = "WHERE p.student_id=?";
    $whereParams = [$sid];
}
$canManage = in_array($role,['admin','hod','faculty','tpo']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $canManage) {
    verify_csrf();
    if ($_POST['action'] === 'add' || $_POST['action'] === 'edit') {
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $student_id = (int)$_POST['student_id'];
        $faculty_id = (int)$_POST['faculty_id'];
        $status = enum_val($_POST['status'], ['proposed','ongoing','completed']);
        $domain = trim($_POST['domain']);
        if ($_POST['action'] === 'add') {
            if (safe_exec("INSERT INTO projects (title,description,student_id,faculty_id,status,domain) VALUES (?,?,?,?,?,?)",
                [$title,$description,$student_id,$faculty_id,$status,$domain])) {
                flash('Project added.');
            }
        } else {
            if (safe_exec("UPDATE projects SET title=?,description=?,student_id=?,faculty_id=?,status=?,domain=? WHERE id=?",
                [$title,$description,$student_id,$faculty_id,$status,$domain,(int)$_POST['id']])) {
                flash('Project updated.');
            }
        }
    } elseif ($_POST['action'] === 'delete') {
        if (safe_exec("DELETE FROM projects WHERE id=?", [(int)$_POST['id']])) {
            flash('Project deleted.', 'danger');
        }
    }
    redirect('modules/projects.php');
}

$edit = null;
if (isset($_GET['edit'])) {
    $edit = $pdo->prepare("SELECT * FROM projects WHERE id=?");
    $edit->execute([(int)$_GET['edit']]);
    $edit = $edit->fetch();
}
$students = $pdo->query("SELECT id,full_name,roll_no FROM students ORDER BY roll_no")->fetchAll();
$faculty = $pdo->query("SELECT id,full_name FROM faculty ORDER BY full_name")->fetchAll();
$projectsStmt = $pdo->prepare("SELECT p.*, s.full_name AS student_name, f.full_name AS faculty_name FROM projects p LEFT JOIN students s ON p.student_id=s.id LEFT JOIN faculty f ON p.faculty_id=f.id $where ORDER BY p.created_at DESC");
$projectsStmt->execute($whereParams);
$projects = $projectsStmt->fetchAll();
?>
<?php
$active = 'projects';
$page_title = 'AIML AcademicHub';
require_once __DIR__ . '/../includes/layout.php';
?>
<h2>Projects</h2>
<?php show_flash(); ?>

<?php if ($canManage): ?>
<div class="card">
    <h3><?= $edit ? 'Edit Project' : 'Add Project' ?></h3>
        <form method="post" class="grid grid-2">
        <input type="hidden" name="action" value="<?= $edit ? 'edit' : 'add' ?>">
        <?= csrf_field() ?>
        <?php if ($edit): ?><input type="hidden" name="id" value="<?= $edit['id'] ?>"><?php endif; ?>
        <div class="form-group"><label>Title</label><input name="title" value="<?= e($edit['title'] ?? '') ?>" required></div>
        <div class="form-group"><label>Domain</label><input name="domain" value="<?= e($edit['domain'] ?? '') ?>"></div>
        <div class="form-group"><label>Student</label>
            <select name="student_id"><option value="0">-- None --</option>
                <?php foreach ($students as $s): ?><option value="<?= $s['id'] ?>" <?= ($edit['student_id']??0)==$s['id']?'selected':'' ?>><?= e($s['roll_no'].' - '.$s['full_name']) ?></option><?php endforeach; ?>
            </select>
        </div>
        <div class="form-group"><label>Faculty Mentor</label>
            <select name="faculty_id"><option value="0">-- None --</option>
                <?php foreach ($faculty as $f): ?><option value="<?= $f['id'] ?>" <?= ($edit['faculty_id']??0)==$f['id']?'selected':'' ?>><?= e($f['full_name']) ?></option><?php endforeach; ?>
            </select>
        </div>
        <div class="form-group" style="grid-column:1/-1"><label>Description</label><textarea name="description" rows="3"><?= e($edit['description'] ?? '') ?></textarea></div>
        <div class="form-group"><label>Status</label>
            <select name="status">
                <option value="proposed" <?= ($edit['status']??'')==='proposed'?'selected':'' ?>>Proposed</option>
                <option value="ongoing" <?= ($edit['status']??'')==='ongoing'?'selected':'' ?>>Ongoing</option>
                <option value="completed" <?= ($edit['status']??'')==='completed'?'selected':'' ?>>Completed</option>
            </select>
        </div>
        <div class="form-group" style="grid-column:1/-1"><button class="btn btn-primary" type="submit"><?= $edit ? 'Update' : 'Add Project' ?></button>
        <?php if ($edit): ?><a href="<?= base_url() ?>/modules/projects.php" class="btn btn-warning">Cancel</a><?php endif; ?></div>
    </form>
</div>
<?php endif; ?>

<div class="card">
    <h3>All Projects (<?= count($projects) ?>)</h3>
<<<<<<< HEAD
    <table>
=======
    <div class="table-wrap"><table>
>>>>>>> b6df4ad04a8aa0ab8bde74ecb2f0ff952f1c32f4
        <tr><th>Title</th><th>Domain</th><th>Student</th><th>Mentor</th><th>Status</th><?php if ($canManage): ?><th>Actions</th><?php endif; ?></tr>
        <?php foreach ($projects as $p): ?>
        <tr>
            <td><?= e($p['title']) ?></td>
            <td><?= e($p['domain']) ?></td>
            <td><?= e($p['student_name']) ?></td>
            <td><?= e($p['faculty_name']) ?></td>
            <td><span class="badge badge-<?= $p['status']==='completed'?'success':($p['status']==='ongoing'?'info':'warning') ?>"><?= ucfirst($p['status']) ?></span></td>
            <?php if ($canManage): ?>
            <td>
                <a href="?edit=<?= $p['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                <form method="post" style="display:inline">
    <input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $p['id'] ?>"><?= csrf_field() ?>
    <button type="submit" class="btn btn-sm btn-danger btn-delete" data-title="Delete?" data-msg="Delete?">Delete</button>
</form>
            </td>
            <?php endif; ?>
        </tr>
        <?php endforeach; ?>
<<<<<<< HEAD
    </table>
=======
    </table></div>
>>>>>>> b6df4ad04a8aa0ab8bde74ecb2f0ff952f1c32f4
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>




