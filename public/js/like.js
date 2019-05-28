$(() => {
    $('a.like').on('click', function (e) {
        e.preventDefault();
        let $icon = $(this).find('i');
        fetch($(this).attr('href'), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        }).then((response) => {
            if (response.status > 404) {
                throw new Error(response.statusText);
            } else {
                if ($icon.hasClass('far')) {
                    $icon
                        .removeClass('far')
                        .addClass('fas');
                } else if ($icon.hasClass('fas')) {
                    $icon
                        .removeClass('fas')
                        .addClass('far');
                }
            }
            return response.text();
        }).then((text) => {
            $(this).next('strong').text(text);
        }).catch((err) => {
            alert(err.message);
        })
    })
});