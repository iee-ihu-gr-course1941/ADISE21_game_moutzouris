let url;
if (window.location.hostname == 'users.iee.ihu.gr') {
	url = '/~it154486/ADISE21_game_moutzouris/src';
} else {
	url = '/src';
}

function checkStateChanged(state) {
	const { game_state, player_cards, usernames, scoreboard } = state;

	const stateChanged = game_state.last_change != serverState.last_change;
	if (stateChanged) {
		//This means it it the first time client is initialized
		if (!clientState.clientInitialized) {
			usernames.forEach((user) => {
				Object.assign(clientState.usernames, user);
			});
			clientState.clientInitialized = true;
			initializeScoreBoard(scoreboard);
		}

		serverState = { ...game_state };
		clientState = {
			winner: game_state.winner,
			loser: game_state.loser,
			clientInitialized: true,
			...clientState,
		};

		updateCurrentPlayer();
		const lastChangeDate = getLastChangeDate(game_state.last_change);
		if (serverState.first_round != 1 && serverState.round_no != clientState.previousRoundNumber) {
			//Check if there are a few cards left
			clientState.previousRoundNumber = serverState.round_no;
			let total_player_cards = 0;
			for (let set in player_cards) {
				total_player_cards += player_cards[set].length;
			}
			seconds = total_player_cards < 12 ? 4 : 6;
			activateDelay(lastChangeDate, seconds);
		}
	}
	return stateChanged;
}

function updateCurrentPlayer() {
	const { previousPlayerIndex, currentPlayerIndex } = getPlayerIndex();
	const { remainingPlayers } = serverState;

	const currentPlayerTurn = remainingPlayers[currentPlayerIndex];
	const previousPlayerTurn = remainingPlayers[previousPlayerIndex];

	if (serverState.my_turn == serverState.player_turn) {
		document.getElementById('current-turn').innerHTML = 'Εγώ';
		document.getElementById('current-turn').classList.add('my-turn');
	} else {
		document.getElementById('current-turn').innerHTML = serverState.player_turn;
		document.getElementById('current-turn').classList.remove('my-turn');
	}

	document.getElementById(`name-header-${previousPlayerTurn}`)?.classList.remove('current-player');
	document.getElementById(`name-header-${currentPlayerTurn}`)?.classList.add('current-player');
	document.getElementById(`username-header-${previousPlayerTurn}`)?.classList.remove('current-player');
	document.getElementById(`username-header-${currentPlayerTurn}`)?.classList.add('current-player');
}

function getPlayerIndex() {
	const { remainingPlayers } = serverState;

	//Fix some issues here

	let currentPlayerIndex = remainingPlayers.findIndex((turn) => turn == serverState.player_turn);
	let myTurnIndex = remainingPlayers.findIndex((turn) => turn == serverState.my_turn);

	let nextPlayerIndex, previousPlayerIndex;

	if (currentPlayerIndex == remainingPlayers.length - 1) {
		nextPlayerIndex = 0;
		previousPlayerIndex = currentPlayerIndex - 1;
	} else if (currentPlayerIndex == 0) {
		previousPlayerIndex = remainingPlayers.length - 1;
		nextPlayerIndex = currentPlayerIndex + 1;
	} else {
		previousPlayerIndex = currentPlayerIndex - 1;
		nextPlayerIndex = currentPlayerIndex + 1;
	}

	return { nextPlayerIndex, previousPlayerIndex, myTurnIndex, currentPlayerIndex };
}

function gameLoop(state) {
	const dateInTime = getLastChangeDate(state.game_state.last_change);

	if (serverState.first_round == 1 && !clientState.winner && serverState.winner == 0) {
		activateDelay(dateInTime, 25);
	}
	if (state.game_state.status == 'aborted') {
		alert('Το παιχνίδι διακόπηκε!');
		window.location.href = '../auth/logout.php';
	}

	if (state.game_state.status == 'started' && clientState.loserShown) {
		initializeClientState();
	}

	checkAvailablePlayer();

	if (checkStateChanged(state)) {
		const { my_turn, remainingPlayers } = state.game_state;
		checkWinnerLoser();
		checkGameEnded();
		checkAvailablePlayer();

		document.getElementById('player-turn').innerHTML = my_turn;

		const myTurnIndex = remainingPlayers.findIndex((turn) => turn == my_turn);

		if (myTurnIndex >= 0) {
			const player_cards = state?.player_cards[my_turn];
			updateMyCards(my_turn, player_cards);
		}

		//Update all remaining players cards
		if (myTurnIndex >= 0) {
			let nextPlayerIndex = myTurnIndex == remainingPlayers.length - 1 ? 0 : myTurnIndex + 1;
			let finished = false;

			while (!finished) {
				if (nextPlayerIndex == myTurnIndex) {
					finished = true;
					break;
				}
				if (nextPlayerIndex <= remainingPlayers.length - 1 && nextPlayerIndex != myTurnIndex) {
					const nextPlayerTurn = remainingPlayers[nextPlayerIndex];
					updateOpponentCards(nextPlayerTurn, state?.player_cards[nextPlayerTurn]);
					nextPlayerIndex++;
				} else if (nextPlayerIndex >= remainingPlayers.length) {
					nextPlayerIndex = 0;
				}
			}
		} else {
			document.getElementById('my-cards').innerHTML = '';
			for (let turn in remainingPlayers) {
				updateCurrentPlayer(turn, state?.player_cards[turn]);
			}
		}
	}
}

function checkWinnerLoser() {
	if (serverState.winner != 0) {
		clientState.winner = clientState.usernames[serverState.winner];
	}
	if (serverState.loser != 0) {
		clientState.loser = clientState.usernames[serverState.loser];
	}
	if (clientState.winnerShown == false && serverState.winner != 0) {
		alert(`Ο παίκτης ${clientState.winner} κέρδισε!!!`);
		clientState.winnerShown = true;
	}
	if (clientState.loserShown == false && serverState.loser != 0) {
		alert(`Ο παίκτης ${clientState.loser} έχασε!!!`);
		clientState.loserShown = true;
	}
}

function activateDelay(last_change, seconds) {
	let secondsRemaining = Math.round((last_change + seconds * 1000 - Date.now()) / 1000);
	if (secondsRemaining > 0 && !clientState.roundEnabled) {
		document.getElementById('countdown-seconds').innerText = `${secondsRemaining} δευτερόλεπτα`;
		clientState.roundEnabled = true;
		document.getElementById('countdown').style.display = 'flex';

		const tickFunction = () => {
			console.log('seconds remaining:', secondsRemaining);
			if (secondsRemaining == 0) {
				clientState.roundEnabled = false;
			}
			document.getElementById('countdown-seconds').innerText = `${secondsRemaining} δευτερόλεπτα`;
			secondsRemaining--;
			if (secondsRemaining <= -1) {
				clientState.roundEnabled = false;
				document.getElementById('countdown').style.display = 'none';
				clearInterval(tick);
			}
		};
		tickFunction();
		const tick = setInterval(() => {
			tickFunction();
		}, 1000);
	}
}

async function stateUpdate() {
	return fetch(`${url}/api/controller.php/game-status`)
		.then((res) => res.json())
		.then((state) => {
			gameLoop(state);
			console.log(state);
		})
		.catch((err) => {
			console.log(err);
		});
}

stateUpdate();

function getLastChangeDate(last_change) {
	const jsDate = new Date(Date.parse(last_change.replace(/[-]/g, '/')));
	const dateInTime = jsDate.getTime();
	return dateInTime;
}

setInterval(() => {
	stateUpdate();
}, 3000);

function checkGameEnded() {
	if (serverState.status == 'ended') {
		document.getElementById('game-end-overlay').style.display = 'flex';
	}
}

async function continueSession() {
	try {
		const response = await fetch(`${url}/api/controller.php/continue-session`).then((res) => res.json());
		if (response.status == 200) {
			fetch(`${url}/api/controller.php/start-game`).then((res) => {
				res = res.json();
				initializeClientState();
			});
		}
	} catch (err) {
		console.log(err);
	}
}

function initializeClientState() {
	document.getElementById('game-end-overlay').style.display = 'none';
	clientState = {
		selectedCards: [],
		roundEnabled: false,
		winnerShown: false,
		winner: undefined,
		loserShown: false,
		loser: undefined,
		usernames: {},
		clientInitialized: false,
	};
}

function initializeScoreBoard(playerScores) {
	const scoreboard = document.getElementById('scoreboard');
	scoreboard.innerHTML = '';

	const newRow = (username, wins, loses) => {
		const row = document.createElement('div');

		const usernameColumn = document.createElement('div');
		usernameColumn.innerText = username;

		const winsColumn = document.createElement('div');
		winsColumn.innerText = wins;

		const losesColumn = document.createElement('div');
		losesColumn.innerText = loses;

		row.appendChild(usernameColumn);
		row.appendChild(winsColumn);
		row.appendChild(losesColumn);

		return row;
	};

	scoreboard.appendChild(newRow('Όνομα', 'Νίκες', 'Ήττες'));

	playerScores.forEach((player) => {
		const { username, wins, loses } = player;
		scoreboard.appendChild(newRow(username, wins, loses));
	});
}

function checkAvailablePlayer() {
	const { my_turn, player_turn, remainingPlayers } = serverState;
	const { nextPlayerIndex } = getPlayerIndex();
	const nextPlayerTurn = remainingPlayers[nextPlayerIndex];

	if (my_turn == player_turn && !remainingPlayers.includes(parseInt(player_turn))) {
		//Update turn
		console.log(nextPlayerTurn);
		fetch(`${url}/api/controller.php/board/end-turn/${nextPlayerTurn}`).then(() => {});
	}
}
