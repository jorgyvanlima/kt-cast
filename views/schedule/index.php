<div class="bg-white shadow-md rounded my-6">
    <div class="p-4 flex justify-between items-center bg-gray-50 border-b">
        <h2 class="text-xl font-bold text-gray-800">Escala de Sobreaviso</h2>
        <a href="/schedule/new" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded text-sm inline-flex items-center"><i class="fas fa-calendar-plus mr-2"></i>Adicionar Escala</a>
    </div>
    <div class="p-4 overflow-x-auto">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Analista</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Telefone</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Início</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Fim</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Observação</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($events): ?>
                    <?php foreach ($events as $event): ?>
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm font-bold"><?= h($event['analyst_name']) ?></td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?= h($event['phone']) ?></td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?= h(date('d/m/Y H:i', strtotime($event['start_date']))) ?></td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?= h(date('d/m/Y H:i', strtotime($event['end_date']))) ?></td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?= h($event['observation']) ?></td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center whitespace-nowrap">
                                <a href="/schedule/<?= h((string) $event['id']) ?>/edit" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white text-xs px-3 py-1 rounded mr-1"><i class="fas fa-edit mr-1"></i>Editar</a>
                                <form method="post" action="/schedule/<?= h((string) $event['id']) ?>/delete" class="inline" onsubmit="return confirm('Confirma a exclusão desta escala?')">
                                    <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
                                    <button type="submit" class="inline-flex items-center bg-red-600 hover:bg-red-700 text-white text-xs px-3 py-1 rounded"><i class="fas fa-trash mr-1"></i>Excluir</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center text-gray-500">Nenhuma escala programada encontrada.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
