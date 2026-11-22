<style>
    .module-markdown { color: #374151; line-height: 1.8; }
    .module-markdown h1, .module-markdown h2, .module-markdown h3 { margin: 1.5rem 0 .75rem; color: #111827; line-height: 1.35; font-weight: 800; }
    .module-markdown h1 { font-size: 1.75rem; }
    .module-markdown h2 { font-size: 1.45rem; }
    .module-markdown h3 { font-size: 1.15rem; }
    .module-markdown h1:first-child, .module-markdown h2:first-child, .module-markdown h3:first-child { margin-top: 0; }
    .module-markdown pre { overflow-x: auto; margin: 1.25rem 0; padding: 1.25rem; border-radius: 12px; background: #1e293b; color: #e2e8f0; font-family: 'Fira Code', ui-monospace, monospace; font-size: .875rem; line-height: 1.7; }
    .module-markdown pre code { display: block; padding: 0; background: transparent; color: inherit; white-space: pre; font-family: inherit; }
    .module-markdown .code-block { overflow: hidden; margin: 1.25rem 0; border-radius: 12px; background: #1e293b; }
    .module-markdown .code-header { display: flex; align-items: center; justify-content: space-between; background: #334155; padding: 10px 16px; }
    .module-markdown .code-header span { color: #94a3b8; font-size: .75rem; font-weight: 600; letter-spacing: .05em; text-transform: uppercase; }
    .module-markdown .code-header button { border: 0; background: transparent; color: #cbd5e1; font-size: .75rem; cursor: pointer; }
    .module-markdown .code-header button:hover { color: #fff; }
    .module-markdown .code-block pre { margin: 0; border-radius: 0; }
    .module-markdown .code-output { margin: 0; border-top: 1px solid #334155; border-radius: 0 0 12px 12px; background: #0f172a; padding: 14px 20px; }
    .module-markdown .code-output-label { margin: 0 0 .35rem; color: #9ca3af; font-size: .7rem; font-weight: 700; }
    .module-markdown .code-output pre { margin: 0; padding: 0; overflow-x: auto; background: transparent; color: #4ade80; font-family: 'Fira Code', ui-monospace, monospace; font-size: .82rem; white-space: pre-wrap; }
    .module-markdown .code-output code { padding: 0; background: transparent; color: inherit; font: inherit; }
    .module-markdown blockquote { margin: 1.25rem 0; border-left: 4px solid #fbbf24; border-radius: 0 .75rem .75rem 0; background: #fffbeb; padding: .85rem 1rem; color: #92400e; }
    .module-markdown blockquote:has(> p > strong:first-child) { border: 1px solid #e2e8f0; border-left: 4px solid #10b981; background: #fff; color: #334155; box-shadow: 0 2px 8px rgba(15, 23, 42, .04); }
    .module-markdown blockquote p { margin: 0; }
    .module-markdown blockquote p + p { margin-top: .75rem; }
    .module-markdown :not(pre) > code { border-radius: 4px; background: #f1f5f9; padding: 2px 6px; color: #0f766e; font-family: 'Fira Code', ui-monospace, monospace; font-size: .85em; }
    .module-markdown table { width: 100%; border-collapse: collapse; }
    .module-markdown th, .module-markdown td { border: 1px solid #e5e7eb; padding: .5rem .75rem; text-align: left; }
    .module-markdown th { background: #f8fafc; font-weight: 700; }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/python.min.js"></script>

@foreach ($additionalLessons as $lesson)
    <article id="materi-extra-{{ $lesson->id }}" data-progress-url="{{ route('lesson.progress', $lesson) }}" data-progress-percent="{{ $lessonProgressValues[$lesson->sort_order] ?? 0 }}" class="mb-6 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm sm:p-8">
        <div class="mb-6 flex items-center gap-3">
            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 text-sm font-bold text-emerald-700">{{ $lesson->sort_order }}</span>
            <div>
                <p class="m-0 text-xs font-semibold uppercase tracking-wider text-emerald-700">Bagian Materi {{ $lesson->sort_order }}</p>
                <h2 class="m-0 text-xl font-extrabold text-gray-900">{{ $lesson->title }}</h2>
            </div>
        </div>
        <div class="prose max-w-none module-markdown">{!! $renderedAdditionalLessons[$lesson->id] !!}</div>
    </article>
@endforeach
