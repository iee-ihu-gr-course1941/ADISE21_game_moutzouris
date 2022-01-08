function updateMyCards(my_turn, cards) {
	document.getElementById('my-cards').innerHTML = '';
	cards?.forEach((card) => {
		const newCard = createCardContainer(my_turn, card);
		newCard.addEventListener('click', selectCard.bind(this, newCard));
		document.getElementById('my-cards').appendChild(newCard);
	});
}

function updateOpponentCards(player_turn, player_cards) {
	let opponentContainer = document.getElementById(`opponent-container-${player_turn}`);
	let opponentCards = document.getElementById(`opponent-cards-${player_turn}`);

	let nameHeader = document.createElement('h3');
	nameHeader.id = `name-header-${player_turn}`;
	nameHeader.innerText = `Παίκτης ${player_turn}`;

	let usernameHeader = document.createElement('h1');
	usernameHeader.id = `username-header-${player_turn}`;
	usernameHeader.innerText = clientState.usernames[player_turn];

	const { currentPlayerIndex } = getPlayerIndex();

	if (player_turn == serverState.remainingPlayers[currentPlayerIndex]) {
		nameHeader.classList.add('current-player');
		usernameHeader.classList.add('current-player');
	}

	if (!opponentContainer) {
		opponentContainer = document.createElement('div');
		opponentContainer.classList.add('opponent-container');
		opponentContainer.id = `opponent-container-${player_turn}`;

		opponentCards = document.createElement('div');
		opponentCards.classList.add(`opponent-card-container`);
		opponentCards.id = `opponent-cards-${player_turn}`;

		opponentContainer.appendChild(usernameHeader);
		opponentContainer.appendChild(nameHeader);
		opponentContainer.appendChild(opponentCards);
	} else {
		document.getElementById(`opponent-cards-${player_turn}`).innerHTML = '';
	}

	for (let card of player_cards) {
		const newCard = createCardContainer(player_turn, card);
		opponentCards.append(newCard);
	}

	if (serverState.number_of_players == 2) {
		opponentContainer.classList.add('players-2');
	} else if (serverState.number_of_players == 3) {
		opponentContainer.classList.add('players-3');
	} else {
		opponentContainer.classList.add('players-4');
	}

	document.getElementById('opponent-cards').appendChild(opponentContainer);
}

function createCardContainer(player_turn, card) {
	const cardContainer = document.createElement('div');
	cardContainer.classList.add('card-container');
	cardContainer.setAttribute('id', card.card_id);

	if (player_turn == serverState.my_turn) {
		cardContainer.setAttribute('card-name', card.card_name);
	}

	const newCard = document.createElement('img');
	newCard.classList.add('card-image');
	if (player_turn == serverState.my_turn) {
		newCard.src = card.url;
	} else {
		newCard.src = 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d4/Card_back_01.svg/703px-Card_back_01.svg.png';
	}

	const nextPlayerTurn = serverState.remainingPlayers[getNextPlayerTurn()];

	if (player_turn == nextPlayerTurn) {
		cardContainer.addEventListener('click', swapCard.bind(this, nextPlayerTurn, serverState.my_turn, card.card_id));
	}

	cardContainer.appendChild(newCard);

	return cardContainer;
}

function getNextPlayerTurn() {
	const { my_turn, number_of_players, remainingPlayers } = serverState;

	let currentPlayerIndex = remainingPlayers.findIndex((turn) => turn == serverState.player_turn);
	let myTurnIndex = remainingPlayers.findIndex((turn) => turn == my_turn);

	let nextPlayerIndex;

	if (myTurnIndex == remainingPlayers.length - 1) {
		nextPlayerIndex = 0;
	} else {
		nextPlayerIndex = myTurnIndex + 1;
	}

	return nextPlayerIndex;
}

async function swapCard(fromPlayer, toPlayer, cardId) {
	const { player_turn, my_turn } = serverState;
	//Check if it is my turn
	if (player_turn == my_turn && !clientState.roundEnabled) {
		const { nextPlayerIndex } = getPlayerIndex();

		const nextPlayerTurn = serverState.remainingPlayers[nextPlayerIndex];
		//Check if swapped card comes from the next player
		if (fromPlayer == nextPlayerTurn && toPlayer == my_turn) {
			const response = await fetch(`${url}/api/controller.php/board/swap-card/${fromPlayer}/${toPlayer}/${cardId}`).then((res) => res.json());

			if (response.status == 200) {
				//End my turn
				const response = await fetch(`${url}/api/controller.php/board/end-turn/${nextPlayerTurn}`).then((res) => res.json());
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
	if (clientState.selectedCards.length == 2) {
		const cardName1 = clientState.selectedCards[0].cardName;
		const cardName2 = clientState.selectedCards[1].cardName;
		const cardId1 = clientState.selectedCards[0].cardId;
		const cardId2 = clientState.selectedCards[1].cardId;
		if (cardId1 == cardId2) {
			document.getElementById(cardId1).classList.remove('selected-card');
			clientState.selectedCards = [];
			return;
		}
		if (cardName1 == cardName2) {
			if (clientState.roundEnabled) {
				setTimeout(() => {
					document.getElementById(cardId1).style.display = 'none';
					document.getElementById(cardId2).style.display = 'none';
				}, 750);
				const response = await fetch(`${url}/api/controller.php/board/discard-cards/${serverState.my_turn}/${cardId1}/${cardId2}`).then((res) => res.json());
				if (response.status == 404) {
					alert('Κάτι πήγε στραβά!');
				}
			}
			clientState.selectedCards = [];
		}
		clientState.selectedCards = [];
		setTimeout(() => {
			document.getElementById(cardId1)?.classList.remove('selected-card');
			document.getElementById(cardId2)?.classList.remove('selected-card');
		}, 750);
	}
}

function selectCard(card) {
	const id = card.getAttribute('id');
	const cardName = card.getAttribute('card-name');
	if (clientState.selectedCards.length < 2) {
		clientState.selectedCards.push({
			cardId: id,
			cardName,
		});
		document.getElementById(id).classList.add('selected-card');
	}
	checkSameCards();
}
