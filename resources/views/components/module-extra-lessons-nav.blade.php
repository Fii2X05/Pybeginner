@if ($additionalLessons->isNotEmpty())
    <nav class="space-y-1 border-t border-gray-100 py-2" aria-label="Bagian materi tambahan">
        @foreach ($additionalLessons as $lesson)
            <a href="#materi-extra-{{ $lesson->id }}" data-progress-number="{{ $lesson->sort_order }}" class="sidebar-link block px-5 py-3 text-sm text-gray-600 transition-all">
                <div class="flex items-center gap-3">
                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full {{ ($lessonProgressValues[$lesson->sort_order] ?? 0) >= 100 ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-500' }} text-xs font-bold">@if (($lessonProgressValues[$lesson->sort_order] ?? 0) >= 100)<i data-lucide="check" class="h-4 w-4"></i>@else{{ sprintf('%02d', $lesson->sort_order) }}@endif</span>
                    <div>
                        <p class="font-medium text-sm">{{ $lesson->title }}</p>
                        <p class="text-[11px] text-gray-400">Bagian materi</p>
                    </div>
                </div>
            </a>
        @endforeach
    </nav>
@endif
