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
	document.getElementById("player1").classList.toggle('centered-oponent');
}