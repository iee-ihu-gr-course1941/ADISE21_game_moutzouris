let url;
if (window.location.hostname == 'users.iee.ihu.gr') {
	url = '/~it154486/ADISE21_game_moutzouris/src';
} else {
	url = '/src';
}

function checkStateChanged(new_game_state) {
	const stateChanged = new_game_state.last_change != serverState.last_change;
	if (stateChanged) {
		serverState = { ...new_game_state };
		updateCurrentPlayer();
		activateDelay(6);
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
			if (nextPlayer <= serverState.number_of_players && nextPlayer != serverState.my_turn) {
				updateOponentCards(nextPlayer, state?.player_cards[nextPlayer]);
				nextPlayer++;
			} else if (nextPlayer > serverState.number_of_players) {
				nextPlayer = 1;
			}
		}
	}
}

function activateDelay(seconds) {
	if (!clientState.roundEnabled) {
		clientState.roundEnabled = true;
		document.getElementById('countdown').style.display = 'flex';
		document.getElementById('countdown-seconds').innerText = `${seconds} δευτερόλεπτα`;
		const tick = setInterval(() => {
			document.getElementById('countdown-seconds').innerText = `${seconds} δευτερόλεπτα`;
			seconds--;
		}, 1000);
		setTimeout(() => {
			clientState.roundEnabled = false;
			clearInterval(tick);
			document.getElementById('countdown').style.display = 'none';
		}, seconds * 1000 + 1000);
	}
}

function stateUpdate() {
	return fetch(`${url}/api/game_loop.php`)
		.then((res) => res.json())
		.then((state) => {
			gameLoop(state);
		})
		.catch((err) => {
			console.log(err);
		});
}
stateUpdate().then(() => {
	if (gameState.first_round == '1') {
		activateDelay(15);
	}
});

setInterval(() => {
	stateUpdate();
}, 1000);
