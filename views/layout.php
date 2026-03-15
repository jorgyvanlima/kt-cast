<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($title) ?> - KT-CAST</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --cast-blue: #003366;
            --cast-orange: #FF6600;
            --cast-gray: #f4f7f6;
        }
        .bg-cast-blue { background-color: var(--cast-blue); }
        .text-cast-blue { color: var(--cast-blue); }
        .text-cast-orange { color: var(--cast-orange); }
        .hover-cast-orange:hover { background-color: #e65c00; }
        .ring-cast-blue:focus { box-shadow: 0 0 0 2px rgba(0, 51, 102, .2); }
    </style>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
<div class="flex flex-col md:flex-row">
    <div class="bg-cast-blue shadow-xl h-16 fixed bottom-0 md:relative md:h-screen z-10 w-full md:w-64">
        <div class="md:mt-12 md:w-64 md:fixed md:left-0 md:top-0 content-center md:content-start text-left justify-between">
            <div class="flex flex-row md:flex-col py-0 md:py-3 px-1 md:px-6 justify-between md:justify-start text-white">
                <div class="flex-1 md:flex-none py-3 md:py-0">
                    <h1 class="font-bold text-xl md:text-2xl text-white pl-2">KT-<span class="text-cast-orange">CAST</span></h1>
                </div>
                <ul class="list-reset flex flex-row md:flex-col py-0 md:py-3 px-1 md:px-0 text-center md:text-left gap-1 md:gap-2">
                    <li class="mr-3 md:mr-0 flex-1 md:flex-none"><a href="/dashboard" class="block py-2 md:py-3 px-3 text-white rounded-lg transition <?= active_menu('/dashboard') || request_path() === '/' ? 'bg-white/10 text-white' : 'hover:bg-white/10' ?>"><i class="fas fa-chart-line pr-0 md:pr-3"></i><span class="text-xs md:text-base text-gray-200">Dashboard</span></a></li>
                    <li class="mr-3 md:mr-0 flex-1 md:flex-none"><a href="/applications" class="block py-2 md:py-3 px-3 text-white rounded-lg transition <?= active_menu('/applications') ? 'bg-white/10 text-white' : 'hover:bg-white/10' ?>"><i class="fas fa-list pr-0 md:pr-3"></i><span class="text-xs md:text-base text-gray-200">Aplicações</span></a></li>
                    <li class="mr-3 md:mr-0 flex-1 md:flex-none"><a href="/contacts" class="block py-2 md:py-3 px-3 text-white rounded-lg transition <?= active_menu('/contacts') ? 'bg-white/10 text-white' : 'hover:bg-white/10' ?>"><i class="fas fa-address-book pr-0 md:pr-3"></i><span class="text-xs md:text-base text-gray-200">Analistas CAST</span></a></li>
                    <li class="mr-3 md:mr-0 flex-1 md:flex-none"><a href="/supplier-contacts" class="block py-2 md:py-3 px-3 text-white rounded-lg transition <?= active_menu('/supplier-contacts') ? 'bg-white/10 text-white' : 'hover:bg-white/10' ?>"><i class="fas fa-building pr-0 md:pr-3"></i><span class="text-xs md:text-base text-gray-200">Contatos Forn.</span></a></li>
                    <li class="mr-3 md:mr-0 flex-1 md:flex-none"><a href="/schedule" class="block py-2 md:py-3 px-3 text-white rounded-lg transition <?= active_menu('/schedule') ? 'bg-white/10 text-white' : 'hover:bg-white/10' ?>"><i class="fas fa-calendar-alt pr-0 md:pr-3"></i><span class="text-xs md:text-base text-gray-200">Sobreaviso</span></a></li>
                    <li class="mr-3 md:mr-0 flex-1 md:flex-none"><a href="/sla" class="block py-2 md:py-3 px-3 text-white rounded-lg transition <?= active_menu('/sla') ? 'bg-white/10 text-white' : 'hover:bg-white/10' ?>"><i class="fas fa-stopwatch pr-0 md:pr-3"></i><span class="text-xs md:text-base text-gray-200">SLA</span></a></li>
                    <li class="mr-3 md:mr-0 flex-1 md:flex-none"><a href="/logout" class="block py-2 md:py-3 px-3 text-white rounded-lg transition hover:bg-white/10"><i class="fas fa-sign-out-alt pr-0 md:pr-3"></i><span class="text-xs md:text-base text-gray-200">Sair</span></a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">
        <div class="bg-cast-blue pt-3">
            <div class="rounded-tl-3xl bg-gradient-to-r from-blue-900 to-cast-blue p-4 shadow text-2xl text-white">
                <h3 class="font-bold pl-2"><?= h($title) ?></h3>
            </div>
        </div>
        <div class="p-6">
            <?php foreach ($flashes as $flash): ?>
                <?php $styles = ['success' => 'bg-green-100 border-green-500 text-green-700', 'danger' => 'bg-red-100 border-red-500 text-red-700', 'warning' => 'bg-yellow-100 border-yellow-500 text-yellow-700', 'info' => 'bg-blue-100 border-blue-500 text-blue-700']; ?>
                <div class="border-l-4 p-4 mb-4 <?= $styles[$flash['type']] ?? $styles['info'] ?>">
                    <p><?= h($flash['message']) ?></p>
                </div>
            <?php endforeach; ?>
            <?php require $contentView; ?>
        </div>
    </div>
</div>
</body>
</html>
