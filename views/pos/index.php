  <!-- ================= VIEW: POS (SALES CONSOLE) ================= -->
        <div id="view-pos" class="view-section">
            <!-- POS Stats Grid -->
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px;">
                <div class="panel-card" style="padding: 16px; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid var(--success);">
                    <div>
                        <span style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">CA Encaissé Net</span>
                        <div style="font-size: 18px; font-weight: 800; color: white; margin-top: 4px;">92 000 F</div>
                    </div>
                    <span style="font-size: 24px;">💰</span>
                </div>
                <div class="panel-card" style="padding: 16px; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid var(--danger);">
                    <div>
                        <span style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Encours Client Total</span>
                        <div style="font-size: 18px; font-weight: 800; color: white; margin-top: 4px;">99 000 F</div>
                    </div>
                    <span style="font-size: 24px;">🛑</span>
                </div>
                <div class="panel-card" style="padding: 16px; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid var(--accent);">
                    <div>
                        <span style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Commandes Enregistrées</span>
                        <div style="font-size: 18px; font-weight: 800; color: white; margin-top: 4px;">4 ventes</div>
                    </div>
                    <span style="font-size: 24px;">📊</span>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 600px 1fr; gap: 32px; align-items: start; margin-bottom: 32px;">
                <!-- Left panel: POS ticket creator (sticky) -->
                <div class="panel-card" style="margin-bottom: 0; padding: 24px; border: 1px solid rgba(59, 130, 246, 0.2); background: linear-gradient(180deg, rgba(17, 24, 43, 0.5) 0%, rgba(10, 15, 30, 0.3) 100%); position: sticky; top: 24px;">
                    <div class="panel-title" style="border-left-color: var(--accent); display: flex; justify-content: space-between; align-items: center;">
                        <span>🛒 Nouvelle Vente</span>
                        <span style="font-size: 11px; font-weight: 600; color: var(--text-muted); background: rgba(255,255,255,0.03); padding: 4px 8px; border-radius: 6px;">Terminal POS</span>
                    </div>
                    <form method="GET" action="#" onsubmit="event.preventDefault(); alert('Action enregistrée (mode démonstration HTML/CSS)');" action="" id="order-creation-form">
                        <input type="hidden" name="action" value="create_order">
                        
                        <div class="form-group">
                            <label for="client_id">Client Acheteur</label>
                            <div style="position: relative;">
                                <select name="client_id" id="client-select" class="form-control" style="width: 100%; appearance: none; padding-right: 30px;" onchange="updateClientLimitInfo()">
                                                                            <option value="6" data-limit="300000">
                                            Cisse Awa (783332211)                                        </option>
                                                                            <option value="4" data-limit="120000">
                                            Diallo Maimouna (701122334)                                        </option>
                                                                            <option value="2" data-limit="200000">
                                            Diouf Fama (781234567)                                        </option>
                                                                            <option value="10" data-limit="250000">
                                            Fall Fatou (789998877)                                        </option>
                                                                            <option value="7" data-limit="150000">
                                            Faye Babacar (762221100)                                        </option>
                                                                            <option value="9" data-limit="100000">
                                            Gueye Ibrahima (778887766)                                        </option>
                                                                            <option value="8" data-limit="400000">
                                            Mbacke Khady (704443322)                                        </option>
                                                                            <option value="1" data-limit="150000">
                                            Ndiaye Abdou (776543210)                                        </option>
                                                                            <option value="3" data-limit="250000">
                                            Sarr Moussa (769876543)                                        </option>
                                                                            <option value="5" data-limit="180000">
                                            Sow Ousmane (775554433)                                        </option>
                                                                    </select>
                                <span style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); pointer-events: none; color: var(--text-muted); font-size: 12px;">▼</span>
                            </div>
                            <span id="credit-limit-info" style="font-size:11px; color:var(--text-muted); font-weight:600; margin-top:4px; display:block;"></span>
                        </div>

                        <!-- Articles Dynamic add -->
                        <div style="border-top: 1px dashed var(--border-color); padding-top: 16px; margin-top: 16px; margin-bottom: 16px;">
                            <label style="font-size: 12px; font-weight: 700; color: var(--accent); display: block; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">Sélection des Articles</label>
                            <div style="display: grid; grid-template-columns: 2.2fr 0.8fr auto; gap: 8px; align-items: flex-end; margin-bottom: 16px;">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label for="pos-item-select" style="font-size: 10px;">Article</label>
                                    <select id="pos-item-select" class="form-control" style="background-color: #0b0f1a; color: white; padding: 10px; font-size: 12px;">
                                                                                    <option value="2" data-price="8000" data-name="Bidon d&#039;huile 5L" data-stock="5">
                                                🟡 Bidon d&#039;huile 5L (5)                                            </option>
                                                                                    <option value="5" data-price="15000" data-name="Carton de lait" data-stock="40">
                                                🟢 Carton de lait (40)                                            </option>
                                                                                    <option value="3" data-price="12000" data-name="Carton de savon" data-stock="3">
                                                🟡 Carton de savon (3)                                            </option>
                                                                                    <option value="6" data-price="2000" data-name="Huile de palme 1L" data-stock="0">
                                                🔴 Huile de palme 1L (0)                                            </option>
                                                                                    <option value="4" data-price="1500" data-name="Paquet de sucre 1kg" data-stock="200">
                                                🟢 Paquet de sucre 1kg (200)                                            </option>
                                                                                    <option value="1" data-price="25000" data-name="Sac de riz 50kg" data-stock="100">
                                                🟢 Sac de riz 50kg (100)                                            </option>
                                                                            </select>
                                </div>
                                <div class="form-group" style="margin-bottom: 0; position: relative;">
                                    <label for="pos-qty" style="font-size: 10px;">Qté</label>
                                    <input type="number" id="pos-qty" class="form-control" value="1" min="1" style="padding: 10px; font-size: 12px;" onfocus="showKeypad('pos-qty')">
                                </div>
                                <button type="button" class="btn-submit" onclick="addToCart(event)" style="height: 38px; width: 38px; font-size: 18px; display: flex; justify-content: center; align-items: center; border-radius: 8px; padding: 0; flex-shrink: 0; min-width: 38px;">+</button>
                            </div>

                            <!-- Keypad for tactile inputs -->
                            <div class="keypad-container" id="pos-keypad" style="max-width: 100%;">
                                <button type="button" class="keypad-btn" onclick="pressKey(1)">1</button>
                                <button type="button" class="keypad-btn" onclick="pressKey(2)">2</button>
                                <button type="button" class="keypad-btn" onclick="pressKey(3)">3</button>
                                <button type="button" class="keypad-btn" onclick="pressKey(4)">4</button>
                                <button type="button" class="keypad-btn" onclick="pressKey(5)">5</button>
                                <button type="button" class="keypad-btn" onclick="pressKey(6)">6</button>
                                <button type="button" class="keypad-btn" onclick="pressKey(7)">7</button>
                                <button type="button" class="keypad-btn" onclick="pressKey(8)">8</button>
                                <button type="button" class="keypad-btn" onclick="pressKey(9)">9</button>
                                <button type="button" class="keypad-btn" onclick="pressKey('C')" style="color: var(--danger);">C</button>
                                <button type="button" class="keypad-btn" onclick="pressKey(0)">0</button>
                                <button type="button" class="keypad-btn" onclick="hideKeypad()" style="color: var(--success); font-size: 12px;">OK</button>
                            </div>

                            <!-- Cart Items list table -->
                            <table class="debt-table" style="font-size: 11px; margin-top: 16px;">
                                <thead>
                                    <tr>
                                        <th style="padding-bottom: 8px;">Produit</th>
                                        <th style="padding-bottom: 8px;">Qté</th>
                                        <th style="padding-bottom: 8px;">Total</th>
                                        <th style="padding-bottom: 8px;"></th>
                                    </tr>
                                </thead>
                                <tbody id="cart-rows">
                                    <tr id="empty-cart-row">
                                        <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 16px 0; border-bottom: none;">Panier vide. Ajoutez des articles.</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div id="hidden-cart-inputs"></div>
                        </div>

                        <!-- Digital Display Panel -->
                        <div style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.08) 0%, rgba(30, 41, 59, 0.4) 100%); border: 1px solid rgba(59, 130, 246, 0.15); border-radius: 16px; padding: 14px; text-align: center; margin-bottom: 20px; box-shadow: inset 0 0 15px rgba(59, 130, 246, 0.08);">
                            <span style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 1px; display: block; margin-bottom: 4px;">Montant Total Net à Payer</span>
                            <div style="font-size: 24px; font-weight: 900; color: #60a5fa; letter-spacing: -0.5px; font-family: monospace; text-shadow: 0 0 10px rgba(96, 165, 250, 0.3);">
                                <span id="montant_total_display_text">0</span> <span style="font-size: 14px; font-weight: 700;">FCFA</span>
                            </div>
                            <input type="hidden" name="montant_total" id="montant_total_display" value="0">
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 24px;">
                             <div class="form-group" style="margin-bottom: 0;">
                                 <label for="mode_reglement" style="font-size: 10px;">Règlement</label>
                                 <select name="mode_reglement" class="form-control" style="background-color: #0b0f1a; padding: 10px; font-size: 12px;">
                                     <option value="Wave">Wave</option>
                                     <option value="Orange Money">OM</option>
                                     <option value="Especes">Espèces</option>
                                 </select>
                             </div>
                             <div class="form-group" style="margin-bottom: 0;">
                                 <label for="pos-montant-verse" style="font-size: 10px;">Versé (Avance)</label>
                                 <input type="number" name="montant_verse" id="pos-montant-verse" class="form-control" value="0" min="0" style="padding: 10px; font-size: 12px;" onfocus="showKeypad('pos-montant-verse')">
                             </div>
                        </div>

                        <button type="submit" class="btn-submit btn-success" style="padding: 14px; font-weight: 800; font-size: 13px; width: 100%;">Valider la Vente (DML)</button>
                    </form>
                </div>

                <!-- Right side: Registry logs -->
                <div class="panel-card" style="margin-bottom: 0;">
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
                                                            <tr>
                                    <td style="font-weight: 700; color: var(--text-muted);">#CMD-4</td>
                                    <td style="font-weight: 700;">
                                        Maimouna Diallo                                        <div style="font-size:11px; color:var(--text-muted); font-weight:normal;">Tél : 701122334</div>
                                    </td>
                                    <td style="font-weight: 800; color: var(--accent);">15 000 F</td>
                                    <td>
                                                                                                                                    <span class="badge badge-danger">CRÉDIT TOTAL</span>
                                                                                                                        </td>
                                    <td>
                                        <button class="btn-quick-action" onclick="toggleDetails('order-details-4')">Lignes</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5" style="padding: 0; border: none;">
                                        <div class="details-drawer" id="order-details-4">
                                            <div style="font-weight: 700; font-size: 12px; color: var(--accent); margin-bottom: 8px;">Détails Facture :</div>
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
                                    </td>
                                </tr>
                                                            <tr>
                                    <td style="font-weight: 700; color: var(--text-muted);">#CMD-3</td>
                                    <td style="font-weight: 700;">
                                        Moussa Sarr                                        <div style="font-size:11px; color:var(--text-muted); font-weight:normal;">Tél : 769876543</div>
                                    </td>
                                    <td style="font-weight: 800; color: var(--accent);">74 000 F</td>
                                    <td>
                                                                                                                                    <span class="badge badge-warning">AVANCE (Credit)</span>
                                                                                                                        </td>
                                    <td>
                                        <button class="btn-quick-action" onclick="toggleDetails('order-details-3')">Lignes</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5" style="padding: 0; border: none;">
                                        <div class="details-drawer" id="order-details-3">
                                            <div style="font-weight: 700; font-size: 12px; color: var(--accent); margin-bottom: 8px;">Détails Facture :</div>
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
                                    </td>
                                </tr>
                                                            <tr>
                                    <td style="font-weight: 700; color: var(--text-muted);">#CMD-2</td>
                                    <td style="font-weight: 700;">
                                        Fama Diouf                                        <div style="font-size:11px; color:var(--text-muted); font-weight:normal;">Tél : 781234567</div>
                                    </td>
                                    <td style="font-weight: 800; color: var(--accent);">44 000 F</td>
                                    <td>
                                                                                                                                    <span class="badge badge-warning">AVANCE (Credit)</span>
                                                                                                                        </td>
                                    <td>
                                        <button class="btn-quick-action" onclick="toggleDetails('order-details-2')">Lignes</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5" style="padding: 0; border: none;">
                                        <div class="details-drawer" id="order-details-2">
                                            <div style="font-weight: 700; font-size: 12px; color: var(--accent); margin-bottom: 8px;">Détails Facture :</div>
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
                                    </td>
                                </tr>
                                                            <tr>
                                    <td style="font-weight: 700; color: var(--text-muted);">#CMD-1</td>
                                    <td style="font-weight: 700;">
                                        Abdou Ndiaye                                        <div style="font-size:11px; color:var(--text-muted); font-weight:normal;">Tél : 776543210</div>
                                    </td>
                                    <td style="font-weight: 800; color: var(--accent);">58 000 F</td>
                                    <td>
                                                                                    <span class="badge badge-success">COMPTANT (Wave)</span>
                                                                            </td>
                                    <td>
                                        <button class="btn-quick-action" onclick="toggleDetails('order-details-1')">Lignes</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5" style="padding: 0; border: none;">
                                        <div class="details-drawer" id="order-details-1">
                                            <div style="font-weight: 700; font-size: 12px; color: var(--accent); margin-bottom: 8px;">Détails Facture :</div>
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
                                                            <td>Bidon d&#039;huile 5L</td>
                                                            <td>1</td>
                                                            <td>8 000 F</td>
                                                            <td style="font-weight: 700; color: var(--accent);">8 000 F</td>
                                                        </tr>
                                                                                                    </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                                                    </tbody>
                    </table>
                </div>
            </div>
        </div>