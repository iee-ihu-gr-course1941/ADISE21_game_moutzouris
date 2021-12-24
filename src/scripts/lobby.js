let url;
if (window.location.hostname == 'users.iee.ihu.gr') {
	url = '/~it154486/ADISE21_game_moutzouris/src';
} else {
	url = '/src';
}

function addPlayerToTable(uid, username) {
	const tableRow = document.createElement('tr');
	const firstColumn = document.createElement('td');
	const secondColumn = document.createElement('td');
	firstColumn.innerText = uid;
	secondColumn.innerText = username;
	tableRow.appendChild(firstColumn);
	tableRow.appendChild(secondColumn);
	document.getElementById('lobby-table').appendChild(tableRow);
}

function startGame() {
	fetch(`${url}/api/controller.php/start-game`)
		.then((res) => res.json())
		.then((data) => {
			// console.log(data);
		});
}

function checkAvailablePlayers(players) {
	const startGameButton = document.getElementById('start-game');
	const gameCheckLabel = document.getElementById('game-check');
	if (players.length > 1) {
		startGameButton.disabled = false;
		startGameButton.classList.remove('disabled');
		gameCheckLabel.innerText = 'Πατήστε Έναρξη για να ξεκινήσει το παιχνίδι';
	} else {
		startGameButton.disabled = true;
		startGameButton.classList.add('disabled');
		gameCheckLabel.innerText = 'Χρειάζονται τουλάχιστον 2 παίκτες για να ξεκινήσει το παιχνίδι';
	}
}

setInterval(async () => {
	document.getElementById('lobby-table').innerHTML = `<tr>
        <th>Νο. Παίκτη</th>
        <th>Username</th>
    </tr>`;
	const data = await fetch(`${url}/api/controller.php/lobby`).then((res) => res.json());
	data.players.forEach((player) => {
		for (const [uid, username] of Object.entries(player)) {
			addPlayerToTable(uid, username);
		}
	});
	if (data.game_status === 'started') {
		window.location.pathname = `${url}/pages/board.php`;
	}
	checkAvailablePlayers(data.players);
}, 3000);
