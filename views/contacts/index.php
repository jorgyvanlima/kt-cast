<div class="bg-white shadow-md rounded my-6 overflow-x-auto">
    <div class="p-4 flex justify-between items-center bg-gray-50 border-b">
        <h2 class="text-xl font-bold text-gray-800">Analistas N2 – Contatos e Escalação</h2>
        <a href="/contacts/new" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-sm inline-flex items-center"><i class="fas fa-plus mr-2"></i>Novo Contato</a>
    </div>
    <table class="min-w-full leading-normal">
        <thead>
            <tr class="bg-gray-100 text-gray-600 uppercase text-xs">
                <th class="px-4 py-3 border-b-2 border-gray-200 text-left">Nome</th>
                <th class="px-4 py-3 border-b-2 border-gray-200 text-left">Celular</th>
                <th class="px-4 py-3 border-b-2 border-gray-200 text-left">E-mail CAST</th>
                <th class="px-4 py-3 border-b-2 border-gray-200 text-left">E-mail Tereos</th>
                <th class="px-4 py-3 border-b-2 border-gray-200 text-left">Escalação (Aplicações)</th>
                <th class="px-4 py-3 border-b-2 border-gray-200 text-left">Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($contacts): ?>
            <?php foreach ($contacts as $contact): ?>
                <?php $appNames = array_filter(explode('||', (string) ($contact['application_names'] ?? ''))); ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-4 border-b border-gray-200 font-semibold text-gray-900"><?= h($contact['name']) ?></td>
                    <td class="px-4 py-4 border-b border-gray-200"><?= h($contact['celular']) ?></td>
                    <td class="px-4 py-4 border-b border-gray-200 text-sm"><?= h($contact['email_cast']) ?></td>
                    <td class="px-4 py-4 border-b border-gray-200 text-sm"><?= h($contact['email_tereos']) ?></td>
                    <td class="px-4 py-4 border-b border-gray-200 text-sm">
                        <?php if ($appNames): ?>
                            <?php foreach ($appNames as $appName): ?>
                                <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mr-1 mb-1"><?= h($appName) ?></span>
                            <?php endforeach; ?>
                        <?php else: ?>—<?php endif; ?>
                    </td>
                    <td class="px-4 py-4 border-b border-gray-200 whitespace-nowrap">
                        <a href="/contacts/<?= h($contact['id']) ?>/edit" class="text-blue-600 hover:text-blue-800 mr-3"><i class="fas fa-edit"></i></a>
                        <form method="post" action="/contacts/<?= h($contact['id']) ?>/delete" class="inline" onsubmit="return confirm('Remover contato?');">
                            <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
                            <button type="submit" class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="6" class="px-5 py-8 text-center text-gray-500">Nenhum contato cadastrado.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
