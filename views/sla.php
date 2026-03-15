<div class="bg-white shadow-md rounded my-6 overflow-hidden">
    <div class="p-4 bg-gray-50 border-b">
        <h2 class="text-xl font-bold text-gray-800">Tabela de SLA</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="table-stack min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Prioridade</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Descrição</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tempo de resposta</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tempo de resolução</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($slaItems as $item): ?>
                    <tr>
                        <td data-label="Prioridade" class="px-5 py-5 border-b border-gray-200 bg-white text-sm font-semibold"><?= h($item['priority']) ?></td>
                        <td data-label="Descrição" class="table-full px-5 py-5 border-b border-gray-200 bg-white text-sm"><?= h($item['description']) ?></td>
                        <td data-label="Tempo de resposta" class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?= h($item['response_time']) ?></td>
                        <td data-label="Tempo de resolução" class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?= h($item['resolution_time']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
