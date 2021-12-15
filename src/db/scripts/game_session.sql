CREATE TABLE game_session (
    id int NOT NULL AUTO_INCREMENT,
    username varchar(255) NOT NULL,
    uid int NOT NULL,
    userToken varchar(20) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE current_cards (
    id int NOT NULL AUTO_INCREMENT,
    cardId int NOT NULL,
    currentUserId in NOT NULL,
    FOREIGN KEY (cardId) REFERENCES cards(id),
    FOREIGN KEY (currentUserId) REFERENCES users(id),
)