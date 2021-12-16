drop table if exists game_session;

drop table if exists game_Status;

drop table if exists current_cards;

drop trigger if exists game_status_update;

CREATE TABLE game_session (
    id int NOT NULL AUTO_INCREMENT,
    username varchar(255) NOT NULL,
    uid int NOT NULL,
    userToken varchar(20) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE game_status (
    id int default 1,
    status enum(
        'not active',
        'initialized',
        'started',
        'ended',
        'aborded'
    ) NOT NULL DEFAULT 'not active',
    p_turn int NOT NULL,
    number_of_players int NOT NULL,
    winner int DEFAULT NULL,
    last_change timestamp NULL DEFAULT NOW(),
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
    currentUserId in NOT NULL,
    FOREIGN KEY (cardId) REFERENCES cards(id),
    FOREIGN KEY (currentUserId) REFERENCES users(id),
);