-- TODO: Put ALL SQL in between `BEGIN TRANSACTION` and `COMMIT`
BEGIN TRANSACTION;

-- TODO: create tables
CREATE TABLE images (
  id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
  file_name TEXT NOT NULL,
  file_ext TEXT NOT NULL
);

CREATE TABLE tags (
  id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
  name TEXT NOT NULL UNIQUE
);

CREATE TABLE image_tags (
  id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
  image_id INTEGER NOT NULL,
  tag_id INTEGER NOT NULL,
  FOREIGN KEY (image_id) REFERENCES images(id),
  FOREIGN KEY (tag_id) REFERENCES tags(id)
);


-- TODO: initial seed data
INSERT INTO images (id, file_name, file_ext) VALUES (1, '1.jpg', 'jpg');
INSERT INTO tags (id, name) VALUES (1, 'New York');
INSERT INTO tags (id, name) VALUES (2, 'brunch');
INSERT INTO tags (id, name) VALUES (3, 'healthy');
INSERT INTO image_tags (image_id, tag_id) VALUES (1, 1);
INSERT INTO image_tags (image_id, tag_id) VALUES (1, 2);
INSERT INTO image_tags (image_id, tag_id) VALUES (1, 3);

INSERT INTO images (id, file_name, file_ext) VALUES (2, '2.jpg', 'jpg');
--INSERT INTO tags (id, name) VALUES (1, 'New York');
INSERT INTO tags (id, name) VALUES (4, 'dessert');
INSERT INTO image_tags (image_id, tag_id) VALUES (2, 1);
INSERT INTO image_tags (image_id, tag_id) VALUES (2, 4);

INSERT INTO images (id, file_name, file_ext) VALUES (3, '3.jpg', 'jpg');
--INSERT INTO tags (id, name) VALUES (4, 'dessert');
INSERT INTO tags (id, name) VALUES (5, 'Denmark');
INSERT INTO tags (id, name) VALUES (6, 'Europe');
INSERT INTO image_tags (image_id, tag_id) VALUES (3, 4);
INSERT INTO image_tags (image_id, tag_id) VALUES (3, 5);
INSERT INTO image_tags (image_id, tag_id) VALUES (3, 6);

INSERT INTO images (id, file_name, file_ext) VALUES (4, '4.jpg', 'jpg');
--INSERT INTO tags (id, name) VALUES (1, 'New York');
--INSERT INTO tags (id, name) VALUES (4, 'dessert');
INSERT INTO tags (id, name) VALUES (7, 'matcha');
INSERT INTO image_tags (image_id, tag_id) VALUES (4, 1);
INSERT INTO image_tags (image_id, tag_id) VALUES (4, 4);
INSERT INTO image_tags (image_id, tag_id) VALUES (4, 7);

INSERT INTO images (id, file_name, file_ext) VALUES (5, '5.jpg', 'jpg');
--INSERT INTO tags (id, name) VALUES (1, 'New York');
--INSERT INTO tags (id, name) VALUES (4, 'dessert');
--INSERT INTO tags (id, name) VALUES (7, 'matcha');
INSERT INTO image_tags (image_id, tag_id) VALUES (5, 1);
INSERT INTO image_tags (image_id, tag_id) VALUES (5, 4);
INSERT INTO image_tags (image_id, tag_id) VALUES (5, 7);

INSERT INTO images (id, file_name, file_ext) VALUES (6, '6.jpg', 'jpg');
INSERT INTO tags (id, name) VALUES (8, 'California');
--INSERT INTO tags (id, name) VALUES (2, 'brunch');
INSERT INTO image_tags (image_id, tag_id) VALUES (6, 8);
INSERT INTO image_tags (image_id, tag_id) VALUES (6, 2);

INSERT INTO images (id, file_name, file_ext) VALUES (7, '7.jpg', 'jpg');
--INSERT INTO tags (id, name) VALUES (5, 'Denmark');
--INSERT INTO tags (id, name) VALUES (6, 'Europe');
INSERT INTO tags (id, name) VALUES (9, 'lunch');
INSERT INTO tags (id, name) VALUES (10, 'Italy');
INSERT INTO image_tags (image_id, tag_id) VALUES (7, 6);
INSERT INTO image_tags (image_id, tag_id) VALUES (7, 9);
INSERT INTO image_tags (image_id, tag_id) VALUES (7, 10);

INSERT INTO images (id, file_name, file_ext) VALUES (8, '8.jpg', 'jpg');
--INSERT INTO tags (id, name) VALUES (1, 'New York');
--INSERT INTO tags (id, name) VALUES (9, 'lunch');
INSERT INTO image_tags (image_id, tag_id) VALUES (8, 1);
INSERT INTO image_tags (image_id, tag_id) VALUES (8, 9);

INSERT INTO images (id, file_name, file_ext) VALUES (9, '9.jpg', 'jpg');
--INSERT INTO tags (id, name) VALUES (1, 'New York');
--INSERT INTO tags (id, name) VALUES (9, 'lunch');
INSERT INTO image_tags (image_id, tag_id) VALUES (9, 1);
INSERT INTO image_tags (image_id, tag_id) VALUES (9, 9);

INSERT INTO images (id, file_name, file_ext) VALUES (10, '10.jpg', 'jpg');
--INSERT INTO tags (id, name) VALUES (9, 'lunch');
--INSERT INTO tags (id, name) VALUES (6, 'Europe');
--INSERT INTO tags (id, name) VALUES (10, 'Italy');
INSERT INTO image_tags (image_id, tag_id) VALUES (10, 9);
INSERT INTO image_tags (image_id, tag_id) VALUES (10, 6);
INSERT INTO image_tags (image_id, tag_id) VALUES (10, 10);


COMMIT;
