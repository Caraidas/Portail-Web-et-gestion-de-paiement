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
  AND B_Transaction.Sens LIKE '+'
GROUP BY NumSiren;

