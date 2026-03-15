<div class="max-w-3xl mx-auto bg-white shadow-md rounded my-6">
    <div class="p-4 bg-gray-50 border-b"><h2 class="text-xl font-bold text-gray-800"><?= h($title) ?></h2></div>
    <form method="post" action="<?= isset($editId) ? '/schedule/' . h((string) $editId) . '/edit' : '/schedule/new' ?>" class="p-6 space-y-6">
        <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Analista (contatos N2)</label>
                <select id="contact-select" name="contact_id" class="w-full border rounded px-3 py-2" required>
                    <option value="">Selecione...</option>
                    <?php foreach (($contacts ?? []) as $contact): ?>
                        <option value="<?= h((string) $contact['id']) ?>"
                                data-phone="<?= h((string) ($contact['celular'] ?? '')) ?>"
                                <?= ((int) ($selectedContactId ?? 0) === (int) $contact['id']) ? 'selected' : '' ?>>
                            <?= h($contact['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Telefone (automatico)</label>
                <input id="contact-phone" class="w-full border rounded px-3 py-2 bg-gray-100" readonly>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Início</label>
                <div class="grid grid-cols-2 gap-2">
                    <input type="date" name="start_date" class="border rounded px-3 py-2" required value="<?= isset($onCall) ? h(date('Y-m-d', strtotime($onCall['start_date']))) : '' ?>">
                    <input type="time" name="start_time" class="border rounded px-3 py-2" required value="<?= isset($onCall) ? h(date('H:i', strtotime($onCall['start_date']))) : '' ?>">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fim</label>
                <div class="grid grid-cols-2 gap-2">
                    <input type="date" name="end_date" class="border rounded px-3 py-2" required value="<?= isset($onCall) ? h(date('Y-m-d', strtotime($onCall['end_date']))) : '' ?>">
                    <input type="time" name="end_time" class="border rounded px-3 py-2" required value="<?= isset($onCall) ? h(date('H:i', strtotime($onCall['end_date']))) : '' ?>">
                </div>
            </div>
        </div>
        <div><label class="block text-sm font-medium text-gray-700 mb-1">Observação</label><input name="observation" class="w-full border rounded px-3 py-2" value="<?= isset($onCall) ? h($onCall['observation']) : '' ?>"></div>
        <div class="flex justify-end pt-4">
            <a href="/schedule" class="mr-3 px-4 py-2 rounded border border-gray-300 text-gray-700 text-sm hover:bg-gray-50">Cancelar</a>
            <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded text-sm font-semibold">Salvar</button>
        </div>
    </form>
</div>

<script>
(function () {
    const selectEl = document.getElementById('contact-select');
    const phoneEl = document.getElementById('contact-phone');

    if (!selectEl || !phoneEl) {
        return;
    }

    const syncPhone = () => {
        const option = selectEl.options[selectEl.selectedIndex];
        phoneEl.value = option ? (option.dataset.phone || '') : '';
    };

    selectEl.addEventListener('change', syncPhone);
    syncPhone();
})();
</script>
