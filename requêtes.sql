

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
  --AND B_Transaction.Sens LIKE '+' Pour moi avoir la tresorerie à besoin de tout types de transaction pour être accurate
  AND B_Transaction.DateVente = CURDATE() --CURDATE pour avoir la date du jour au format aaaa:mm:jj--
--AND B_Client.NumSiren = "XXX"--
GROUP BY NumSiren;

--N siren | Raison sociale | N° Remise | Date traitement | Nbre transactions | Devise (EUR) | Montant total | Sens + ou - --

--test1--
SELECT B_Client.NumSiren,
       B_Client.RaisonSociale,
       B_Client.Devise,
       B_Remise.NumRemise,
       B_Remise.DateTraitement,
       COUNT(B_Transaction.NumAutorisation) AS 'Nombre de transaction' , 
       MontantsTablePositif.montant - MontantsTableNegatif.montant AS 'Montant Total',
       '+' AS 'Sens'
FROM B_Remise,
     B_Client,
     B_Transaction,
     (SELECT SUM(Montant) montant,NumRemise FROM B_Transaction NATURAL JOIN B_Remise WHERE sens = "+" GROUP BY B_Remise.NumRemise)
     AS MontantsTablePositif,
     (SELECT SUM(Montant) montant,NumRemise FROM B_Transaction NATURAL JOIN B_Remise WHERE sens = "-" GROUP BY B_Remise.NumRemise)
     AS MontantsTableNegatif
WHERE B_Client.NumSiren LIKE B_Remise.NumSiren
  AND B_Remise.NumRemise LIKE B_Transaction.NumRemise
  AND MontantsTablePositif.NumRemise LIKE B_Remise.NumRemise
  AND MontantsTableNegatif.NumRemise LIKE B_Remise.NumRemise
GROUP BY B_Remise.NumRemise;

--autre facon de lier--
FROM 
     B_Client NATURAL JOIN B_Remise NATURAL JOIN B_Transaction NATURAL JOIN ((SELECT SUM(Montant) montant,NumRemise FROM B_Transaction NATURAL JOIN B_Remise WHERE sens = "+" GROUP BY B_Remise.NumRemise)
     AS MontantsTablePositif) NATURAL JOIN ((SELECT SUM(Montant) montant,NumRemise FROM B_Transaction NATURAL JOIN B_Remise WHERE sens = "-" GROUP BY B_Remise.NumRemise)
     AS MontantsTableNegatif)


--test2--
SELECT B_Client.NumSiren,
       B_Client.RaisonSociale,
       B_Client.Devise,
       B_Remise.NumRemise,
       B_Remise.DateTraitement,
       COUNT(B_Transaction.NumAutorisation) AS 'Nombre de transaction' , 
       SUM(IF B_Transaction.Sens = '+' SELECT B_Transaction.Montant; ELSE SELECT -(B_Transaction.Montant)) AS 'montant total';
       '+' AS 'Sens'
FROM 
     B_Client NATURAL JOIN B_Remise NATURAL JOIN B_Transaction
GROUP BY B_Remise.NumRemise;



