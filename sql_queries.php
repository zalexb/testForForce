<?php
//1.	Баланс по каждому пользователю (сумма денег по всем номерам и операторам каждого пользователя)
$sum_balance = 'SELECT users.*, (SELECT SUM(phones.balance) FROM phones WHERE phones.user_id = users.id) as sum_balance from `users` ';
//2.	количество номеров телефонов по операторам (список: код оператора, кол-во номеров этого оператора);
$operator_num = 'SELECT DISTINCT operator_code as code, (SELECT COUNT(ID) from phones WHERE operator_code = code ) as phone_num FROM `phones`';
//3.	количество телефонов у каждого пользователя (список: имя пользователя, кол-во номеров у пользователя);
$phones_count = 'SELECT users.name, (SELECT COUNT(phones.id) FROM phones WHERE phones.user_id = users.id) as phone_num from `users` ';
//4.	вывести имена 10 пользователей с максимальным балансом на счету (максимальный баланс по одному номеру);
$max_balance = 'SELECT users.name,  (SELECT MAX(phones.balance) FROM phones WHERE phones.user_id = users.id) as max_balance from `users` order by `max_balance` desc limit 10 offset 0 ';