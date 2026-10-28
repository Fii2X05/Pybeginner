<script>
    const staticLessonProgressUrls = @json($lessonProgressUrls ?? []);
    const staticLessonProgressValues = @json($lessonProgressValues ?? []);

    document.querySelectorAll('article[id^="materi-"]').forEach(function (article, index) {
        if (article.id.startsWith('materi-extra-') || article.dataset.progressUrl) {
            return;
        }

        const sortOrder = index + 1;
        if (staticLessonProgressUrls[sortOrder]) {
            article.dataset.progressUrl = staticLessonProgressUrls[sortOrder];
            article.dataset.progressPercent = staticLessonProgressValues[sortOrder] || 0;
        }
    });

    document.querySelectorAll('.module-markdown pre > code, .module-prose pre > code').forEach(function (code) {
        const pre = code.parentElement;
        if (pre.parentElement.classList.contains('code-block')) {
            return;
        }

        const languageClass = Array.from(code.classList).find(function (className) {
            return className.startsWith('language-');
        });
        const language = languageClass ? languageClass.slice('language-'.length) : 'text';
        if (language.toLowerCase() === 'output') {
            const output = document.createElement('div');
            output.className = 'code-output';
            const outputLabel = document.createElement('p');
            outputLabel.className = 'code-output-label';
            outputLabel.textContent = 'OUTPUT:';
            pre.before(output);
            output.append(outputLabel, pre);
            return;
        }

        const wrapper = document.createElement('div');
        wrapper.className = 'code-block';

        const header = document.createElement('div');
        header.className = 'code-header';

        const label = document.createElement('span');
        label.textContent = language;

        const copyButton = document.createElement('button');
        copyButton.type = 'button';
        copyButton.textContent = 'Salin kode';
        copyButton.addEventListener('click', async function () {
            try {
                await navigator.clipboard.writeText(code.textContent);
                copyButton.textContent = 'Tersalin';
                window.setTimeout(function () {
                    copyButton.textContent = 'Salin kode';
                }, 1500);
            } catch (error) {
                copyButton.textContent = 'Gagal menyalin';
                window.setTimeout(function () {
                    copyButton.textContent = 'Salin kode';
                }, 1500);
            }
        });

        header.append(label, copyButton);
        pre.before(wrapper);
        wrapper.append(header, pre);

        if (window.hljs) {
            window.hljs.highlightElement(code);
        }
    });

    (function trackModuleReadingProgress() {
        const articles = Array.from(document.querySelectorAll('article[data-progress-url]'))
            .filter(function (article) {
                return article.dataset.progressUrl.trim() !== '';
            });
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        const progressLabels = document.querySelectorAll('[data-module-progress]');
        const readCountLabels = document.querySelectorAll('[data-module-read-count]');

        function getReadPercentage(article) {
            const bounds = article.getBoundingClientRect();
            const travelDistance = bounds.height + window.innerHeight;
            if (travelDistance <= 0) {
                return 0;
            }

            return Math.max(0, Math.min(100, Math.round(((window.innerHeight - bounds.top) / travelDistance) * 100)));
        }

        function updateNavigation() {
            document.querySelectorAll('.sidebar-link[href^="#materi-"]').forEach(function (link) {
                const articleId = link.getAttribute('href').slice(1);
                const article = document.getElementById(articleId);
                const marker = link.querySelector('span.w-6, span.w-7');
                if (!article || !marker) {
                    return;
                }

                const number = link.dataset.progressNumber || marker.dataset.progressNumber || articleId.replace('materi-', '').replace('extra-', '');
                const isRead = Number(article.dataset.localProgressPercent || article.dataset.progressPercent || 0) > 0;
                marker.classList.remove('bg-emerald-100', 'text-emerald-700', 'bg-emerald-600', 'text-white', 'bg-slate-100', 'text-slate-500');
                marker.classList.add(isRead ? 'bg-emerald-600' : 'bg-slate-100', isRead ? 'text-white' : 'text-slate-500');
                marker.innerHTML = isRead
                    ? '<i data-lucide="check" class="h-4 w-4"></i>'
                    : String(number).padStart(2, '0');
            });

            if (window.lucide) {
                window.lucide.createIcons();
            }
        }

        function saveArticleProgress(article, progressPercent, keepalive = false) {
            if (!csrfToken || progressPercent <= Number(article.dataset.progressPercent || 0)) {
                return;
            }

            if (article.dataset.progressRequestPending === 'true') {
                article.dataset.queuedProgress = String(Math.max(
                    Number(article.dataset.queuedProgress || 0),
                    progressPercent
                ));
                return;
            }

            article.dataset.progressRequestPending = 'true';

            window.fetch(article.dataset.progressUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ progress_percent: progressPercent }),
                keepalive: true,
            }).then(function (response) {
                if (!response.ok) {
                    article.dataset.lastSentProgress = String(article.dataset.progressPercent || 0);
                    console.error('Gagal menyimpan progres materi:', response.status, article.dataset.progressUrl);
                    return;
                }

                return response.json().then(function (result) {
                    article.dataset.progressPercent = String(result.progress_percent || progressPercent);
                });
            }).catch(function () {
                article.dataset.lastSentProgress = String(article.dataset.progressPercent || 0);
                console.error('Tidak dapat mengirim progres materi:', article.dataset.progressUrl);
            }).finally(function () {
                article.dataset.progressRequestPending = 'false';
                const queuedProgress = Number(article.dataset.queuedProgress || 0);
                delete article.dataset.queuedProgress;

                if (queuedProgress > Number(article.dataset.progressPercent || 0)) {
                    saveArticleProgress(article, queuedProgress, keepalive);
                }
            });
        }

        function sendArticleProgressBeacon(article, progressPercent) {
            if (!csrfToken || progressPercent <= Number(article.dataset.progressPercent || 0)) {
                return;
            }

            const payload = new FormData();
            payload.append('_token', csrfToken);
            payload.append('progress_percent', String(progressPercent));

            if (typeof navigator.sendBeacon === 'function' && navigator.sendBeacon(article.dataset.progressUrl, payload)) {
                article.dataset.lastSentProgress = String(Math.max(
                    Number(article.dataset.lastSentProgress || 0),
                    progressPercent
                ));
                return;
            }

            saveArticleProgress(article, progressPercent, true);
        }

        function updateProgress() {
            let totalPercent = 0;
            let readCount = 0;

            articles.forEach(function (article) {
                const savedPercent = Number(article.dataset.progressPercent || 0);
                const readPercent = savedPercent >= 100
                    ? 100
                    : Math.min(99, Math.max(savedPercent, Number(article.dataset.localProgressPercent || 0), getReadPercentage(article)));
                article.dataset.localProgressPercent = String(readPercent);
                totalPercent += readPercent;
                if (readPercent > 0) {
                    readCount++;
                }

                const lastSent = Number(article.dataset.lastSentProgress || savedPercent);
                const nextMilestone = readPercent >= 100 ? 100 : Math.floor(readPercent / 5) * 5;
                if (csrfToken && nextMilestone > lastSent) {
                    article.dataset.lastSentProgress = String(nextMilestone);
                    saveArticleProgress(article, nextMilestone);
                }
            });

            const modulePercent = articles.length > 0 ? Math.round(readCount / articles.length * 100) : 0;
            progressLabels.forEach(function (label) {
                label.textContent = modulePercent + '%';
            });
            readCountLabels.forEach(function (label) {
                label.textContent = readCount + '/' + label.dataset.totalLessons + ' Materi Dibaca';
            });
            updateNavigation();
        }

        document.addEventListener('click', function (event) {
            const link = event.target.closest('.sidebar-link[href^="#materi-"]');
            if (!link) {
                return;
            }

            const article = document.getElementById(link.getAttribute('href').slice(1));
            if (!article || !articles.includes(article)) {
                return;
            }

            article.dataset.localProgressPercent = '99';
            saveArticleProgress(article, 99, true);
            updateProgress();
        });

        let scrollTimer;
        function scheduleProgressUpdate() {
            window.clearTimeout(scrollTimer);
            scrollTimer = window.setTimeout(updateProgress, 120);
        }

        function flushProgress() {
            articles.forEach(function (article) {
                const readPercent = Math.max(
                    Number(article.dataset.progressPercent || 0),
                    Number(article.dataset.localProgressPercent || 0),
                    getReadPercentage(article)
                );
                if (readPercent <= Number(article.dataset.progressPercent || 0)) {
                    return;
                }

                sendArticleProgressBeacon(article, Math.min(99, readPercent));
            });
        }

        window.addEventListener('scroll', scheduleProgressUpdate, { passive: true });
        document.addEventListener('scroll', scheduleProgressUpdate, { passive: true, capture: true });
        window.addEventListener('resize', scheduleProgressUpdate);
        window.addEventListener('pagehide', flushProgress);
        document.addEventListener('visibilitychange', function () {
            if (document.visibilityState === 'hidden') {
                flushProgress();
            }
        });
        updateProgress();

        if ('IntersectionObserver' in window) {
            const articleObserver = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (!entry.isIntersecting) {
                        return;
                    }

                    const article = entry.target;
                    article.dataset.localProgressPercent = String(Math.max(
                        Number(article.dataset.progressPercent || 0),
                        Number(article.dataset.localProgressPercent || 0),
                        99
                    ));
                    saveArticleProgress(article, 99, true);
                });

                updateProgress();
            }, { threshold: 0.01 });

            articles.forEach(function (article) {
                articleObserver.observe(article);
            });
        }
    })();
</script>
