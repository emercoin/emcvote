#!/bin/sh
MYSQL_PWD=hidden_pass_here /usr/bin/mysql -u vote <<EOF
  
    Use vote;
    Insert into Camp	(CampID, Start, Finish, Ballots, Descr) values(1, '2016-01-01', '2017-01-01', 4, 'Размер членских взносов');
    Insert into Ballot	(CampID, TXID) values (1, '8598ee1eb286d1cfab0ad0033169fcf21d10d3d805041339c957e214b0343f55');
    Insert into CampTo	(CampID, ToID, ToAddr, ToName, ToDesc) values (1, 1, 'EXsNNqW2pS4MRADtQF8PdZBEMdRgaM5q79', 'vote:dev-00:1000+R', 'Более 1000 рублей в месяц');
    Insert into CampTo	(CampID, ToID, ToAddr, ToName, ToDesc) values (1, 2, 'fXsNNqW2pS4MRADtQF8PdZBEMdRgaM5q79', 'vote:dev-00:100-1000R', 'От 100 до 1000 рублей в месяц');
    Insert into CampTo	(CampID, ToID, ToAddr, ToName, ToDesc) values (1, 3, 'gXsNNqW2pS4MRADtQF8PdZBEMdRgaM5q79', 'vote:dev-00:less_100R', 'Менее 100 рублей в месяц');
    Insert into CampTo	(CampID, ToID, ToAddr, ToName, ToDesc) values (1, 4, 'hXsNNqW2pS4MRADtQF8PdZBEMdRgaM5q79', 'vote:dev-00:NoPay', 'Не надо');
    Insert into Votes   (CampID, TXID, WhoAddr, ToID) values (1, 'No_Vote', 'EQE1C2fJVZBVCbm6KkE8zPyib5iR9HdecP', 0);
    Insert into Votes   (CampID, TXID, WhoAddr, ToID) values (1, 'No_Vote', 'fQE1C2fJVZBVCbm6KkE8zPyib5iR9HdecP', 0);
    Insert into Votes   (CampID, TXID, WhoAddr, ToID) values (1, 'No_Vote', 'gQE1C2fJVZBVCbm6KkE8zPyib5iR9HdecP', 0);
    Insert into Votes   (CampID, TXID, WhoAddr, ToID) values (1, 'No_Vote', 'hQE1C2fJVZBVCbm6KkE8zPyib5iR9HdecP', 0);

    Insert into Ballot	(CampID, TXID) values (2, '5cb34c0cdf0dc02262ecb879544b68da9b94efe1009f0cd64fe4be3426d4eff7');
    Insert into Ballot	(CampID, TXID) values (2, 'f435ec9f4ce2118a897dd90fede0a0a534ce3189d3a357444a6da8fca93b1653');
    Insert into Ballot	(CampID, TXID) values (2, 'bbc41c925786328209a5b15cb9d93e7bef05e2bd16e511a40a70a1679e1fe1c3');
    Insert into Ballot	(CampID, TXID) values (2, '32e83a8744868ac6971d8e67d56b45231c4fc2d130d1085f7950b9f344dc983e');
    Insert into Ballot	(CampID, TXID) values (2, 'ba64df8333ed7d05148fbdc8782c594edd8ab760a2aee7e71f857ed08bba650b');
    Insert into Ballot	(CampID, TXID) values (2, '62b03c1d10ab9865c8668e023da6c7dd21ca1e0c2fb5d98dfc509582a161ba47');
EOF

