<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editor Bagian Materi – {{ $lesson->title }} | PyBeginner Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/python.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <style>
        * { box-sizing: border-box; }
        html, body { height: 100%; margin: 0; font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
        .editor-shell { display: flex; flex-direction: column; min-height: 100vh; background: #f8fafc; }
        .topbar { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 0 1.25rem; min-height: 58px; background: #fff; border-bottom: 1px solid #e2e8f0; flex-shrink: 0; }
        .topbar-left { display: flex; align-items: center; gap: 0.75rem; }
        .topbar-back { display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.78rem; font-weight: 600; color: #475569; text-decoration: none; padding: 0.35rem 0.75rem; border-radius: 0.5rem; border: 1px solid #e2e8f0; transition: all .15s; }
        .topbar-back:hover { color: #065f46; background: #ecfdf5; }
        .topbar-title { font-size: 0.85rem; font-weight: 700; color: #0f172a; max-width: 320px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .topbar-badge { font-size: 0.7rem; font-weight: 700; padding: 0.25rem 0.65rem; border-radius: 999px; background: #d1fae5; color: #047857; letter-spacing: .03em; }
        .topbar-right { display: flex; align-items: center; gap: 0.5rem; }
        .btn-save { display: inline-flex; align-items: center; gap: 0.4rem; background: #10b981; color: #fff; font-weight: 700; font-size: 0.82rem; padding: 0.45rem 1.1rem; border-radius: 0.6rem; border: none; cursor: pointer; transition: background .15s; box-shadow: 0 2px 8px #10b98133; }
        .btn-save:hover { background: #059669; }
        .editor-toolbar { position: sticky; top: 0; z-index: 30; display: flex; align-items: center; gap: 0.25rem; padding: 0.55rem 1rem; background: #fff; border-bottom: 1px solid #e2e8f0; box-shadow: 0 4px 10px rgba(15, 23, 42, .04); flex-shrink: 0; flex-wrap: wrap; }
        .toolbar-sep { width: 1px; height: 18px; background: #e2e8f0; margin: 0 0.35rem; }
        .tb-btn { display: inline-flex; align-items: center; gap: 0.25rem; font-size: 0.75rem; font-weight: 600; color: #475569; padding: 0.3rem 0.6rem; border-radius: 0.4rem; border: 1px solid transparent; cursor: pointer; background: transparent; transition: all .12s; }
        .tb-btn:hover { background: #ecfdf5; color: #047857; border-color: #a7f3d0; }
        .toolbar-label { font-size: 0.7rem; font-weight: 700; color: #94a3b8; letter-spacing: .06em; padding: 0 0.25rem; }
        .split-pane { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1.05fr); min-height: 620px; flex: 1; overflow: hidden; }
        .editor-pane { display: flex; flex-direction: column; border-right: 1px solid #e2e8f0; min-height: 0; background: #fff; }
        .pane-header { display: flex; align-items: center; gap: 0.5rem; padding: 0.65rem 1rem; background: #fff; border-bottom: 1px solid #e2e8f0; font-size: 0.72rem; font-weight: 700; color: #64748b; letter-spacing: .04em; text-transform: uppercase; flex-shrink: 0; }
        .pane-dot { width: 7px; height: 7px; border-radius: 50%; }
        #markdown-editor { flex: 1; min-height: 520px; padding: 1.25rem 1.5rem; background: #fff; color: #334155; font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; font-size: 0.94rem; line-height: 1.85; resize: vertical; border: none; outline: none; overflow-y: auto; tab-size: 4; }
        #markdown-editor::placeholder { color: #94a3b8; }
        .preview-pane { display: flex; flex-direction: column; background: #f8fafc; min-height: 0; }
        .preview-scroll { flex: 1; overflow-y: auto; padding: 2rem 2.5rem; }
        .preview-lesson-title { font-size: 1.7rem; font-weight: 800; color: #111827; margin-bottom: 1.5rem; line-height: 1.3; padding-bottom: 1rem; border-bottom: 1px solid #e5e7eb; }
        .lesson-content { color: #374151; line-height: 1.8; font-size: 1rem; }
        .lesson-content code { background: #f1f5f9; padding: 2px 6px; border-radius: 4px; font-size: .82rem; font-family: ui-monospace, 'Fira Code', monospace; color: #0f766e; }
        .lesson-content pre code { background: transparent; padding: 0; border-radius: 0; color: inherit; }
        .lesson-content > * + * { margin-top: 1rem; }
        .lesson-content h1 { font-size: 1.6rem; font-weight: 800; color: #111827; margin-top: 1.5rem; }
        .lesson-content h2 { font-size: 1.25rem; font-weight: 700; color: #111827; margin-top: 2rem; }
        .lesson-content h3 { font-size: 1.05rem; font-weight: 700; color: #111827; margin-top: 1.5rem; }
        .lesson-content ul { list-style: disc; padding-left: 1.5rem; }
        .lesson-content ol { list-style: decimal; padding-left: 1.5rem; }
        .lesson-content li + li { margin-top: .3rem; }
        .lesson-content a { color: #047857; text-decoration: underline; }
        .lesson-content strong { color: #111827; }
        .lesson-content em { font-style: italic; }
        .lesson-content blockquote { border-left: 4px solid #fcd34d; background: #fffbeb; padding: .75rem 1rem; border-radius: .5rem; color: #92400e; }
        .lesson-content blockquote:has(> p > strong:first-child) { border: 1px solid #e2e8f0; border-left: 4px solid #10b981; background: #fff; color: #334155; box-shadow: 0 2px 8px rgba(15, 23, 42, .04); }
        .lesson-content :not(pre) > code { background: #eef2ff; color: #3730a3; padding: .1rem .4rem; border-radius: .375rem; font-size: .875em; font-family: ui-monospace, 'Fira Code', monospace; }
        .lesson-content .code-block { background: #1e293b; border-radius: 12px; overflow: hidden; }
        .lesson-content .code-block .code-header { background: #334155; padding: 10px 16px; display: flex; align-items: center; justify-content: space-between; }
        .lesson-content .code-block .code-header span { color: #94a3b8; font-size: .75rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; }
        .lesson-content .code-block pre { padding: 20px; margin: 0; overflow-x: auto; color: #e2e8f0; background: transparent; }
        .lesson-content .code-block pre code { color: #e2e8f0; }
        .lesson-content .code-output { margin: 0; border-top: 1px solid #334155; border-radius: 0 0 12px 12px; background: #0f172a; padding: 14px 20px; }
        .lesson-content .code-output-label { margin: 0 0 .35rem; color: #9ca3af; font-size: .7rem; font-weight: 700; }
        .lesson-content .code-output pre { margin: 0; padding: 0; overflow-x: auto; background: transparent; color: #4ade80; font-family: ui-monospace, 'Fira Code', monospace; font-size: .82rem; white-space: pre-wrap; }
        .lesson-content .code-output code { padding: 0; background: transparent; color: inherit; font: inherit; }
        .lesson-content table { width: 100%; border-collapse: collapse; font-size: .9rem; margin-top: 1rem; }
        .lesson-content th, .lesson-content td { border: 1px solid #e5e7eb; padding: .5rem .75rem; text-align: left; }
        .lesson-content th { background: #f9fafb; font-weight: 700; }
        .lesson-content hr { border: none; border-top: 1px solid #e5e7eb; margin: 1.5rem 0; }
        .meta-row { display: flex; align-items: center; gap: 1rem; padding: 0.8rem 1.25rem; background: #fff; border-top: 1px solid #e2e8f0; flex-shrink: 0; flex-wrap: wrap; }
        .meta-field { display: flex; flex-direction: column; gap: 0.15rem; flex: 1; min-width: 130px; }
        .meta-label { font-size: 0.68rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: .06em; }
        .meta-input { background: #f8fafc; border: 1px solid #e2e8f0; color: #0f172a; border-radius: 0.45rem; padding: 0.4rem 0.7rem; font-size: 0.8rem; outline: none; transition: border-color .15s; }
        .meta-input:focus { border-color: #10b981; background: #fff; }
        .preview-empty { display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; color: #9ca3af; text-align: center; gap: 0.75rem; padding: 2rem; }
        .wc { font-size: 0.72rem; color: #64748b; }
        #flash-bar { display: none; align-items: center; gap: 0.5rem; padding: 0.6rem 1.25rem; background: #064e3b; color: #6ee7b7; font-size: 0.82rem; font-weight: 600; border-bottom: 1px solid #065f46; flex-shrink: 0; }
        #markdown-editor::-webkit-scrollbar, .preview-scroll::-webkit-scrollbar { width: 6px; }
        #markdown-editor::-webkit-scrollbar-track { background: #f8fafc; }
        #markdown-editor::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        .preview-scroll::-webkit-scrollbar-track { background: #f1f5f9; }
        .preview-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        @media (max-width: 900px) { .split-pane { grid-template-columns: 1fr; overflow: visible; } .editor-pane { border-right: 0; border-bottom: 1px solid #e2e8f0; } .preview-pane { min-height: 540px; } .topbar { align-items: flex-start; padding: .75rem 1rem; } .topbar-left, .topbar-right { flex-wrap: wrap; } }
    </style>
</head>
<body>
<div class="editor-shell">

    <div id="flash-bar">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        @if(session('status')) {{ session('status') }} @else Perubahan berhasil disimpan! @endif
    </div>

    <div class="topbar">
        <div class="topbar-left">
            <a href="{{ route('admin.materi.lessons', $lesson->module) }}" class="topbar-back">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="13" height="13"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                Kembali
            </a>
            <div style="width:1px;height:20px;background:#334155;"></div>
            <span class="topbar-badge">BAGIAN MATERI</span>
            <span class="topbar-title">{{ $lesson->module->title }} &rarr; {{ $lesson->title }}</span>
        </div>
        <div class="topbar-right">
            @if ($lesson->module->course && $lesson->module->is_published && $lesson->module->course->is_published)
                <a href="{{ route('module.show', [$lesson->module->course, $lesson->module]) }}" target="_blank" class="topbar-back">Lihat modul lengkap</a>
            @endif
            <span class="wc" id="word-count">0 karakter</span>
            <button type="button" class="btn-save" onclick="submitForm()">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="15" height="15"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline stroke-linecap="round" stroke-linejoin="round" points="17 21 17 13 7 13 7 21"/><polyline stroke-linecap="round" stroke-linejoin="round" points="7 3 7 8 15 8"/></svg>
                Simpan Materi
            </button>
        </div>
    </div>

    <div class="editor-toolbar">
        <span class="toolbar-label">FORMAT</span>
        <button type="button" class="tb-btn" onclick="insertMd('**', '**', 'teks tebal')"><b>B</b></button>
        <button type="button" class="tb-btn" onclick="insertMd('*', '*', 'teks miring')"><i>I</i></button>
        <button type="button" class="tb-btn" onclick="insertMd('`', '`', 'kode')">&lt;/&gt;</button>
        <div class="toolbar-sep"></div>
        <span class="toolbar-label">JUDUL</span>
        <button type="button" class="tb-btn" onclick="insertLine('# ')">H1</button>
        <button type="button" class="tb-btn" onclick="insertLine('## ')">H2</button>
        <button type="button" class="tb-btn" onclick="insertLine('### ')">H3</button>
        <div class="toolbar-sep"></div>
        <span class="toolbar-label">DAFTAR</span>
        <button type="button" class="tb-btn" onclick="insertLine('- ')">&#8226; List</button>
        <button type="button" class="tb-btn" onclick="insertLine('1. ')">1. Ordered</button>
        <button type="button" class="tb-btn" onclick="insertTable()">Tabel</button>
        <div class="toolbar-sep"></div>
        <span class="toolbar-label">CONTOH KODE</span>
        <button type="button" class="tb-btn" onclick="insertCodeBlock('python')" style="color:#79c0ff;">&#x1F40D; python</button>
        <button type="button" class="tb-btn" onclick="insertOutputBlock()" style="color:#15803d;">Output</button>
        <div class="toolbar-sep"></div>
        <span class="toolbar-label">RINGKASAN</span>
        <button type="button" class="tb-btn" onclick="insertSummaryBlock()">Rangkuman</button>
        <div class="toolbar-sep"></div>
        <button type="button" class="tb-btn" onclick="insertLine('> ')">&#10077; Quote</button>
        <button type="button" class="tb-btn" onclick="insertLine('---')">&#8212; Garis</button>
    </div>

    <div class="split-pane">
        <div class="editor-pane">
            <div class="pane-header">
                <i data-lucide="file-text" style="width:16px;height:16px;color:#059669;"></i>
                <span>Tulis penjelasan, contoh kode, dan rangkuman materi</span>
            </div>
            <textarea id="markdown-editor" placeholder="Mulai dengan penjelasan singkat. Tambahkan subjudul, contoh kode, daftar, atau rangkuman memakai tombol di atas.">{{ $lesson->content }}</textarea>
        </div>

        <div class="preview-pane">
            <div class="pane-header" style="background:#f1f5f9;border-bottom:1px solid #e2e8f0;color:#64748b;">
                &#128065;&#65039; PRATINJAU BAGIAN DI HALAMAN MATERI
            </div>
            <div class="preview-scroll" id="preview-scroll-area">
                <div class="preview-empty" id="preview-empty">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="48" height="48"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <p>Mulai mengetik di editor kiri<br>untuk melihat preview di sini.</p>
                </div>
                <article id="preview-content" class="mb-6 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm sm:p-8" style="display:none;">
                    <div class="mb-5 flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-sm font-extrabold text-emerald-800">{{ sprintf('%02d', $lesson->sort_order) }}</span>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-emerald-700">Bagian materi</p>
                            <h1 class="text-xl font-extrabold text-gray-900 sm:text-2xl" id="preview-title">{{ $lesson->title }}</h1>
                        </div>
                    </div>
                    <div class="lesson-content prose max-w-none" id="preview-body"></div>
                </article>
            </div>
        </div>
    </div>

    <div class="meta-row">
        <form id="save-form" method="POST" action="{{ route('admin.lessons.update', $lesson) }}" style="display:contents;">
            @csrf
            @method('PUT')
            <input type="hidden" name="content" id="hidden-content">
            <div class="meta-field">
                <span class="meta-label">Judul Bagian Materi *</span>
                <input type="text" name="title" id="field-title" required class="meta-input" value="{{ old('title', $lesson->title) }}">
            </div>
            <div class="meta-field" style="max-width:100px;">
                <span class="meta-label">Urutan</span>
                <input type="number" name="sort_order" required min="1" class="meta-input" value="{{ old('sort_order', $lesson->sort_order) }}">
            </div>
            <div class="meta-field" style="max-width:160px;">
                <span class="meta-label">Status</span>
                <select name="is_published" class="meta-input">
                    <option value="1" {{ $lesson->is_published ? 'selected' : ' ' }}>Terbitkan</option>
                    <option value="0" {{ !$lesson->is_published ? 'selected' : ' ' }}>Draft</option>
                </select>
            </div>
            <div style="display:flex;align-items:flex-end;">
                <button type="button" class="btn-save" onclick="submitForm()">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
const renderer = new marked.Renderer();
renderer.code = function(code, lang) {
    const token = typeof code === 'object' && code !== null ? code : null;
    const codeText = String(token ? token.text ?? '' : code ?? '');
    const requestedLanguage = token ? token.lang : lang;
    const langLabel = /^[\w+-]+$/.test(requestedLanguage || '') ? requestedLanguage : 'text';
    const safeCode = codeText.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    if (langLabel.toLowerCase() === 'output') {
        return '<div class="code-output"><p class="code-output-label">OUTPUT:</p><pre><code>' + safeCode + '</code></pre></div>';
    }
    let highlighted = safeCode;

    if (langLabel !== 'text' && window.hljs) {
        try {
            highlighted = hljs.highlight(codeText, { language: langLabel, ignoreIllegals: true }).value;
        } catch (error) {
            highlighted = safeCode;
        }
    }

    return '<div class="code-block"><div class="code-header"><span>' + langLabel + '</span><button type="button" class="text-xs text-slate-300 hover:text-white" onclick="navigator.clipboard.writeText(this.parentElement.nextElementSibling.textContent)">Salin kode</button></div><pre><code class="language-' + langLabel + '">' + highlighted + '</code></pre></div>';
};
marked.use({ renderer, breaks: true, gfm: true });

const editor = document.getElementById('markdown-editor');
const previewBody = document.getElementById('preview-body');
const previewTitle = document.getElementById('preview-title');
const previewContent = document.getElementById('preview-content');
const previewEmpty = document.getElementById('preview-empty');
const wordCount = document.getElementById('word-count');
const titleField = document.getElementById('field-title');

function updatePreview() {
    const md = editor.value;
    wordCount.textContent = md.length.toLocaleString('id') + ' karakter';
    if (md.trim() === '') {
        previewEmpty.style.display = 'flex';
        previewContent.style.display = 'none';
    } else {
        previewEmpty.style.display = 'none';
        previewContent.style.display = 'block';
        previewBody.innerHTML = marked.parse(md);
    }
    previewTitle.textContent = titleField.value || '(Judul Pelajaran)';
}

editor.addEventListener('input', updatePreview);
titleField.addEventListener('input', updatePreview);

editor.addEventListener('keydown', function(e) {
    if (e.key === 'Tab') {
        e.preventDefault();
        const s = this.selectionStart, en = this.selectionEnd;
        this.value = this.value.substring(0, s) + '    ' + this.value.substring(en);
        this.selectionStart = this.selectionEnd = s + 4;
        updatePreview();
    }
    if ((e.ctrlKey || e.metaKey) && e.key === 's') { e.preventDefault(); submitForm(); }
    if ((e.ctrlKey || e.metaKey) && e.key === 'b') { e.preventDefault(); insertMd('**','**','teks tebal'); }
    if ((e.ctrlKey || e.metaKey) && e.key === 'i') { e.preventDefault(); insertMd('*','*','teks miring'); }
});

function insertMd(before, after, placeholder) {
    const s = editor.selectionStart, en = editor.selectionEnd;
    const sel = editor.value.substring(s, en) || placeholder;
    editor.value = editor.value.substring(0, s) + before + sel + after + editor.value.substring(en);
    editor.selectionStart = s + before.length;
    editor.selectionEnd = s + before.length + sel.length;
    editor.focus(); updatePreview();
}
function insertLine(prefix) {
    const pos = editor.selectionStart;
    const ls = editor.value.lastIndexOf('\n', pos - 1) + 1;
    editor.value = editor.value.substring(0, ls) + prefix + editor.value.substring(ls);
    editor.selectionStart = editor.selectionEnd = ls + prefix.length;
    editor.focus(); updatePreview();
}
function insertTable() {
    const start = editor.selectionStart;
    const end = editor.selectionEnd;
    const table = '\n| Kolom 1 | Kolom 2 | Kolom 3 |\n| --- | --- | --- |\n| Isi 1 | Isi 2 | Isi 3 |\n';
    editor.value = editor.value.substring(0, start) + table + editor.value.substring(end);
    const selectedStart = start + '\n| '.length;
    editor.selectionStart = selectedStart;
    editor.selectionEnd = selectedStart + 'Kolom 1'.length;
    editor.focus();
    updatePreview();
}
function insertCodeBlock(lang) {
    const s = editor.selectionStart, en = editor.selectionEnd;
    const sel = editor.value.substring(s, en);
    const block = '\n```' + lang + '\n' + (sel || 'kode di sini') + '\n```\n';
    editor.value = editor.value.substring(0, s) + block + editor.value.substring(en);
    editor.focus(); updatePreview();
}
function insertOutputBlock() {
    const start = editor.selectionStart;
    const end = editor.selectionEnd;
    const selectedText = editor.value.substring(start, end);
    const output = '\n```output\n' + (selectedText || 'Tulis hasil keluaran program di sini') + '\n```\n';
    editor.value = editor.value.substring(0, start) + output + editor.value.substring(end);
    editor.selectionStart = editor.selectionEnd = start + output.length;
    editor.focus();
    updatePreview();
}
function insertSummaryBlock() {
    const start = editor.selectionStart;
    const end = editor.selectionEnd;
    const summary = '\n> **Rangkuman:**\n> Tulis poin-poin penting materi di sini.\n';
    editor.value = editor.value.substring(0, start) + summary + editor.value.substring(end);
    editor.selectionStart = editor.selectionEnd = start + summary.length;
    editor.focus();
    updatePreview();
}
function submitForm() {
    document.getElementById('hidden-content').value = editor.value;
    document.getElementById('save-form').submit();
}
@if(session('status'))
    const fb = document.getElementById('flash-bar');
    fb.style.display = 'flex';
    setTimeout(function(){ fb.style.display = 'none'; }, 4000);
@endif
updatePreview();
lucide.createIcons();
</script>
</body>
</html>
