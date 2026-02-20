document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.news-posts').forEach(section => {
        const loadMoreBtn = section.querySelector('#load-more-posts');
        let postsToShow = 2;
        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', () => {
                let shown = 0;
                // Show the next 2 hidden posts
                const hiddenPosts = section.querySelectorAll('.news-posts__item--hidden');
                while(shown < postsToShow) {
                    hiddenPosts[shown].classList.remove('news-posts__item--hidden');
                    hiddenPosts[shown].classList.add('news-posts__item');
                    shown++;
                }
                // If no more hidden posts, hide the load more button
                if (section.querySelectorAll('.news-posts__item--hidden').length === 0) {
                    loadMoreBtn.style.display = 'none';
                }
            });
        }
    });
})