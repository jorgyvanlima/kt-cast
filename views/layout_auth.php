<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($title) ?> - KT-CAST</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root { --cast-blue: #003366; --cast-orange: #FF6600; }
        .bg-cast-blue { background-color: var(--cast-blue); }
        .bg-cast-orange { background-color: var(--cast-orange); }
    </style>
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center px-4 py-6 sm:py-10">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-cast-blue px-5 py-5 sm:px-6 text-white text-center">
            <h1 class="text-3xl font-bold">KT-<span class="text-orange-400">CAST</span></h1>
            <p class="text-sm text-gray-200 mt-2">Portal em PHP</p>
        </div>
        <div class="p-5 sm:p-6">
            <?php foreach ($flashes as $flash): ?>
                <?php $styles = ['success' => 'bg-green-100 border-green-500 text-green-700', 'danger' => 'bg-red-100 border-red-500 text-red-700', 'warning' => 'bg-yellow-100 border-yellow-500 text-yellow-700', 'info' => 'bg-blue-100 border-blue-500 text-blue-700']; ?>
                <div class="border-l-4 p-4 mb-4 text-sm sm:text-base <?= $styles[$flash['type']] ?? $styles['info'] ?>">
                    <p><?= h($flash['message']) ?></p>
                </div>
            <?php endforeach; ?>
            <?php require $contentView; ?>
        </div>
    </div>
</body>
</html>
