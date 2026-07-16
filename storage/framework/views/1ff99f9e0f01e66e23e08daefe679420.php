<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>APACC-M e-Livre</title>
<style>
body { margin:0; padding:0; background:#f6f3ef; font-family:'Helvetica Neue',Arial,sans-serif; color:#1a1a1a; }
.wrapper { max-width:580px; margin:0 auto; padding:32px 16px 48px; }
.card { background:#ffffff; border-radius:8px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.08); }
.card-header { background:#b91c1c; padding:28px 32px; text-align:center; }
.card-header img { height:36px; }
.logo-text { color:#ffffff; font-size:22px; font-weight:700; letter-spacing:0.1em; text-decoration:none; }
.logo-suffix { font-size:13px; font-weight:400; opacity:0.75; margin-left:6px; }
.card-body { padding:36px 32px; }
.card-body h1 { font-size:22px; font-weight:700; color:#1a1a1a; margin:0 0 12px; line-height:1.3; }
.card-body p { font-size:15px; color:#555; line-height:1.7; margin:0 0 16px; }
.btn { display:inline-block; background:#b91c1c; color:#ffffff !important; text-decoration:none; padding:13px 28px; border-radius:4px; font-size:14px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; margin:8px 0 16px; }
.highlight-box { background:#f6f3ef; border-left:4px solid #b91c1c; border-radius:4px; padding:16px 20px; margin:20px 0; }
.highlight-box p { margin:0; font-size:14px; color:#555; }
.highlight-box strong { color:#1a1a1a; }
.divider { border:none; border-top:1px solid #eee; margin:24px 0; }
.card-footer { background:#f9fafb; border-top:1px solid #eee; padding:20px 32px; text-align:center; }
.card-footer p { font-size:12px; color:#888; margin:4px 0; line-height:1.6; }
.card-footer a { color:#b91c1c; text-decoration:none; font-weight:600; }
</style>
</head>
<body>
<div class="wrapper">
    <div style="text-align:center;margin-bottom:20px;">
        <span class="logo-text">APACC-M<span class="logo-suffix">e-Livre</span></span>
    </div>
    <div class="card">
        <div class="card-header">
            <p style="color:rgba(255,255,255,0.8);font-size:12px;letter-spacing:0.2em;text-transform:uppercase;margin:8px 0 0;">Patrimoine culturel martiniquais</p>
        </div>
        <div class="card-body">
            <?php echo $__env->yieldContent('body'); ?>
        </div>
        <div class="card-footer">
            <p>APACC-M e-Livre · Martinique</p>
            <p><a href="<?php echo e(config('app.url')); ?>"><?php echo e(config('app.url')); ?></a> · <a href="https://apacc-martinique.fr">apacc-martinique.fr</a></p>
            <p style="margin-top:8px;">Si vous n'êtes pas à l'origine de cette action, ignorez cet email.</p>
        </div>
    </div>
</div>
</body>
</html>
<?php /**PATH C:\laragon\www\apacc-m-ebook\resources\views/emails/layout.blade.php ENDPATH**/ ?>