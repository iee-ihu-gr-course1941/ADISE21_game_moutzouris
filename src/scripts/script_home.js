function addCard() {
	const userCards = [
		{
			id: 1,
			cardName: 'Assos',
			cardchar: 'clubs',
			url: 'https://upload.wikimedia.org/wikipedia/commons/3/36/Playing_card_club_A.svg',
		},
		{
			id: 2,
			cardName: 'Dyo',
			cardchar: 'clubs',
			url: 'https://upload.wikimedia.org/wikipedia/commons/f/f5/Playing_card_club_2.svg',
		},
	];

	for (let card of userCards) {
		const newCard = document.createElement('img');
		newCard.setAttribute('id', card.id);
		newCard.setAttribute('cardName', card.cardName);
		newCard.setAttribute('cardChar', card.cardchar);
		newCard.src = card.url;
		newCard.addEventListener('click', () => {
			alert(newCard.getAttribute('cardName'));
		});

		document.getElementById('myCards').appendChild(newCard);
	}
}

addCard();
