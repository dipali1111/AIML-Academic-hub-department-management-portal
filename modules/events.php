<?php
require_once __DIR__ . '/../includes/auth.php';
require_role(['admin','hod','faculty','student','tpo']);
require_once __DIR__ . '/../includes/functions.php';

$role = $_SESSION['role'];
$user = current_user();
$canManage = in_array($role,['admin','hod','faculty','tpo']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $canManage) {
    verify_csrf();
    if ($_POST['action'] === 'add' || $_POST['action'] === 'edit') {
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $event_date = valid_date($_POST['event_date'] ?? '');
        $venue = trim($_POST['venue']);
        if ($_POST['action'] === 'add') {
            if (safe_exec("INSERT INTO events (title,description,event_date,venue,created_by) VALUES (?,?,?,?,?)",
                [$title,$description,$event_date,$venue,$_SESSION['user_id']])) {
                flash('Event created.');
            }
        } else {
            if (safe_exec("UPDATE events SET title=?,description=?,event_date=?,venue=? WHERE id=?",
                [$title,$description,$event_date,$venue,(int)$_POST['id']])) {
                flash('Event updated.');
            }
        }
    } elseif ($_POST['action'] === 'delete') {
        if (safe_exec("DELETE FROM events WHERE id=?", [(int)$_POST['id']])) {
            flash('Event deleted.', 'danger');
        }
    }
    redirect('modules/events.php');
}

$edit = null;
if (isset($_GET['edit'])) {
    $edit = $pdo->prepare("SELECT * FROM events WHERE id=?");
    $edit->execute([(int)$_GET['edit']]);
    $edit = $edit->fetch();
}
$events = $pdo->query("SELECT * FROM events ORDER BY event_date ASC")->fetchAll();
?>
<?php
$active = 'events';
$page_title = 'AIML AcademicHub';
require_once __DIR__ . '/../includes/layout.php';
?>
<h2>Events</h2>
<?php show_flash(); ?>

<?php if ($canManage): ?>
<div class="card">
    <h3><?= $edit ? 'Edit Event' : 'Create Event' ?></h3>
        <form method="post" class="grid grid-2">
        <input type="hidden" name="action" value="<?= $edit ? 'edit' : 'add' ?>">
        <?= csrf_field() ?>
        <?php if ($edit): ?><input type="hidden" name="id" value="<?= $edit['id'] ?>"><?php endif; ?>
        <div class="form-group"><label>Title</label><input name="title" value="<?= e($edit['title'] ?? '') ?>" required></div>
        <div class="form-group"><label>Venue</label><input name="venue" value="<?= e($edit['venue'] ?? '') ?>"></div>
        <div class="form-group" style="grid-column:1/-1"><label>Description</label><textarea name="description" rows="3"><?= e($edit['description'] ?? '') ?></textarea></div>
        <div class="form-group"><label>Event Date</label><input type="date" name="event_date" value="<?= e($edit['event_date'] ?? '') ?>" required></div>
        <div class="form-group" style="grid-column:1/-1"><button class="btn btn-primary" type="submit"><?= $edit ? 'Update' : 'Create Event' ?></button>
        <?php if ($edit): ?><a href="<?= base_url() ?>/modules/events.php" class="btn btn-warning">Cancel</a><?php endif; ?></div>
    </form>
</div>
<?php endif; ?>

<div class="card">
    <h3>All Events (<?= count($events) ?>)</h3>
    <table>
        <tr><th>Title</th><th>Description</th><th>Date</th><th>Venue</th><?php if ($canManage): ?><th>Actions</th><?php endif; ?></tr>
        <?php foreach ($events as $ev): ?>
        <tr>
            <td><?= e($ev['title']) ?></td>
            <td><?= e(mb_strimwidth($ev['description'],0,80,'...')) ?></td>
            <td><?= $ev['event_date'] ?></td>
            <td><?= e($ev['venue']) ?></td>
            <?php if ($canManage): ?>
            <td>
                <a href="?edit=<?= $ev['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                <form method="post" style="display:inline">
    <input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $ev['id'] ?>"><?= csrf_field() ?>
    <button type="submit" class="btn btn-sm btn-danger btn-delete" data-title="Delete?" data-msg="Delete?">Delete</button>
</form>
            </td>
            <?php endif; ?>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>




