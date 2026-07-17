s<?php
// ⚠️ FICHIER DE DIAGNOSTIC TEMPORAIRE — À SUPPRIMER APRÈS USAGE.
// Ouvre https://TON-DOMAINE/_upcheck.php et téléverse ton PDF via le formulaire.

header('Content-Type: text/html; charset=utf-8');

$codes = [
    0 => 'UPLOAD_ERR_OK (aucun problème)',
    1 => 'UPLOAD_ERR_INI_SIZE (dépasse upload_max_filesize)',
    2 => 'UPLOAD_ERR_FORM_SIZE (dépasse MAX_FILE_SIZE du formulaire)',
    3 => 'UPLOAD_ERR_PARTIAL (upload interrompu / timeout)',
    4 => 'UPLOAD_ERR_NO_FILE (aucun fichier envoyé)',
    6 => 'UPLOAD_ERR_NO_TMP_DIR (dossier temporaire manquant)',
    7 => 'UPLOAD_ERR_CANT_WRITE (écriture disque impossible / quota plein)',
    8 => 'UPLOAD_ERR_EXTENSION (bloqué par une extension PHP)',
];

echo '<pre style="font:14px/1.5 monospace;padding:1rem;background:#111;color:#eee;">';
echo "=== Valeurs PHP effectives (SAPI: " . php_sapi_name() . ") ===\n";
foreach (['upload_max_filesize','post_max_size','memory_limit','max_execution_time','max_input_time','file_uploads','upload_tmp_dir','max_file_uploads'] as $k) {
    echo str_pad($k, 22) . ' = ' . var_export(ini_get($k), true) . "\n";
}
$tmp = ini_get('upload_tmp_dir') ?: sys_get_temp_dir();
echo str_pad('temp utilisé', 22) . ' = ' . $tmp . "\n";
$probe = @tempnam($tmp, 'up_');
if ($probe && @file_put_contents($probe, 'x') !== false) {
    echo str_pad('écriture temp', 22) . " = OK\n";
    @unlink($probe);
} else {
    echo str_pad('écriture temp', 22) . " = ÉCHEC (temp non inscriptible / plein)\n";
}
$fu = @disk_free_space($tmp);
if ($fu !== false) {
    echo str_pad('espace libre temp', 22) . ' = ' . round($fu / 1048576, 1) . " Mo\n";
}
echo str_pad('.user.ini présent', 22) . ' = ' . (is_file(__DIR__ . '/.user.ini') ? 'oui (' . __DIR__ . '/.user.ini)' : 'NON') . "\n";
echo "\n";

if (!empty($_FILES['f'])) {
    $err = $_FILES['f']['error'];
    echo "=== Résultat de l'upload ===\n";
    echo "Nom      : " . htmlspecialchars($_FILES['f']['name']) . "\n";
    echo "Taille   : " . round(($_FILES['f']['size'] ?? 0) / 1048576, 2) . " Mo\n";
    echo "Code     : $err\n";
    echo "Signif.  : " . ($codes[$err] ?? 'inconnu') . "\n";
    echo "tmp_name : " . var_export($_FILES['f']['tmp_name'] ?? null, true) . "\n";
    echo "is_uploaded_file = " . var_export(is_uploaded_file($_FILES['f']['tmp_name'] ?? ''), true) . "\n";
}
echo '</pre>';
echo '<form method="post" enctype="multipart/form-data" style="font:14px monospace;padding:1rem;">'
   . '<input type="file" name="f" accept="application/pdf"> '
   . '<button type="submit">Tester l\'upload</button></form>';
