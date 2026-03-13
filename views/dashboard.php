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
