let url;
if (window.location.hostname == 'users.iee.ihu.gr') {
	url = '/~it154486/ADISE21_game_moutzouris/src';
} else {
	url = '/src';
}

function checkStateChanged(new_game_state) {
	const stateChanged = new_game_state.last_change != gameState.last_change;
	if (stateChanged) {
		gameState = { ...new_game_state };
		updateCurrentPlayer();
	}
	return stateChanged;
}

function updateCurrentPlayer() {
	if (gameState.my_turn == gameState.player_turn) {
		document.getElementById('current-turn').innerHTML = 'Εγώ';
		document.getElementById('current-turn').classList.add('my-turn');
	} else {
		document.getElementById('current-turn').innerHTML = gameState.player_turn;
		document.getElementById('current-turn').classList.remove('my-turn');
	}
	document.getElementById(`name-header-${parseInt(gameState.player_turn) - 1 || parseInt(gameState.number_of_players)}`)?.classList.remove('current-player');
	document.getElementById(`name-header-${parseInt(gameState.player_turn)}`)?.classList.add('current-player');
}

function gameLoop(state) {
	if (state.game_state.status == 'aborted') {
		alert('Το παιχνίδι διακόπηκε!');
		window.location.href = '../auth/logout.php';
	}

	const { my_turn, number_of_players } = state.game_state;
	const player_cards = state?.player_cards[my_turn];
	if (checkStateChanged(state.game_state)) {
		document.getElementById('player-turn').innerHTML = my_turn;
		updateMyCards(my_turn, player_cards);

		let nextPlayer = my_turn == number_of_players ? 1 : parseInt(my_turn) + 1;
		let finished = false;

		while (!finished) {
			if (nextPlayer == my_turn) {
				finished = true;
				break;
			}
			if (nextPlayer <= gameState.number_of_players && nextPlayer != gameState.my_turn) {
				updateOponentCards(nextPlayer, state?.player_cards[nextPlayer]);
				nextPlayer++;
			} else if (nextPlayer > gameState.number_of_players) {
				nextPlayer = 1;
			}
		}
	}
}

function stateUpdate() {
	fetch(`${url}/api/game_loop.php`)
		.then((res) => res.json())
		.then((state) => {
			gameLoop(state);
		})
		.catch((err) => {
			console.log(err);
		});
}
stateUpdate();

setInterval(() => {
	stateUpdate();
}, 1000);
