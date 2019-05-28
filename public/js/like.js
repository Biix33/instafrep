$(()=>{
    $('a.like').on('click', function(e) {
        e.preventDefault();
        $(this).find('i').toggleClass('far', 'fas');
        fetch($(this).attr('href'), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        }).then((response) => {
            if (response.status > 404){
                throw new Error(response.statusText);
            }
            return response.text();
        }).then((text) => {
            $(this).next('strong').text(text);
        }).catch((err)=>{
            alert(err.message);
        })
    })
});