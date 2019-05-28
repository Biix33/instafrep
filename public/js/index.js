$(() => {
    const current = $('#num');
    let digit = current.text();
    $('#roll').on('click', ()=> {
        $('#roll').attr('disabled', true).html('<div class="lds-hourglass"></div>');
        fetch('/rand-digit/', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
            .then((response) => {
                if (response.status > 404){
                    throw new Error(response.statusText);
                }
                return response.text();
            })
            .then((text) => {
                current.text(text);
                $('#roll').attr('disabled', false).html('roll the dice');
            })
            .catch((err) => {
                $('#roll').attr('disabled', false).html(err.message);
                alert(err.message);
            });
    })
});
/*
fetch('/rand-digit/')
.then((response) => {
    console.log(response);
    alert(response);
})
.catch(()=> {
    alert('FAIL');
});*/
