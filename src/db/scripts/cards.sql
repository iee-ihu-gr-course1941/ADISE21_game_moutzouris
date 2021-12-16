DROP TABLE IF EXISTS cards;

CREATE TABLE cards
(
id int NOT NULL,
cardname varchar(255) NOT NULL,
cardchar varchar(255) NOT NULL,
url varchar(2000) NOT NULL
);

INSERT INTO cards VALUES (1, 'ace', 'clubs','https://upload.wikimedia.org/wikipedia/commons/3/36/Playing_card_club_A.svg' );
INSERT INTO cards VALUES (2, 'two', 'clubs','https://upload.wikimedia.org/wikipedia/commons/f/f5/Playing_card_club_2.svg' );
INSERT INTO cards VALUES (3, 'three', 'clubs','https://upload.wikimedia.org/wikipedia/commons/6/6b/Playing_card_club_3.svg' );
INSERT INTO cards VALUES (4, 'four', 'clubs','https://upload.wikimedia.org/wikipedia/commons/3/3d/Playing_card_club_4.svg' );
INSERT INTO cards VALUES (5, 'five', 'clubs','https://upload.wikimedia.org/wikipedia/commons/5/50/Playing_card_club_5.svg' );
INSERT INTO cards VALUES (6, 'six', 'clubs','https://upload.wikimedia.org/wikipedia/commons/a/a0/Playing_card_club_6.svg' );
INSERT INTO cards VALUES (7, 'seven', 'clubs','https://upload.wikimedia.org/wikipedia/commons/4/4b/Playing_card_club_7.svg' );
INSERT INTO cards VALUES (8, 'eight', 'clubs','https://upload.wikimedia.org/wikipedia/commons/e/eb/Playing_card_club_8.svg' );
INSERT INTO cards VALUES (9, 'nine', 'clubs','https://upload.wikimedia.org/wikipedia/commons/2/27/Playing_card_club_9.svg' );
INSERT INTO cards VALUES (10, 'ten', 'clubs','https://upload.wikimedia.org/wikipedia/commons/3/3e/Playing_card_club_10.svg' );
INSERT INTO cards VALUES (11, 'ace', 'diamonds','https://upload.wikimedia.org/wikipedia/commons/d/d3/Playing_card_diamond_A.svg' );
INSERT INTO cards VALUES (12, 'two', 'diamonds','https://upload.wikimedia.org/wikipedia/commons/5/59/Playing_card_diamond_2.svg' );
INSERT INTO cards VALUES (13, 'three', 'diamonds','https://upload.wikimedia.org/wikipedia/commons/8/82/Playing_card_diamond_3.svg' );
INSERT INTO cards VALUES (14, 'four', 'diamonds','https://upload.wikimedia.org/wikipedia/commons/2/20/Playing_card_diamond_4.svg' );
INSERT INTO cards VALUES (15, 'five', 'diamonds','https://upload.wikimedia.org/wikipedia/commons/f/fd/Playing_card_diamond_5.svg' );
INSERT INTO cards VALUES (16, 'six', 'diamonds','https://upload.wikimedia.org/wikipedia/commons/8/80/Playing_card_diamond_6.svg' );
INSERT INTO cards VALUES (17, 'seven', 'diamonds','https://upload.wikimedia.org/wikipedia/commons/e/e6/Playing_card_diamond_7.svg' );
INSERT INTO cards VALUES (18, 'eight', 'diamonds','https://upload.wikimedia.org/wikipedia/commons/7/78/Playing_card_diamond_8.svg' );
INSERT INTO cards VALUES (19, 'nine', 'diamonds','https://upload.wikimedia.org/wikipedia/commons/9/9e/Playing_card_diamond_9.svg' );
INSERT INTO cards VALUES (20, 'ten', 'diamonds','https://upload.wikimedia.org/wikipedia/commons/3/34/Playing_card_diamond_10.svg' );
INSERT INTO cards VALUES (21, 'ace', 'hearts','https://upload.wikimedia.org/wikipedia/commons/5/57/Playing_card_heart_A.svg' );
INSERT INTO cards VALUES (22, 'two', 'hearts','https://upload.wikimedia.org/wikipedia/commons/d/d5/Playing_card_heart_2.svg' );
INSERT INTO cards VALUES (23, 'three', 'hearts','https://upload.wikimedia.org/wikipedia/commons/b/b6/Playing_card_heart_3.svg' );
INSERT INTO cards VALUES (24, 'four', 'hearts','https://upload.wikimedia.org/wikipedia/commons/a/a2/Playing_card_heart_4.svg' );
INSERT INTO cards VALUES (25, 'five', 'hearts','https://upload.wikimedia.org/wikipedia/commons/5/52/Playing_card_heart_5.svg' );
INSERT INTO cards VALUES (26, 'six', 'hearts','https://upload.wikimedia.org/wikipedia/commons/c/cd/Playing_card_heart_6.svg' );
INSERT INTO cards VALUES (27, 'seven', 'hearts','https://upload.wikimedia.org/wikipedia/commons/c/cd/Playing_card_heart_6.svg' );
INSERT INTO cards VALUES (28, 'eight', 'hearts','https://upload.wikimedia.org/wikipedia/commons/5/50/Playing_card_heart_8.svg' );
INSERT INTO cards VALUES (29, 'nine', 'hearts','https://upload.wikimedia.org/wikipedia/commons/5/50/Playing_card_heart_9.svg' );
INSERT INTO cards VALUES (30, 'ten', 'hearts','https://upload.wikimedia.org/wikipedia/commons/9/98/Playing_card_heart_10.svg' );
INSERT INTO cards VALUES (31, 'ace', 'spades','https://upload.wikimedia.org/wikipedia/commons/2/25/Playing_card_spade_A.svg' );
INSERT INTO cards VALUES (32, 'two', 'spades','https://upload.wikimedia.org/wikipedia/commons/f/f2/Playing_card_spade_2.svg' );
INSERT INTO cards VALUES (33, 'three', 'spades','https://upload.wikimedia.org/wikipedia/commons/5/52/Playing_card_spade_3.svg' );
INSERT INTO cards VALUES (34, 'four', 'spades','https://upload.wikimedia.org/wikipedia/commons/2/2c/Playing_card_spade_4.svg' );
INSERT INTO cards VALUES (35, 'five', 'spades','https://upload.wikimedia.org/wikipedia/commons/9/94/Playing_card_spade_5.svg' );
INSERT INTO cards VALUES (36, 'six', 'spades','https://upload.wikimedia.org/wikipedia/commons/d/d2/Playing_card_spade_6.svg' );
INSERT INTO cards VALUES (37, 'seven', 'spades','https://upload.wikimedia.org/wikipedia/commons/6/66/Playing_card_spade_7.svg' );
INSERT INTO cards VALUES (38, 'eight', 'spades','https://upload.wikimedia.org/wikipedia/commons/2/21/Playing_card_spade_8.svg' );
INSERT INTO cards VALUES (39, 'nine', 'spades','https://upload.wikimedia.org/wikipedia/commons/e/e0/Playing_card_spade_9.svg' );
INSERT INTO cards VALUES (40, 'ten', 'spades','https://upload.wikimedia.org/wikipedia/commons/8/87/Playing_card_spade_10.svg' );
INSERT INTO cards VALUES (41, 'king', 'clubs','https://upload.wikimedia.org/wikipedia/commons/9/9f/Playing_card_spade_K.svg' );
INSERT INTO cards VALUES (42, 'back', 'back','https://upload.wikimedia.org/wikipedia/commons/thumb/d/d4/Card_back_01.svg/703px-Card_back_01.svg.png' );