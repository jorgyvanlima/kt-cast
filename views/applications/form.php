<?php $app = $application ?? []; ?>
<div class="max-w-5xl mx-auto bg-white shadow-md rounded my-6">
    <div class="p-4 bg-gray-50 border-b"><h2 class="text-xl font-bold text-gray-800"><?= h($title) ?></h2></div>
    <form method="post" class="p-6 space-y-6">
        <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Nome da Aplicação</label><input name="name" value="<?= h($app['name'] ?? '') ?>" class="w-full border rounded px-3 py-2" required></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Esteira</label><input name="n2_track" value="<?= h($app['n2_track'] ?? '') ?>" class="w-full border rounded px-3 py-2"></div>
        </div>
        <div><label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label><textarea name="description" class="w-full border rounded px-3 py-2 h-24"><?= h($app['description'] ?? '') ?></textarea></div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Fila CAST</label><input name="fila_cast" value="<?= h($app['fila_cast'] ?? '') ?>" class="w-full border rounded px-3 py-2"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Entrada</label><input name="hora_entrada" value="<?= h($app['hora_entrada'] ?? '') ?>" class="w-full border rounded px-3 py-2"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Saída</label><input name="hora_saida" value="<?= h($app['hora_saida'] ?? '') ?>" class="w-full border rounded px-3 py-2"></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Almoço</label><input name="hora_almoco" value="<?= h($app['hora_almoco'] ?? '') ?>" class="w-full border rounded px-3 py-2"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Criticidade</label><select name="criticidade" class="w-full border rounded px-3 py-2 bg-white"><option value="">Selecione</option><?php foreach (['Baixa','Média','Alta','Crítica','Medium','High','Very High'] as $level): ?><option value="<?= h($level) ?>" <?= (($app['criticidade'] ?? '') === $level) ? 'selected' : '' ?>><?= h($level) ?></option><?php endforeach; ?></select></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Analista CAST N2</label><input name="analista_cast_n2" value="<?= h($app['analista_cast_n2'] ?? '') ?>" class="w-full border rounded px-3 py-2"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Analista Tereos N2</label><input name="analista_tereos_n2" value="<?= h($app['analista_tereos_n2'] ?? '') ?>" class="w-full border rounded px-3 py-2"></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Operação Tereos</label><input name="operacoes_tereos" value="<?= h($app['operacoes_tereos'] ?? '') ?>" class="w-full border rounded px-3 py-2"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Suporte Fornecedor</label><input name="suporte_fornecedor" value="<?= h($app['suporte_fornecedor'] ?? '') ?>" class="w-full border rounded px-3 py-2"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Fornecedor</label><input name="fornecedor" value="<?= h($app['fornecedor'] ?? '') ?>" class="w-full border rounded px-3 py-2"></div>
        </div>
        <div><label class="block text-sm font-medium text-gray-700 mb-1">Business Application (SNOW)</label><input name="business_application_snow" value="<?= h($app['business_application_snow'] ?? '') ?>" class="w-full border rounded px-3 py-2"></div>
        <div class="flex justify-end pt-4">
            <a href="/applications" class="mr-3 px-4 py-2 rounded border border-gray-300 text-gray-700 text-sm hover:bg-gray-50">Cancelar</a>
            <button type="submit" class="bg-cast-blue hover-cast-orange text-white px-4 py-2 rounded text-sm font-semibold">Salvar</button>
        </div>
    </form>
</div>
