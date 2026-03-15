<?php $item = $contact ?? []; ?>
<div class="max-w-2xl mx-auto bg-white shadow-md rounded my-6 overflow-hidden">
    <div class="p-4 bg-gray-50 border-b"><h2 class="text-xl font-bold text-gray-800"><?= h($title) ?></h2></div>
    <form method="post" class="p-5 sm:p-6 space-y-5">
        <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Nome</label><input name="name" value="<?= h($item['name'] ?? '') ?>" class="w-full border rounded px-3 py-2" required></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Celular</label><input name="celular" value="<?= h($item['celular'] ?? '') ?>" class="w-full border rounded px-3 py-2"></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><label class="block text-sm font-medium text-gray-700 mb-1">E-mail CAST</label><input name="email_cast" value="<?= h($item['email_cast'] ?? '') ?>" class="w-full border rounded px-3 py-2"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">E-mail Tereos</label><input name="email_tereos" value="<?= h($item['email_tereos'] ?? '') ?>" class="w-full border rounded px-3 py-2"></div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Escalação (Aplicações)</label>
            <select name="applications[]" multiple size="10" class="w-full border rounded px-3 py-2 h-40">
                <?php foreach ($applications as $application): ?>
                    <option value="<?= h($application['id']) ?>" <?= in_array((int) $application['id'], $selectedApplications, true) ? 'selected' : '' ?>><?= h($application['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <p class="text-xs text-gray-500 mt-1">Use Ctrl+clique para selecionar várias aplicações.</p>
        </div>
        <div class="flex flex-col-reverse sm:flex-row sm:justify-end pt-4 gap-3">
            <a href="/contacts" class="px-4 py-2 rounded border border-gray-300 text-gray-700 text-sm hover:bg-gray-50 text-center">Cancelar</a>
            <button type="submit" class="bg-cast-blue text-white px-5 py-2 rounded text-sm font-semibold">Salvar</button>
        </div>
    </form>
</div>
