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
		const lastChangeDate = getLastChangeDate();
		activateDelay(lastChangeDate, 8);
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

function activateDelay(last_change, seconds) {
	let secondsRemaining = Math.ceil((last_change + seconds * 1000 - Date.now()) / 1000);
	if (secondsRemaining > 0) {
		if (!clientState.roundEnabled) {
			document.getElementById('countdown-seconds').innerText = `${secondsRemaining} δευτερόλεπτα`;
			clientState.roundEnabled = true;
			document.getElementById('countdown').style.display = 'flex';
			let finished = false;
			const tick = setInterval(() => {
				document.getElementById('countdown-seconds').innerText = `${secondsRemaining} δευτερόλεπτα`;
				secondsRemaining--;
				if (secondsRemaining == 0) {
					finished = true;
					clientState.roundEnabled = false;
					document.getElementById('countdown').style.display = 'none';
					clearInterval(tick);
				}
			}, 1000);
		}
	}
}

function stateUpdate() {
	return fetch(`${url}/api/game_loop.php`)
		.then((res) => res.json())
		.then((state) => {
			gameLoop(state);
			// console.log(state);
		})
		.catch((err) => {
			console.log(err);
		});
}
stateUpdate().then(() => {
	const dateInTime = getLastChangeDate();
	if (serverState.first_round == '1') {
		activateDelay(dateInTime, 15);
	}
});

function getLastChangeDate() {
	const jsDate = new Date(Date.parse(serverState.last_change.replace(/[-]/g, '/')));
	const dateInTime = jsDate.getTime();
	return dateInTime;
}

setInterval(() => {
	stateUpdate();
}, 1000);
