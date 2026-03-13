SET NAMES utf8mb4;

UPDATE contacts
SET name = CONVERT(0x4C6574C3AD636961204361726F6C696E65 USING utf8mb4)
WHERE email_cast = 'leticia.freitas@castgroup.com.br';

UPDATE contacts
SET name = CONVERT(0x446965676F20436F7272C3AA61 USING utf8mb4)
WHERE email_cast = 'diego.onmousemove@castgrouppartner.com';

CREATE TEMPORARY TABLE tmp_contact_keep AS
SELECT MIN(id) AS keep_id, email_cast
FROM contacts
WHERE email_cast IS NOT NULL
  AND email_cast <> ''
GROUP BY email_cast
HAVING COUNT(*) > 1;

INSERT IGNORE INTO contact_applications (contact_id, application_id)
SELECT k.keep_id, ca.application_id
FROM contact_applications ca
JOIN contacts c ON c.id = ca.contact_id
JOIN tmp_contact_keep k ON k.email_cast = c.email_cast
WHERE ca.contact_id <> k.keep_id;

DELETE ca
FROM contact_applications ca
JOIN contacts c ON c.id = ca.contact_id
JOIN tmp_contact_keep k ON k.email_cast = c.email_cast
WHERE ca.contact_id <> k.keep_id;

DELETE c
FROM contacts c
JOIN tmp_contact_keep k ON k.email_cast = c.email_cast
WHERE c.id <> k.keep_id;

DROP TEMPORARY TABLE tmp_contact_keep;
