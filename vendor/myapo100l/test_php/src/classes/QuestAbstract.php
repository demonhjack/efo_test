<?php
/**
 * @author: Apostol Alexandr <dev@apo100l.ru>
 * @since: 12.06.16
 */


namespace Apo100l\Quest;

abstract class QuestAbstract
{
    /**
     * @var \PDO
     */
    private $db = null;

    public final function __construct()
    {
        $this->db = new \PDO('sqlite::memory:');
        $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $sql = file_get_contents(__DIR__.'/../../resources/database.sql');
        $this->db->exec($sql);
    }

    /**
     * @return \PDO
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * @param \PDO $db
     */
    public function setDb($db)
    {
        $this->db = $db;
    }

}