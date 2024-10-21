<?php
function checkForBlock() {
    if (!isset($_SESSION['block_time'])) return false;
    $delta = time() - $_SESSION['block_time'];
    $blockTime = 600;
    $remaining = $blockTime - $delta;
    return $remaining > 0 ? $remaining : false;
}