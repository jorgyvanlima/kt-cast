# KT-CAST — Portal de Gestão de Aplicações Estratégicas

> Portal interno para controle e visibilidade de aplicações estratégicas, contatos de suporte, escalações e disponibilidade de equipes de plantão.

![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?logo=mysql&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-Compose-2496ED?logo=docker&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind-CDN-06B6D4?logo=tailwindcss&logoColor=white)
![License](https://img.shields.io/badge/license-MIT-green)

---

## Índice

- [Visão Geral](#visão-geral)
- [Funcionalidades](#funcionalidades)
- [Stack Tecnológica](#stack-tecnológica)
- [Pré-requisitos](#pré-requisitos)
- [Iniciando o Projeto](#iniciando-o-projeto)
- [Variáveis de Ambiente](#variáveis-de-ambiente)
- [Estrutura de Pastas](#estrutura-de-pastas)
- [Banco de Dados](#banco-de-dados)
- [Rotas da Aplicação](#rotas-da-aplicação)
- [Contribuindo](#contribuindo)

---

## Visão Geral

O **KT-CAST** é um portal web interno desenvolvido em **PHP 8 puro + MySQL**, containerizado com **Docker Compose**. Foi projetado para centralizar informações críticas de operação de TI, incluindo:

- Catálogo de aplicações estratégicas com documentos anexados
- Matriz de contatos N2 e contatos de fornecedores
- Cronograma de escalações por prioridade (Alta / Média / Baixa)
- Tabela de SLA consolidada por tipo de chamado
- Gestão de plantão (sobreaviso) da equipe

---

## Funcionalidades

| Módulo | Descrição |
|--------|-----------|
| **Autenticação** | Login/logout com sessões PHP e proteção CSRF |
| **Dashboard** | Visão geral com totais de aplicações, contatos e escalações |
| **Aplicações** | CRUD completo: cadastro, edição, exclusão e upload de documentos (PDF, Word, Excel, ODT, ODS e mais) |
| **Contatos N2** | Cadastro e listagem de contatos internos com vínculo a múltiplas aplicações |
| **Contatos Fornecedores** | Tabela de contatos externos agrupados por prioridade de escalação (Alta, Média, Baixa, Outros) + Contatos de Suporte |
| **Sobreaviso** | Cronograma de plantão da equipe por período |
| **Tabela de SLA** | Visualização de SLA consolidada por categoria de atendimento |

---

## Stack Tecnológica

- **Back-end:** PHP 8.2 (sem framework — roteamento flat em `public/index.php`)
- **Banco de dados:** MySQL 8.0
- **Front-end:** HTML5 + [Tailwind CSS](https://tailwindcss.com/) (via CDN) + [Font Awesome 6](https://fontawesome.com/)
- **Infraestrutura:** Docker + Docker Compose (containers `web` e `db`)
- **Conector DB:** PDO com prepared statements

---

## Pré-requisitos

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) instalado e em execução
- Git

---

## Iniciando o Projeto

```bash
# 1. Clone o repositório
git clone https://github.com/jorgyvanlima/kt-cast.git
cd kt-cast

# 2. Suba os containers
docker compose up -d --build

# 3. Aguarde o healthcheck do MySQL (~10 segundos) e acesse
# http://localhost:8080
```

### Credenciais padrão

| Campo | Valor |
|-------|-------|
| Usuário | `admin` |
| Senha | `admin123` |

> As credenciais podem ser alteradas diretamente no banco após o primeiro login.

---

## Variáveis de Ambiente

Crie um arquivo `.env` na raiz do projeto (já incluído em `.gitignore`). Exemplo:

```env
APP_ENV=development
DB_HOST=db
DB_PORT=3306
DB_DATABASE=kt_cast
DB_USERNAME=ktcast
DB_PASSWORD=ktcast123
```

As variáveis são lidas pelo container `web` via `docker-compose.yml`.

---

## Estrutura de Pastas

```
kt-cast/
├── docker/
│   ├── apache-vhost.conf       # Virtual host Apache
│   ├── php-uploads.ini         # Configurações de upload PHP
│   └── mysql/
│       └── init/               # Scripts SQL executados na criação do DB
│           ├── 01-schema.sql   # Schema completo (tabelas)
│           └── 02-seed.sql     # Dados iniciais (admin, SLA etc.)
├── public/
│   └── index.php               # Front controller — todas as rotas
├── src/
│   ├── bootstrap.php           # Inicialização (sessões, helpers, PDO)
│   ├── db.php                  # Conexão PDO singleton
│   └── helpers.php             # Funções utilitárias (CSRF, flash etc.)
├── views/
│   ├── layout.php              # Layout base com menu lateral
│   ├── layout_auth.php         # Layout para telas de login
│   ├── dashboard.php
│   ├── login.php
│   ├── sla.php
│   ├── applications/
│   │   ├── index.php           # Listagem de aplicações
│   │   ├── form.php            # Cadastro / edição
│   │   └── documents.php       # Upload e listagem de documentos
│   ├── contacts/
│   │   ├── index.php           # Contatos N2
│   │   ├── form.php
│   │   ├── suppliers.php       # Contatos de fornecedores (5 tabelas)
│   │   └── suppliers_form.php  # Formulário de contato fornecedor
│   └── schedule/
│       ├── index.php           # Cronograma de sobreaviso
│       └── form.php
├── uploads/                    # Arquivos enviados (ignorado pelo Git)
├── docker-compose.yml
├── Dockerfile
└── README.md
```

---

## Banco de Dados

O schema é criado automaticamente na primeira inicialização do container MySQL via scripts em `docker/mysql/init/`.

### Principais tabelas

| Tabela | Descrição |
|--------|-----------|
| `users` | Usuários com autenticação |
| `applications` | Catálogo de aplicações estratégicas |
| `application_documents` | Documentos vinculados a cada aplicação |
| `contacts` | Contatos internos N2 |
| `contact_applications` | Relação N:N entre contatos e aplicações |
| `supplier_contacts` | Contatos externos de fornecedores |
| `supplier_support_contacts` | Contatos de suporte técnico de fornecedores |
| `schedules` | Registros de sobreaviso da equipe |
| `sla_entries` | Entradas da tabela de SLA |

---

## Rotas da Aplicação

| Método | Rota | Descrição |
|--------|------|-----------|
| `GET` | `/` | Dashboard |
| `GET/POST` | `/login` | Autenticação |
| `GET` | `/logout` | Encerrar sessão |
| `GET` | `/applications` | Listagem de aplicações |
| `GET/POST` | `/applications/new` | Nova aplicação |
| `GET/POST` | `/applications/{id}/edit` | Editar aplicação |
| `POST` | `/applications/{id}/delete` | Excluir aplicação |
| `GET/POST` | `/applications/{id}/documents` | Documentos da aplicação |
| `POST` | `/applications/{id}/documents/{docId}/delete` | Excluir documento |
| `GET` | `/contacts` | Listagem de contatos N2 |
| `GET/POST` | `/contacts/new` | Novo contato |
| `GET/POST` | `/contacts/{id}/edit` | Editar contato |
| `POST` | `/contacts/{id}/delete` | Excluir contato |
| `GET` | `/supplier-contacts` | Contatos de fornecedores |
| `GET/POST` | `/supplier-contacts/new` | Novo contato fornecedor |
| `GET/POST` | `/supplier-contacts/{id}/edit` | Editar contato fornecedor |
| `POST` | `/supplier-contacts/{id}/delete` | Excluir contato fornecedor |
| `GET` | `/schedule` | Cronograma de sobreaviso |
| `GET/POST` | `/schedule/new` | Novo registro de sobreaviso |
| `GET/POST` | `/schedule/{id}/edit` | Editar sobreaviso |
| `POST` | `/schedule/{id}/delete` | Excluir sobreaviso |
| `GET` | `/sla` | Tabela de SLA |

---

## Contribuindo

1. Fork este repositório
2. Crie uma branch: `git checkout -b feature/minha-funcionalidade`
3. Commit suas alterações: `git commit -m 'feat: adiciona minha funcionalidade'`
4. Push para a branch: `git push origin feature/minha-funcionalidade`
5. Abra um Pull Request

---

<p align="center">Desenvolvido com ❤️ para a equipe de Suporte KT · 2026</p>
