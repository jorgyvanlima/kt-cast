<form method="post" class="space-y-5">
    <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Usuário</label>
        <input type="text" name="username" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200" required>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Senha</label>
        <input type="password" name="password" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200" required>
    </div>
    <button type="submit" class="w-full bg-cast-blue text-white py-2 rounded font-semibold hover:bg-blue-900">Entrar</button>
</form>
