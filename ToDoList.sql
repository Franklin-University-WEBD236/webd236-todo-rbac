PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
DROP TABLE IF EXISTS `user`;
DROP TABLE IF EXISTS `todo`;
DROP TABLE IF EXISTS `groups`;
DROP TABLE IF EXISTS `permissions`;
DROP TABLE IF EXISTS `usergroups`;
DROP TABLE IF EXISTS `grouppermissions`;

CREATE TABLE `user` (
  -- Note that storing passwords in plaintext like this is very, very bad.
  -- But we'll address that issue later.
  id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  email TEXT UNIQUE NOT NULL,
  password TEXT NOT NULL,
  firstName TEXT NOT NULL,
  lastName TEXT NOT NULL
);

-- passwords:
--  Arya: v@larM0rghul1s
--  Theon: !r0nBorn
--  Tyrion: th3Imp?!
--  Todd: N1ceP@ssword
--  Admin: c@llM3R00t
INSERT INTO "user" VALUES(1,'nobody@nowhere.com','$2y$10$uKHrSOviTMvN9vbGNLsvzOzk1aRNqwFmMkobUfd5IMNRymr7U0lBm','Arya','Stark');
INSERT INTO "user" VALUES(2,'ironborn@pyke.com','$2y$10$eulmGacwa6TjIOPHWC4an.i8o1cgcdBAiMBUyrNXQ7kHeBgJ79tl.','Theon','Greyjoy');
INSERT INTO "user" VALUES(3,'alwayspayshisdebts@casterlyrock.com','$2y$10$GNup.tzD3/kTYX3SN1g.neHtKQ295arZXGoelfo3Tk5ONyi05BM7m','Tyrion','Lannister');
INSERT INTO "user" VALUES(4,'todd.whittaker@franklin.edu','$2y$10$roDY2iVzz3gj0HDl1H1FvuE7tedwoE67p.0CMziZi7QsHC5NVL.8G','Todd','Whittaker');
INSERT INTO "user" VALUES(5,'admin@webd236.com','$2y$10$6L4KwSvwx/lQQOxC6Tbzb.kRZRNPqYCWaJY0Lqq1CSBoXmI2aFagy','Admin','User');


CREATE TABLE `todo` (
  description VARCHAR(50) NOT NULL,
  done INTEGER NOT NULL,
  id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  user_id INTEGER NOT NULL REFERENCES user(id) ON DELETE CASCADE
);

INSERT INTO "todo" VALUES('Prepare a model 1 architecture example',0,2,4);
INSERT INTO "todo" VALUES('Teach class on Wednesday, 7:30 PM EST.',1,5,4);
INSERT INTO "todo" VALUES('Prepare a model 2 architecture example',1,6,4);
INSERT INTO "todo" VALUES('Get this sample app working on Glitch',1,8,4);
INSERT INTO "todo" VALUES('Another thing to do. Great.',0,11,4);
INSERT INTO "todo" VALUES('Defeat the Waif.',0,12,1);
INSERT INTO "todo" VALUES('Make a list of people to assassinate.',1,13,1);
INSERT INTO "todo" VALUES('Become nobody.',1,14,1);
INSERT INTO "todo" VALUES('Betray the Starks',1,15,2);
INSERT INTO "todo" VALUES('Rule Winterfell poorly',1,16,2);
INSERT INTO "todo" VALUES('Be tortured by Ramsay Bolton.',1,17,2);
INSERT INTO "todo" VALUES('Escape from Ramsay Bolton',0,18,2);
INSERT INTO "todo" VALUES('Be debauched',1,19,3);
INSERT INTO "todo" VALUES('Win the Battle of Blackwater.',1,20,3);
INSERT INTO "todo" VALUES('Kill my father.',1,21,3);
INSERT INTO "todo" VALUES('Run away to find Danerys Targarian.',1,22,3);
INSERT INTO "todo" VALUES('Betray my best friend',0,23,3);
INSERT INTO "todo" VALUES('Suggest a new king.',0,24,3);

------------------------------------------------------------------------
-- Role based access control tables
------------------------------------------------------------------------
CREATE TABLE `groups` (
  id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  name TEXT NOT NULL
);

INSERT INTO "groups" VALUES(1, "Users");
INSERT INTO "groups" VALUES(2, "Administrators");

CREATE TABLE `permissions` (
  id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  name TEXT NOT NULL
);

INSERT INTO "permissions" VALUES(1, "create_todo");
INSERT INTO "permissions" VALUES(2, "edit_todo");
INSERT INTO "permissions" VALUES(3, "delete_todo");
INSERT INTO "permissions" VALUES(4, "view_todo");
INSERT INTO "permissions" VALUES(5, "admin_page");
INSERT INTO "permissions" VALUES(6, "edit_user");
INSERT INTO "permissions" VALUES(7, "delete_user");
INSERT INTO "permissions" VALUES(8, "view_user");

CREATE TABLE `usergroups` (
  userId INTEGER NOT NULL,
  groupId INTEGER NOT NULL,
  FOREIGN KEY(userId) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY(groupId) REFERENCES groups(id) ON DELETE CASCADE
);

-- standard users
INSERT INTO "usergroups" VALUES(1, 1);
INSERT INTO "usergroups" VALUES(2, 1);
INSERT INTO "usergroups" VALUES(3, 1);
INSERT INTO "usergroups" VALUES(4, 1);

-- admin users
INSERT INTO "usergroups" VALUES(5, 1);
INSERT INTO "usergroups" VALUES(5, 2);

CREATE TABLE `grouppermissions` (
  permissionId INTEGER NOT NULL,
  groupId INTEGER NOT NULL,
  FOREIGN KEY(permissionId) REFERENCES permissions(id) ON DELETE CASCADE,
  FOREIGN KEY(groupId) REFERENCES groups(id) ON DELETE CASCADE
);

-- standard user permissions
INSERT INTO "grouppermissions" VALUES(1, 1);
INSERT INTO "grouppermissions" VALUES(2, 1);
INSERT INTO "grouppermissions" VALUES(3, 1);
INSERT INTO "grouppermissions" VALUES(4, 1);

-- admin permissions
INSERT INTO "grouppermissions" VALUES(5, 2);
INSERT INTO "grouppermissions" VALUES(6, 2);
INSERT INTO "grouppermissions" VALUES(7, 2);
INSERT INTO "grouppermissions" VALUES(8, 2);

DELETE FROM sqlite_sequence;
INSERT INTO "sqlite_sequence" VALUES('todo',24);
INSERT INTO "sqlite_sequence" VALUES('user',5);
INSERT INTO "sqlite_sequence" VALUES('groups',2);
INSERT INTO "sqlite_sequence" VALUES('permissions',8);
COMMIT;
