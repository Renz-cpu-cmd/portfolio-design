<?php
require_once __DIR__ . '/../config/config.php';

function ensureAdminSessionStarted(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function isAdminLoggedIn(): bool
{
    ensureAdminSessionStarted();

    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
}

function requireAdminAuth(): void
{
    ensureAdminSessionStarted();

    if (isAdminLoggedIn()) {
        return;
    }

    $currentPath = basename($_SERVER['PHP_SELF'] ?? '');
    $queryString = $_SERVER['QUERY_STRING'] ?? '';
    $redirectTarget = $queryString !== '' ? $currentPath . '?' . $queryString : $currentPath;

    header('Location: admin_login.php?redirect=' . urlencode($redirectTarget));
    exit;
}
