$(() => {
    const combo = [
        'ArrowLeft',
        'ArrowUp',
        'ArrowDown',
        'ArrowRight',
        'ArrowLeft',
        'ArrowRight',
        'ArrowUp',
        'a'
    ];
    const tryCombo = [];
    $(document).keyup(function (e) {
        e.preventDefault();
        if (combo.includes(e.key)) {
            tryCombo.push(e.key);
            if (areArraysSimilar(combo, tryCombo)) {
                alert("You're master");
                $('.navbar')
                    .removeClass('bg-dark')
                    .addClass('golden');
                fetch('user/combo', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                }).then((response) => {
                    console.log(response.text());
                })
            }
        }
    })
});

/**
 * Compare two arrays for strict similarity
 * @param {array[]} array1
 * @param {array[]} array2
 * @returns {boolean} true if arrays are similar
 */
function areArraysSimilar(array1, array2) {
    if (array1.length !== array2.length) return false;

    const arrayLength = array1.length;

    for (let i = 0; i < arrayLength; i++) {
        const element1 = array1[i];
        const element2 = array2[i];
        if (element1 !== element2) {
            return false;
        }
    }
    return true;
}