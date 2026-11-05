<?php
// Asset cache-buster guard: fails the suite if asset_version() is stale
// relative to asset file mtimes (style.css / assets/*.js newer than the
// version string date => someone forgot to bump).
function test_asset_version_covers_asset_changes(T $t): void
{
    $layout = file_get_contents(__DIR__ . '/../src/layout.php');
    if (!preg_match("/return '(\d{8})'/", $layout, $m)) {
        $t->assertSame(1, 0, 'asset_version() date-string not found in layout.php');
        return;
    }
    $verTs = strtotime($m[1]);
    $files = array_merge(
        [__DIR__ . '/../style.css'],
        glob(__DIR__ . '/../assets/*.{css,js}', GLOB_BRACE) ?: []
    );
    foreach ($files as $f) {
        if (is_file($f) && filemtime($f) > $verTs + 86400) {
            $t->assertSame(1, 0, basename($f) . ' modified after asset_version date ' . $m[1] . ' — bump src/layout.php asset_version()');
            return;
        }
    }
    $t->assertSame(1, 1);
}
