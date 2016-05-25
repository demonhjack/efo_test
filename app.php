#!/usr/bin/env php
<?php
namespace Apo100l\Quest;
require_once('./vendor/myapo100l/test_php/src/classes/QuestAbstract.php');

class DbClass extends QuestAbstract {
	public function getWDocs($dates) {
		try {
			$db = $this->getDb();
        	$query = $db->query('SELECT count(p.id) as amount, sum(p.amount) as sum
        							FROM payments p
        							WHERE EXISTS (	SELECT d.id
                   									FROM documents d
                   									WHERE p.id = d.entity_id
                   										AND d.create_ts >= "'.$dates['start'].'"
                   										AND d.create_ts < "'.$dates['end'].'")');
        	$res = $query->fetch();
        } catch(PDOException $e) {
        	echo $e->getMessage();
        }

        return $res;
    }

	public function getWODocs($dates) {
		try {
			$db = $this->getDb();
        	$query = $db->query('SELECT count(p.id) as amount, sum(p.amount) as sum
        							FROM payments p
        							WHERE NOT EXISTS (	SELECT d.id
                   										FROM documents d
                   										WHERE p.id = d.entity_id
                   										AND d.create_ts >= "'.$dates['start'].'"
                   										AND d.create_ts < "'.$dates['end'].'")');
        	$res = $query->fetch();
        } catch(PDOException $e) {
        	echo $e->getMessage();
        }

        return $res;
    }
}

class ConClass {
	public function __construct() {
	}

	public function parseArgs($args) {
		$flag = 0;

		if (count($args) == 1) {
			$this->showUsage();
			return 0;
		} else {
			array_shift($args);
			foreach ($args as $arg) {
				switch ($arg) {
					case '--without-documents':
						$flag += 1;
						break;
					case '--with-documents':
						$flag += 2;
						break;
					default:
						break;
				}
			}
		}

		if ($flag == 0) {
			$this->showUsage();
		}

		return $flag;
	}

	public function getDates() {
		$carr = [];

		$tmp = [0,0,0];
		while (!checkdate($tmp[1], $tmp[2], $tmp[0])) {
			$tmp = explode("-", readline("Please enter start date:"));
		}
		$carr['start'] = implode("-", $tmp);

		$tmp = [0,0,0];
		while (!checkdate($tmp[1], $tmp[2], $tmp[0])) {
			$tmp = explode("-", readline("Please enter end date:"));
		}
		$carr['end'] = implode("-", $tmp);

		return $carr;
	}

	public function showUsage() {
		echo "Usage"."\n";
	}

	public function drawHeader() {
		echo "+---------+-------+-----------+"."\n"."| type    | count | amount    |"."\n"."+---------+-------+-----------+"."\n";
	}

	public function drawFooter() {
		echo "+---------+-------+-----------+"."\n";
	}

	public function drawLine($args) {
		if (isset($args['type']) && isset($args['count']) && isset($args['amount'])) {
			echo "| ".str_pad($args['type'], 7)." | ".str_pad($args['count'], 5)." | ".str_pad($args['amount'], 9)." |"."\n";
		}
	}
}

$console = new ConClass;
$db = new DbClass;
$f = $console->parseArgs($argv);
if ($f > 0) {
	$dates = $console->getDates();
	$console->drawHeader();
	if ($f > 1) {
		$tmp = $db->getWDocs($dates);
		$out['type'] = "w/docs";
		$out['count'] = $tmp['amount'];
		$out['amount'] = $tmp['sum'];
		$console->drawLine($out);
	}
	if ($f != 2) {
		$tmp = $db->getWODocs($dates);
		$out['type'] = "w/docs";
		$out['count'] = $tmp['amount'];
		$out['amount'] = $tmp['sum'];
		$console->drawLine($out);
	}
	$console->drawFooter();
}
//echo $console->parseArgs($argv);
//print_r($console->getDates());