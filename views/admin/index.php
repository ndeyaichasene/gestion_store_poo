<?php
$activePage = 'dashboard';
$pageTitle = 'Tableau de Bord Admin | StoreManager Pro';

require_once dirname(__DIR__) . "/layout/header.php";
?>

<div id="view-dashboard">
    <!-- Top KPIs Grid -->
    <div class="kpi-grid">
        <div class="kpi-card" style="border-left: 4px solid var(--success);">
            <div>
                <div class="kpi-label">Chiffre d'Affaires Encaissé</div>
                <div class="kpi-val" style="color: var(--success);"><?= number_format($stats['ca_encaisse_net'] ?? 92000, 0, ',', ' ') ?> F</div>
            </div>
            <span style="font-size: 32px;">💰</span>
        </div>

        <div class="kpi-card" style="border-left: 4px solid var(--danger);">
            <div>
                <div class="kpi-label">Créances & Dettes Clients</div>
                <div class="kpi-val" style="color: var(--danger);"><?= number_format($stats['encours_client_total'] ?? 99000, 0, ',', ' ') ?> F</div>
            </div>
            <span style="font-size: 32px;">🛑</span>
        </div>

        <div class="kpi-card" style="border-left: 4px solid var(--accent);">
            <div>
                <div class="kpi-label">Commandes Réalisées</div>
                <div class="kpi-val" style="color: var(--accent);"><?= (int)($stats['total_commandes'] ?? 4) ?> ventes</div>
            </div>
            <span style="font-size: 32px;">📊</span>
        </div>
    </div>

    <!-- Main Admin Panels -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
        <!-- Left: Sales summary -->
        <div class="panel-card">
            <div class="panel-title">
                <span>Dernières Ventes Enregistrées</span>
                <a href="?view=pos" class="btn-quick-action" style="color: var(--accent); border-color: var(--accent);">Ouvrir Caisse POS →</a>
            </div>
            <p style="font-size: 13px; color: var(--text-muted); line-height: 1.6;">
                Visualisez et supervisez l'activité en temps réel des encaissements et des commandes des clients.
            </p>
        </div>

        <!-- Right: Stock alerts -->
        <div class="panel-card">
            <div class="panel-title">
                <span>Alertes de Stock Critique</span>
                <a href="?view=stock" class="btn-quick-action" style="color: var(--warning); border-color: var(--warning);">Approvisionner →</a>
            </div>
            <p style="font-size: 13px; color: var(--text-muted); line-height: 1.6;">
                Surveillance automatique des seuils de réapprovisionnement et suivi des bons de livraison fournisseurs.
            </p>
        </div>
    </div>
</div>

<?php
require_once dirname(__DIR__) . "/layout/footer.php";
?>
