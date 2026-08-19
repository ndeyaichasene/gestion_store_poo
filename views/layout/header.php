<?php
$activePage = $activePage ?? 'pos';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'StoreManager | ERP Tactical Workspace') ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href=" /css/style.css">
    <link rel="stylesheet" href=" public/css/style.css">
    <link rel="stylesheet" href="./public/css/style.css">
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>
    <div class="app-container">
        <!-- Top Navbar (Exacte de l'ÉNONCÉ) -->
        <div class="navbar">
            <div class="nav-logo">
                <span>📦</span> StoreManager Pro
            </div>
            <div class="nav-menu">
                <a href="?view=dashboard" class="nav-item <?= $activePage === 'dashboard' ? 'active' : '' ?>">Tableau de Bord</a>
                <a href="?view=pos" class="nav-item <?= $activePage === 'pos' ? 'active' : '' ?>">Ventes / POS</a>
                <a href="?view=dettes" class="nav-item <?= $activePage === 'dettes' ? 'active' : '' ?>">Gestion Dettes</a>
                <a href="?view=stock" class="nav-item <?= $activePage === 'stock' ? 'active' : '' ?>">Approvisionnements</a>
                <a href="?view=inventaire" class="nav-item <?= $activePage === 'inventaire' ? 'active' : '' ?>">Produits & Tiers</a>
            </div>
            
            <div style="margin-left: auto; display: flex; align-items: center; gap: 14px;">
                <div style="text-align: right;">
                    <div id="current-user-role" style="font-size: 12px; font-weight: 800; color: var(--accent);">
                        <?= htmlspecialchars(SessionManager::get('user_nom', 'Admin Boutique')) ?>
                    </div>
                    <div style="font-size: 10px; color: var(--text-muted);">
                        <?= htmlspecialchars(SessionManager::get('user_role', 'Session active')) ?>
                    </div>
                </div>
                <a href="?view=pos" class="btn-quick-action" style="border-color: var(--danger); color: var(--danger); background: rgba(248, 113, 113, 0.08); padding: 8px 12px; text-decoration: none;">
                    Déconnexion 🚪
                </a>
            </div>
        </div>

        <!-- Flash Messages Toast -->
        <?php if (!empty($success) || !empty($error)): ?>
            <div class="toast-box">
                <?php if (!empty($success)): ?>
                    <div class="toast success">
                        <span>✓</span>
                        <div><?= htmlspecialchars($success) ?></div>
                    </div>
                <?php endif; ?>
                <?php if (!empty($error)): ?>
                    <div class="toast danger">
                        <span>⚠️</span>
                        <div><?= htmlspecialchars($error) ?></div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <main>
