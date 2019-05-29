$(() => {
    const $postContainer = $('#posts');
    let nextPage = 2;
    let isLoading = false;
    let isDone = false;
    const loader = `<div class="lds-hourglass"></div>`;

    $(window).on('scroll', function(e) {
        if (isLoading) return;
        let scrollTop = $(this).scrollTop() + $(window).height();
        if (scrollTop > $(document).height()/2 && !isDone) {
            $postContainer.append(loader);
            fetch(`post?p=${nextPage}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            }).then((response) => {
                if (response.status !== 200) {
                    throw new Error('An error occurred');
                }
                if (parseInt(response.headers.get('X-infrep-Is-last-page'))) {
                    isDone = true;
                }
                return response.text();
            }).then((text) => {
                nextPage++;
                $postContainer.children().last().remove();
                $postContainer.append(text);
            }).catch((err) => {
                alert(err);
            }).finally(()=>{
                isLoading = false;
            });
            isLoading = true;
        }
    })
});