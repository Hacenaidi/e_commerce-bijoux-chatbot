(function(){
    // debug version marker
    try { console.log('confirm-modal loaded - v8'); } catch(e) {}
    if (document.getElementById('confirm-modal')) return;
    var modal = document.createElement('div');
    modal.id = 'confirm-modal';
    modal.className = 'confirm-modal';
    modal.innerHTML = '\n                <div class="confirm-backdrop"></div>\n                <div class="confirm-shell" role="dialog" aria-modal="true">\n                    <div class="confirm-title">\n                        <span class="confirm-icon">\n                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">\n                                <circle cx="12" cy="12" r="10" fill="#ff6b78"/>\n                                <path d="M12 7v6" stroke="#fff" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>\n                                <circle cx="12" cy="17" r="1" fill="#fff"/>\n                            </svg>\n                        </span>\n                        <span class="confirm-title-text">Confirm action</span>\n                    </div>\n                    <div class="confirm-body">Are you sure?</div>\n                    <div class="confirm-actions">\n                        <button class="btn btn-cancel">Cancel</button>\n                        <button class="btn btn-confirm action-link danger">Confirm</button>\n                    </div>\n                </div>';
    document.body.appendChild(modal);
    modal.style.display = 'none';

    var backdrop = modal.querySelector('.confirm-backdrop');
    var shell = modal.querySelector('.confirm-shell');
    var titleEl = modal.querySelector('.confirm-title-text');
    var bodyEl = modal.querySelector('.confirm-body');
    var actionsEl = modal.querySelector('.confirm-actions');
    var btnCancel = modal.querySelector('.btn-cancel');
    var btnConfirm = modal.querySelector('.btn-confirm');

    // Force inline dark styles to prevent other CSS from overriding
    try {
        var root = getComputedStyle(document.documentElement);
        var panel = root.getPropertyValue('--panel') || '#0a0a0a';
        var text = root.getPropertyValue('--text') || '#eef4ff';
        var accent1 = root.getPropertyValue('--accent') || '#ef4444';
        var accent2 = root.getPropertyValue('--accent-2') || '#f87171';

        modal.style.position = 'fixed';
        modal.style.left = '50%';
        modal.style.top = '50%';
        modal.style.transform = 'translate(-50%, -40%)';
        modal.style.zIndex = '99999';

        shell.style.background = panel.trim();
        shell.style.color = text.trim();
        shell.style.borderRadius = '12px';
        shell.style.padding = '14px 16px';
        shell.style.minWidth = '420px';
        shell.style.width = '520px';
        shell.style.maxWidth = '92vw';
        shell.style.boxShadow = '0 18px 50px rgba(2,6,20,0.6)';
        shell.style.border = '1px solid rgba(255,255,255,0.03)';
        // ensure shell sits above backdrop and isn't stretched by other css
        shell.style.position = 'relative';
        shell.style.zIndex = '99999';

        titleEl.style.color = 'var(--muted)';
        bodyEl.style.color = text.trim();

        btnCancel.style.background = 'rgba(255,255,255,0.02)';
        btnCancel.style.border = '1px solid rgba(255,255,255,0.04)';
        btnCancel.style.color = text.trim();
        btnCancel.style.padding = '6px 12px';
        btnCancel.style.borderRadius = '10px';

        // Let `.action-link.danger` CSS control confirm button visuals
        btnConfirm.classList.add('action-link', 'danger');
        // enforce backdrop inline style too (prevents global CSS overrides)
        backdrop.style.position = 'fixed';
        backdrop.style.left = '0'; backdrop.style.right = '0'; backdrop.style.top = '0'; backdrop.style.bottom = '0';
        backdrop.style.background = 'transparent';
        backdrop.style.backdropFilter = 'none';
        backdrop.style.zIndex = '99998';
    } catch (e) {
        // ignore styling errors
    }

    function close() { modal.classList.remove('open');
        // wait for transition then hide
        setTimeout(function(){ modal.style.display = 'none'; }, 200);
    }

    backdrop.addEventListener('click', close);
    btnCancel.addEventListener('click', close);

    modal.showConfirm = function (title, message) {
        return new Promise(function (resolve) {
            var titleNode = shell.querySelector('.confirm-title-text');
            var bodyNode = shell.querySelector('.confirm-body');
            titleNode.textContent = title || 'Confirm action';
            bodyNode.textContent = message || 'Are you sure?';
            modal.style.display = 'block';
            // tiny delay to allow CSS transition
            requestAnimationFrame(function(){ modal.classList.add('open'); });

            function onConfirm() { cleanup(); resolve(true); }
            function onCancel() { cleanup(); resolve(false); }
            function cleanup() {
                btnConfirm.removeEventListener('click', onConfirm);
                btnCancel.removeEventListener('click', onCancel);
                backdrop.removeEventListener('click', onCancel);
                modal.classList.remove('open');
                document.removeEventListener('keydown', escHandler);
                close();
            }

            btnConfirm.addEventListener('click', onConfirm);
            btnCancel.addEventListener('click', onCancel);
            backdrop.addEventListener('click', onCancel);
            // focus and keyboard handling
            var escHandler = function(e) { if (e.key === 'Escape') { close(false); } };
            document.addEventListener('keydown', escHandler);
            setTimeout(function(){ btnCancel.focus(); }, 80);
        });
    };

    // global click interception for elements with data-confirm
    document.addEventListener('click', function (e) {
        var el = e.target.closest('[data-confirm]');
        if (!el) return;
        e.preventDefault();
        var msg = el.getAttribute('data-confirm') || 'Are you sure?';
        var title = el.getAttribute('data-confirm-title') || 'Confirm action';
        if (!modal || !modal.showConfirm) {
            if (window.confirm(msg)) proceedConfirmTarget(el);
            return;
        }
        modal.showConfirm(title, msg).then(function (ok) { if (ok) proceedConfirmTarget(el); });
    }, false);

    function proceedConfirmTarget(el) {
        if (!el) return;
        if (el.tagName.toLowerCase() === 'a' && el.href) { window.location.href = el.href; return; }
        if (el.tagName.toLowerCase() === 'button') { var form = el.closest('form'); if (form) { form.submit(); return; } }
        var targetForm = el.getAttribute('data-confirm-target-form');
        if (targetForm) { var form = document.getElementById(targetForm); if (form) form.submit(); }
    }
})();
