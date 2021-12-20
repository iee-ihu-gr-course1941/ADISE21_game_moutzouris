function updateMyCards(my_turn, cards) {
	document.getElementById('my-cards').innerHTML = '';
	cards.forEach((card) => {
		const newCard = createCardContainer(my_turn, card);
		newCard.addEventListener('click', selectCard.bind(null, newCard));
		document.getElementById('my-cards').appendChild(newCard);
	});
}

function updateOponentCards(player_turn, player_cards) {
	let oponentContainer = document.getElementById(`oponent-container-${player_turn}`);
	let oponentCards = document.getElementById(`oponent-cards-${player_turn}`);

	let nameHeader = document.createElement('h2');
	nameHeader.id = `name-header-${player_turn}`;
	nameHeader.innerText = `Παίκτης ${player_turn}`;
	if (player_turn == 1) {
		nameHeader.classList.add('current-player');
	}

	if (!oponentContainer) {
		oponentContainer = document.createElement('div');
		oponentContainer.classList.add('oponent-container');
		oponentContainer.id = `oponent-container-${player_turn}`;

		oponentCards = document.createElement('div');
		oponentCards.classList.add(`oponent-card-container`);
		oponentCards.id = `oponent-cards-${player_turn}`;

		oponentContainer.appendChild(nameHeader);
		oponentContainer.appendChild(oponentCards);
	} else {
		document.getElementById(`oponent-cards-${player_turn}`).innerHTML = '';
	}

	for (let card of player_cards) {
		const newCard = createCardContainer(player_turn, card);
		oponentCards.append(newCard);
	}

	if (gameState.number_of_players == 2) {
		oponentContainer.classList.add('players-2');
	} else if (gameState.number_of_players == 3) {
		oponentContainer.classList.add('players-3');
	} else {
		oponentContainer.classList.add('players-4');
	}

	document.getElementById('oponent-cards').appendChild(oponentContainer);
}

function createCardContainer(player_turn, card) {
	const cardContainer = document.createElement('div');
	cardContainer.classList.add('card-container');
	cardContainer.setAttribute('id', card.card_id);
	cardContainer.setAttribute('card-name', card.card_name);

	const newCard = document.createElement('img');
	newCard.classList.add('card-image');
	if (player_turn == gameState.my_turn) {
		newCard.src = card.url;
	} else {
		newCard.src = 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d4/Card_back_01.svg/703px-Card_back_01.svg.png';
	}

	const nextPlayerTurn = getNextPlayerTurn();
	if (player_turn == nextPlayerTurn) {
		cardContainer.addEventListener('click', swapCard.bind(this, player_turn, gameState.my_turn, card.card_id));
	}

	cardContainer.appendChild(newCard);

	return cardContainer;
}

function getNextPlayerTurn() {
	const { my_turn, number_of_players } = gameState;
	let nextPlayerTurn;
	if (my_turn == number_of_players) {
		nextPlayerTurn = 1;
	} else {
		nextPlayerTurn = parseInt(my_turn) + 1;
	}
	return nextPlayerTurn;
}

async function swapCard(fromPlayer, toPlayer, cardId) {
	const { player_turn, my_turn } = gameState;
	//Check if it is my turn
	if (player_turn == my_turn) {
		const nextPlayerTurn = getNextPlayerTurn();
		//Check if swapped card comes from the next player
		if (fromPlayer == nextPlayerTurn && toPlayer == my_turn) {
			const response = await fetch(`${url}/api/game_functions.php/board/swap-card/${fromPlayer}/${toPlayer}/${cardId}`).then((res) => res.json());

			if (response.status == 200) {
				//End my turn
				const response = await fetch(`${url}/api/game_functions.php/board/end-turn/${nextPlayerTurn}`).then((res) => res.json());
				if (response.status == 404) {
					alert('Κάτι πήγε στραβά!');
				} else {
					stateUpdate();
				}
			}
		}
	}
}

async function checkSameCards() {
	if (selectedCards.length == 2) {
		const cardName1 = Object.values(selectedCards[0])[0];
		const cardName2 = Object.values(selectedCards[1])[0];
		const cardId1 = Object.keys(selectedCards[0])[0];
		const cardId2 = Object.keys(selectedCards[1])[0];
		if (cardId1 == cardId2) {
			document.getElementById(cardId1).classList.remove('selected-card');
			selectedCards = [];
			return;
		}
		if (cardName1 == cardName2) {
			const response = await fetch(`${url}/api/game_functions.php/board/discard-cards/${gameState.my_turn}/${cardId1}/${cardId2}`).then((res) => res.json());
			if (response.status == 404) {
				alert('Κάτι πήγε στραβά!');
			} else {
				selectedCards = [];
			}
		} else {
			setTimeout(() => {
				document.getElementById(cardId1).classList.remove('selected-card');
				document.getElementById(cardId2).classList.remove('selected-card');
				selectedCards = [];
			}, 1500);
		}
	}
}

function selectCard(card) {
	const id = card.getAttribute('id');
	const cardName = card.getAttribute('card-name');
	if (selectedCards.length < 2) {
		selectedCards.push({ [id]: cardName });
		document.getElementById(id).classList.add('selected-card');
	}
	checkSameCards();
}

function check() {
	fetch(`${url}/api/game_functions.php/board/game-ended`).then((res) => res.json());
}
