drop table if exists game_status;

drop table if exists game_session;

drop table if exists current_cards;

drop trigger if exists game_status_update;

CREATE TABLE game_session (
    id int NOT NULL AUTO_INCREMENT,
    session_id int,
    username varchar(255) NOT NULL,
    user_id int NOT NULL,
    user_turn enum(1, 2, 3, 4),
    user_token varchar(20) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE game_status (
    id int NOT NULL AUTO_INCREMENT,
    status enum(
        'initialized',
        'started',
        'ended',
        'aborded'
    ) NOT NULL DEFAULT 'initialized',
    p_turn int NOT NULL DEFAULT 1,
    number_of_players int NOT NULL,
    winner int DEFAULT NULL,
    last_change timestamp DEFAULT NOW(),
    session_id int not null,
    PRIMARY KEY (id)
);

DELIMITER $ $ CREATE TRIGGER game_status_update BEFORE
UPDATE
    ON game_status FOR EACH ROW BEGIN
SET
    NEW.last_change = NOW();

END $ $ DELIMITER;

CREATE TABLE current_cards (
    id int NOT NULL AUTO_INCREMENT,
    cardId int NOT NULL,
    player_turn int NOT NULL,
    session_id int NOT NULL,
    PRIMARY KEY(id)
);