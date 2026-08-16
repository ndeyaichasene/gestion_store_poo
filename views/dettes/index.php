 <!-- ================= VIEW: DEBTS (GESTION DETTES) ================= -->
        <div id="view-dettes" class="view-section">
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

            <div style="display: block;">
                <!-- Full width: Debt registry logs -->
                <div class="panel-card" style="margin-bottom: 0;">
                    <div class="panel-title">
                        <span>Registre des Dettes Actives</span>
                        <input type="text" id="debt-search" class="search-control" placeholder="Rechercher un client..." onkeyup="filterDebtsTable()">
                    </div>
                    <table class="debt-table" id="debts-main-table">
                        <thead>
                            <tr>
                                <th>ID Dette</th>
                                <th>Date Création</th>
                                <th>Client</th>
                                <th>Montant Initial</th>
                                <th>Montant Payé</th>
                                <th>Reste Dû</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                                                            <tr id="debt-row-3" data-client-name="maimouna diallo 701122334" style="transition: all 0.2s;">
                                    <td style="font-weight: 700; color: var(--text-muted);">
                                        #DT-3                                                                                    <span style="font-size: 10px; color: var(--text-muted); display: block; font-weight: normal; margin-top: 2px;">#CMD-4</span>
                                                                            </td>
                                    <td style="font-size: 12px;">07 Aug 2026 23:48</td>
                                    <td style="font-weight: 700;">
                                        Maimouna Diallo                                        <div style="font-size:11px; color:var(--text-muted); font-weight:normal;">Tél : 701122334</div>
                                    </td>
                                    <td style="font-weight: 700; color: var(--text-main);">15 000 F</td>
                                    <td style="font-weight: 700; color: var(--success);">0 F</td>
                                    <td style="color: var(--danger); font-weight: 800;">15 000 F</td>
                                    <td>
                                        <span class="badge badge-danger">
                                            NON SOLDEE                                        </span>
                                    </td>
                                    <td style="display: flex; gap: 6px;">
                                        <button class="btn-quick-action" onclick="toggleDetails('debt-lines-3')">Articles</button>
                                        <button class="btn-quick-action" style="border-color: var(--accent); color: var(--accent);" onclick="toggleDetails('debt-details-3')">💳 Paiements</button>
                                                                                    <button class="btn-quick-action" style="border-color: var(--warning); color: var(--warning);" onclick="toggleDetails('debt-repay-drawer-3')">Rembourser</button>
                                                                            </td>
                                </tr>
                                <tr>
                                    <td colspan="8" style="padding: 0; border: none;">
                                        <!-- Drawer 1: Payments list -->
                                        <div class="details-drawer" id="debt-details-3">
                                            <div style="font-weight: 700; font-size: 12px; color: var(--accent); margin-bottom: 8px;">Paiements enregistrés :</div>
                                            <table class="debt-table" style="font-size: 11px;">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Versement</th>
                                                        <th>Mode</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                                                                            <tr><td colspan="3" style="text-align: center; color: var(--text-muted);">Aucun acompte versé.</td></tr>
                                                                                                    </tbody>
                                            </table>
                                        </div>

                                        <!-- Drawer 2: Product lines -->
                                        <div class="details-drawer" id="debt-lines-3">
                                            <div style="font-weight: 700; font-size: 12px; color: var(--accent); margin-bottom: 8px;">Articles de la Vente à Crédit :</div>
                                            <table class="debt-table" style="font-size: 11px;">
                                                <thead>
                                                    <tr>
                                                        <th>Produit</th>
                                                        <th>Qté</th>
                                                        <th>P.U.</th>
                                                        <th>Sous-total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                                                                                <tr>
                                                                <td>Paquet de sucre 1kg</td>
                                                                <td>10</td>
                                                                <td>1 500 F</td>
                                                                <td style="font-weight: 700; color: var(--accent);">15 000 F</td>
                                                            </tr>
                                                                                                        </tbody>
                                            </table>
                                        </div>

                                        <!-- Drawer 3: Remboursement form -->
                                                                                      <div class="details-drawer" id="debt-repay-drawer-3" style="border: 1px solid rgba(45, 212, 191, 0.25); background: linear-gradient(180deg, rgba(11, 15, 25, 0.95) 0%, rgba(11, 15, 25, 0.98) 100%); border-radius: 14px; padding: 18px 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.4); max-width: 850px; margin: 12px 0;">
                                                 
                                                 <!-- Header row with title and badge -->
                                                 <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; border-bottom: 1px dashed var(--border-color); padding-bottom: 10px;">
                                                     <div style="display: flex; align-items: center; gap: 8px;">
                                                         <span style="font-size: 16px;">💳</span>
                                                         <span style="font-weight: 800; font-size: 13px; color: var(--text-main);">
                                                             Nouveau Remboursement — <span style="color: var(--accent);">Maimouna Diallo</span>
                                                         </span>
                                                     </div>
                                                     <div style="background: rgba(244, 63, 94, 0.12); border: 1px solid rgba(244, 63, 94, 0.3); padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 800; color: var(--danger);">
                                                         Reste dû : 15 000 FCFA
                                                     </div>
                                                 </div>

                                                 <!-- Quick preset amount chips -->
                                                 <div style="display: flex; gap: 8px; align-items: center; margin-bottom: 16px;">
                                                     <span style="font-size: 10px; text-transform: uppercase; color: var(--text-muted); font-weight: 700;">Raccourcis :</span>
                                                     <button type="button" onclick="setRepayAmount(3, 15000)" style="background: rgba(45, 212, 191, 0.1); border: 1px solid var(--accent); color: var(--accent); font-size: 10px; font-weight: 700; padding: 4px 10px; border-radius: 6px; cursor: pointer;">Tout solder (15 000 F)</button>
                                                     <button type="button" onclick="setRepayAmount(3, 7500)" style="background: rgba(255, 255, 255, 0.04); border: 1px solid var(--border-color); color: var(--text-main); font-size: 10px; font-weight: 700; padding: 4px 10px; border-radius: 6px; cursor: pointer;">50% (7 500 F)</button>
                                                 </div>

                                                 <!-- Form fields grid -->
                                                 <form method="GET" action="#" onsubmit="event.preventDefault(); alert('Action enregistrée (mode démonstration HTML/CSS)');" action="" style="display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap;">
                                                     <input type="hidden" name="action" value="add_payment">
                                                     <input type="hidden" name="dette_id" value="3">

                                                     <div style="flex: 1; min-width: 200px;">
                                                         <label style="font-size: 10px; color: var(--text-muted); display: block; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700;">Montant du Versement (FCFA)</label>
                                                         <div style="position: relative;">
                                                             <input type="number" name="montant_verse" id="repay-input-3" class="form-control" max="15000" value="15000" min="1" required style="font-size: 13px; font-weight: 700; padding: 10px 12px; background: #0b0f19; border: 1px solid var(--border-color); color: white; width: 100%;">
                                                         </div>
                                                     </div>

                                                     <div style="flex: 1; min-width: 200px;">
                                                         <label style="font-size: 10px; color: var(--text-muted); display: block; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700;">Canal de Paiement</label>
                                                         <select name="mode_paiement" class="form-control" style="font-size: 13px; font-weight: 600; padding: 10px 12px; background: #0b0f19; border: 1px solid var(--border-color); color: white; width: 100%;" required>
                                                             <option value="Orange Money">🟠 Orange Money</option>
                                                             <option value="Wave">🌊 Wave</option>
                                                             <option value="Especes">💵 Espèces (Cash)</option>
                                                             <option value="Virement">🏦 Virement Bceao</option>
                                                         </select>
                                                     </div>

                                                     <div>
                                                         <button type="submit" class="btn-submit btn-success" style="padding: 11px 24px; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 8px; border-radius: 10px; height: 42px;">
                                                             ✓ Enregistrer le Remboursement
                                                         </button>
                                                     </div>
                                                 </form>
                                             </div>
                                                                             </td>
                                </tr>
                                                            <tr id="debt-row-2" data-client-name="moussa sarr 769876543" style="transition: all 0.2s;">
                                    <td style="font-weight: 700; color: var(--text-muted);">
                                        #DT-2                                                                                    <span style="font-size: 10px; color: var(--text-muted); display: block; font-weight: normal; margin-top: 2px;">#CMD-3</span>
                                                                            </td>
                                    <td style="font-size: 12px;">07 Aug 2026 22:48</td>
                                    <td style="font-weight: 700;">
                                        Moussa Sarr                                        <div style="font-size:11px; color:var(--text-muted); font-weight:normal;">Tél : 769876543</div>
                                    </td>
                                    <td style="font-weight: 700; color: var(--text-main);">74 000 F</td>
                                    <td style="font-weight: 700; color: var(--success);">24 000 F</td>
                                    <td style="color: var(--danger); font-weight: 800;">50 000 F</td>
                                    <td>
                                        <span class="badge badge-danger">
                                            NON SOLDEE                                        </span>
                                    </td>
                                    <td style="display: flex; gap: 6px;">
                                        <button class="btn-quick-action" onclick="toggleDetails('debt-lines-2')">Articles</button>
                                        <button class="btn-quick-action" style="border-color: var(--accent); color: var(--accent);" onclick="toggleDetails('debt-details-2')">💳 Paiements</button>
                                                                                    <button class="btn-quick-action" style="border-color: var(--warning); color: var(--warning);" onclick="toggleDetails('debt-repay-drawer-2')">Rembourser</button>
                                                                            </td>
                                </tr>
                                <tr>
                                    <td colspan="8" style="padding: 0; border: none;">
                                        <!-- Drawer 1: Payments list -->
                                        <div class="details-drawer" id="debt-details-2">
                                            <div style="font-weight: 700; font-size: 12px; color: var(--accent); margin-bottom: 8px;">Paiements enregistrés :</div>
                                            <table class="debt-table" style="font-size: 11px;">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Versement</th>
                                                        <th>Mode</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                                                                            <tr>
                                                            <td>2026-08-07 22:48:53</td>
                                                            <td style="font-weight: 700; color: var(--success);">24 000 F</td>
                                                            <td>Wave</td>
                                                        </tr>
                                                                                                    </tbody>
                                            </table>
                                        </div>

                                        <!-- Drawer 2: Product lines -->
                                        <div class="details-drawer" id="debt-lines-2">
                                            <div style="font-weight: 700; font-size: 12px; color: var(--accent); margin-bottom: 8px;">Articles de la Vente à Crédit :</div>
                                            <table class="debt-table" style="font-size: 11px;">
                                                <thead>
                                                    <tr>
                                                        <th>Produit</th>
                                                        <th>Qté</th>
                                                        <th>P.U.</th>
                                                        <th>Sous-total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                                                                                <tr>
                                                                <td>Sac de riz 50kg</td>
                                                                <td>2</td>
                                                                <td>25 000 F</td>
                                                                <td style="font-weight: 700; color: var(--accent);">50 000 F</td>
                                                            </tr>
                                                                                                                    <tr>
                                                                <td>Carton de savon</td>
                                                                <td>2</td>
                                                                <td>12 000 F</td>
                                                                <td style="font-weight: 700; color: var(--accent);">24 000 F</td>
                                                            </tr>
                                                                                                        </tbody>
                                            </table>
                                        </div>

                                        <!-- Drawer 3: Remboursement form -->
                                                                                      <div class="details-drawer" id="debt-repay-drawer-2" style="border: 1px solid rgba(45, 212, 191, 0.25); background: linear-gradient(180deg, rgba(11, 15, 25, 0.95) 0%, rgba(11, 15, 25, 0.98) 100%); border-radius: 14px; padding: 18px 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.4); max-width: 850px; margin: 12px 0;">
                                                 
                                                 <!-- Header row with title and badge -->
                                                 <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; border-bottom: 1px dashed var(--border-color); padding-bottom: 10px;">
                                                     <div style="display: flex; align-items: center; gap: 8px;">
                                                         <span style="font-size: 16px;">💳</span>
                                                         <span style="font-weight: 800; font-size: 13px; color: var(--text-main);">
                                                             Nouveau Remboursement — <span style="color: var(--accent);">Moussa Sarr</span>
                                                         </span>
                                                     </div>
                                                     <div style="background: rgba(244, 63, 94, 0.12); border: 1px solid rgba(244, 63, 94, 0.3); padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 800; color: var(--danger);">
                                                         Reste dû : 50 000 FCFA
                                                     </div>
                                                 </div>

                                                 <!-- Quick preset amount chips -->
                                                 <div style="display: flex; gap: 8px; align-items: center; margin-bottom: 16px;">
                                                     <span style="font-size: 10px; text-transform: uppercase; color: var(--text-muted); font-weight: 700;">Raccourcis :</span>
                                                     <button type="button" onclick="setRepayAmount(2, 50000)" style="background: rgba(45, 212, 191, 0.1); border: 1px solid var(--accent); color: var(--accent); font-size: 10px; font-weight: 700; padding: 4px 10px; border-radius: 6px; cursor: pointer;">Tout solder (50 000 F)</button>
                                                     <button type="button" onclick="setRepayAmount(2, 25000)" style="background: rgba(255, 255, 255, 0.04); border: 1px solid var(--border-color); color: var(--text-main); font-size: 10px; font-weight: 700; padding: 4px 10px; border-radius: 6px; cursor: pointer;">50% (25 000 F)</button>
                                                 </div>

                                                 <!-- Form fields grid -->
                                                 <form method="GET" action="#" onsubmit="event.preventDefault(); alert('Action enregistrée (mode démonstration HTML/CSS)');" action="" style="display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap;">
                                                     <input type="hidden" name="action" value="add_payment">
                                                     <input type="hidden" name="dette_id" value="2">

                                                     <div style="flex: 1; min-width: 200px;">
                                                         <label style="font-size: 10px; color: var(--text-muted); display: block; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700;">Montant du Versement (FCFA)</label>
                                                         <div style="position: relative;">
                                                             <input type="number" name="montant_verse" id="repay-input-2" class="form-control" max="50000" value="50000" min="1" required style="font-size: 13px; font-weight: 700; padding: 10px 12px; background: #0b0f19; border: 1px solid var(--border-color); color: white; width: 100%;">
                                                         </div>
                                                     </div>

                                                     <div style="flex: 1; min-width: 200px;">
                                                         <label style="font-size: 10px; color: var(--text-muted); display: block; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700;">Canal de Paiement</label>
                                                         <select name="mode_paiement" class="form-control" style="font-size: 13px; font-weight: 600; padding: 10px 12px; background: #0b0f19; border: 1px solid var(--border-color); color: white; width: 100%;" required>
                                                             <option value="Orange Money">🟠 Orange Money</option>
                                                             <option value="Wave">🌊 Wave</option>
                                                             <option value="Especes">💵 Espèces (Cash)</option>
                                                             <option value="Virement">🏦 Virement Bceao</option>
                                                         </select>
                                                     </div>

                                                     <div>
                                                         <button type="submit" class="btn-submit btn-success" style="padding: 11px 24px; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 8px; border-radius: 10px; height: 42px;">
                                                             ✓ Enregistrer le Remboursement
                                                         </button>
                                                     </div>
                                                 </form>
                                             </div>
                                                                             </td>
                                </tr>
                                                            <tr id="debt-row-1" data-client-name="fama diouf 781234567" style="transition: all 0.2s;">
                                    <td style="font-weight: 700; color: var(--text-muted);">
                                        #DT-1                                                                                    <span style="font-size: 10px; color: var(--text-muted); display: block; font-weight: normal; margin-top: 2px;">#CMD-2</span>
                                                                            </td>
                                    <td style="font-size: 12px;">07 Aug 2026 21:48</td>
                                    <td style="font-weight: 700;">
                                        Fama Diouf                                        <div style="font-size:11px; color:var(--text-muted); font-weight:normal;">Tél : 781234567</div>
                                    </td>
                                    <td style="font-weight: 700; color: var(--text-main);">44 000 F</td>
                                    <td style="font-weight: 700; color: var(--success);">10 000 F</td>
                                    <td style="color: var(--danger); font-weight: 800;">34 000 F</td>
                                    <td>
                                        <span class="badge badge-danger">
                                            NON SOLDEE                                        </span>
                                    </td>
                                    <td style="display: flex; gap: 6px;">
                                        <button class="btn-quick-action" onclick="toggleDetails('debt-lines-1')">Articles</button>
                                        <button class="btn-quick-action" style="border-color: var(--accent); color: var(--accent);" onclick="toggleDetails('debt-details-1')">💳 Paiements</button>
                                                                                    <button class="btn-quick-action" style="border-color: var(--warning); color: var(--warning);" onclick="toggleDetails('debt-repay-drawer-1')">Rembourser</button>
                                                                            </td>
                                </tr>
                                <tr>
                                    <td colspan="8" style="padding: 0; border: none;">
                                        <!-- Drawer 1: Payments list -->
                                        <div class="details-drawer" id="debt-details-1">
                                            <div style="font-weight: 700; font-size: 12px; color: var(--accent); margin-bottom: 8px;">Paiements enregistrés :</div>
                                            <table class="debt-table" style="font-size: 11px;">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Versement</th>
                                                        <th>Mode</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                                                                            <tr>
                                                            <td>2026-08-07 21:48:53</td>
                                                            <td style="font-weight: 700; color: var(--success);">10 000 F</td>
                                                            <td>Orange Money</td>
                                                        </tr>
                                                                                                    </tbody>
                                            </table>
                                        </div>

                                        <!-- Drawer 2: Product lines -->
                                        <div class="details-drawer" id="debt-lines-1">
                                            <div style="font-weight: 700; font-size: 12px; color: var(--accent); margin-bottom: 8px;">Articles de la Vente à Crédit :</div>
                                            <table class="debt-table" style="font-size: 11px;">
                                                <thead>
                                                    <tr>
                                                        <th>Produit</th>
                                                        <th>Qté</th>
                                                        <th>P.U.</th>
                                                        <th>Sous-total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                                                                                <tr>
                                                                <td>Bidon d&#039;huile 5L</td>
                                                                <td>3</td>
                                                                <td>8 000 F</td>
                                                                <td style="font-weight: 700; color: var(--accent);">24 000 F</td>
                                                            </tr>
                                                                                                                    <tr>
                                                                <td>Paquet de sucre 1kg</td>
                                                                <td>13</td>
                                                                <td>1 500 F</td>
                                                                <td style="font-weight: 700; color: var(--accent);">19 500 F</td>
                                                            </tr>
                                                                                                        </tbody>
                                            </table>
                                        </div>

                                        <!-- Drawer 3: Remboursement form -->
                                                                                      <div class="details-drawer" id="debt-repay-drawer-1" style="border: 1px solid rgba(45, 212, 191, 0.25); background: linear-gradient(180deg, rgba(11, 15, 25, 0.95) 0%, rgba(11, 15, 25, 0.98) 100%); border-radius: 14px; padding: 18px 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.4); max-width: 850px; margin: 12px 0;">
                                                 
                                                 <!-- Header row with title and badge -->
                                                 <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; border-bottom: 1px dashed var(--border-color); padding-bottom: 10px;">
                                                     <div style="display: flex; align-items: center; gap: 8px;">
                                                         <span style="font-size: 16px;">💳</span>
                                                         <span style="font-weight: 800; font-size: 13px; color: var(--text-main);">
                                                             Nouveau Remboursement — <span style="color: var(--accent);">Fama Diouf</span>
                                                         </span>
                                                     </div>
                                                     <div style="background: rgba(244, 63, 94, 0.12); border: 1px solid rgba(244, 63, 94, 0.3); padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 800; color: var(--danger);">
                                                         Reste dû : 34 000 FCFA
                                                     </div>
                                                 </div>

                                                 <!-- Quick preset amount chips -->
                                                 <div style="display: flex; gap: 8px; align-items: center; margin-bottom: 16px;">
                                                     <span style="font-size: 10px; text-transform: uppercase; color: var(--text-muted); font-weight: 700;">Raccourcis :</span>
                                                     <button type="button" onclick="setRepayAmount(1, 34000)" style="background: rgba(45, 212, 191, 0.1); border: 1px solid var(--accent); color: var(--accent); font-size: 10px; font-weight: 700; padding: 4px 10px; border-radius: 6px; cursor: pointer;">Tout solder (34 000 F)</button>
                                                     <button type="button" onclick="setRepayAmount(1, 17000)" style="background: rgba(255, 255, 255, 0.04); border: 1px solid var(--border-color); color: var(--text-main); font-size: 10px; font-weight: 700; padding: 4px 10px; border-radius: 6px; cursor: pointer;">50% (17 000 F)</button>
                                                 </div>

                                                 <!-- Form fields grid -->
                                                 <form method="GET" action="#" onsubmit="event.preventDefault(); alert('Action enregistrée (mode démonstration HTML/CSS)');" action="" style="display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap;">
                                                     <input type="hidden" name="action" value="add_payment">
                                                     <input type="hidden" name="dette_id" value="1">

                                                     <div style="flex: 1; min-width: 200px;">
                                                         <label style="font-size: 10px; color: var(--text-muted); display: block; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700;">Montant du Versement (FCFA)</label>
                                                         <div style="position: relative;">
                                                             <input type="number" name="montant_verse" id="repay-input-1" class="form-control" max="34000" value="34000" min="1" required style="font-size: 13px; font-weight: 700; padding: 10px 12px; background: #0b0f19; border: 1px solid var(--border-color); color: white; width: 100%;">
                                                         </div>
                                                     </div>

                                                     <div style="flex: 1; min-width: 200px;">
                                                         <label style="font-size: 10px; color: var(--text-muted); display: block; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700;">Canal de Paiement</label>
                                                         <select name="mode_paiement" class="form-control" style="font-size: 13px; font-weight: 600; padding: 10px 12px; background: #0b0f19; border: 1px solid var(--border-color); color: white; width: 100%;" required>
                                                             <option value="Orange Money">🟠 Orange Money</option>
                                                             <option value="Wave">🌊 Wave</option>
                                                             <option value="Especes">💵 Espèces (Cash)</option>
                                                             <option value="Virement">🏦 Virement Bceao</option>
                                                         </select>
                                                     </div>

                                                     <div>
                                                         <button type="submit" class="btn-submit btn-success" style="padding: 11px 24px; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 8px; border-radius: 10px; height: 42px;">
                                                             ✓ Enregistrer le Remboursement
                                                         </button>
                                                     </div>
                                                 </form>
                                             </div>
                                                                             </td>
                                </tr>
                                                    </tbody>
                    </table>
                </div>
            </div>
        </div>