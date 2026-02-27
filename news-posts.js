document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.news-posts').forEach(section => {
        const loadMoreBtn = section.querySelector('#load-more-posts');
        const grid = section.querySelector('.news-posts__grid');

        if (!grid) {
            return;
        }

        const allPosts = Array.from(grid.querySelectorAll('.news-posts__item, .news-posts__item--hidden'));
        if (allPosts.length === 0) {
            if (loadMoreBtn) {
                loadMoreBtn.style.display = 'none';
            }
            return;
        }

        let visibleCount = 0;

        function getColumnCount() {
            const template = window.getComputedStyle(grid).gridTemplateColumns;
            if (!template || template === 'none') {
                return 1;
            }

            const cols = template
                .split(' ')
                .map((part) => part.trim())
                .filter((part) => part.length > 0).length;

            return Math.max(1, cols);
        }

        function removePlaceholders() {
            grid.querySelectorAll('.news-posts__item--placeholder').forEach((placeholder) => {
                placeholder.remove();
            });
        }

        function syncVisiblePosts() {
            const columns = getColumnCount();

            if (visibleCount <= 0) {
                visibleCount = Math.min(columns, allPosts.length);
            } else {
                visibleCount = Math.min(allPosts.length, Math.max(visibleCount, columns));
            }

            allPosts.forEach((post, index) => {
                if (index < visibleCount) {
                    post.classList.remove('news-posts__item--hidden');
                    post.classList.add('news-posts__item');
                } else {
                    post.classList.remove('news-posts__item');
                    post.classList.add('news-posts__item--hidden');
                }
            });

            removePlaceholders();

            if (columns > 1) {
                const placeholdersNeeded = (columns - (visibleCount % columns)) % columns;
                for (let i = 0; i < placeholdersNeeded; i++) {
                    const placeholder = document.createElement('div');
                    placeholder.className = 'news-posts__item news-posts__item--placeholder';
                    placeholder.setAttribute('aria-hidden', 'true');
                    grid.appendChild(placeholder);
                }
            }

            if (loadMoreBtn) {
                loadMoreBtn.style.display = visibleCount < allPosts.length ? '' : 'none';
            }
        }

        syncVisiblePosts();

        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', () => {
                const columns = getColumnCount();
                visibleCount = Math.min(allPosts.length, visibleCount + columns);
                syncVisiblePosts();
            });
        }

        window.addEventListener('resize', () => {
            syncVisiblePosts();
        });
    });
});
