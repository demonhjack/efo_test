# test_php
Тестовое задание для PHP программиста
# Задача
Необходимо используя composer-пакет [myapo100l/test_php](https://packagist.org/packages/myapo100l/test_php) написать консольную утилиту вычисляющую количество и сумму платежей для которых сформированы и не сформированы документы.

Для установки пакета воспользуйтесь командой: <pre>composer require myapo100l/test_php</pre>

Пример работы утилиты:
<pre>
$$ php quest_done.php statistic --without-documents --with-documents
Please enter start date: 2015-07-20
Please enter end date: 2015-11-01
+-------+---------+
| count | amount  |
+-------+---------+
| 15    | 11400   |
| 6     | 4679.84 |
+-------+---------+
$$ 
</pre>
