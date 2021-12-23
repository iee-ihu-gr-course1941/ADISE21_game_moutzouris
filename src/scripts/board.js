let url;
if (window.location.hostname == 'users.iee.ihu.gr') {
	url = '/~it154486/ADISE21_game_moutzouris/src';
} else {
	url = '/src';
}

function checkStateChanged(new_game_state, player_cards) {
	const stateChanged = new_game_state.last_change != serverState.last_change;
	if (stateChanged) {
		//This means it it the first time client is initialized
		if (!clientState.clientInitialized) {
			new_game_state.usernames.forEach((user) => {
				Object.assign(clientState.usernames, user);
			});
			clientState.clientInitialized = true;
		}

		serverState = { ...new_game_state };
		clientState = {
			winner: new_game_state.winner,
			loser: new_game_state.loser,
			...clientState,
		};

		updateCurrentPlayer();
		const lastChangeDate = getLastChangeDate(new_game_state.last_change);
		if (serverState.first_round != '1') {
			//Check if there are a few cards left
			let total_player_cards = 0;
			for (let set in player_cards) {
				total_player_cards += player_cards[set].length;
			}
			console.log(total_player_cards);
			seconds = total_player_cards < 15 ? 4 : 8;

			activateDelay(lastChangeDate, seconds);
		}
	}
	return stateChanged;
}

function updateCurrentPlayer() {
	if (serverState.my_turn == serverState.player_turn) {
		document.getElementById('current-turn').innerHTML = 'Εγώ';
		document.getElementById('current-turn').classList.add('my-turn');
	} else {
		document.getElementById('current-turn').innerHTML = serverState.player_turn;
		document.getElementById('current-turn').classList.remove('my-turn');
	}
	document.getElementById(`name-header-${parseInt(serverState.player_turn) - 1 || parseInt(serverState.number_of_players)}`)?.classList.remove('current-player');
	document.getElementById(`name-header-${parseInt(serverState.player_turn)}`)?.classList.add('current-player');
	document.getElementById(`username-header-${parseInt(serverState.player_turn) - 1 || parseInt(serverState.number_of_players)}`)?.classList.remove('current-player');
	document.getElementById(`username-header-${parseInt(serverState.player_turn)}`)?.classList.add('current-player');
}

function gameLoop(state) {
	const dateInTime = getLastChangeDate(state.game_state.last_change);
	if (serverState.first_round == '1') {
		activateDelay(dateInTime, 18);
	}
	if (state.game_state.status == 'aborted') {
		alert('Το παιχνίδι διακόπηκε!');
		window.location.href = '../auth/logout.php';
	}

	if (state.game_state.status == 'started') {
		initializeClientState();
	}

	const { my_turn, number_of_players } = state.game_state;
	const player_cards = state?.player_cards[my_turn];
	if (checkStateChanged(state.game_state, state.player_cards)) {
		checkWinnerLoser();
		checkGameEnded();

		document.getElementById('player-turn').innerHTML = my_turn;
		updateMyCards(my_turn, player_cards);

		let nextPlayer = my_turn == number_of_players ? 1 : parseInt(my_turn) + 1;
		let finished = false;

		while (!finished) {
			if (nextPlayer == my_turn) {
				finished = true;
				break;
			}
			if (nextPlayer <= serverState.number_of_players && nextPlayer != serverState.my_turn) {
				updateOponentCards(nextPlayer, state?.player_cards[nextPlayer]);
				nextPlayer++;
			} else if (nextPlayer > serverState.number_of_players) {
				nextPlayer = 1;
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
			document.getElementById('countdown-seconds').innerText = `${secondsRemaining} δευτερόλεπτα`;
			secondsRemaining--;
			if (secondsRemaining == -1) {
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
			// console.log(state);
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
}, 1000);

function checkGameEnded() {
	if (serverState.status == 'ended') {
		console.log(serverState);
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

function initializeScoreBoard() {
	
}