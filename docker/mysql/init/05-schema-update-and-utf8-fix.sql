SET NAMES utf8mb4;

ALTER TABLE application_documents
    MODIFY COLUMN stored_name VARCHAR(255) NULL;

SET @note_col_exists := (
    SELECT COUNT(*)
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'application_documents'
      AND COLUMN_NAME = 'note'
);

SET @sql := IF(
    @note_col_exists = 0,
    'ALTER TABLE application_documents ADD COLUMN note TEXT NULL AFTER stored_name',
    'SELECT 1'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @contact_col_exists := (
    SELECT COUNT(*)
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'on_calls'
      AND COLUMN_NAME = 'contact_id'
);

SET @sql := IF(
    @contact_col_exists = 0,
    'ALTER TABLE on_calls ADD COLUMN contact_id INT NULL AFTER id',
    'SELECT 1'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @fk_exists := (
    SELECT COUNT(*)
    FROM information_schema.TABLE_CONSTRAINTS
    WHERE CONSTRAINT_SCHEMA = DATABASE()
      AND TABLE_NAME = 'on_calls'
      AND CONSTRAINT_NAME = 'fk_on_calls_contact'
      AND CONSTRAINT_TYPE = 'FOREIGN KEY'
);

SET @sql := IF(
    @fk_exists = 0,
    'ALTER TABLE on_calls ADD CONSTRAINT fk_on_calls_contact FOREIGN KEY (contact_id) REFERENCES contacts(id) ON DELETE SET NULL',
    'SELECT 1'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

UPDATE supplier_contacts
SET nome = CONVERT(BINARY CONVERT(nome USING latin1) USING utf8mb4)
WHERE nome LIKE '%Ã%' OR nome LIKE '%Â%';

UPDATE supplier_contacts
SET referencia_escalacao = CONVERT(BINARY CONVERT(referencia_escalacao USING latin1) USING utf8mb4)
WHERE referencia_escalacao LIKE '%Ã%' OR referencia_escalacao LIKE '%Â%';

UPDATE supplier_contacts
SET cargo_referencia = CONVERT(BINARY CONVERT(cargo_referencia USING latin1) USING utf8mb4)
WHERE cargo_referencia LIKE '%Ã%' OR cargo_referencia LIKE '%Â%';

UPDATE supplier_contacts
SET telefone = CONVERT(BINARY CONVERT(telefone USING latin1) USING utf8mb4)
WHERE telefone LIKE '%Ã%' OR telefone LIKE '%Â%';

UPDATE supplier_contacts
SET empresa = CONVERT(BINARY CONVERT(empresa USING latin1) USING utf8mb4)
WHERE empresa LIKE '%Ã%' OR empresa LIKE '%Â%';

UPDATE supplier_contacts
SET aplicacoes_referencia = CONVERT(BINARY CONVERT(aplicacoes_referencia USING latin1) USING utf8mb4)
WHERE aplicacoes_referencia LIKE '%Ã%' OR aplicacoes_referencia LIKE '%Â%';

UPDATE supplier_support_contacts
SET fornecedor = CONVERT(BINARY CONVERT(fornecedor USING latin1) USING utf8mb4)
WHERE fornecedor LIKE '%Ã%' OR fornecedor LIKE '%Â%';

UPDATE supplier_support_contacts
SET aplicacao = CONVERT(BINARY CONVERT(aplicacao USING latin1) USING utf8mb4)
WHERE aplicacao LIKE '%Ã%' OR aplicacao LIKE '%Â%';

UPDATE supplier_support_contacts
SET observacao = CONVERT(BINARY CONVERT(observacao USING latin1) USING utf8mb4)
WHERE observacao LIKE '%Ã%' OR observacao LIKE '%Â%';
