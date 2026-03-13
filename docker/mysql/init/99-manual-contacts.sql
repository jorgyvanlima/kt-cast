INSERT INTO contacts (name, celular, email_cast, email_tereos)
SELECT v.name, v.celular, v.email_cast, v.email_tereos
FROM (
    SELECT 'Jimmy Chiogna' AS name, '(11) 982042328' AS celular, 'jimmy.chiogna@castgroup.com.br' AS email_cast, 'jimmy.chiogna-external@tereos.com' AS email_tereos
    UNION ALL SELECT 'Danilo Eduardo', '(61) 995514590', 'danilo.vieira@castgroup.com.br', 'danilo.vieira-external@tereos.com'
    UNION ALL SELECT 'Letícia Caroline', '(61) 981723789', 'leticia.freitas@castgroup.com.br', 'leticia.silva-external@tereos.com'
    UNION ALL SELECT 'Rodrigo Leandro', '(61) 999187214', 'rodrigo.leandro@castgroup.com.br', 'rodrigo.lima-external@tereos.com'
    UNION ALL SELECT 'Simone Dantas', '(11) 94714-2035', 'simone.dantas@castgrouppartner.com.br', 'simone.dantas-external@tereos.com'
    UNION ALL SELECT 'Diego Corrêa', '(51) 9710-3060', 'diego.onmousemove@castgrouppartner.com', 'diego.correa-external@tereos.com'
    UNION ALL SELECT 'Igson', '(92) 8100-3580', 'igson.silva@castgroup.com.br', 'igson.silva-external@tereos.com'
    UNION ALL SELECT 'Anelize', '(61) 8151-1351', 'anelize.gehres@castgroup.com.br', 'anelize.gehres-external@tereos.com'
    UNION ALL SELECT 'Jorgvan', '(11) 93713-6730', 'jorgvan.lima@castgroup.com.br', 'jorgvan.lima-external@tereos.com'
) AS v
LEFT JOIN contacts c
    ON c.name = v.name
   AND c.email_cast = v.email_cast
WHERE c.id IS NULL;

SELECT name, celular, email_cast, email_tereos
FROM contacts
WHERE name IN ('Jimmy Chiogna','Danilo Eduardo','Letícia Caroline','Rodrigo Leandro','Simone Dantas','Diego Corrêa','Igson','Anelize','Jorgvan')
ORDER BY name;
