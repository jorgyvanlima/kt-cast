<?php
/** @var array $form */
/** @var string $formAction */
/** @var string $cancelUrl */
/** @var string $submitLabel */
?>

<div class="max-w-3xl mx-auto">
    <div class="bg-white shadow-md rounded p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-800">Novo Contato Fornecedor</h2>
            <a href="/supplier-contacts" class="text-blue-600 hover:text-blue-800 text-sm inline-flex items-center">
                <i class="fas fa-arrow-left mr-1"></i>Voltar
            </a>
        </div>

        <form method="post" action="<?= h($formAction ?? '/supplier-contacts/new') ?>" class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nome *</label>
                <input type="text" name="nome" required value="<?= h($form['nome'] ?? '') ?>" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Referência de escalação</label>
                    <select name="referencia_escalacao" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <?php $ref = (string) ($form['referencia_escalacao'] ?? ''); ?>
                        <option value="" <?= $ref === '' ? 'selected' : '' ?>>Selecione</option>
                        <option value="Alta" <?= $ref === 'Alta' ? 'selected' : '' ?>>Alta</option>
                        <option value="Média" <?= $ref === 'Média' ? 'selected' : '' ?>>Média</option>
                        <option value="Baixa" <?= $ref === 'Baixa' ? 'selected' : '' ?>>Baixa</option>
                        <option value="Outros" <?= $ref === 'Outros' ? 'selected' : '' ?>>Outros</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cargo/Referência</label>
                    <input type="text" name="cargo_referencia" value="<?= h($form['cargo_referencia'] ?? '') ?>" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
                    <input type="email" name="email" value="<?= h($form['email'] ?? '') ?>" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Telefone/Celular</label>
                    <input type="text" name="telefone" value="<?= h($form['telefone'] ?? '') ?>" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Empresa</label>
                    <input type="text" name="empresa" value="<?= h($form['empresa'] ?? '') ?>" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Aplicações de referência</label>
                    <input type="text" name="aplicacoes_referencia" value="<?= h($form['aplicacoes_referencia'] ?? '') ?>" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="pt-2 flex gap-2">
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-sm inline-flex items-center">
                    <i class="fas fa-save mr-2"></i><?= h($submitLabel ?? 'Salvar contato') ?>
                </button>
                <a href="<?= h($cancelUrl ?? '/supplier-contacts') ?>" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded text-sm">Cancelar</a>
            </div>
        </form>
    </div>
</div>
