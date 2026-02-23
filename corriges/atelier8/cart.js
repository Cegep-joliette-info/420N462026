fetch('api.php?refresh=1')
    .then(updateCart)
    .catch(error => {
        let boiteErreur = document.querySelector('#error-message');
        boiteErreur.classList.remove('hidden');
        boiteErreur.innerText = error;
    });

document.querySelectorAll('button').forEach(button => {
    button.addEventListener('click', e => {
        let formData = new FormData();
        formData.append('id', button.dataset.id);
        formData.append('quantity', button.dataset.action == '+' ? 1 : -1);
        fetch('api.php', {method: 'POST', body: formData})
            .then(updateCart)
            .catch(error => {
                let boiteErreur = document.querySelector('#error-message');
                boiteErreur.classList.remove('hidden');
                boiteErreur.innerText = error;
            });
    });
});

async function updateCart(response) {
    let data = await response.json();
    let boiteErreur = document.querySelector('#error-message');
    if (response.ok) {
        boiteErreur.classList.add('hidden');
        document.querySelector('#qte').innerText = data.nb;
        document.querySelector('#sous-total').innerText = formatPrice(data.sousTotal);
        document.querySelector('#tps').innerText = formatPrice(data.tps);
        document.querySelector('#tvq').innerText = formatPrice(data.tvq);
        document.querySelector('#total').innerText = formatPrice(data.total);

        Object.keys(data.produits).forEach(id => {
            let qte = document.querySelector(`#qte-${id}`);
            if (qte) {
                qte.innerText = data.produits[id];
            }
        });
    }
    else {
        boiteErreur.classList.remove('hidden');
        boiteErreur.innerText = data;
    }
}

function formatPrice(price) {
    return price.toLocaleString('fr-CA', {style: 'currency', currency: 'CAD'});
}