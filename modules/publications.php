<?php
require_once __DIR__ . '/../includes/auth.php';
require_role(['admin','hod','faculty','tpo']);
require_once __DIR__ . '/../includes/functions.php';

$role = $_SESSION['role'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    verify_csrf();
    if ($_POST['action'] === 'add' || $_POST['action'] === 'edit') {
        $title = trim($_POST['title']);
        $authors = trim($_POST['authors']);
        $journal = trim($_POST['journal']);
        $publication_year = (int)$_POST['publication_year'];
        $index_type = enum_val($_POST['index_type'], ['SCI','Scopus','UGC','Other']);
        $faculty_id = (int)$_POST['faculty_id'];
        if ($_POST['action'] === 'add') {
            if (safe_exec("INSERT INTO publications (title,authors,journal,publication_year,index_type,faculty_id) VALUES (?,?,?,?,?,?)",
                [$title,$authors,$journal,$publication_year,$index_type,$faculty_id])) {
                flash('Publication added.');
            }
        } else {
            if (safe_exec("UPDATE publications SET title=?,authors=?,journal=?,publication_year=?,index_type=?,faculty_id=? WHERE id=?",
                [$title,$authors,$journal,$publication_year,$index_type,$faculty_id,(int)$_POST['id']])) {
                flash('Publication updated.');
            }
        }
    } elseif ($_POST['action'] === 'delete') {
        if (safe_exec("DELETE FROM publications WHERE id=?", [(int)$_POST['id']])) {
            flash('Publication deleted.', 'danger');
        }
    }
    redirect('modules/publications.php');
}

$edit = null;
if (isset($_GET['edit'])) {
    $edit = $pdo->prepare("SELECT * FROM publications WHERE id=?");
    $edit->execute([(int)$_GET['edit']]);
    $edit = $edit->fetch();
}
$faculty = $pdo->query("SELECT id,full_name FROM faculty ORDER BY full_name")->fetchAll();
$pubs = $pdo->query("SELECT p.*, f.full_name AS faculty_name FROM publications p LEFT JOIN faculty f ON p.faculty_id=f.id ORDER BY p.publication_year DESC")->fetchAll();
?>
<?php
$active = 'publications';
$page_title = 'AIML AcademicHub';
require_once __DIR__ . '/../includes/layout.php';
?>
<h2>Publications</h2>
<?php show_flash(); ?>

<div class="card">
    <h3><?= $edit ? 'Edit Publication' : 'Add Publication' ?></h3>
        <form method="post" class="grid grid-2">
        <input type="hidden" name="action" value="<?= $edit ? 'edit' : 'add' ?>">
        <?= csrf_field() ?>
        <?php if ($edit): ?><input type="hidden" name="id" value="<?= $edit['id'] ?>"><?php endif; ?>
        <div class="form-group" style="grid-column:1/-1"><label>Title</label><input name="title" value="<?= e($edit['title'] ?? '') ?>" required></div>
        <div class="form-group"><label>Authors</label><input name="authors" value="<?= e($edit['authors'] ?? '') ?>"></div>
        <div class="form-group"><label>Journal</label><input name="journal" value="<?= e($edit['journal'] ?? '') ?>"></div>
        <div class="form-group"><label>Year</label><input type="number" name="publication_year" value="<?= e($edit['publication_year'] ?? date('Y')) ?>"></div>
        <div class="form-group"><label>Index Type</label>
            <select name="index_type">
                <option value="SCI" <?= ($edit['index_type']??'')==='SCI'?'selected':'' ?>>SCI</option>
                <option value="Scopus" <?= ($edit['index_type']??'')==='Scopus'?'selected':'' ?>>Scopus</option>
                <option value="UGC" <?= ($edit['index_type']??'')==='UGC'?'selected':'' ?>>UGC</option>
                <option value="Other" <?= ($edit['index_type']??'')==='Other'?'selected':'' ?>>Other</option>
            </select>
        </div>
        <div class="form-group"><label>Faculty</label>
            <select name="faculty_id">
                <?php foreach ($faculty as $f): ?><option value="<?= $f['id'] ?>" <?= ($edit['faculty_id']??0)==$f['id']?'selected':'' ?>><?= e($f['full_name']) ?></option><?php endforeach; ?>
            </select>
        </div>
        <div class="form-group" style="grid-column:1/-1"><button class="btn btn-primary" type="submit"><?= $edit ? 'Update' : 'Add Publication' ?></button>
        <?php if ($edit): ?><a href="<?= base_url() ?>/modules/publications.php" class="btn btn-warning">Cancel</a><?php endif; ?></div>
    </form>
</div>

<div class="card">
    <h3>All Publications (<?= count($pubs) ?>)</h3>
    <div class="table-wrap"><table>
        <tr><th>Title</th><th>Authors</th><th>Journal</th><th>Year</th><th>Index</th><th>Faculty</th><th>Actions</th></tr>
        <?php foreach ($pubs as $p): ?>
        <tr>
            <td><?= e($p['title']) ?></td>
            <td><?= e($p['authors']) ?></td>
            <td><?= e($p['journal']) ?></td>
            <td><?= $p['publication_year'] ?></td>
            <td><span class="badge badge-info"><?= e($p['index_type']) ?></span></td>
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
    </table></div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>




