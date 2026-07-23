<?php
require_once __DIR__ . '/../includes/auth.php';
require_role(['admin','hod','faculty','student','tpo']);
require_once __DIR__ . '/../includes/functions.php';

$role = $_SESSION['role'];
$user = current_user();
$where = '';
$whereParams = [];
$canManage = in_array($role,['admin','hod','faculty','tpo']);
if ($role === 'student') {
    $sidStmt = $pdo->prepare("SELECT id FROM students WHERE user_id=?");
    $sidStmt->execute([$user['id']]);
    $sid = $sidStmt->fetch()['id'] ?? 0;
    $where = "WHERE i.student_id=?";
    $whereParams = [$sid];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $canManage) {
    verify_csrf();
    if ($_POST['action'] === 'add' || $_POST['action'] === 'edit') {
        $student_id = (int)$_POST['student_id'];
        $company_name = trim($_POST['company_name']);
        $role_ = trim($_POST['role']);
        $start_date = valid_date($_POST['start_date'] ?? '');
        $end_date = valid_date($_POST['end_date'] ?? '');
        $stipend = (float)$_POST['stipend'];
        $status = enum_val($_POST['status'], ['applied','ongoing','completed','rejected']);
        if ($_POST['action'] === 'add') {
            if (safe_exec("INSERT INTO internships (student_id,company_name,role,start_date,end_date,stipend,status) VALUES (?,?,?,?,?,?,?)",
                [$student_id,$company_name,$role_,$start_date,$end_date,$stipend,$status])) {
                flash('Internship added.');
            }
        } else {
            if (safe_exec("UPDATE internships SET student_id=?,company_name=?,role=?,start_date=?,end_date=?,stipend=?,status=? WHERE id=?",
                [$student_id,$company_name,$role_,$start_date,$end_date,$stipend,$status,(int)$_POST['id']])) {
                flash('Internship updated.');
            }
        }
    } elseif ($_POST['action'] === 'delete') {
        if (safe_exec("DELETE FROM internships WHERE id=?", [(int)$_POST['id']])) {
            flash('Internship deleted.', 'danger');
        }
    }
    redirect('modules/internships.php');
}

$edit = null;
if (isset($_GET['edit'])) {
    $edit = $pdo->prepare("SELECT * FROM internships WHERE id=?");
    $edit->execute([(int)$_GET['edit']]);
    $edit = $edit->fetch();
}
$students = $pdo->query("SELECT id,full_name,roll_no FROM students ORDER BY roll_no")->fetchAll();
$internshipsStmt = $pdo->prepare("SELECT i.*, s.full_name, s.roll_no FROM internships i LEFT JOIN students s ON i.student_id=s.id $where ORDER BY i.start_date DESC");
$internshipsStmt->execute($whereParams);
$internships = $internshipsStmt->fetchAll();
?>
<?php
$active = 'internships';
$page_title = 'AIML AcademicHub';
require_once __DIR__ . '/../includes/layout.php';
?>
<h2>Internships</h2>
<?php show_flash(); ?>

<?php if ($canManage): ?>
<div class="card">
    <h3><?= $edit ? 'Edit Internship' : 'Add Internship' ?></h3>
        <form method="post" class="grid grid-3">
        <input type="hidden" name="action" value="<?= $edit ? 'edit' : 'add' ?>">
        <?= csrf_field() ?>
        <?php if ($edit): ?><input type="hidden" name="id" value="<?= $edit['id'] ?>"><?php endif; ?>
        <div class="form-group"><label>Student</label>
            <select name="student_id" required>
                <?php foreach ($students as $s): ?><option value="<?= $s['id'] ?>" <?= ($edit['student_id']??0)==$s['id']?'selected':'' ?>><?= e($s['roll_no'].' - '.$s['full_name']) ?></option><?php endforeach; ?>
            </select>
        </div>
        <div class="form-group"><label>Company</label><input name="company_name" value="<?= e($edit['company_name'] ?? '') ?>" required></div>
        <div class="form-group"><label>Role</label><input name="role" value="<?= e($edit['role'] ?? '') ?>"></div>
        <div class="form-group"><label>Start Date</label><input type="date" name="start_date" value="<?= e($edit['start_date'] ?? '') ?>"></div>
        <div class="form-group"><label>End Date</label><input type="date" name="end_date" value="<?= e($edit['end_date'] ?? '') ?>"></div>
        <div class="form-group"><label>Stipend (INR)</label><input type="number" step="0.01" name="stipend" value="<?= e($edit['stipend'] ?? '0') ?>"></div>
        <div class="form-group"><label>Status</label>
            <select name="status">
                <option value="applied" <?= ($edit['status']??'')==='applied'?'selected':'' ?>>Applied</option>
                <option value="ongoing" <?= ($edit['status']??'')==='ongoing'?'selected':'' ?>>Ongoing</option>
                <option value="completed" <?= ($edit['status']??'')==='completed'?'selected':'' ?>>Completed</option>
                <option value="rejected" <?= ($edit['status']??'')==='rejected'?'selected':'' ?>>Rejected</option>
            </select>
        </div>
        <div class="form-group" style="grid-column:1/-1"><button class="btn btn-primary" type="submit"><?= $edit ? 'Update' : 'Add Internship' ?></button>
        <?php if ($edit): ?><a href="<?= base_url() ?>/modules/internships.php" class="btn btn-warning">Cancel</a><?php endif; ?></div>
    </form>
</div>
<?php endif; ?>

<div class="card">
    <h3>All Internships (<?= count($internships) ?>)</h3>
    <table>
        <tr><th>Student</th><th>Company</th><th>Role</th><th>Period</th><th>Stipend</th><th>Status</th><?php if ($canManage): ?><th>Actions</th><?php endif; ?></tr>
        <?php foreach ($internships as $i): ?>
        <tr>
            <td><?= e($i['roll_no'].' - '.$i['full_name']) ?></td>
            <td><?= e($i['company_name']) ?></td>
            <td><?= e($i['role']) ?></td>
            <td><?= $i['start_date'] ?> to <?= $i['end_date'] ?></td>
            <td>&#8377;<?= number_format($i['stipend'],2) ?></td>
            <td><span class="badge badge-<?= $i['status']==='completed'?'success':($i['status']==='ongoing'?'info':($i['status']==='rejected'?'danger':'warning')) ?>"><?= ucfirst($i['status']) ?></span></td>
            <?php if ($canManage): ?>
            <td>
                <a href="?edit=<?= $i['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                <form method="post" style="display:inline">
    <input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $i['id'] ?>"><?= csrf_field() ?>
    <button type="submit" class="btn btn-sm btn-danger btn-delete" data-title="Delete?" data-msg="Delete?">Delete</button>
</form>
            </td>
            <?php endif; ?>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>




