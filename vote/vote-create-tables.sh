#!/bin/sh
MYSQL_PWD=hidden_pass_here /usr/bin/mysql -u vote <<EOF
  
    Use vote;

    Drop table Wallet;
    Drop table Camp;
    Drop table Ballot;
    Drop table CampTo;
    Drop table Votes;


    Create table Wallet(
	StartB	char(64) NOT NULL,
	LastB	char(64) NOT NULL
    );

    Create table Camp (
	CampID	int NOT NULL default 0,
	Start	timestamp NOT NULL default '2010-01-01 00:00:00',
	Finish	timestamp NOT NULL default '2010-01-01 00:00:00',
        Ballots	int NOT NULL default 0,
        Descr	varchar(255) NOT NULL,
	primary key (CampID)
    );

    Create table Ballot (
	CampID	int NOT NULL default 0,
	TXID	char(64) NOT NULL
    );

    Create table CampTo (
	CampID	int NOT NULL default 0,
	ToID	int NOT NULL default 0,
	ToAddr	char(34),
        ToName	varchar(255) NOT NULL,
        ToDesc	varchar(255) NOT NULL,
	primary key (ToAddr)
    );

    Create table Votes (
	CampID	int NOT NULL default 0,
        TXID    char(64),
	WhoAddr	char(34) NOT NULL,
	ToID	int NOT NULL default 0,
	Id	INT AUTO_INCREMENT NOT NULL,
	Index(WhoAddr, CampID),
	PRIMARY KEY(Id)
    );


    Insert into Wallet	(StartB, LastB) values("914af3779a8bc60c27157fa404f51e4db2e08886be5730ff48897d13dd77e8bb", "914af3779a8bc60c27157fa404f51e4db2e08886be5730ff48897d13dd77e8bb");

EOF

