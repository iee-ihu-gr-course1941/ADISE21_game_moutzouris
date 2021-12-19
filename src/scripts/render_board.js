function updateMyCards(my_turn, cards) {
	document.getElementById('my-cards').innerHTML = '';
	cards.forEach((card) => {
		const newCard = createCardContainer(my_turn, card);
		document.getElementById('my-cards').appendChild(newCard);
	});
}

function updateOponentCards(player_turn, player_cards) {
	let oponentContainer = document.getElementById(`oponent-container-${player_turn}`);
	let oponentCards = document.getElementById(`oponent-cards-${player_turn}`);
	let nameHeader = document.createElement('h2');
	nameHeader.innerText = `Παίκτης ${player_turn}`;

	if (!oponentContainer) {
		oponentContainer = document.createElement('div');
		oponentContainer.classList.add('oponent-container');
		oponentContainer.id = `oponent-container-${player_turn}`;
		oponentCards = document.createElement('div');
		oponentCards.classList.add(`oponent-card-container`);
		oponentCards.id = `oponent-cards-${player_turn}`;
		oponentContainer.appendChild(nameHeader);
		oponentContainer.appendChild(oponentCards);
	}
	oponentContainer.innerHTML = '';
	nameHeader = document.createElement('h2');
	nameHeader.innerText = `Παίκτης ${player_turn}`;
	oponentContainer.appendChild(nameHeader);
	for (let card of player_cards) {
		const newCard = createCardContainer(player_turn, card);
		oponentCards.append(newCard);
	}
	document.getElementById('oponent-cards').appendChild(oponentCards);
}

function createCardContainer(player_turn, card) {
	const cardContainer = document.createElement('div');
	cardContainer.classList.add('card-container');
	const newCard = document.createElement('img');
	newCard.classList.add('card-image');
	newCard.setAttribute('id', card.card_id);
	newCard.setAttribute('card-name', card.card_name);
	if (player_turn == gameState.my_turn) {
		newCard.src = card.url;
	} else {
		newCard.src = 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d4/Card_back_01.svg/703px-Card_back_01.svg.png';
	}
	newCard.addEventListener('click', () => {
		alert(newCard.getAttribute('card-name'));
	});
	cardContainer.appendChild(newCard);
	return cardContainer;
}
