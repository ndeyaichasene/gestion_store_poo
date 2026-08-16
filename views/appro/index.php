 <!-- ================= VIEW: SUPPLIES (APPROVISIONNEMENT) ================= -->
        <div id="view-supplies" class="view-section">
            <!-- Supplies Stats Grid -->
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px;">
                <div class="panel-card" style="padding: 16px; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid var(--accent);">
                    <div>
                        <span style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Coût Total des Entrées</span>
                        <div style="font-size: 18px; font-weight: 800; color: white; margin-top: 4px;">4 520 000 F</div>
                    </div>
                    <span style="font-size: 24px;">📥</span>
                </div>
                <div class="panel-card" style="padding: 16px; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid var(--warning);">
                    <div>
                        <span style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Bons de Réception (BL)</span>
                        <div style="font-size: 18px; font-weight: 800; color: white; margin-top: 4px;">2 BL reçus</div>
                    </div>
                    <span style="font-size: 24px;">📄</span>
                </div>
                <div class="panel-card" style="padding: 16px; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid var(--success);">
                    <div>
                        <span style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Fournisseurs Actifs</span>
                        <div style="font-size: 18px; font-weight: 800; color: white; margin-top: 4px;">3 entreprises</div>
                    </div>
                    <span style="font-size: 24px;">🤝</span>
                </div>
            </div>

            <div style="display: block;">
                <!-- Full width: deliveries table list -->
                <div class="panel-card" style="padding: 20px; margin-bottom: 0;">
                    <div class="panel-title" style="font-size: 15px; margin-bottom: 16px;">Bordereaux de Livraison (Réceptions)</div>
                    <table class="debt-table" id="supplies-main-table" style="font-size: 12px;">
                        <thead>
                            <tr>
                                <th>Réf BL</th>
                                <th>Fournisseur</th>
                                <th>Valeur Lot</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                                                            <tr>
                                    <td style="font-weight: 700; color: var(--text-muted); padding: 8px 0;">BL-SEN-102</td>
                                    <td style="padding: 8px 0;">
                                        Sénégal Import-Export                                        <div style="font-size:10px; color:var(--text-muted);">Tél : 338211010</div>
                                    </td>
                                    <td style="font-weight: 800; color: var(--accent); padding: 8px 0;">190 000 F</td>
                                    <td style="padding: 8px 0;">
                                        <span class="badge badge-warning">
                                            EN COURS                                        </span>
                                    </td>
                                    <td style="padding: 8px 0; display: flex; gap: 6px;">
                                        <button class="btn-quick-action" onclick="toggleDetails('supply-details-4')">Lignes</button>
                                                                                    <button type="button" class="btn-quick-action" style="border-color: var(--success); color: var(--success); background: rgba(52, 211, 153, 0.05);" onclick="toggleDetails('supply-receive-drawer-4')">Réceptionner</button>
                                                                            </td>
                                </tr>
                                <tr>
                                    <td colspan="5" style="padding: 0; border: none;">
                                        <!-- Drawer 1: Supply lines -->
                                        <div class="details-drawer" id="supply-details-4">
                                            <div style="font-weight: 700; font-size: 11px; color: var(--accent); margin-bottom: 6px;">Détails Réception :</div>
                                            <table class="debt-table" style="font-size: 10px;">
                                                <thead>
                                                    <tr>
                                                        <th>Produit</th>
                                                        <th>Qté Livrée</th>
                                                        <th>Coût Unitaire</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                                                                            <tr>
                                                            <td>Paquet de sucre 1kg</td>
                                                            <td>50</td>
                                                            <td>1 000 F</td>
                                                            <td style="font-weight: 700; color: var(--accent);">50 000 F</td>
                                                        </tr>
                                                                                                            <tr>
                                                            <td>Carton de lait</td>
                                                            <td>10</td>
                                                            <td>14 000 F</td>
                                                            <td style="font-weight: 700; color: var(--accent);">140 000 F</td>
                                                        </tr>
                                                                                                    </tbody>
                                            </table>
                                        </div>

                                        <!-- Drawer 2: Confirm Reception Form inline -->
                                                                                    <div class="details-drawer" id="supply-receive-drawer-4" style="border: 1px solid rgba(52, 211, 153, 0.3); background: linear-gradient(180deg, rgba(11, 15, 25, 0.95) 0%, rgba(11, 15, 25, 0.98) 100%); border-radius: 14px; padding: 18px 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.4); max-width: 850px; margin: 12px 0;">
                                                
                                                <!-- Header row -->
                                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; border-bottom: 1px dashed var(--border-color); padding-bottom: 10px;">
                                                    <div style="display: flex; align-items: center; gap: 8px;">
                                                        <span style="font-size: 16px;">📦</span>
                                                        <span style="font-weight: 800; font-size: 13px; color: var(--text-main);">
                                                            Réceptionner le BL — <span style="color: var(--accent);">BL-SEN-102</span>
                                                        </span>
                                                    </div>
                                                    <div style="background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.3); padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 800; color: var(--warning);">
                                                        Fournisseur : Sénégal Import-Export                                                    </div>
                                                </div>

                                                <form method="GET" action="#" onsubmit="event.preventDefault(); alert('Action enregistrée (mode démonstration HTML/CSS)');" action="">
                                                    <input type="hidden" name="action" value="receive_supply">
                                                    <input type="hidden" name="approvisionnement_id" value="4">

                                                    <div style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 16px;">
                                                                                                                    <div style="background: rgba(255,255,255,0.02); border: 1px solid var(--border-color); border-radius: 10px; padding: 10px 14px; display: flex; justify-content: space-between; align-items: center;">
                                                                <div>
                                                                    <div style="font-weight: 700; font-size: 13px; color: white;">Paquet de sucre 1kg</div>
                                                                    <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">
                                                                        Quantité théorique commandée : <strong style="color: var(--text-main);">50</strong>
                                                                    </div>
                                                                </div>
                                                                <div style="display: flex; align-items: center; gap: 10px;">
                                                                    <label style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Qté Reçue :</label>
                                                                    <input type="number" name="quantites_livrees[4]" class="form-control" value="50" min="0" required style="width: 100px; padding: 6px 10px; font-size: 13px; font-weight: 700; text-align: center; background: #0b0f1a;">
                                                                </div>
                                                            </div>
                                                                                                                    <div style="background: rgba(255,255,255,0.02); border: 1px solid var(--border-color); border-radius: 10px; padding: 10px 14px; display: flex; justify-content: space-between; align-items: center;">
                                                                <div>
                                                                    <div style="font-weight: 700; font-size: 13px; color: white;">Carton de lait</div>
                                                                    <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">
                                                                        Quantité théorique commandée : <strong style="color: var(--text-main);">10</strong>
                                                                    </div>
                                                                </div>
                                                                <div style="display: flex; align-items: center; gap: 10px;">
                                                                    <label style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Qté Reçue :</label>
                                                                    <input type="number" name="quantites_livrees[5]" class="form-control" value="10" min="0" required style="width: 100px; padding: 6px 10px; font-size: 13px; font-weight: 700; text-align: center; background: #0b0f1a;">
                                                                </div>
                                                            </div>
                                                                                                            </div>

                                                    <div style="display: flex; justify-content: flex-end;">
                                                        <button type="submit" class="btn-submit btn-success" style="padding: 11px 24px; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 8px; border-radius: 10px;">
                                                            ✓ Valider la Réception en Stock
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                                                            </td>
                                </tr>
                                                            <tr>
                                    <td style="font-weight: 700; color: var(--text-muted); padding: 8px 0;">BL-CCS-101</td>
                                    <td style="padding: 8px 0;">
                                        Comptoir Céréalier Sénégalais                                        <div style="font-size:10px; color:var(--text-muted);">Tél : 338245678</div>
                                    </td>
                                    <td style="font-weight: 800; color: var(--accent); padding: 8px 0;">525 000 F</td>
                                    <td style="padding: 8px 0;">
                                        <span class="badge badge-warning">
                                            EN COURS                                        </span>
                                    </td>
                                    <td style="padding: 8px 0; display: flex; gap: 6px;">
                                        <button class="btn-quick-action" onclick="toggleDetails('supply-details-3')">Lignes</button>
                                                                                    <button type="button" class="btn-quick-action" style="border-color: var(--success); color: var(--success); background: rgba(52, 211, 153, 0.05);" onclick="toggleDetails('supply-receive-drawer-3')">Réceptionner</button>
                                                                            </td>
                                </tr>
                                <tr>
                                    <td colspan="5" style="padding: 0; border: none;">
                                        <!-- Drawer 1: Supply lines -->
                                        <div class="details-drawer" id="supply-details-3">
                                            <div style="font-weight: 700; font-size: 11px; color: var(--accent); margin-bottom: 6px;">Détails Réception :</div>
                                            <table class="debt-table" style="font-size: 10px;">
                                                <thead>
                                                    <tr>
                                                        <th>Produit</th>
                                                        <th>Qté Livrée</th>
                                                        <th>Coût Unitaire</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                                                                            <tr>
                                                            <td>Sac de riz 50kg</td>
                                                            <td>25</td>
                                                            <td>21 000 F</td>
                                                            <td style="font-weight: 700; color: var(--accent);">525 000 F</td>
                                                        </tr>
                                                                                                    </tbody>
                                            </table>
                                        </div>

                                        <!-- Drawer 2: Confirm Reception Form inline -->
                                                                                    <div class="details-drawer" id="supply-receive-drawer-3" style="border: 1px solid rgba(52, 211, 153, 0.3); background: linear-gradient(180deg, rgba(11, 15, 25, 0.95) 0%, rgba(11, 15, 25, 0.98) 100%); border-radius: 14px; padding: 18px 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.4); max-width: 850px; margin: 12px 0;">
                                                
                                                <!-- Header row -->
                                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; border-bottom: 1px dashed var(--border-color); padding-bottom: 10px;">
                                                    <div style="display: flex; align-items: center; gap: 8px;">
                                                        <span style="font-size: 16px;">📦</span>
                                                        <span style="font-weight: 800; font-size: 13px; color: var(--text-main);">
                                                            Réceptionner le BL — <span style="color: var(--accent);">BL-CCS-101</span>
                                                        </span>
                                                    </div>
                                                    <div style="background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.3); padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 800; color: var(--warning);">
                                                        Fournisseur : Comptoir Céréalier Sénégalais                                                    </div>
                                                </div>

                                                <form method="GET" action="#" onsubmit="event.preventDefault(); alert('Action enregistrée (mode démonstration HTML/CSS)');" action="">
                                                    <input type="hidden" name="action" value="receive_supply">
                                                    <input type="hidden" name="approvisionnement_id" value="3">

                                                    <div style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 16px;">
                                                                                                                    <div style="background: rgba(255,255,255,0.02); border: 1px solid var(--border-color); border-radius: 10px; padding: 10px 14px; display: flex; justify-content: space-between; align-items: center;">
                                                                <div>
                                                                    <div style="font-weight: 700; font-size: 13px; color: white;">Sac de riz 50kg</div>
                                                                    <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">
                                                                        Quantité théorique commandée : <strong style="color: var(--text-main);">25</strong>
                                                                    </div>
                                                                </div>
                                                                <div style="display: flex; align-items: center; gap: 10px;">
                                                                    <label style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Qté Reçue :</label>
                                                                    <input type="number" name="quantites_livrees[1]" class="form-control" value="25" min="0" required style="width: 100px; padding: 6px 10px; font-size: 13px; font-weight: 700; text-align: center; background: #0b0f1a;">
                                                                </div>
                                                            </div>
                                                                                                            </div>

                                                    <div style="display: flex; justify-content: flex-end;">
                                                        <button type="submit" class="btn-submit btn-success" style="padding: 11px 24px; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 8px; border-radius: 10px;">
                                                            ✓ Valider la Réception en Stock
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                                                            </td>
                                </tr>
                                                            <tr>
                                    <td style="font-weight: 700; color: var(--text-muted); padding: 8px 0;">BL-DIP-099</td>
                                    <td style="padding: 8px 0;">
                                        Grossiste Diop &amp; Frères                                        <div style="font-size:10px; color:var(--text-muted);">Tél : 773456789</div>
                                    </td>
                                    <td style="font-weight: 800; color: var(--accent); padding: 8px 0;">320 000 F</td>
                                    <td style="padding: 8px 0;">
                                        <span class="badge badge-success">
                                            REÇU                                        </span>
                                    </td>
                                    <td style="padding: 8px 0; display: flex; gap: 6px;">
                                        <button class="btn-quick-action" onclick="toggleDetails('supply-details-2')">Lignes</button>
                                                                            </td>
                                </tr>
                                <tr>
                                    <td colspan="5" style="padding: 0; border: none;">
                                        <!-- Drawer 1: Supply lines -->
                                        <div class="details-drawer" id="supply-details-2">
                                            <div style="font-weight: 700; font-size: 11px; color: var(--accent); margin-bottom: 6px;">Détails Réception :</div>
                                            <table class="debt-table" style="font-size: 10px;">
                                                <thead>
                                                    <tr>
                                                        <th>Produit</th>
                                                        <th>Qté Livrée</th>
                                                        <th>Coût Unitaire</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                                                                            <tr>
                                                            <td>Bidon d&#039;huile 5L</td>
                                                            <td>20</td>
                                                            <td>7 000 F</td>
                                                            <td style="font-weight: 700; color: var(--accent);">140 000 F</td>
                                                        </tr>
                                                                                                            <tr>
                                                            <td>Carton de savon</td>
                                                            <td>15</td>
                                                            <td>12 000 F</td>
                                                            <td style="font-weight: 700; color: var(--accent);">180 000 F</td>
                                                        </tr>
                                                                                                    </tbody>
                                            </table>
                                        </div>

                                        <!-- Drawer 2: Confirm Reception Form inline -->
                                                                            </td>
                                </tr>
                                                            <tr>
                                    <td style="font-weight: 700; color: var(--text-muted); padding: 8px 0;">BL-CCS-098</td>
                                    <td style="padding: 8px 0;">
                                        Comptoir Céréalier Sénégalais                                        <div style="font-size:10px; color:var(--text-muted);">Tél : 338245678</div>
                                    </td>
                                    <td style="font-weight: 800; color: var(--accent); padding: 8px 0;">4 200 000 F</td>
                                    <td style="padding: 8px 0;">
                                        <span class="badge badge-success">
                                            REÇU                                        </span>
                                    </td>
                                    <td style="padding: 8px 0; display: flex; gap: 6px;">
                                        <button class="btn-quick-action" onclick="toggleDetails('supply-details-1')">Lignes</button>
                                                                            </td>
                                </tr>
                                <tr>
                                    <td colspan="5" style="padding: 0; border: none;">
                                        <!-- Drawer 1: Supply lines -->
                                        <div class="details-drawer" id="supply-details-1">
                                            <div style="font-weight: 700; font-size: 11px; color: var(--accent); margin-bottom: 6px;">Détails Réception :</div>
                                            <table class="debt-table" style="font-size: 10px;">
                                                <thead>
                                                    <tr>
                                                        <th>Produit</th>
                                                        <th>Qté Livrée</th>
                                                        <th>Coût Unitaire</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                                                                            <tr>
                                                            <td>Sac de riz 50kg</td>
                                                            <td>200</td>
                                                            <td>21 000 F</td>
                                                            <td style="font-weight: 700; color: var(--accent);">4 200 000 F</td>
                                                        </tr>
                                                                                                    </tbody>
                                            </table>
                                        </div>

                                        <!-- Drawer 2: Confirm Reception Form inline -->
                                                                            </td>
                                </tr>
                                                    </tbody>
                    </table>
                </div>
            </div>
        </div>