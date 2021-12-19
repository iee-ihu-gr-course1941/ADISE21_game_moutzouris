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
		console.log('changed');
		updateCurrentPlayer();
	}
	return stateChanged;
}

function updateCurrentPlayer() {
	if (gameState.my_turn == gameState.player_turn) {
		document.getElementById('current-turn').innerHTML = 'Εγώ';
	} else {
		document.getElementById('current-turn').innerHTML = gameState.player_turn;
	}
	document.getElementById(`name-header-${parseInt(gameState.player_turn) - 1 || parseInt(gameState.number_of_players)}`)?.classList.toggle('current-player');
	document.getElementById(`name-header-${parseInt(gameState.player_turn)}`)?.classList.toggle('current-player');
}

function gameLoop(state) {
	const { my_turn } = state.game_state;
	const player_cards = state?.player_cards[my_turn];
	if (checkStateChanged(state.game_state)) {
		document.getElementById('player-turn').innerHTML = my_turn;
		updateMyCards(my_turn, player_cards);

		let i = parseInt(my_turn) + 1;
		let finished = false;

		while (!finished) {
			if (i == my_turn) {
				finished = true;
			}
			if (i <= gameState.number_of_players && i != gameState.my_turn) {
				updateOponentCards(i, state?.player_cards[i]);
				i++;
			} else if (i > gameState.number_of_players) {
				i = 1;
			}
		}
	}
}

async function center() {
	const response = await fetch(`${url}/api/game_functions.php/board/swap-card/1/1/2`).then((res) => res.json());
	// console.log(response);
	// document.getElementById('player1').classList.toggle('centered-oponent');
}

fetch(`${url}/api/game_loop.php`)
	.then((res) => res.json())
	.then((state) => {
		gameLoop(state);
	});
setInterval(async () => {
	const state = await fetch(`${url}/api/game_loop.php`).then((res) => res.json());
	gameLoop(state);
	console.log(state);
}, 3000);
