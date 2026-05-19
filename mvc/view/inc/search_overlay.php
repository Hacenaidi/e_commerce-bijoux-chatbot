<?php
// Centralized search overlay used across public and account pages
?>
<div id="searchOv" class="search-ov" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.96);z-index:3000;flex-direction:column;align-items:center;justify-content:center;">
    <i class="fa fa-times search-ov-close" id="searchClose" style="position:absolute;top:36px;right:56px;font-size:22px;color:rgba(255,255,255,.4);cursor:pointer;"></i>
    <input id="globalSearchInput" type="text" placeholder="Search bijoux..." style="background:none;border:none;border-bottom:1px solid rgba(255,255,255,.25);font-family:'Cormorant Garamond',serif;font-size:34px;color:#fff;width:60%;max-width:580px;padding:14px 0;text-align:center;outline:none;">
    <p style="color:rgba(255,255,255,.2);font-size:9px;letter-spacing:4px;text-transform:uppercase;margin-top:20px;">Press Enter</p>
</div>

<script>
// Wire up search overlay behaviour (works on every page that includes this file)
(function(){
    var ov = document.getElementById('searchOv');
    var input = document.getElementById('globalSearchInput');
    var close = document.getElementById('searchClose');

    if (!ov || !input) return;

    // Close control
    if (close) close.addEventListener('click', function(){ ov.style.display = 'none'; });

    // Escape key closes overlay
    document.addEventListener('keydown', function(e){ if(e.key === 'Escape'){ ov.style.display = 'none'; } });

    // Enter in input -> redirect to shop.php with nom param
    input.addEventListener('keydown', function(e){
        if (e.key === 'Enter'){
            var q = input.value.trim();
            if (!q) return;
            // Close overlay, navigate to shop.php with query
            ov.style.display = 'none';
            window.location.href = 'shop.php?nom=' + encodeURIComponent(q);
        }
    });

    // Expose a helper to open overlay and focus input (some pages call this)
    window.openGlobalSearch = function(){ ov.style.display = 'flex'; input.focus(); };
})();
</script>
