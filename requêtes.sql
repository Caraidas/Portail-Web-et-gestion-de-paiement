

--N siren|Raison Sociale|Nombre de transaction|Devise|Montant Total--
SELECT B_Client.NumSiren,
       RaisonSociale,
       B_Client.Devise,
       COUNT(B_Transaction.Montant) AS "nombre de transactions",
       SUM(B_Transaction.Montant) AS "Montant total"
FROM B_Remise,
     B_Client,
     B_Transaction
WHERE B_Client.NumSiren LIKE B_Remise.NumSiren
  AND B_Remise.NumRemise LIKE B_Transaction.NumRemise
  AND B_Transaction.DateVente = CURDATE() --CURDATE pour avoir la date du jour au format aaaa:mm:jj--
--AND B_Client.NumSiren = "XXX"--
GROUP BY NumSiren;

--N siren | Raison sociale | N° Remise | Date traitement | Nbre transactions | Devise (EUR) | Montant total | Sens + ou - --

--création des vues de prétraitement des informations
CREATE VIEW TableMontantPositif AS 
SELECT SUM(Montant) montant,NumRemise 
FROM B_Transaction NATURAL JOIN B_Remise 
WHERE sens = "+" GROUP BY B_Remise.NumRemise;
CREATE VIEW TableMontantNegatif AS 
SELECT SUM(Montant) montant,NumRemise 
FROM B_Transaction NATURAL JOIN B_Remise 
WHERE sens = "-" GROUP BY B_Remise.NumRemise;

--Commande de selection--
SELECT B_Client.NumSiren,
       B_Client.RaisonSociale,
       B_Client.Devise,
       B_Remise.NumRemise,
       B_Remise.DateTraitement,
       COUNT(B_Transaction.NumAutorisation) AS 'Nombre de transaction' , 
       ABS(TableMontantPositif.montant - TableMontantNegatif.montant) AS 'Montant Total',
       CHAR(44 - SIGN(TableMontantPositif.montant - TableMontantNegatif.montant)) AS 'sens'
FROM B_Remise
     LEFT JOIN B_Client ON B_Remise.NumSiren = B_Client.NumSiren
     LEFT JOIN B_Transaction ON B_Transaction.NumRemise = B_Remise.NumRemise
     LEFT JOIN TableMontantPositif ON TableMontantPositif.NumRemise = B_Remise.NumRemise
     LEFT JOIN TableMontantNegatif ON TableMontantNegatif.NumRemise = B_Remise.NumRemise
--WHERE B_Client.NumSiren = "XXX"--
GROUP BY B_Remise.NumRemise;

--- Détail ---
SELECT B_Client.NumSiren,
       B_Transaction.DateVente,
       B_Transaction.NumCarte,
       B_Transaction.Reseau,
       B_Transaction.Montant,
       B_Transaction.Sens
FROM B_Client
     NATURAL JOIN B_Remise
     NATURAL JOIN B_Transaction
WHERE B_Remise.NumRemise = 'XXX'



-- N Siren | Date vente | Date remise | N Carte | Réseau | N dossier impayé | Devise | Montant  | Libellé impayé --
SELECT B_Client.NumSiren,
       B_Remise.DateTraitement 'Date Remise',
       B_Transaction.NumCarte,
       B_Transaction.Reseau,
       B_Transaction.NumImpaye 'Numero de dossier impaye',  
       B_Transaction.Devise,
       B_Transaction.Montant,
       B_Transaction.LibelleImpaye
FROM B_Client 
     NATURAL JOIN B_Remise
     NATURAL JOIN B_Transaction
WHERE B_Transaction.NumImpaye IS NOT NULL;
--AND B_Client.NumSiren = "XXX"--