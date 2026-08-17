<?php
require_once dirname(__DIR__,2)."/src/Core/SessionManager.php";
$ventesAvecLignes = $ventesAvecLignes ?? [];
$produits = $produits ?? [];
$ventes = $ventes ?? [];
$clients = $clients ?? [];
$panier = SessionManager::get('pos_cart', []);
$totalPanier = 0;
foreach ($panier as $item) {
    $totalPanier += $item['sous_total'];
}
$activePage = 'pos';
$pageTitle = 'STORE-MANAGER POS - Caisse Tactile | StoreManager Pro';

require_once dirname(__DIR__) . "/layout/header.php";
?>

<!-- ================= VIEW: POS (SALES CONSOLE) ================= -->
<div id="view-pos" class="view-section">
    <!-- POS Stats Grid -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-bottom: 28px;">
        <div class="panel-card" style="padding: 22px 26px; display: flex; align-items: center; justify-content: space-between; border-left: 5px solid var(--success); margin-bottom: 0;">
            <div>
                <span style="font-size: 12px; color: var(--text-muted); text-transform: uppercase; font-weight: 800; letter-spacing: 0.5px;">CA Encaissé Net</span>
                <div style="font-size: 22px; font-weight: 800; color: white; margin-top: 6px;">
                    <?= number_format($stats['ca_encaisse_net'] ?? 0, 0, ',', ' ') ?> F
                </div>
            </div>
            <span style="font-size: 28px;">💰</span>
        </div>
        <div class="panel-card" style="padding: 22px 26px; display: flex; align-items: center; justify-content: space-between; border-left: 5px solid var(--danger); margin-bottom: 0;">
            <div>
                <span style="font-size: 12px; color: var(--text-muted); text-transform: uppercase; font-weight: 800; letter-spacing: 0.5px;">Encours Client Total</span>
                <div style="font-size: 22px; font-weight: 800; color: white; margin-top: 6px;">
                    <?= number_format($stats['encours_client_total'] ?? 0, 0, ',', ' ') ?> F
                </div>
            </div>
            <span style="font-size: 28px;">🛑</span>
        </div>
        <div class="panel-card" style="padding: 22px 26px; display: flex; align-items: center; justify-content: space-between; border-left: 5px solid var(--accent); margin-bottom: 0;">
            <div>
                <span style="font-size: 12px; color: var(--text-muted); text-transform: uppercase; font-weight: 800; letter-spacing: 0.5px;">Commandes Enregistrées</span>
                <div style="font-size: 22px; font-weight: 800; color: white; margin-top: 6px;">
                    <?= (int) ($stats['total_commandes'] ?? count($ventes)) ?> ventes
                </div>
            </div>
            <span style="font-size: 28px;">📊</span>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 640px 1fr; gap: 32px; align-items: start; margin-bottom: 36px;">
        <!-- Left panel: POS ticket creator (sticky) -->
        <div class="panel-card" style="margin-bottom: 0; padding: 32px; border: 1px solid rgba(59, 130, 246, 0.25); background: linear-gradient(180deg, rgba(17, 24, 43, 0.6) 0%, rgba(10, 15, 30, 0.4) 100%); position: sticky; top: 24px;">
            <div class="panel-title" style="border-left-color: var(--accent); display: flex; justify-content: space-between; align-items: center;">
                <span>🛒 Nouvelle Vente</span>
                <span style="font-size: 12px; font-weight: 700; color: var(--accent); background: var(--accent-glow); border: 1px solid rgba(45, 212, 191, 0.25); padding: 5px 12px; border-radius: 8px;">Terminal POS</span>
            </div>

            <!-- 1. Formulaire d'ajout d'article au panier -->
            <form method="POST" action="?view=pos" style="margin-bottom: 20px;">
                <div class="form-group">
                    <label for="client-select">Client Acheteur</label>
                    <div style="position: relative;">
                        <select name="client_id" id="client-select" form="order-creation-form" class="form-control" style="width: 100%; appearance: none; padding-right: 36px;" required>
                            <option value="">Sélectionner un client...</option>
                            <?php foreach ($clients as $client): ?>
                                <option value="<?= $client->getId() ?>" <?= ($client->getId() == SessionManager::get('selected_client_id', 0)) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($client->getPrenom() . ' ' . $client->getNom()) ?> (<?= htmlspecialchars($client->getTelephone()) ?>) — Limite : <?= number_format($client->getLimiteCredit(), 0, ',', ' ') ?> F
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <span style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); pointer-events: none; color: var(--text-muted); font-size: 14px;">▼</span>
                    </div>
                </div>

                <input type="hidden" name="action" value="add_to_cart">

                <div style="border-top: 1px dashed var(--border-color); padding-top: 20px; margin-top: 12px; margin-bottom: 20px;">
                    <label style="font-size: 13px; font-weight: 800; color: var(--accent); display: block; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px;">
                        Sélection des Articles
                    </label>

                    <div style="display: grid; grid-template-columns: 2.2fr 0.8fr auto; gap: 8px; align-items: flex-end; margin-bottom: 16px;">

                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="pos-item-select" style="font-size: 12px;">Article</label>
                            <select name="produit_id" id="pos-item-select" class="form-control" style="background-color: #0b0f1a; color: white; width: 100%; box-sizing: border-box;" required>
                                <option value="">Choisir un article...</option>
                                <?php foreach ($produits as $produit): ?>
                                    <?php
                                    $stockIcon = $produit->getQteStock() > $produit->getSeuilAlerte() ? '🟢' : ($produit->getQteStock() > 0 ? '🟡' : '🔴');
                                    ?>
                                    <option value="<?= $produit->getId() ?>" <?= $produit->getQteStock() <= 0 ? 'disabled' : '' ?>>
                                        <?= $stockIcon ?> <?= htmlspecialchars($produit->getLibelle()) ?> (Stock: <?= $produit->getQteStock() ?>) — <?= number_format($produit->getPrixVente(), 0, ',', ' ') ?> F
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="pos-qty" style="font-size: 12px;">Qté</label>
                            <input type="number" name="quantite" id="pos-qty" class="form-control" value="1" min="1" style="width: 100%; box-sizing: border-box;" required>
                        </div>

                        <button type="submit" class="btn-submit" style="width: 52px; height: 52px; min-width: 52px; max-width: 52px; font-size: 24px; font-weight: 800; display: flex; justify-content: center; align-items: center; border-radius: 14px; padding: 0; margin: 0; flex-shrink: 0; box-sizing: border-box;" title="Ajouter au panier">+</button>
                    </div>

                    <!-- Tableau du Panier -->
                    <table class="debt-table" style="margin-top: 18px;">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Qté</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody id="cart-rows">
                            <?php if (empty($panier)): ?>
                                <tr>
                                    <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 20px 0; border-bottom: none; font-style: italic;">
                                        Panier vide. Ajoutez des articles.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($panier as $pId => $item): ?>
                                    <tr>
                                        <td style="font-weight: 700;"><?= htmlspecialchars($item['libelle']) ?></td>
                                        <td><?= (int) $item['quantite'] ?></td>
                                        <td style="font-weight: 800; color: var(--accent);">
                                            <?= number_format($item['sous_total'], 0, ',', ' ') ?> F
                                        </td>
                                        <td style="text-align: right;">
                                            <a href="?view=pos&action=remove_from_cart&produit_id=<?= $pId ?>" class="btn-quick-action" style="color: var(--danger); text-decoration: none; padding: 6px 12px; font-size: 14px;" title="Retirer">
                                                ✕
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <?php if (!empty($panier)): ?>
                        <div style="text-align: right; margin-top: 12px;">
                            <a href="?view=pos&action=clear_cart" style="font-size: 12px; color: var(--danger); text-decoration: none; font-weight: 800;">
                                Vider le panier ✕
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </form>

            <!-- Digital Display Panel -->
            <div style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(30, 41, 59, 0.45) 100%); border: 1px solid rgba(59, 130, 246, 0.2); border-radius: 18px; padding: 18px; text-align: center; margin-bottom: 24px; box-shadow: inset 0 0 20px rgba(59, 130, 246, 0.1);">
                <span style="font-size: 12px; color: var(--text-muted); text-transform: uppercase; font-weight: 800; letter-spacing: 1px; display: block; margin-bottom: 6px;">
                    Montant Total Net à Payer
                </span>
                <div style="font-size: 30px; font-weight: 900; color: #60a5fa; letter-spacing: -0.5px; font-family: monospace; text-shadow: 0 0 12px rgba(96, 165, 250, 0.35);">
                    <span id="montant_total_display_text"><?= number_format($totalPanier, 0, ',', ' ') ?></span>
                    <span style="font-size: 16px; font-weight: 700;">FCFA</span>
                </div>
            </div>

            <!-- 2. Formulaire de validation finale de la vente -->
            <form method="POST" action="?view=pos" id="order-creation-form">
                <input type="hidden" name="action" value="create_order">

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="mode_reglement" style="font-size: 12px;">Règlement</label>
                        <select name="mode_reglement" id="mode_reglement" class="form-control" style="background-color: #0b0f1a;">
                            <option value="Wave">Wave</option>
                            <option value="Orange Money">Orange Money</option>
                            <option value="Especes">Espèces</option>
                            <option value="Virement">Virement</option>
                        </select>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="pos-montant-verse" style="font-size: 12px;">Versé (Avance)</label>
                        <input type="number" name="montant_verse" id="pos-montant-verse" class="form-control" value="<?= $totalPanier ?>" min="0" required>
                    </div>
                </div>

                <button type="submit" class="btn-submit btn-success" style="padding: 16px; font-weight: 800; font-size: 15px; width: 100%;" <?= empty($panier) ? 'disabled style="opacity: 0.5; cursor: not-allowed;"' : '' ?>>
                    Valider la Vente (DML)
                </button>
            </form>
        </div>

        <!-- Right side: Registry logs -->
        <div class="panel-card" style="margin-bottom: 0; padding: 32px;">
            <div class="panel-title">Registre Général des Ventes & Commandes</div>
            <table class="debt-table" id="orders-main-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Total Facture</th>
                        <th>Règlement</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($ventesAvecLignes)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 24px 0;">
                                Aucune vente enregistrée pour le moment.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($ventesAvecLignes as $item): ?>
                            <?php
                            $v = $item['vente'];
                            $lignes = $item['lignes'] ?? [];
                            $badgeClass = ($v->getStatut() === 'COMPTANT' || $v->getStatut() === 'PAYEE' || $v->isSoldee())
                                ? 'badge-success'
                                : (($v->getStatut() === 'CREDIT_TOTAL' || $v->isCredit()) ? 'badge-danger' : 'badge-warning');
                            ?>
                            <tr>
                                <td style="font-weight: 700; color: var(--text-muted);">
                                    #CMD-<?= htmlspecialchars((string) ($v->getId())) ?>
                                </td>
                                <td style="font-weight: 700;">
                                    <?= htmlspecialchars($v->getClient() ? $v->getClient()->getNomComplet() : 'Client Comptoir') ?>
                                    <div style="font-size: 12px; color: var(--text-muted); font-weight: normal; margin-top: 2px;">
                                        Tél : <?= htmlspecialchars($v->getClient() ? $v->getClient()->getTelephone() : '-') ?>
                                    </div>
                                </td>
                                <td style="font-weight: 800; color: var(--accent); font-size: 15px;">
                                    <?= number_format($v->getMontantTotal(), 0, ',', ' ') ?> F
                                </td>
                                <td>
                                    <span class="badge <?= $badgeClass ?>">
                                        <?= htmlspecialchars($v->getStatut()) ?>
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn-quick-action" onclick="toggleDetails('order-details-<?= $v->getId() ?>')">Lignes</button>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5" style="padding: 0; border: none;">
                                    <div class="details-drawer" id="order-details-<?= $v->getId() ?>" style="display: none;">
                                        <div style="font-weight: 800; font-size: 13px; color: var(--accent); margin-bottom: 12px;">
                                            Détails Facture (<?= $v->getDateCreation()->format('d/m/Y H:i') ?>) :
                                        </div>
                                        <table class="debt-table">
                                            <thead>
                                                <tr>
                                                    <th>Produit</th>
                                                    <th>Qté</th>
                                                    <th>P.U.</th>
                                                    <th>Sous-total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (empty($lignes)): ?>
                                                    <tr>
                                                        <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 10px 0;">
                                                            Aucun article enregistré pour cette vente.
                                                        </td>
                                                    </tr>
                                                <?php else: ?>
                                                    <?php foreach ($lignes as $lg): ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($lg->getProduit() ? $lg->getProduit()->getLibelle() : 'Article') ?></td>
                                                            <td><?= $lg->getQuantite() ?></td>
                                                            <td><?= number_format($lg->getPrixUnitaire(), 0, ',', ' ') ?> F</td>
                                                            <td style="font-weight: 800; color: var(--accent);">
                                                                <?= number_format($lg->getSousTotal(), 0, ',', ' ') ?> F
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function toggleDetails(panelId) {
        const panel = document.getElementById(panelId);
        if (!panel) return;
        const isVisible = window.getComputedStyle(panel).display !== 'none';
        panel.style.display = isVisible ? 'none' : 'block';
    }
</script>

<?php
require_once dirname(__DIR__) . "/layout/footer.php";
?>