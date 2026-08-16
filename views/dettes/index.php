<?php
$activePage = 'dettes';
$pageTitle = 'Gestion des Dettes & Créances | StoreManager Pro';

require_once dirname(__DIR__) . "/layout/header.php";
?>

<div id="view-dettes">
    <!-- Debts Stats Grid -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px;">
        <div class="panel-card" style="padding: 16px; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid var(--danger);">
            <div>
                <span style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Créances Actives</span>
                <div style="font-size: 18px; font-weight: 800; color: white; margin-top: 4px;">99 000 F</div>
            </div>
            <span style="font-size: 24px;">💸</span>
        </div>
        <div class="panel-card" style="padding: 16px; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid var(--warning);">
            <div>
                <span style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Clients Débiteurs</span>
                <div style="font-size: 18px; font-weight: 800; color: white; margin-top: 4px;">3 clients</div>
            </div>
            <span style="font-size: 24px;">👥</span>
        </div>
        <div class="panel-card" style="padding: 16px; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid var(--success);">
            <div>
                <span style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Total Recouvrements</span>
                <div style="font-size: 18px; font-weight: 800; color: white; margin-top: 4px;">34 000 F</div>
            </div>
            <span style="font-size: 24px;">📈</span>
        </div>
    </div>

    <!-- Debts Table -->
    <div class="panel-card">
        <div class="panel-title">
            <span>Registre des Créances et Dettes Clients</span>
            <input type="text" id="debts-search" class="search-control" placeholder="Rechercher un client débiteur..." onkeyup="filterTable('debts-search', 'debts-main-table', 'data-client-name')">
        </div>
        <p style="font-size: 13px; color: var(--text-muted);">
            Suivi des soldes impayés, historique des versements et enregistrement des règlements partiels ou totaux.
        </p>
    </div>
</div>

<?php
require_once dirname(__DIR__) . "/layout/footer.php";
?>
