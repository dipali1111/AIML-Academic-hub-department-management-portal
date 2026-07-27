<?php
require_once __DIR__ . '/../includes/auth.php';
require_role(['admin','hod','faculty','student','tpo']);
require_once __DIR__ . '/../includes/functions.php';

$role = $_SESSION['role'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && in_array($role,['admin','hod','faculty','tpo'])) {
    verify_csrf();
    if ($_POST['action'] === 'add' || $_POST['action'] === 'edit') {
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);
        $priority = enum_val($_POST['priority'], ['normal','important','urgent']);
        if ($_POST['action'] === 'add') {
            if (safe_exec("INSERT INTO notices (title,content,posted_by,priority) VALUES (?,?,?,?)",
                [$title,$content,$_SESSION['user_id'],$priority])) {
                flash('Notice posted.');
            }
        } else {
            if (safe_exec("UPDATE notices SET title=?,content=?,priority=? WHERE id=?",
                [$title,$content,$priority,(int)$_POST['id']])) {
                flash('Notice updated.');
            }
        }
    } elseif ($_POST['action'] === 'delete') {
        if (safe_exec("DELETE FROM notices WHERE id=?", [(int)$_POST['id']])) {
            flash('Notice deleted.', 'danger');
        }
    }
    redirect('modules/notices.php');
}

$edit = null;
if (isset($_GET['edit'])) {
    $edit = $pdo->prepare("SELECT * FROM notices WHERE id=?");
    $edit->execute([(int)$_GET['edit']]);
    $edit = $edit->fetch();
}
$notices = $pdo->query("SELECT n.*, u.full_name AS posted_by_name FROM notices n JOIN users u ON n.posted_by=u.id ORDER BY n.created_at DESC")->fetchAll();
$canPost = in_array($role,['admin','hod','faculty','tpo']);
?>
<?php
$active = 'notices';
$page_title = 'AIML AcademicHub';
require_once __DIR__ . '/../includes/layout.php';
?>
<h2>Notices</h2>
<?php show_flash(); ?>

<?php if ($canPost): ?>
<div class="card">
    <h3><?= $edit ? 'Edit Notice' : 'Post Notice' ?></h3>
        <form method="post">
        <input type="hidden" name="action" value="<?= $edit ? 'edit' : 'add' ?>">
        <?= csrf_field() ?>
        <?php if ($edit): ?><input type="hidden" name="id" value="<?= $edit['id'] ?>"><?php endif; ?>
        <div class="form-group"><label>Title</label><input name="title" value="<?= e($edit['title'] ?? '') ?>" required></div>
        <div class="form-group"><label>Content</label><textarea name="content" rows="3" required><?= e($edit['content'] ?? '') ?></textarea></div>
        <div class="form-group"><label>Priority</label>
            <select name="priority">
                <option value="normal" <?= ($edit['priority']??'')==='normal'?'selected':'' ?>>Normal</option>
                <option value="important" <?= ($edit['priority']??'')==='important'?'selected':'' ?>>Important</option>
                <option value="urgent" <?= ($edit['priority']??'')==='urgent'?'selected':'' ?>>Urgent</option>
            </select>
        </div>
        <button class="btn btn-primary" type="submit"><?= $edit ? 'Update' : 'Post Notice' ?></button>
        <?php if ($edit): ?><a href="<?= base_url() ?>/modules/notices.php" class="btn btn-warning">Cancel</a><?php endif; ?>
    </form>
</div>
<?php endif; ?>

<div class="card">
    <h3>All Notices (<?= count($notices) ?>)</h3>
    <table>
        <tr><th>Title</th><th>Content</th><th>Priority</th><th>Posted By</th><th>Date</th><?php if ($canPost): ?><th>Actions</th><?php endif; ?></tr>
        <?php foreach ($notices as $n): ?>
        <tr>
            <td><?= e($n['title']) ?></td>
            <td><?= e(mb_strimwidth($n['content'],0,80,'...')) ?></td>
            <td><span class="badge badge-<?= $n['priority']==='urgent'?'danger':($n['priority']==='important'?'warning':'info') ?>"><?= ucfirst($n['priority']) ?></span></td>
            <td><?= e($n['posted_by_name']) ?></td>
            <td><?= date('d M Y', strtotime($n['created_at'])) ?></td>
            <?php if ($canPost): ?>
            <td>
                <a href="?edit=<?= $n['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                <form method="post" style="display:inline">
    <input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $n['id'] ?>"><?= csrf_field() ?>
    <button type="submit" class="btn btn-sm btn-danger btn-delete" data-title="Delete?" data-msg="Delete?">Delete</button>
</form>
            </td>
            <?php endif; ?>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>




