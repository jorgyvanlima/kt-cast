<?php
declare(strict_types=1);

require dirname(__DIR__) . '/src/bootstrap.php';

ensure_default_admin();

$pdo = db();
$path = request_path();
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($path === '/login') {
    if ($method === 'POST') {
        verify_csrf();
        $username = trim((string) ($_POST['username'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            login_user($user);
            flash('Login realizado com sucesso.', 'success');
            redirect_to('/dashboard');
        }

        flash('Usuário ou senha inválidos.', 'danger');
        render('login', ['title' => 'Entrar'], 'layout_auth');
        exit;
    }

    render('login', ['title' => 'Entrar'], 'layout_auth');
    exit;
}

if ($path === '/logout') {
    logout_user();
    flash('Sessão encerrada.', 'success');
    redirect_to('/login');
}

require_login();

if ($path === '/' || $path === '/dashboard') {
    $appCount = (int) $pdo->query('SELECT COUNT(*) FROM applications')->fetchColumn();
    $contactCount = (int) $pdo->query('SELECT COUNT(*) FROM contacts')->fetchColumn();
    $scheduleCount = (int) $pdo->query('SELECT COUNT(*) FROM on_calls')->fetchColumn();

    $today = date('Y-m-d');
    $stmtToday = $pdo->prepare(
        'SELECT analyst_name, phone, start_date, end_date, observation
         FROM on_calls
         WHERE DATE(start_date) <= :today AND DATE(end_date) >= :today2
         ORDER BY start_date'
    );
    $stmtToday->execute([':today' => $today, ':today2' => $today]);
    $todayOnCalls = $stmtToday->fetchAll();

    render('dashboard', [
        'title' => 'Dashboard',
        'appCount' => $appCount,
        'contactCount' => $contactCount,
        'scheduleCount' => $scheduleCount,
        'todayOnCalls' => $todayOnCalls,
    ]);
    exit;
}

if ($path === '/applications') {
    $search = trim((string) ($_GET['search'] ?? ''));
    $page = max(1, (int) ($_GET['page'] ?? 1));
    $perPage = 10;
    $offset = ($page - 1) * $perPage;

    $searchFields = [
        'name',
        'description',
        'fila_cast',
        'hora_entrada',
        'hora_saida',
        'hora_almoco',
        'analista_cast_n2',
        'analista_tereos_n2',
        'n2_track',
        'criticidade',
        'operacoes_tereos',
        'suporte_fornecedor',
        'fornecedor',
        'business_application_snow',
    ];

    if ($search !== '') {
        $like = '%' . $search . '%';
        $whereParts = array_map(static fn (string $field): string => $field . ' LIKE ?', $searchFields);
        $whereSql = implode(' OR ', $whereParts);
        $params = array_fill(0, count($searchFields), $like);

        $countStmt = $pdo->prepare('SELECT COUNT(*) FROM applications WHERE ' . $whereSql);
        $countStmt->execute($params);
        $totalApplications = (int) $countStmt->fetchColumn();

        $stmt = $pdo->prepare('SELECT * FROM applications WHERE ' . $whereSql . ' ORDER BY name LIMIT ? OFFSET ?');
        $paginatedParams = array_merge($params, [$perPage, $offset]);
        foreach ($paginatedParams as $index => $value) {
            $stmt->bindValue($index + 1, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->execute();
        $applications = $stmt->fetchAll();
    } else {
        $countStmt = $pdo->query('SELECT COUNT(*) FROM applications');
        $totalApplications = (int) $countStmt->fetchColumn();

        $stmt = $pdo->prepare('SELECT * FROM applications ORDER BY name LIMIT ? OFFSET ?');
        $stmt->bindValue(1, $perPage, PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, PDO::PARAM_INT);
        $stmt->execute();
        $applications = $stmt->fetchAll();
    }

    $totalPages = max(1, (int) ceil($totalApplications / $perPage));
    if ($page > $totalPages) {
        $page = $totalPages;
        $offset = ($page - 1) * $perPage;

        if ($search !== '') {
            $stmt = $pdo->prepare('SELECT * FROM applications WHERE ' . $whereSql . ' ORDER BY name LIMIT ? OFFSET ?');
            $paginatedParams = array_merge($params, [$perPage, $offset]);
            foreach ($paginatedParams as $index => $value) {
                $stmt->bindValue($index + 1, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
            }
            $stmt->execute();
            $applications = $stmt->fetchAll();
        } else {
            $stmt = $pdo->prepare('SELECT * FROM applications ORDER BY name LIMIT ? OFFSET ?');
            $stmt->bindValue(1, $perPage, PDO::PARAM_INT);
            $stmt->bindValue(2, $offset, PDO::PARAM_INT);
            $stmt->execute();
            $applications = $stmt->fetchAll();
        }
    }

    $pagination = [
        'page' => $page,
        'perPage' => $perPage,
        'totalItems' => $totalApplications,
        'totalPages' => $totalPages,
        'startItem' => $totalApplications > 0 ? ($offset + 1) : 0,
        'endItem' => min($offset + $perPage, $totalApplications),
    ];

    render('applications/index', [
        'title' => 'Aplicações',
        'applications' => $applications,
        'search' => $search,
        'pagination' => $pagination,
    ]);
    exit;
}

if ($path === '/applications/new') {
    if ($method === 'POST') {
        verify_csrf();
        $stmt = $pdo->prepare('INSERT INTO applications (name, fila_cast, hora_entrada, hora_saida, hora_almoco, analista_cast_n2, analista_tereos_n2, n2_track, criticidade, operacoes_tereos, suporte_fornecedor, fornecedor, business_application_snow, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            trim((string) ($_POST['name'] ?? '')),
            trim((string) ($_POST['fila_cast'] ?? '')),
            trim((string) ($_POST['hora_entrada'] ?? '')),
            trim((string) ($_POST['hora_saida'] ?? '')),
            trim((string) ($_POST['hora_almoco'] ?? '')),
            trim((string) ($_POST['analista_cast_n2'] ?? '')),
            trim((string) ($_POST['analista_tereos_n2'] ?? '')),
            trim((string) ($_POST['n2_track'] ?? '')),
            trim((string) ($_POST['criticidade'] ?? '')),
            trim((string) ($_POST['operacoes_tereos'] ?? '')),
            trim((string) ($_POST['suporte_fornecedor'] ?? '')),
            trim((string) ($_POST['fornecedor'] ?? '')),
            trim((string) ($_POST['business_application_snow'] ?? '')),
            trim((string) ($_POST['description'] ?? '')),
        ]);
        flash('Aplicação criada com sucesso.', 'success');
        redirect_to('/applications');
    }

    render('applications/form', ['title' => 'Nova Aplicação', 'application' => null]);
    exit;
}

if (preg_match('#^/applications/(\d+)/edit$#', $path, $matches)) {
    $id = (int) $matches[1];
    $stmt = $pdo->prepare('SELECT * FROM applications WHERE id = ?');
    $stmt->execute([$id]);
    $application = $stmt->fetch();

    if (!$application) {
        http_response_code(404);
        exit('Aplicação não encontrada.');
    }

    if ($method === 'POST') {
        verify_csrf();
        $stmt = $pdo->prepare('UPDATE applications SET name = ?, fila_cast = ?, hora_entrada = ?, hora_saida = ?, hora_almoco = ?, analista_cast_n2 = ?, analista_tereos_n2 = ?, n2_track = ?, criticidade = ?, operacoes_tereos = ?, suporte_fornecedor = ?, fornecedor = ?, business_application_snow = ?, description = ? WHERE id = ?');
        $stmt->execute([
            trim((string) ($_POST['name'] ?? '')),
            trim((string) ($_POST['fila_cast'] ?? '')),
            trim((string) ($_POST['hora_entrada'] ?? '')),
            trim((string) ($_POST['hora_saida'] ?? '')),
            trim((string) ($_POST['hora_almoco'] ?? '')),
            trim((string) ($_POST['analista_cast_n2'] ?? '')),
            trim((string) ($_POST['analista_tereos_n2'] ?? '')),
            trim((string) ($_POST['n2_track'] ?? '')),
            trim((string) ($_POST['criticidade'] ?? '')),
            trim((string) ($_POST['operacoes_tereos'] ?? '')),
            trim((string) ($_POST['suporte_fornecedor'] ?? '')),
            trim((string) ($_POST['fornecedor'] ?? '')),
            trim((string) ($_POST['business_application_snow'] ?? '')),
            trim((string) ($_POST['description'] ?? '')),
            $id,
        ]);
        flash('Aplicação atualizada com sucesso.', 'success');
        redirect_to('/applications');
    }

    render('applications/form', ['title' => 'Editar Aplicação', 'application' => $application]);
    exit;
}

if (preg_match('#^/applications/(\d+)/delete$#', $path, $matches) && $method === 'POST') {
    verify_csrf();
    $stmt = $pdo->prepare('DELETE FROM applications WHERE id = ?');
    $stmt->execute([(int) $matches[1]]);
    flash('Aplicação removida.', 'success');
    redirect_to('/applications');
}

// ── Documentos da Aplicação ─────────────────────────────────────────────────

if (preg_match('#^/applications/(\d+)/documents$#', $path, $matches)) {
    $appId = (int) $matches[1];
    $stmt = $pdo->prepare('SELECT * FROM applications WHERE id = ?');
    $stmt->execute([$appId]);
    $application = $stmt->fetch();

    if (!$application) {
        http_response_code(404);
        exit('Aplicação não encontrada.');
    }

    $docs = $pdo->prepare('SELECT * FROM application_documents WHERE application_id = ? ORDER BY created_at DESC');
    $docs->execute([$appId]);
    $documents = $docs->fetchAll();

    render('applications/documents', [
        'title' => 'Documentos — ' . $application['name'],
        'application' => $application,
        'documents' => $documents,
    ]);
    exit;
}

if (preg_match('#^/applications/(\d+)/documents/upload$#', $path, $matches) && $method === 'POST') {
    verify_csrf();
    $appId = (int) $matches[1];

    $stmt = $pdo->prepare('SELECT id, name FROM applications WHERE id = ?');
    $stmt->execute([$appId]);
    $application = $stmt->fetch();
    if (!$application) { http_response_code(404); exit('Aplicação não encontrada.'); }

    $note = trim((string) ($_POST['note'] ?? ''));
    $hasFile = isset($_FILES['document']) && ($_FILES['document']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE;

    if (!$hasFile && $note === '') {
        flash('Envie um arquivo ou preencha uma nota/texto.', 'danger');
        redirect_to("/applications/{$appId}/documents");
    }

    $originalName = 'Nota sem anexo';
    $storedName = null;
    $mimeType = null;
    $fileSize = null;

    if ($hasFile) {
        if (($_FILES['document']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            flash('Erro ao enviar o arquivo. Tente novamente.', 'danger');
            redirect_to("/applications/{$appId}/documents");
        }

        $file = $_FILES['document'];
        $originalName = basename($file['name']);
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        $allowed = ['pdf','doc','docx','odt','xls','xlsx','ods','ppt','pptx','odp','txt','png','jpg','jpeg','zip','rar','7z','csv','md'];
        if (!in_array($ext, $allowed, true)) {
            flash('Tipo de arquivo não permitido.', 'danger');
            redirect_to("/applications/{$appId}/documents");
        }

        $maxSize = 50 * 1024 * 1024; // 50 MB
        if (($file['size'] ?? 0) > $maxSize) {
            flash('Arquivo muito grande. Limite: 50 MB.', 'danger');
            redirect_to("/applications/{$appId}/documents");
        }

        $uploadDir = BASE_PATH . '/uploads/';
        if (!is_dir($uploadDir)) { mkdir($uploadDir, 0755, true); }

        $storedName = uniqid('doc_', true) . '.' . $ext;
        if (!move_uploaded_file($file['tmp_name'], $uploadDir . $storedName)) {
            flash('Falha ao salvar o arquivo no servidor.', 'danger');
            redirect_to("/applications/{$appId}/documents");
        }

        $mimeType = $file['type'] ?? null;
        $fileSize = isset($file['size']) ? (int) $file['size'] : null;
    }

    $stmt = $pdo->prepare('INSERT INTO application_documents (application_id, original_name, stored_name, note, mime_type, file_size, uploaded_by) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([
        $appId,
        $originalName,
        $storedName,
        $note !== '' ? $note : null,
        $mimeType,
        $fileSize,
        $_SESSION['user']['username'] ?? 'sistema',
    ]);

    flash('Registro de documento salvo com sucesso.', 'success');
    redirect_to("/applications/{$appId}/documents");
}

if (preg_match('#^/applications/(\d+)/documents/(\d+)/download$#', $path, $matches)) {
    $appId = (int) $matches[1];
    $docId = (int) $matches[2];

    $stmt = $pdo->prepare('SELECT * FROM application_documents WHERE id = ? AND application_id = ?');
    $stmt->execute([$docId, $appId]);
    $doc = $stmt->fetch();

    if (!$doc) { http_response_code(404); exit('Documento não encontrado.'); }

    if (empty($doc['stored_name'])) {
        flash('Este registro é apenas nota e não possui arquivo para download.', 'warning');
        redirect_to("/applications/{$appId}/documents");
    }

    $filePath = BASE_PATH . '/uploads/' . $doc['stored_name'];
    if (!file_exists($filePath)) { http_response_code(404); exit('Arquivo não encontrado no servidor.'); }

    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . rawurlencode($doc['original_name']) . '"');
    header('Content-Length: ' . filesize($filePath));
    header('Cache-Control: no-cache');
    readfile($filePath);
    exit;
}

if (preg_match('#^/applications/(\d+)/documents/(\d+)/delete$#', $path, $matches) && $method === 'POST') {
    verify_csrf();
    $appId = (int) $matches[1];
    $docId = (int) $matches[2];

    $stmt = $pdo->prepare('SELECT * FROM application_documents WHERE id = ? AND application_id = ?');
    $stmt->execute([$docId, $appId]);
    $doc = $stmt->fetch();

    if ($doc) {
        $filePath = BASE_PATH . '/uploads/' . $doc['stored_name'];
        if (file_exists($filePath)) { unlink($filePath); }
        $pdo->prepare('DELETE FROM application_documents WHERE id = ?')->execute([$docId]);
        flash("Documento \"{$doc['original_name']}\" removido.", 'success');
    }

    redirect_to("/applications/{$appId}/documents");
}

// ── Fim Documentos ──────────────────────────────────────────────────────────

if ($path === '/contacts') {
    $sql = 'SELECT c.*, GROUP_CONCAT(a.name ORDER BY a.name SEPARATOR "||") AS application_names
            FROM contacts c
            LEFT JOIN contact_applications ca ON ca.contact_id = c.id
            LEFT JOIN applications a ON a.id = ca.application_id
            GROUP BY c.id
            ORDER BY c.name';
    $contacts = $pdo->query($sql)->fetchAll();
    render('contacts/index', ['title' => 'Contatos', 'contacts' => $contacts]);
    exit;
}

if ($path === '/contacts/new') {
    $applications = all_applications();

    if ($method === 'POST') {
        verify_csrf();
        $selectedApps = array_map('intval', $_POST['applications'] ?? []);
        $pdo->beginTransaction();
        $stmt = $pdo->prepare('INSERT INTO contacts (name, celular, email_cast, email_tereos) VALUES (?, ?, ?, ?)');
        $stmt->execute([
            trim((string) ($_POST['name'] ?? '')),
            trim((string) ($_POST['celular'] ?? '')),
            trim((string) ($_POST['email_cast'] ?? '')),
            trim((string) ($_POST['email_tereos'] ?? '')),
        ]);
        $contactId = (int) $pdo->lastInsertId();
        $linkStmt = $pdo->prepare('INSERT INTO contact_applications (contact_id, application_id) VALUES (?, ?)');
        foreach ($selectedApps as $appId) {
            $linkStmt->execute([$contactId, $appId]);
        }
        $pdo->commit();
        flash('Contato criado com sucesso.', 'success');
        redirect_to('/contacts');
    }

    render('contacts/form', ['title' => 'Novo Contato', 'contact' => null, 'applications' => $applications, 'selectedApplications' => []]);
    exit;
}

if (preg_match('#^/contacts/(\d+)/edit$#', $path, $matches)) {
    $id = (int) $matches[1];
    $stmt = $pdo->prepare('SELECT * FROM contacts WHERE id = ?');
    $stmt->execute([$id]);
    $contact = $stmt->fetch();

    if (!$contact) {
        http_response_code(404);
        exit('Contato não encontrado.');
    }

    $applications = all_applications();
    $selectedApplications = selected_applications_for_contact($id);

    if ($method === 'POST') {
        verify_csrf();
        $selectedApps = array_map('intval', $_POST['applications'] ?? []);
        $pdo->beginTransaction();
        $stmt = $pdo->prepare('UPDATE contacts SET name = ?, celular = ?, email_cast = ?, email_tereos = ? WHERE id = ?');
        $stmt->execute([
            trim((string) ($_POST['name'] ?? '')),
            trim((string) ($_POST['celular'] ?? '')),
            trim((string) ($_POST['email_cast'] ?? '')),
            trim((string) ($_POST['email_tereos'] ?? '')),
            $id,
        ]);
        $pdo->prepare('DELETE FROM contact_applications WHERE contact_id = ?')->execute([$id]);
        $linkStmt = $pdo->prepare('INSERT INTO contact_applications (contact_id, application_id) VALUES (?, ?)');
        foreach ($selectedApps as $appId) {
            $linkStmt->execute([$id, $appId]);
        }
        $pdo->commit();
        flash('Contato atualizado com sucesso.', 'success');
        redirect_to('/contacts');
    }

    render('contacts/form', ['title' => 'Editar Contato', 'contact' => $contact, 'applications' => $applications, 'selectedApplications' => $selectedApplications]);
    exit;
}

if (preg_match('#^/contacts/(\d+)/delete$#', $path, $matches) && $method === 'POST') {
    verify_csrf();
    $stmt = $pdo->prepare('DELETE FROM contacts WHERE id = ?');
    $stmt->execute([(int) $matches[1]]);
    flash('Contato removido.', 'success');
    redirect_to('/contacts');
}

$normalizeEscalacao = static function (?string $value): string {
    $text = trim((string) ($value ?? ''));
    if ($text === '') {
        return 'OUTROS';
    }

    $normalized = function_exists('iconv')
        ? strtoupper((string) iconv('UTF-8', 'ASCII//TRANSLIT', $text))
        : strtoupper($text);

    if (str_contains($normalized, 'ALTA') || $normalized === 'ALTO') {
        return 'ALTA';
    }
    if (str_contains($normalized, 'MEDIA')) {
        return 'MEDIA';
    }
    if (str_contains($normalized, 'BAIXA')) {
        return 'BAIXA';
    }

    return 'OUTROS';
};

if ($path === '/supplier-contacts') {
    $allStmt = $pdo->query('SELECT * FROM supplier_contacts ORDER BY nome');
    $allSuppliers = $allStmt->fetchAll();

    $supportStmt = $pdo->query('SELECT * FROM supplier_support_contacts ORDER BY fornecedor, tipo');
    $supportContacts = $supportStmt->fetchAll();

    $tables = [
        'alta' => [],
        'media' => [],
        'baixa' => [],
        'outros' => [],
        'suporte' => $supportContacts,
    ];

    foreach ($allSuppliers as &$row) {
        $row['nome'] = normalize_utf8_text($row['nome'] ?? null);
        $row['referencia_escalacao'] = normalize_utf8_text($row['referencia_escalacao'] ?? null);
        $row['cargo_referencia'] = normalize_utf8_text($row['cargo_referencia'] ?? null);
        $row['email'] = normalize_utf8_text($row['email'] ?? null);
        $row['telefone'] = normalize_utf8_text($row['telefone'] ?? null);
        $row['empresa'] = normalize_utf8_text($row['empresa'] ?? null);
        $row['aplicacoes_referencia'] = normalize_utf8_text($row['aplicacoes_referencia'] ?? null);

        $bucket = $normalizeEscalacao($row['referencia_escalacao'] ?? null);
        if ($bucket === 'ALTA') {
            $tables['alta'][] = $row;
        } elseif ($bucket === 'MEDIA') {
            $tables['media'][] = $row;
        } elseif ($bucket === 'BAIXA') {
            $tables['baixa'][] = $row;
        } else {
            $tables['outros'][] = $row;
        }
    }
    unset($row);

    foreach ($supportContacts as &$row) {
        $row['tipo'] = normalize_utf8_text($row['tipo'] ?? null);
        $row['numero'] = normalize_utf8_text($row['numero'] ?? null);
        $row['fornecedor'] = normalize_utf8_text($row['fornecedor'] ?? null);
        $row['aplicacao'] = normalize_utf8_text($row['aplicacao'] ?? null);
        $row['observacao'] = normalize_utf8_text($row['observacao'] ?? null);
    }
    unset($row);

    $tables['suporte'] = $supportContacts;

    render('contacts/suppliers', [
        'title' => 'Contatos Fornecedores',
        'tables' => $tables,
    ]);
    exit;
}

if ($path === '/supplier-contacts/new') {
    if ($method === 'POST') {
        verify_csrf();

        $nome = trim((string) normalize_utf8_text((string) ($_POST['nome'] ?? '')));
        $referenciaEscalacao = trim((string) normalize_utf8_text((string) ($_POST['referencia_escalacao'] ?? '')));
        $cargoReferencia = trim((string) normalize_utf8_text((string) ($_POST['cargo_referencia'] ?? '')));
        $email = trim((string) normalize_utf8_text((string) ($_POST['email'] ?? '')));
        $telefone = trim((string) normalize_utf8_text((string) ($_POST['telefone'] ?? '')));
        $empresa = trim((string) normalize_utf8_text((string) ($_POST['empresa'] ?? '')));
        $aplicacoesReferencia = trim((string) normalize_utf8_text((string) ($_POST['aplicacoes_referencia'] ?? '')));

        if ($nome === '') {
            flash('O nome é obrigatório.', 'danger');
            render('contacts/suppliers_form', [
                'title' => 'Novo Contato Fornecedor',
                'form' => $_POST,
                'formAction' => '/supplier-contacts/new',
                'cancelUrl' => '/supplier-contacts',
                'submitLabel' => 'Salvar contato',
            ]);
            exit;
        }

        $stmt = $pdo->prepare('INSERT INTO supplier_contacts (nome, referencia_escalacao, cargo_referencia, email, telefone, empresa, aplicacoes_referencia) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            $nome,
            $referenciaEscalacao !== '' ? $referenciaEscalacao : null,
            $cargoReferencia !== '' ? $cargoReferencia : null,
            $email !== '' ? $email : null,
            $telefone !== '' ? $telefone : null,
            $empresa !== '' ? $empresa : null,
            $aplicacoesReferencia !== '' ? $aplicacoesReferencia : null,
        ]);

        $bucket = $normalizeEscalacao($referenciaEscalacao);
        $anchor = match ($bucket) {
            'ALTA' => 'sec-alta',
            'MEDIA' => 'sec-media',
            'BAIXA' => 'sec-baixa',
            default => 'sec-outros',
        };

        flash('Contato fornecedor cadastrado com sucesso.', 'success');
        redirect_to('/supplier-contacts#' . $anchor);
    }

    render('contacts/suppliers_form', [
        'title' => 'Novo Contato Fornecedor',
        'form' => [],
        'formAction' => '/supplier-contacts/new',
        'cancelUrl' => '/supplier-contacts',
        'submitLabel' => 'Salvar contato',
    ]);
    exit;
}

if (preg_match('#^/supplier-contacts/(\d+)/edit$#', $path, $matches)) {
    $id = (int) $matches[1];
    $stmt = $pdo->prepare('SELECT * FROM supplier_contacts WHERE id = ?');
    $stmt->execute([$id]);
    $supplier = $stmt->fetch();

    if (!$supplier) {
        http_response_code(404);
        exit('Contato fornecedor não encontrado.');
    }

    if ($method === 'POST') {
        verify_csrf();

        $nome = trim((string) normalize_utf8_text((string) ($_POST['nome'] ?? '')));
        $referenciaEscalacao = trim((string) normalize_utf8_text((string) ($_POST['referencia_escalacao'] ?? '')));
        $cargoReferencia = trim((string) normalize_utf8_text((string) ($_POST['cargo_referencia'] ?? '')));
        $email = trim((string) normalize_utf8_text((string) ($_POST['email'] ?? '')));
        $telefone = trim((string) normalize_utf8_text((string) ($_POST['telefone'] ?? '')));
        $empresa = trim((string) normalize_utf8_text((string) ($_POST['empresa'] ?? '')));
        $aplicacoesReferencia = trim((string) normalize_utf8_text((string) ($_POST['aplicacoes_referencia'] ?? '')));

        if ($nome === '') {
            flash('O nome é obrigatório.', 'danger');
            render('contacts/suppliers_form', [
                'title' => 'Editar Contato Fornecedor',
                'form' => $_POST,
                'formAction' => "/supplier-contacts/{$id}/edit",
                'cancelUrl' => '/supplier-contacts',
                'submitLabel' => 'Salvar alterações',
            ]);
            exit;
        }

        $stmt = $pdo->prepare('UPDATE supplier_contacts SET nome = ?, referencia_escalacao = ?, cargo_referencia = ?, email = ?, telefone = ?, empresa = ?, aplicacoes_referencia = ? WHERE id = ?');
        $stmt->execute([
            $nome,
            $referenciaEscalacao !== '' ? $referenciaEscalacao : null,
            $cargoReferencia !== '' ? $cargoReferencia : null,
            $email !== '' ? $email : null,
            $telefone !== '' ? $telefone : null,
            $empresa !== '' ? $empresa : null,
            $aplicacoesReferencia !== '' ? $aplicacoesReferencia : null,
            $id,
        ]);

        $bucket = $normalizeEscalacao($referenciaEscalacao);
        $anchor = match ($bucket) {
            'ALTA' => 'sec-alta',
            'MEDIA' => 'sec-media',
            'BAIXA' => 'sec-baixa',
            default => 'sec-outros',
        };

        flash('Contato fornecedor atualizado com sucesso.', 'success');
        redirect_to('/supplier-contacts#' . $anchor);
    }

    render('contacts/suppliers_form', [
        'title' => 'Editar Contato Fornecedor',
        'form' => $supplier,
        'formAction' => "/supplier-contacts/{$id}/edit",
        'cancelUrl' => '/supplier-contacts',
        'submitLabel' => 'Salvar alterações',
    ]);
    exit;
}

if (preg_match('#^/supplier-contacts/(\d+)/delete$#', $path, $matches) && $method === 'POST') {
    verify_csrf();
    $id = (int) $matches[1];

    $stmt = $pdo->prepare('SELECT referencia_escalacao FROM supplier_contacts WHERE id = ?');
    $stmt->execute([$id]);
    $supplier = $stmt->fetch();

    if ($supplier) {
        $pdo->prepare('DELETE FROM supplier_contacts WHERE id = ?')->execute([$id]);

        $bucket = $normalizeEscalacao($supplier['referencia_escalacao'] ?? null);
        $anchor = match ($bucket) {
            'ALTA' => 'sec-alta',
            'MEDIA' => 'sec-media',
            'BAIXA' => 'sec-baixa',
            default => 'sec-outros',
        };

        flash('Contato fornecedor removido.', 'success');
        redirect_to('/supplier-contacts#' . $anchor);
    }

    flash('Contato fornecedor não encontrado.', 'warning');
    redirect_to('/supplier-contacts');
}

if ($path === '/schedule') {
    $events = $pdo->query('SELECT * FROM on_calls ORDER BY start_date DESC')->fetchAll();
    render('schedule/index', ['title' => 'Sobreaviso', 'events' => $events]);
    exit;
}

// Editar sobreaviso
if (preg_match('#^/schedule/(\d+)/edit$#', $path, $m)) {
    $onCallId = (int) $m[1];
    $contacts = all_contacts();
    $hasContactIdColumn = on_calls_has_contact_id();

    $stmtOc = $pdo->prepare('SELECT * FROM on_calls WHERE id = ?');
    $stmtOc->execute([$onCallId]);
    $onCall = $stmtOc->fetch();

    if (!$onCall) {
        flash('Registro não encontrado.', 'danger');
        redirect_to('/schedule');
    }

    if ($method === 'POST') {
        verify_csrf();
        $contactId = (int) ($_POST['contact_id'] ?? 0);
        $start = trim((string) ($_POST['start_date'] ?? '')) . ' ' . trim((string) ($_POST['start_time'] ?? '')) . ':00';
        $end   = trim((string) ($_POST['end_date'] ?? '')) . ' ' . trim((string) ($_POST['end_time'] ?? '')) . ':00';

        $contactStmt = $pdo->prepare('SELECT id, name, celular FROM contacts WHERE id = ?');
        $contactStmt->execute([$contactId]);
        $contact = $contactStmt->fetch();

        if (!$contact) {
            flash('Selecione um analista válido da tabela de contatos.', 'danger');
            render('schedule/form', ['title' => 'Editar Escala', 'contacts' => $contacts, 'selectedContactId' => $contactId, 'onCall' => $onCall, 'editId' => $onCallId]);
            exit;
        }

        if (strtotime($end) <= strtotime($start)) {
            flash('A data/hora final deve ser maior que a inicial.', 'danger');
            render('schedule/form', ['title' => 'Editar Escala', 'contacts' => $contacts, 'selectedContactId' => $contactId, 'onCall' => $onCall, 'editId' => $onCallId]);
            exit;
        }

        if ($hasContactIdColumn) {
            $stmtUpd = $pdo->prepare('UPDATE on_calls SET contact_id=?, analyst_name=?, phone=?, start_date=?, end_date=?, observation=? WHERE id=?');
            $stmtUpd->execute([
                (int) $contact['id'],
                trim((string) $contact['name']),
                trim((string) ($contact['celular'] ?? '')),
                $start,
                $end,
                trim((string) ($_POST['observation'] ?? '')),
                $onCallId,
            ]);
        } else {
            $stmtUpd = $pdo->prepare('UPDATE on_calls SET analyst_name=?, phone=?, start_date=?, end_date=?, observation=? WHERE id=?');
            $stmtUpd->execute([
                trim((string) $contact['name']),
                trim((string) ($contact['celular'] ?? '')),
                $start,
                $end,
                trim((string) ($_POST['observation'] ?? '')),
                $onCallId,
            ]);
        }
        flash('Escala atualizada com sucesso.', 'success');
        redirect_to('/schedule');
    }

    render('schedule/form', [
        'title' => 'Editar Escala',
        'contacts' => $contacts,
        'selectedContactId' => (int) ($onCall['contact_id'] ?? 0),
        'onCall' => $onCall,
        'editId' => $onCallId,
    ]);
    exit;
}

// Excluir sobreaviso
if (preg_match('#^/schedule/(\d+)/delete$#', $path, $m)) {
    if ($method !== 'POST') {
        redirect_to('/schedule');
    }
    verify_csrf();
    $onCallId = (int) $m[1];
    $stmtDel = $pdo->prepare('DELETE FROM on_calls WHERE id = ?');
    $stmtDel->execute([$onCallId]);
    flash('Escala excluída com sucesso.', 'success');
    redirect_to('/schedule');
}

if ($path === '/schedule/new') {
    $contacts = all_contacts();
    $hasContactIdColumn = on_calls_has_contact_id();

    if ($method === 'POST') {
        verify_csrf();
        $contactId = (int) ($_POST['contact_id'] ?? 0);
        $start = trim((string) ($_POST['start_date'] ?? '')) . ' ' . trim((string) ($_POST['start_time'] ?? '')) . ':00';
        $end = trim((string) ($_POST['end_date'] ?? '')) . ' ' . trim((string) ($_POST['end_time'] ?? '')) . ':00';

        $contactStmt = $pdo->prepare('SELECT id, name, celular FROM contacts WHERE id = ?');
        $contactStmt->execute([$contactId]);
        $contact = $contactStmt->fetch();

        if (!$contact) {
            flash('Selecione um analista válido da tabela de contatos.', 'danger');
            render('schedule/form', ['title' => 'Nova Escala', 'contacts' => $contacts, 'selectedContactId' => $contactId]);
            exit;
        }

        if (strtotime($end) <= strtotime($start)) {
            flash('A data/hora final deve ser maior que a inicial.', 'danger');
            render('schedule/form', ['title' => 'Nova Escala', 'contacts' => $contacts, 'selectedContactId' => $contactId]);
            exit;
        }

        if ($hasContactIdColumn) {
            $stmt = $pdo->prepare('INSERT INTO on_calls (contact_id, analyst_name, phone, start_date, end_date, observation) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->execute([
                (int) $contact['id'],
                trim((string) $contact['name']),
                trim((string) ($contact['celular'] ?? '')),
                $start,
                $end,
                trim((string) ($_POST['observation'] ?? '')),
            ]);
        } else {
            $stmt = $pdo->prepare('INSERT INTO on_calls (analyst_name, phone, start_date, end_date, observation) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([
                trim((string) $contact['name']),
                trim((string) ($contact['celular'] ?? '')),
                $start,
                $end,
                trim((string) ($_POST['observation'] ?? '')),
            ]);
        }
        flash('Escala cadastrada com sucesso.', 'success');
        redirect_to('/schedule');
    }

    render('schedule/form', ['title' => 'Nova Escala', 'contacts' => $contacts, 'selectedContactId' => 0]);
    exit;
}

if ($path === '/sla') {
    $slaItems = [
        ['priority' => 'Crítica', 'description' => 'Impacto alto em produção, parada total ou risco relevante ao negócio.', 'response_time' => 'Até 15 minutos', 'resolution_time' => 'Até 4 horas'],
        ['priority' => 'Alta', 'description' => 'Impacto significativo, porém com workaround possível.', 'response_time' => 'Até 30 minutos', 'resolution_time' => 'Até 8 horas'],
        ['priority' => 'Média', 'description' => 'Impacto moderado e localizado.', 'response_time' => 'Até 2 horas', 'resolution_time' => 'Até 24 horas'],
        ['priority' => 'Baixa', 'description' => 'Dúvidas, melhorias e ajustes sem impacto direto.', 'response_time' => 'Até 4 horas', 'resolution_time' => 'Até 3 dias úteis'],
    ];

    render('sla', ['title' => 'Tabela de SLA', 'slaItems' => $slaItems]);
    exit;
}

http_response_code(404);
exit('Página não encontrada.');
