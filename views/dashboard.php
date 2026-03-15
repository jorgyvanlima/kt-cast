<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white rounded-xl shadow p-6 border-l-4 border-blue-900">
        <p class="text-sm text-gray-500">Aplicações</p>
        <p class="text-3xl font-bold text-gray-800 mt-2"><?= h($appCount) ?></p>
    </div>
    <div class="bg-white rounded-xl shadow p-6 border-l-4 border-orange-500">
        <p class="text-sm text-gray-500">Contatos N2</p>
        <p class="text-3xl font-bold text-gray-800 mt-2"><?= h($contactCount) ?></p>
    </div>
    <div class="bg-white rounded-xl shadow p-6 border-l-4 border-green-500">
        <p class="text-sm text-gray-500">Sobreavisos</p>
        <p class="text-3xl font-bold text-gray-800 mt-2"><?= h($scheduleCount) ?></p>
    </div>
</div>

<div class="mt-8 bg-white rounded-xl shadow p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Atalhos</h2>
    <div class="flex flex-wrap gap-3">
        <a href="/applications/new" class="bg-blue-900 text-white px-4 py-2 rounded">Nova Aplicação</a>
        <a href="/contacts/new" class="bg-orange-500 text-white px-4 py-2 rounded">Novo Contato</a>
        <a href="/schedule/new" class="bg-green-600 text-white px-4 py-2 rounded">Nova Escala</a>
    </div>
</div>

<div class="mt-8 bg-white rounded-xl shadow p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4">
        <i class="fas fa-user-clock text-orange-500 mr-2"></i>
        Analistas de Sobreaviso Hoje (<?= date('d/m/Y') ?>)
    </h2>
    <?php if (!empty($todayOnCalls)): ?>
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Analista</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Telefone</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Início</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Fim</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Observação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($todayOnCalls as $oc): ?>
                        <tr>
                            <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm font-bold text-gray-800"><?= h($oc['analyst_name']) ?></td>
                            <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm"><?= h($oc['phone']) ?></td>
                            <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm"><?= h(date('d/m/Y H:i', strtotime($oc['start_date']))) ?></td>
                            <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm"><?= h(date('d/m/Y H:i', strtotime($oc['end_date']))) ?></td>
                            <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm"><?= h($oc['observation']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-gray-500 text-sm">Nenhum analista de sobreaviso agendado para hoje.</p>
    <?php endif; ?>
</div>
