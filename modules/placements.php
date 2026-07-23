<?php
require_once __DIR__ . '/../includes/auth.php';
require_role(['admin','hod','student','tpo']);
require_once __DIR__ . '/../includes/functions.php';

$role = $_SESSION['role'];
$user = current_user();
$canManage = in_array($role,['admin','hod','tpo']);
$where = '';
$whereParams = [];
if ($role === 'student') {
    $sidStmt = $pdo->prepare("SELECT id FROM students WHERE user_id=?");
    $sidStmt->execute([$user['id']]);
    $sid = $sidStmt->fetch()['id'] ?? 0;
    $where = "WHERE p.student_id=?";
    $whereParams = [$sid];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $canManage) {
    verify_csrf();
    if ($_POST['action'] === 'add' || $_POST['action'] === 'edit') {
        $company_id = (int)$_POST['company_id'];
        $drive_title = trim($_POST['drive_title']);
        $drive_date = valid_date($_POST['drive_date'] ?? '');
        $position = trim($_POST['position']);
        $package = (float)$_POST['package'];
        $student_id = (int)$_POST['student_id'];
        $offer_status = enum_val($_POST['offer_status'], ['registered','selected','rejected','pending']);
        if ($_POST['action'] === 'add') {
            if (safe_exec("INSERT INTO placements (company_id,drive_title,drive_date,position,package,student_id,offer_status) VALUES (?,?,?,?,?,?,?)",
                [$company_id,$drive_title,$drive_date,$position,$package,$student_id,$offer_status])) {
                flash('Placement drive added.');
            }
        } else {
            if (safe_exec("UPDATE placements SET company_id=?,drive_title=?,drive_date=?,position=?,package=?,student_id=?,offer_status=? WHERE id=?",
                [$company_id,$drive_title,$drive_date,$position,$package,$student_id,$offer_status,(int)$_POST['id']])) {
                flash('Placement updated.');
            }
        }
    } elseif ($_POST['action'] === 'delete') {
        if (safe_exec("DELETE FROM placements WHERE id=?", [(int)$_POST['id']])) {
            flash('Record deleted.', 'danger');
        }
    }
    redirect('modules/placements.php');
}

$edit = null;
if (isset($_GET['edit'])) {
    $edit = $pdo->prepare("SELECT * FROM placements WHERE id=?");
    $edit->execute([(int)$_GET['edit']]);
    $edit = $edit->fetch();
}
$companies = $pdo->query("SELECT id,name FROM companies ORDER BY name")->fetchAll();
$students = $pdo->query("SELECT id,full_name,roll_no FROM students ORDER BY roll_no")->fetchAll();
$placementsStmt = $pdo->prepare("SELECT p.*, c.name AS company, s.full_name, s.roll_no FROM placements p LEFT JOIN companies c ON p.company_id=c.id LEFT JOIN students s ON p.student_id=s.id $where ORDER BY p.drive_date DESC");
$placementsStmt->execute($whereParams);
$placements = $placementsStmt->fetchAll();
?>
<?php
$active = 'placements';
$page_title = 'AIML AcademicHub';
require_once __DIR__ . '/../includes/layout.php';
?>
<h2>Placements</h2>
<?php show_flash(); ?>

<?php if ($canManage): ?>
<div class="card">
    <h3><?= $edit ? 'Edit Placement' : 'Add Placement Drive' ?></h3>
        <form method="post" class="grid grid-3">
        <input type="hidden" name="action" value="<?= $edit ? 'edit' : 'add' ?>">
        <?= csrf_field() ?>
        <?php if ($edit): ?><input type="hidden" name="id" value="<?= $edit['id'] ?>"><?php endif; ?>
        <div class="form-group"><label>Company</label>
            <select name="company_id" required>
                <?php foreach ($companies as $c): ?><option value="<?= $c['id'] ?>" <?= ($edit['company_id']??0)==$c['id']?'selected':'' ?>><?= e($c['name']) ?></option><?php endforeach; ?>
            </select>
        </div>
        <div class="form-group"><label>Drive Title</label><input name="drive_title" value="<?= e($edit['drive_title'] ?? '') ?>" required></div>
        <div class="form-group"><label>Drive Date</label><input type="date" name="drive_date" value="<?= e($edit['drive_date'] ?? '') ?>"></div>
        <div class="form-group"><label>Position</label><input name="position" value="<?= e($edit['position'] ?? '') ?>"></div>
        <div class="form-group"><label>Package (INR)</label><input type="number" step="0.01" name="package" value="<?= e($edit['package'] ?? '0') ?>"></div>
        <div class="form-group"><label>Student</label>
            <select name="student_id"><option value="0">-- None --</option>
                <?php foreach ($students as $s): ?><option value="<?= $s['id'] ?>" <?= ($edit['student_id']??0)==$s['id']?'selected':'' ?>><?= e($s['roll_no'].' - '.$s['full_name']) ?></option><?php endforeach; ?>
            </select>
        </div>
        <div class="form-group"><label>Offer Status</label>
            <select name="offer_status">
                <option value="registered" <?= ($edit['offer_status']??'')==='registered'?'selected':'' ?>>Registered</option>
                <option value="selected" <?= ($edit['offer_status']??'')==='selected'?'selected':'' ?>>Selected</option>
                <option value="rejected" <?= ($edit['offer_status']??'')==='rejected'?'selected':'' ?>>Rejected</option>
                <option value="pending" <?= ($edit['offer_status']??'')==='pending'?'selected':'' ?>>Pending</option>
            </select>
        </div>
        <div class="form-group" style="grid-column:1/-1"><button class="btn btn-primary" type="submit"><?= $edit ? 'Update' : 'Add Drive' ?></button>
        <?php if ($edit): ?><a href="<?= base_url() ?>/modules/placements.php" class="btn btn-warning">Cancel</a><?php endif; ?></div>
    </form>
</div>
<?php endif; ?>

<div class="card">
    <h3>Placement Records (<?= count($placements) ?>)</h3>
    <table>
        <tr><th>Company</th><th>Drive</th><th>Date</th><th>Position</th><th>Package</th><th>Student</th><th>Status</th><?php if ($canManage): ?><th>Actions</th><?php endif; ?></tr>
        <?php foreach ($placements as $p): ?>
        <tr>
            <td><?= e($p['company']) ?></td>
            <td><?= e($p['drive_title']) ?></td>
            <td><?= $p['drive_date'] ?></td>
            <td><?= e($p['position']) ?></td>
            <td>&#8377;<?= number_format($p['package'],2) ?></td>
            <td><?= e(($p['roll_no']??'').' '.($p['full_name']??'')) ?></td>
            <td><span class="badge badge-<?= $p['offer_status']==='selected'?'success':($p['offer_status']==='rejected'?'danger':'warning') ?>"><?= ucfirst($p['offer_status']) ?></span></td>
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
    </table>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>




