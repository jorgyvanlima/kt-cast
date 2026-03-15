<?php
$pagination = $pagination ?? ['page' => 1, 'perPage' => 10, 'totalItems' => 0, 'totalPages' => 1, 'startItem' => 0, 'endItem' => 0];
$currentPage = (int) ($pagination['page'] ?? 1);
$totalPages = (int) ($pagination['totalPages'] ?? 1);
$queryBase = [];
if (!empty($search)) {
    $queryBase['search'] = $search;
}
?>

<div class="bg-white shadow-md rounded my-6 overflow-hidden">
    <div class="p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-gray-50 border-b">
        <h2 class="text-xl font-bold text-gray-800">Diretório de Aplicações</h2>
        <a href="/applications/new" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-sm inline-flex items-center w-full sm:w-auto justify-center">
            <i class="fas fa-plus mr-2"></i>Nova Aplicação
        </a>
    </div>
    <div class="p-4 border-b bg-white">
        <form method="get" action="/applications" class="flex flex-col md:flex-row gap-2 md:items-center">
            <label for="search" class="text-sm font-medium text-gray-700">Localizar</label>
            <input
                id="search"
                name="search"
                type="text"
                value="<?= h($search ?? '') ?>"
                placeholder="Buscar por nome, analista CAST, analista Tereos, esteira ou fornecedor"
                class="w-full md:max-w-xl border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
            <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">
                    Buscar
                </button>
                <?php if (!empty($search)): ?>
                    <a href="/applications" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded text-sm">
                        Limpar
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>
    <div class="px-4 py-3 border-b bg-gray-50 flex flex-col md:flex-row md:items-center md:justify-between gap-2 text-sm text-gray-600">
        <div>
            Exibindo <?= h((string) $pagination['startItem']) ?> a <?= h((string) $pagination['endItem']) ?> de <?= h((string) $pagination['totalItems']) ?> registros
            <?php if (!empty($search)): ?>
                para a busca "<?= h($search) ?>"
            <?php endif; ?>
        </div>
        <div>
            <?= h((string) $totalPages) ?> página(s) no total
        </div>
    </div>
    <div class="overflow-x-auto">
    <table class="table-stack min-w-full leading-normal text-xs">
        <thead>
            <tr class="bg-gray-100 text-gray-600 uppercase text-xs">
                <th class="px-3 py-3 border-b-2 border-gray-200 text-left">Nome</th>
                <th class="px-3 py-3 border-b-2 border-gray-200 text-left">Fila CAST</th>
                <th class="px-3 py-3 border-b-2 border-gray-200 text-left">Entrada / Saída</th>
                <th class="px-3 py-3 border-b-2 border-gray-200 text-left">Almoço</th>
                <th class="px-3 py-3 border-b-2 border-gray-200 text-left">Analista CAST N2</th>
                <th class="px-3 py-3 border-b-2 border-gray-200 text-left">Analista Tereos N2</th>
                <th class="px-3 py-3 border-b-2 border-gray-200 text-left">Esteira</th>
                <th class="px-3 py-3 border-b-2 border-gray-200 text-left">Criticidade</th>
                <th class="px-3 py-3 border-b-2 border-gray-200 text-left">Operação Tereos</th>
                <th class="px-3 py-3 border-b-2 border-gray-200 text-left">Suporte Fornecedor</th>
                <th class="px-3 py-3 border-b-2 border-gray-200 text-left">Fornecedor</th>
                <th class="px-3 py-3 border-b-2 border-gray-200 text-left">Business App (SNOW)</th>
                <th class="px-3 py-3 border-b-2 border-gray-200 text-center">Documentação</th>
                <th class="px-3 py-3 border-b-2 border-gray-200 text-left">Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($applications): ?>
            <?php foreach ($applications as $application): ?>
                <tr class="hover:bg-gray-50">
                    <td data-label="Nome" class="px-3 py-3 border-b border-gray-200 font-semibold text-gray-900 whitespace-nowrap"><?= h($application['name']) ?></td>
                    <td data-label="Fila CAST" class="px-3 py-3 border-b border-gray-200"><?= h($application['fila_cast']) ?></td>
                    <td data-label="Entrada / Saída" class="px-3 py-3 border-b border-gray-200"><?= h($application['hora_entrada']) ?><?= $application['hora_saida'] ? ' - ' . h($application['hora_saida']) : '' ?></td>
                    <td data-label="Almoço" class="px-3 py-3 border-b border-gray-200"><?= h($application['hora_almoco']) ?></td>
                    <td data-label="Analista CAST N2" class="px-3 py-3 border-b border-gray-200"><?= h($application['analista_cast_n2']) ?></td>
                    <td data-label="Analista Tereos N2" class="px-3 py-3 border-b border-gray-200"><?= h($application['analista_tereos_n2']) ?></td>
                    <td data-label="Esteira" class="px-3 py-3 border-b border-gray-200"><?= h($application['n2_track']) ?></td>
                    <td data-label="Criticidade" class="px-3 py-3 border-b border-gray-200"><?= h($application['criticidade']) ?></td>
                    <td data-label="Operação Tereos" class="px-3 py-3 border-b border-gray-200"><?= h($application['operacoes_tereos']) ?></td>
                    <td data-label="Suporte Fornecedor" class="px-3 py-3 border-b border-gray-200"><?= h($application['suporte_fornecedor']) ?></td>
                    <td data-label="Fornecedor" class="px-3 py-3 border-b border-gray-200"><?= h($application['fornecedor']) ?></td>
                    <td data-label="Business App (SNOW)" class="px-3 py-3 border-b border-gray-200"><?= h($application['business_application_snow']) ?></td>
                    <td data-label="Documentação" class="px-3 py-3 border-b border-gray-200 text-center">
                        <a href="/applications/<?= h($application['id']) ?>/documents"
                           class="inline-flex items-center gap-1 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 hover:border-blue-400 rounded-lg px-3 py-1 text-xs font-medium transition-colors"
                           title="Ver documentos de <?= h($application['name']) ?>">
                            <i class="fas fa-folder-open"></i>
                            <span class="hidden md:inline">Docs</span>
                        </a>
                    </td>
                    <td class="table-actions px-3 py-3 border-b border-gray-200 whitespace-nowrap">
                        <a href="/applications/<?= h($application['id']) ?>/edit" class="text-blue-600 hover:text-blue-800 mr-3"><i class="fas fa-edit"></i></a>
                        <form method="post" action="/applications/<?= h($application['id']) ?>/delete" class="inline" onsubmit="return confirm('Remover aplicação?');">
                            <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
                            <button type="submit" class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="14" class="px-5 py-8 text-center text-gray-500">Nenhuma aplicação cadastrada.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
    </div>
</div>

<?php if ($totalPages > 1): ?>
    <div class="flex flex-wrap items-center justify-center gap-2 mb-6 text-sm">
        <?php if ($currentPage > 1): ?>
            <a href="/applications?<?= h(http_build_query(array_merge($queryBase, ['page' => $currentPage - 1]))) ?>" class="px-3 py-2 rounded border border-gray-300 text-gray-700 hover:bg-gray-50">Anterior</a>
        <?php endif; ?>

        <?php
        $startPage = max(1, $currentPage - 2);
        $endPage = min($totalPages, $currentPage + 2);

        if ($startPage > 1):
        ?>
            <a href="/applications?<?= h(http_build_query(array_merge($queryBase, ['page' => 1]))) ?>" class="px-3 py-2 rounded border border-gray-300 text-gray-700 hover:bg-gray-50">1</a>
            <?php if ($startPage > 2): ?>
                <span class="px-1 text-gray-400">...</span>
            <?php endif; ?>
        <?php endif; ?>

        <?php for ($pageNumber = $startPage; $pageNumber <= $endPage; $pageNumber++): ?>
            <a href="/applications?<?= h(http_build_query(array_merge($queryBase, ['page' => $pageNumber]))) ?>"
               class="px-3 py-2 rounded border <?= $pageNumber === $currentPage ? 'bg-blue-600 border-blue-600 text-white' : 'border-gray-300 text-gray-700 hover:bg-gray-50' ?>">
                <?= h((string) $pageNumber) ?>
            </a>
        <?php endfor; ?>

        <?php if ($endPage < $totalPages): ?>
            <?php if ($endPage < $totalPages - 1): ?>
                <span class="px-1 text-gray-400">...</span>
            <?php endif; ?>
            <a href="/applications?<?= h(http_build_query(array_merge($queryBase, ['page' => $totalPages]))) ?>" class="px-3 py-2 rounded border border-gray-300 text-gray-700 hover:bg-gray-50"><?= h((string) $totalPages) ?></a>
        <?php endif; ?>

        <?php if ($currentPage < $totalPages): ?>
            <a href="/applications?<?= h(http_build_query(array_merge($queryBase, ['page' => $currentPage + 1]))) ?>" class="px-3 py-2 rounded border border-gray-300 text-gray-700 hover:bg-gray-50">Próxima</a>
        <?php endif; ?>
    </div>
<?php endif; ?>
