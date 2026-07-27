    </div><!-- /.content -->
</div><!-- /.main -->

<!-- Modal popup -->
<div class="modal-overlay" id="modal">
    <div class="modal">
        <div class="ico danger" id="modalIco">!</div>
        <h3 id="modalTitle">Are you sure?</h3>
        <p id="modalMsg">This action cannot be undone.</p>
        <div class="actions">
            <button class="btn btn-primary" id="modalCancel">Cancel</button>
            <button class="btn btn-danger" id="modalOk">Delete</button>
        </div>
    </div>
</div>

<!-- Toast container -->
<div class="toast-wrap" id="toasts"></div>

<script>
if (window.innerWidth <= 900) {
    document.getElementById('menuToggle').style.display = 'inline-block';
}

/* ---- Beautiful confirm modal ---- */
var modal = document.getElementById('modal');
var modalTitle = document.getElementById('modalTitle');
var modalMsg = document.getElementById('modalMsg');
var modalIco = document.getElementById('modalIco');
var modalOk = document.getElementById('modalOk');
var modalCancel = document.getElementById('modalCancel');
var pendingForm = null;

function openModal(opts) {
    opts = opts || {};
    modalTitle.textContent = opts.title || 'Are you sure?';
    modalMsg.textContent = opts.msg || 'This action cannot be undone.';
    modalIco.className = 'ico ' + (opts.type || 'danger');
    modalIco.textContent = opts.icon || '!';
    modalOk.textContent = opts.okText || 'Delete';
    modalOk.className = 'btn ' + (opts.okClass || 'btn-danger');
    pendingForm = opts.form || null;
    modal.classList.add('show');
}
function closeModal() { modal.classList.remove('show'); pendingForm = null; }

modalCancel.addEventListener('click', closeModal);
modal.addEventListener('click', function(e){ if (e.target === modal) closeModal(); });
modalOk.addEventListener('click', function(){
    if (pendingForm) pendingForm.submit();
    closeModal();
});
document.addEventListener('keydown', function(e){ if (e.key === 'Escape') closeModal(); });

/* Delete buttons use class 'btn-delete' and data attributes */
document.querySelectorAll('.btn-delete').forEach(function(btn){
    btn.addEventListener('click', function(e){
        e.preventDefault();
        var form = btn.closest('form');
        openModal({
            title: btn.dataset.title || 'Delete?',
            msg: btn.dataset.msg || 'This action cannot be undone.',
            okText: btn.dataset.ok || 'Delete',
            type: 'danger', icon: '!',
            form: form
        });
    });
});

/* ---- Toast notifications from flash messages ---- */
function showToast(type, msg) {
    var wrap = document.getElementById('toasts');
    var icons = { success:'&#10003;', danger:'&#10007;', warning:'&#9888;', info:'&#9432;' };
    var t = document.createElement('div');
    t.className = 'toast ' + type;
    t.innerHTML = '<span class="t-ico">' + (icons[type] || '&#9432;') + '</span><span>' + msg + '</span>';
    wrap.appendChild(t);
    requestAnimationFrame(function(){ t.classList.add('show'); });
    setTimeout(function(){ t.classList.remove('show'); setTimeout(function(){ t.remove(); }, 400); }, 3500);
}
<?php if (!empty($_SESSION['flash'])): ?>
showToast('<?= $_SESSION['flash']['type'] ?>', <?= json_encode($_SESSION['flash']['msg']) ?>);
<?php unset($_SESSION['flash']); endif; ?>
<<<<<<< HEAD
=======

// --- Hide page loader ---
(function(){
    var loader = document.getElementById('pageLoader');
    if (!loader) return;
    var start = Date.now();
    function hide() {
        var elapsed = Date.now() - start;
        var delay = Math.max(0, 700 - elapsed);
        setTimeout(function(){ loader.classList.add('hidden'); }, delay);
    }
    if (document.readyState === 'complete') hide();
    else window.addEventListener('load', hide);
})();
>>>>>>> 45e2ed514fc6a290a4cb5ac7331c5ed0f6b52cd7
</script>
</body>
</html>
