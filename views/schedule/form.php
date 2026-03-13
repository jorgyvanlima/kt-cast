<div class="max-w-3xl mx-auto bg-white shadow-md rounded my-6">
    <div class="p-4 bg-gray-50 border-b"><h2 class="text-xl font-bold text-gray-800"><?= h($title) ?></h2></div>
    <form method="post" class="p-6 space-y-6">
        <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Analista</label><input name="analyst_name" class="w-full border rounded px-3 py-2" required></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Telefone</label><input name="phone" class="w-full border rounded px-3 py-2"></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Início</label>
                <div class="grid grid-cols-2 gap-2">
                    <input type="date" name="start_date" class="border rounded px-3 py-2" required>
                    <input type="time" name="start_time" class="border rounded px-3 py-2" required>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fim</label>
                <div class="grid grid-cols-2 gap-2">
                    <input type="date" name="end_date" class="border rounded px-3 py-2" required>
                    <input type="time" name="end_time" class="border rounded px-3 py-2" required>
                </div>
            </div>
        </div>
        <div><label class="block text-sm font-medium text-gray-700 mb-1">Observação</label><input name="observation" class="w-full border rounded px-3 py-2"></div>
        <div class="flex justify-end pt-4">
            <a href="/schedule" class="mr-3 px-4 py-2 rounded border border-gray-300 text-gray-700 text-sm hover:bg-gray-50">Cancelar</a>
            <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded text-sm font-semibold">Salvar</button>
        </div>
    </form>
</div>
