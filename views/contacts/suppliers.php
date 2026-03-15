<?php
/** @var array $tables */

function table_title(string $key): string
{
    return match ($key) {
        'alta' => '1) Escalação Alta',
        'media' => '2) Escalação Média',
        'baixa' => '3) Escalação Baixa',
        'outros' => '4) Demais Contatos de Fornecedores',
        'suporte' => '5) Contatos de Suporte',
        default => $key,
    };
}
?>

<div class="space-y-6">
    <div class="bg-white shadow-md rounded p-4 border-l-4 border-blue-600 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Contatos de Fornecedores</h2>
        </div>
        <a href="/supplier-contacts/new" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-sm inline-flex items-center w-fit">
            <i class="fas fa-plus mr-2"></i>Novo contato fornecedor
        </a>
    </div>

    <?php foreach (['alta', 'media', 'baixa', 'outros'] as $bucket): ?>
        <div id="sec-<?= h($bucket) ?>" class="bg-white shadow-md rounded overflow-hidden scroll-mt-24">
            <div class="p-4 bg-gray-50 border-b">
                <h3 class="font-semibold text-gray-800"><?= h(table_title($bucket)) ?> <span class="text-gray-500 text-sm">(<?= count($tables[$bucket]) ?>)</span></h3>
            </div>
            <div class="overflow-x-auto">
            <table class="table-stack min-w-full leading-normal text-xs">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-xs">
                        <th class="px-3 py-3 border-b-2 border-gray-200 text-left">Nome</th>
                        <th class="px-3 py-3 border-b-2 border-gray-200 text-left">Referência de escalação</th>
                        <th class="px-3 py-3 border-b-2 border-gray-200 text-left">Cargo/Referência</th>
                        <th class="px-3 py-3 border-b-2 border-gray-200 text-left">E-mail</th>
                        <th class="px-3 py-3 border-b-2 border-gray-200 text-left">Telefone/Celular</th>
                        <th class="px-3 py-3 border-b-2 border-gray-200 text-left">Empresa</th>
                        <th class="px-3 py-3 border-b-2 border-gray-200 text-left">Aplicações de referência</th>
                        <th class="px-3 py-3 border-b-2 border-gray-200 text-left">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($tables[$bucket])): ?>
                        <?php foreach ($tables[$bucket] as $row): ?>
                            <tr class="hover:bg-gray-50">
                                <td data-label="Nome" class="px-3 py-3 border-b border-gray-200 font-semibold text-gray-900"><?= h($row['nome']) ?></td>
                                <td data-label="Referência de escalação" class="px-3 py-3 border-b border-gray-200"><?= h($row['referencia_escalacao']) ?></td>
                                <td data-label="Cargo/Referência" class="px-3 py-3 border-b border-gray-200"><?= h($row['cargo_referencia']) ?></td>
                                <td data-label="E-mail" class="px-3 py-3 border-b border-gray-200"><?= h($row['email']) ?></td>
                                <td data-label="Telefone/Celular" class="px-3 py-3 border-b border-gray-200"><?= h($row['telefone']) ?></td>
                                <td data-label="Empresa" class="px-3 py-3 border-b border-gray-200"><?= h($row['empresa']) ?></td>
                                <td data-label="Aplicações de referência" class="table-full px-3 py-3 border-b border-gray-200"><?= h($row['aplicacoes_referencia']) ?></td>
                                <td class="table-actions px-3 py-3 border-b border-gray-200 whitespace-nowrap">
                                    <a href="/supplier-contacts/<?= h($row['id']) ?>/edit" class="text-blue-600 hover:text-blue-800 mr-3" title="Editar contato">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="post" action="/supplier-contacts/<?= h($row['id']) ?>/delete" class="inline" onsubmit="return confirm('Excluir este contato fornecedor?');">
                                        <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
                                        <button type="submit" class="text-red-500 hover:text-red-700" title="Excluir contato">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="8" class="px-5 py-8 text-center text-gray-500">Sem registros nesta tabela.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
            </div>
        </div>
    <?php endforeach; ?>

    <div id="sec-suporte" class="bg-white shadow-md rounded overflow-hidden scroll-mt-24">
        <div class="p-4 bg-gray-50 border-b">
            <h3 class="font-semibold text-gray-800"><?= h(table_title('suporte')) ?> <span class="text-gray-500 text-sm">(<?= count($tables['suporte']) ?>)</span></h3>
        </div>
        <div class="overflow-x-auto">
        <table class="table-stack min-w-full leading-normal text-xs">
            <thead>
                <tr class="bg-gray-100 text-gray-600 uppercase text-xs">
                    <th class="px-3 py-3 border-b-2 border-gray-200 text-left">Tipo</th>
                    <th class="px-3 py-3 border-b-2 border-gray-200 text-left">Número</th>
                    <th class="px-3 py-3 border-b-2 border-gray-200 text-left">Fornecedor</th>
                    <th class="px-3 py-3 border-b-2 border-gray-200 text-left">Aplicação</th>
                    <th class="px-3 py-3 border-b-2 border-gray-200 text-left">Observação</th>
                    <th class="px-3 py-3 border-b-2 border-gray-200 text-left">Link do portal</th>
                    <th class="px-3 py-3 border-b-2 border-gray-200 text-left">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($tables['suporte'])): ?>
                    <?php foreach ($tables['suporte'] as $row): ?>
                        <tr class="hover:bg-gray-50">
                            <td data-label="Tipo" class="px-3 py-3 border-b border-gray-200"><?= h($row['tipo']) ?></td>
                            <td data-label="Número" class="px-3 py-3 border-b border-gray-200"><?= h($row['numero']) ?></td>
                            <td data-label="Fornecedor" class="px-3 py-3 border-b border-gray-200 font-semibold text-gray-900"><?= h($row['fornecedor']) ?></td>
                            <td data-label="Aplicação" class="px-3 py-3 border-b border-gray-200"><?= h($row['aplicacao']) ?></td>
                            <td data-label="Observação" class="table-full px-3 py-3 border-b border-gray-200"><?= h($row['observacao']) ?></td>
                            <td data-label="Link do portal" class="px-3 py-3 border-b border-gray-200">
                                <?php if (!empty($row['portal_link'])): ?>
                                    <a href="<?= h($row['portal_link']) ?>" target="_blank" rel="noopener" class="text-blue-600 hover:text-blue-800 underline">Acessar</a>
                                <?php endif; ?>
                            </td>
                            <td class="table-actions px-3 py-3 border-b border-gray-200 whitespace-nowrap">
                                <a href="/supplier-support-contacts/<?= h((string) $row['id']) ?>/edit" class="text-blue-600 hover:text-blue-800 mr-3" title="Editar contato suporte">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="post" action="/supplier-support-contacts/<?= h((string) $row['id']) ?>/delete" class="inline" onsubmit="return confirm('Excluir este contato de suporte?');">
                                    <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
                                    <button type="submit" class="text-red-500 hover:text-red-700" title="Excluir contato suporte">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="px-5 py-8 text-center text-gray-500">Sem registros de suporte.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        </div>
    </div>
</div>
