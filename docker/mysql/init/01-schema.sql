SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(64) NOT NULL UNIQUE,
    email VARCHAR(120) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'tech',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    description TEXT NULL,
    fila_cast VARCHAR(150) NULL,
    hora_entrada VARCHAR(20) NULL,
    hora_saida VARCHAR(20) NULL,
    hora_almoco VARCHAR(50) NULL,
    analista_cast_n2 VARCHAR(150) NULL,
    analista_tereos_n2 VARCHAR(150) NULL,
    n2_track VARCHAR(100) NULL,
    criticidade VARCHAR(50) NULL,
    operacoes_tereos VARCHAR(100) NULL,
    suporte_fornecedor VARCHAR(100) NULL,
    fornecedor VARCHAR(100) NULL,
    business_application_snow VARCHAR(150) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    celular VARCHAR(30) NULL,
    email_cast VARCHAR(120) NULL,
    email_tereos VARCHAR(120) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS contact_applications (
    contact_id INT NOT NULL,
    application_id INT NOT NULL,
    PRIMARY KEY (contact_id, application_id),
    CONSTRAINT fk_contact_app_contact FOREIGN KEY (contact_id) REFERENCES contacts(id) ON DELETE CASCADE,
    CONSTRAINT fk_contact_app_application FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS on_calls (
    id INT AUTO_INCREMENT PRIMARY KEY,
    contact_id INT NULL,
    analyst_name VARCHAR(100) NOT NULL,
    phone VARCHAR(30) NULL,
    start_date DATETIME NOT NULL,
    end_date DATETIME NOT NULL,
    observation VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_on_calls_contact FOREIGN KEY (contact_id) REFERENCES contacts(id) ON DELETE SET NULL
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS application_documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    application_id INT NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    stored_name VARCHAR(255) NULL,
    note TEXT NULL,
    mime_type VARCHAR(100) NULL,
    file_size INT NULL,
    uploaded_by VARCHAR(64) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_doc_application FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS supplier_contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NULL,
    referencia_escalacao VARCHAR(120) NULL,
    cargo_referencia VARCHAR(150) NULL,
    email VARCHAR(180) NULL,
    telefone VARCHAR(255) NULL,
    empresa VARCHAR(120) NULL,
    aplicacoes_referencia VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS supplier_support_contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo VARCHAR(120) NULL,
    numero VARCHAR(255) NULL,
    fornecedor VARCHAR(150) NULL,
    aplicacao VARCHAR(150) NULL,
    observacao VARCHAR(255) NULL,
    portal_link VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
