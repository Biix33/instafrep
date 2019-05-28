$(() => {
    const $postContainer = $('#posts');
    let currentPage = 2;
    let docHeight = $(document).height();
    console.log($postContainer.scrollTop());
    $('#next').on('click', function(e) {
        e.preventDefault();
        fetch(`post?p=${currentPage}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        }).then((response) => {
            if (response.status !== 200){
                throw new Error('An error occurred');
            }
            return response.text();
        }).then((text) => {
            currentPage++;
            $postContainer.append(text);
        }).catch((err) => {
            alert(err);
        })
    })
});