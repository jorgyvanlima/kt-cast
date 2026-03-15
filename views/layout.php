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
        html { scroll-behavior: smooth; }
        body { overflow-x: hidden; }
        .bg-cast-blue { background-color: var(--cast-blue); }
        .text-cast-blue { color: var(--cast-blue); }
        .text-cast-orange { color: var(--cast-orange); }
        .hover-cast-orange:hover { background-color: #e65c00; }
        .ring-cast-blue:focus { box-shadow: 0 0 0 2px rgba(0, 51, 102, .2); }
        .content-shell { width: 100%; max-width: 1440px; margin: 0 auto; }
        .table-stack { width: 100%; }

        @media (max-width: 767px) {
            .mobile-nav-list {
                overflow-x: auto;
                flex-wrap: nowrap;
                scrollbar-width: none;
                -ms-overflow-style: none;
            }

            .mobile-nav-list::-webkit-scrollbar {
                display: none;
            }

            .mobile-nav-item {
                flex: 0 0 auto;
                min-width: 88px;
            }

            .mobile-nav-link {
                min-height: 52px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                gap: 4px;
            }

            .mobile-nav-icon {
                padding-right: 0 !important;
            }

            .table-stack thead {
                display: none;
            }

            .table-stack,
            .table-stack tbody,
            .table-stack tr,
            .table-stack td {
                display: block;
                width: 100%;
            }

            .table-stack tbody {
                display: grid;
                gap: 12px;
                padding: 12px;
            }

            .table-stack tr {
                border: 1px solid #e5e7eb;
                border-radius: 14px;
                overflow: hidden;
                background: #fff;
                box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
            }

            .table-stack td {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                gap: 16px;
                padding: 12px 16px;
                border-bottom: 1px solid #f3f4f6;
                text-align: right;
                white-space: normal;
            }

            .table-stack td:last-child {
                border-bottom: 0;
            }

            .table-stack td::before {
                content: attr(data-label);
                font-size: 11px;
                line-height: 1.4;
                font-weight: 700;
                letter-spacing: .04em;
                text-transform: uppercase;
                color: #6b7280;
                text-align: left;
                flex: 0 0 42%;
            }

            .table-stack td.table-actions,
            .table-stack td.table-actions-inline {
                justify-content: flex-start;
                flex-wrap: wrap;
                text-align: left;
                gap: 8px;
            }

            .table-stack td.table-actions::before,
            .table-stack td.table-actions-inline::before {
                display: none;
            }

            .table-stack td.table-full {
                display: block;
                text-align: left;
            }

            .table-stack td.table-full::before {
                display: block;
                margin-bottom: 8px;
            }
        }
    </style>
</head>
<body class="min-h-screen bg-gray-100 font-sans leading-normal tracking-normal">
<div class="min-h-screen flex flex-col md:flex-row">
    <div class="bg-cast-blue shadow-xl h-16 fixed bottom-0 md:relative md:h-screen z-10 w-full md:w-64">
        <div class="md:mt-12 md:w-64 md:fixed md:left-0 md:top-0 content-center md:content-start text-left justify-between">
            <div class="flex flex-row md:flex-col py-0 md:py-3 px-1 md:px-6 justify-between md:justify-start text-white">
                <div class="flex-1 md:flex-none py-3 md:py-0">
                    <h1 class="font-bold text-xl md:text-2xl text-white pl-2">KT-<span class="text-cast-orange">CAST</span></h1>
                </div>
                <ul class="mobile-nav-list list-reset flex flex-row md:flex-col py-0 md:py-3 px-1 md:px-0 text-center md:text-left gap-1 md:gap-2">
                    <li class="mobile-nav-item mr-1 md:mr-0 flex-1 md:flex-none"><a href="/dashboard" class="mobile-nav-link block py-2 md:py-3 px-3 text-white rounded-lg transition <?= active_menu('/dashboard') || request_path() === '/' ? 'bg-white/10 text-white' : 'hover:bg-white/10' ?>"><i class="mobile-nav-icon fas fa-chart-line pr-0 md:pr-3"></i><span class="text-[11px] md:text-base text-gray-200">Dashboard</span></a></li>
                    <li class="mobile-nav-item mr-1 md:mr-0 flex-1 md:flex-none"><a href="/applications" class="mobile-nav-link block py-2 md:py-3 px-3 text-white rounded-lg transition <?= active_menu('/applications') ? 'bg-white/10 text-white' : 'hover:bg-white/10' ?>"><i class="mobile-nav-icon fas fa-list pr-0 md:pr-3"></i><span class="text-[11px] md:text-base text-gray-200">Aplicações</span></a></li>
                    <li class="mobile-nav-item mr-1 md:mr-0 flex-1 md:flex-none"><a href="/contacts" class="mobile-nav-link block py-2 md:py-3 px-3 text-white rounded-lg transition <?= active_menu('/contacts') ? 'bg-white/10 text-white' : 'hover:bg-white/10' ?>"><i class="mobile-nav-icon fas fa-address-book pr-0 md:pr-3"></i><span class="text-[11px] md:text-base text-gray-200">Analistas CAST</span></a></li>
                    <li class="mobile-nav-item mr-1 md:mr-0 flex-1 md:flex-none"><a href="/supplier-contacts" class="mobile-nav-link block py-2 md:py-3 px-3 text-white rounded-lg transition <?= active_menu('/supplier-contacts') ? 'bg-white/10 text-white' : 'hover:bg-white/10' ?>"><i class="mobile-nav-icon fas fa-building pr-0 md:pr-3"></i><span class="text-[11px] md:text-base text-gray-200">Contatos Forn.</span></a></li>
                    <li class="mobile-nav-item mr-1 md:mr-0 flex-1 md:flex-none"><a href="/schedule" class="mobile-nav-link block py-2 md:py-3 px-3 text-white rounded-lg transition <?= active_menu('/schedule') ? 'bg-white/10 text-white' : 'hover:bg-white/10' ?>"><i class="mobile-nav-icon fas fa-calendar-alt pr-0 md:pr-3"></i><span class="text-[11px] md:text-base text-gray-200">Sobreaviso</span></a></li>
                    <li class="mobile-nav-item mr-1 md:mr-0 flex-1 md:flex-none"><a href="/sla" class="mobile-nav-link block py-2 md:py-3 px-3 text-white rounded-lg transition <?= active_menu('/sla') ? 'bg-white/10 text-white' : 'hover:bg-white/10' ?>"><i class="mobile-nav-icon fas fa-stopwatch pr-0 md:pr-3"></i><span class="text-[11px] md:text-base text-gray-200">SLA</span></a></li>
                    <li class="mobile-nav-item mr-1 md:mr-0 flex-1 md:flex-none"><a href="/logout" class="mobile-nav-link block py-2 md:py-3 px-3 text-white rounded-lg transition hover:bg-white/10"><i class="mobile-nav-icon fas fa-sign-out-alt pr-0 md:pr-3"></i><span class="text-[11px] md:text-base text-gray-200">Sair</span></a></li>
                </ul>
                <div class="hidden md:flex w-full justify-center mt-4 px-3 text-center text-xs font-medium tracking-wide text-blue-100/80">
                    Desenvolvido por Jorgyvan Lima 
                </div>
            </div>
        </div>
    </div>
    <div class="main-content flex-1 min-w-0 bg-gray-100 pb-24 md:pb-5">
        <div class="bg-cast-blue pt-3">
            <div class="rounded-tl-none md:rounded-tl-3xl bg-gradient-to-r from-blue-900 to-cast-blue px-4 py-4 sm:px-6 shadow text-white">
                <div class="content-shell">
                    <h3 class="font-bold text-lg sm:text-2xl pl-1 sm:pl-2 break-words"><?= h($title) ?></h3>
                </div>
            </div>
        </div>
        <div class="content-shell px-4 py-4 sm:px-6 sm:py-6">
            <?php foreach ($flashes as $flash): ?>
                <?php $styles = ['success' => 'bg-green-100 border-green-500 text-green-700', 'danger' => 'bg-red-100 border-red-500 text-red-700', 'warning' => 'bg-yellow-100 border-yellow-500 text-yellow-700', 'info' => 'bg-blue-100 border-blue-500 text-blue-700']; ?>
                <div class="border-l-4 p-4 mb-4 text-sm sm:text-base <?= $styles[$flash['type']] ?? $styles['info'] ?>">
                    <p><?= h($flash['message']) ?></p>
                </div>
            <?php endforeach; ?>
            <?php require $contentView; ?>
        </div>
    </div>
</div>
</body>
</html>
