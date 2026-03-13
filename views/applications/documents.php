<?php
/** @var array $application */
/** @var array $documents */

function fmt_bytes(int $bytes): string {
    if ($bytes >= 1048576) return number_format($bytes / 1048576, 1) . ' MB';
    if ($bytes >= 1024)    return number_format($bytes / 1024, 1) . ' KB';
    return $bytes . ' B';
}

function doc_icon(string $filename): string {
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    return match($ext) {
        'pdf'           => 'fa-file-pdf text-red-500',
        'doc', 'docx'  => 'fa-file-word text-blue-600',
        'xls', 'xlsx'  => 'fa-file-excel text-green-600',
        'ppt', 'pptx'  => 'fa-file-powerpoint text-orange-500',
        'zip','rar','7z'=> 'fa-file-archive text-yellow-600',
        'png','jpg','jpeg'=> 'fa-file-image text-purple-500',
        'txt','md','csv'=> 'fa-file-alt text-gray-500',
        default         => 'fa-file text-gray-400',
    };
}
?>

<div class="max-w-5xl mx-auto mt-6 space-y-6">

    <!-- Cabeçalho -->
    <div class="flex items-center gap-3">
        <a href="/applications" class="text-blue-600 hover:text-blue-800 text-sm flex items-center gap-1">
            <i class="fas fa-arrow-left"></i> Voltar para Aplicações
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-5 border-b" style="background: linear-gradient(135deg, #003366 0%, #004b99 100%);">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center">
                    <i class="fas fa-folder-open text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-white text-xl font-bold"><?= h($application['name']) ?></h2>
                    <p class="text-blue-200 text-sm">Documentação / Manuais da Aplicação</p>
                </div>
            </div>
        </div>

        <!-- Upload -->
        <div class="p-6 border-b bg-gray-50">
            <h3 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wide">
                <i class="fas fa-upload mr-1 text-orange-500"></i> Enviar Novo Documento
            </h3>
            <form method="post"
                  action="/applications/<?= h($application['id']) ?>/documents/upload"
                  enctype="multipart/form-data"
                  class="flex flex-col sm:flex-row gap-3 items-start sm:items-center">
                <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">

                <label class="flex-1 w-full">
                    <div id="drop-zone"
                         class="border-2 border-dashed border-gray-300 rounded-lg px-4 py-4 text-center cursor-pointer hover:border-orange-400 transition-colors">
                        <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 mb-1"></i>
                        <p class="text-sm text-gray-500">Clique para selecionar ou arraste o arquivo aqui</p>
                        <p id="file-name" class="text-xs text-orange-600 font-medium mt-1 hidden"></p>
                    </div>
                          <input id="file-input" type="file" name="document" class="hidden" required
                              accept=".pdf,.doc,.docx,.odt,.xls,.xlsx,.ods,.ppt,.pptx,.odp,.txt,.png,.jpg,.jpeg,.zip,.rar,.7z,.csv,.md">
                </label>

                <button type="submit"
                        class="shrink-0 bg-orange-500 hover:bg-orange-600 text-white px-5 py-3 rounded-lg font-semibold text-sm transition-colors flex items-center gap-2">
                    <i class="fas fa-upload"></i> Enviar
                </button>
            </form>
            <p class="text-xs text-gray-400 mt-2">
                Formatos aceitos: PDF, Word (DOC/DOCX/ODT), Excel (XLS/XLSX/ODS), PowerPoint (PPT/PPTX/ODP), imagens, ZIP, TXT, CSV, MD. Tamanho máximo: 50 MB.
            </p>
        </div>

        <!-- Lista de documentos -->
        <div class="p-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-4 uppercase tracking-wide">
                <i class="fas fa-folder mr-1 text-blue-500"></i>
                Documentos (<?= count($documents) ?>)
            </h3>

            <?php if ($documents): ?>
                <div class="space-y-2">
                    <?php foreach ($documents as $doc): ?>
                        <div class="flex items-center justify-between bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-lg px-4 py-3 transition-colors">
                            <div class="flex items-center gap-3 min-w-0">
                                <i class="fas <?= doc_icon($doc['original_name']) ?> text-2xl shrink-0"></i>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-gray-800 truncate" title="<?= h($doc['original_name']) ?>">
                                        <?= h($doc['original_name']) ?>
                                    </p>
                                    <p class="text-xs text-gray-400">
                                        <?= fmt_bytes((int) $doc['file_size']) ?>
                                        &bull; enviado por <strong><?= h($doc['uploaded_by']) ?></strong>
                                        &bull; <?= date('d/m/Y H:i', strtotime($doc['created_at'])) ?>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0 ml-4">
                                <a href="/applications/<?= h($application['id']) ?>/documents/<?= h($doc['id']) ?>/download"
                                   class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 border border-blue-200 hover:border-blue-400 rounded px-3 py-1 text-xs font-medium transition-colors"
                                   title="Baixar documento">
                                    <i class="fas fa-download"></i> Baixar
                                </a>
                                <form method="post"
                                      action="/applications/<?= h($application['id']) ?>/documents/<?= h($doc['id']) ?>/delete"
                                      class="inline"
                                      onsubmit="return confirm('Remover o documento \'<?= h(addslashes($doc['original_name'])) ?>\'?');">
                                    <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 text-red-500 hover:text-red-700 border border-red-200 hover:border-red-400 rounded px-3 py-1 text-xs font-medium transition-colors"
                                            title="Remover documento">
                                        <i class="fas fa-trash"></i> Remover
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-12 text-gray-400">
                    <i class="fas fa-folder-open text-5xl mb-3"></i>
                    <p class="text-base font-medium">Nenhum documento cadastrado ainda.</p>
                    <p class="text-sm mt-1">Use o formulário acima para enviar o primeiro manual.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
(function () {
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('file-input');
    const fileNameEl = document.getElementById('file-name');

    dropZone.addEventListener('click', () => fileInput.click());

    fileInput.addEventListener('change', () => {
        if (fileInput.files.length > 0) {
            fileNameEl.textContent = fileInput.files[0].name;
            fileNameEl.classList.remove('hidden');
            dropZone.style.borderColor = '#f97316';
        }
    });

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.style.borderColor = '#f97316';
        dropZone.style.background = '#fff7ed';
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.style.borderColor = '';
        dropZone.style.background = '';
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.style.borderColor = '';
        dropZone.style.background = '';
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            fileNameEl.textContent = files[0].name;
            fileNameEl.classList.remove('hidden');
            dropZone.style.borderColor = '#f97316';
        }
    });
})();
</script>
