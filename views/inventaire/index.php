<?php
$activePage = 'inventaire';
$pageTitle = 'Répertoires & Catalogue | StoreManager Pro';

require_once dirname(__DIR__) . "/layout/header.php";
?>

<div id="view-catalog">
    <!-- Catalog Stats -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px;">
        <div class="panel-card" style="padding: 16px; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid var(--success);">
            <div>
                <span style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Valeur Totale Stock</span>
                <div style="font-size: 18px; font-weight: 800; color: white; margin-top: 4px;">3 476 000 F</div>
            </div>
            <span style="font-size: 24px;">📦</span>
        </div>
        <div class="panel-card" style="padding: 16px; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid var(--accent);">
            <div>
                <span style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Articles au Catalogue</span>
                <div style="font-size: 18px; font-weight: 800; color: white; margin-top: 4px;">6 références</div>
            </div>
            <span style="font-size: 24px;">🏷️</span>
        </div>
        <div class="panel-card" style="padding: 16px; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid var(--warning);">
            <div>
                <span style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Clients Enregistrés</span>
                <div style="font-size: 18px; font-weight: 800; color: white; margin-top: 4px;">10 clients</div>
            </div>
            <span style="font-size: 24px;">👥</span>
        </div>
    </div>

    <!-- Panel Card -->
    <div class="panel-card">
        <div class="panel-title">
            <span>Catalogue Produits & Répertoires Partenaires</span>
        </div>
        <p style="font-size: 13px; color: var(--text-muted);">
            Espace réservé à l'<strong>Agent d'Inventaire</strong> pour le comptage physique des stocks, la mise à jour des seuils d'alerte et la consultation des répertoires clients et fournisseurs.
        </p>
    </div>
</div>

<?php
require_once dirname(__DIR__) . "/layout/footer.php";
?>
