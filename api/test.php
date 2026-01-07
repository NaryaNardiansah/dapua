<?php
echo "<h1>INFO PHP VERCEL</h1>";
if (extension_loaded('pdo_pgsql')) {
    echo "<p style='color:green'>✅ PDO PGSQL ADA</p>";
} else {
    echo "<p style='color:red'>❌ PDO PGSQL TIDAK ADA</p>";
}

if (extension_loaded('pgsql')) {
    echo "<p style='color:green'>✅ PGSQL ADA</p>";
} else {
    echo "<p style='color:red'>❌ PGSQL TIDAK ADA</p>";
}

phpinfo();
