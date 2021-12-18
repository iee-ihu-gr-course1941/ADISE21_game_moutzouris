let url;
if (window.location.hostname == 'users.iee.ihu.gr') {
	url = '/~it154486/ADISE21_game_moutzouris/src';
} else {
	url = '/src';
}

function addCard(cardData) {
	const newCard = document.createElement('img');
	newCard.classList.add('card-image');
	newCard.setAttribute('id', cardData.id);
	newCard.setAttribute('cardName', cardData.cardname);
	newCard.setAttribute('cardChar', cardData.cardchar);
	newCard.src = cardData.url;
	newCard.addEventListener('click', () => {
		alert(newCard.getAttribute('cardName'));
	});

	document.getElementById('my-cards').appendChild(newCard);
}

function center() {
	document.getElementById('player1').classList.toggle('centered-oponent');
}

setInterval(async () => {
	const game_state = await fetch(`${url}/api/game_loop.php`).then((res) => res.json());
	console.log(game_state);
}, 3000);


