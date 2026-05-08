<?php
$footerYear = $footerYear ?? date('Y');
$footerText = $footerText ?? 'Renz Alvarez. All rights reserved.';
?>
<footer>
    <p>&copy; <?= htmlspecialchars((string) $footerYear, ENT_QUOTES, 'UTF-8') ?> <?= htmlspecialchars($footerText, ENT_QUOTES, 'UTF-8') ?></p>
</footer>
</body>

</html>
