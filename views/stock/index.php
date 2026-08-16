<?php
$activePage = 'stock';
$pageTitle = 'Approvisionnements & Réception | StoreManager Pro';

require_once dirname(__DIR__) . "/layout/header.php";
?>

<div id="view-supplies">
    <!-- Header Stats -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px;">
        <div class="panel-card" style="padding: 16px; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid var(--success);">
            <div>
                <span style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Valeur Réceptions</span>
                <div style="font-size: 18px; font-weight: 800; color: white; margin-top: 4px;">4 520 000 F</div>
            </div>
            <span style="font-size: 24px;">📥</span>
        </div>
        <div class="panel-card" style="padding: 16px; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid var(--warning);">
            <div>
                <span style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Commandes En Cours</span>
                <div style="font-size: 18px; font-weight: 800; color: white; margin-top: 4px;">2 BL en attente</div>
            </div>
            <span style="font-size: 24px;">⏳</span>
        </div>
        <div class="panel-card" style="padding: 16px; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid var(--accent);">
            <div>
                <span style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Fournisseurs Actifs</span>
                <div style="font-size: 18px; font-weight: 800; color: white; margin-top: 4px;">3 partenaires</div>
            </div>
            <span style="font-size: 24px;">🤝</span>
        </div>
    </div>

    <!-- Main Content Panel -->
    <div class="panel-card">
        <div class="panel-title">
            <span>Gestion des Bons de Livraison & Réceptions</span>
            <input type="text" id="supplies-search" class="search-control" placeholder="Filtrer les approvisionnements..." onkeyup="filterTable('supplies-search', 'supplies-main-table', 'data-bl-ref')">
        </div>
        <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 20px;">
            Espace réservé au <strong>Chargé de Stock</strong> pour la passation de commandes auprès des grossistes et la validation des réceptions de marchandises en magasin.
        </p>
    </div>
</div>

<?php
require_once dirname(__DIR__) . "/layout/footer.php";
?>
