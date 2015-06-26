<?php
namespace Tt;

use Vtk13\LibSql\IDatabase;
use Vtk13\LibSql\Mysql\Mysql;
use Vtk13\Mvc\Handlers\AbstractController;

class BaseController extends AbstractController
{
    /**
     * @var IDatabase
     */
    protected $db;

    public function __construct($name)
    {
        parent::__construct($name);

        $this->db = new Mysql('localhost', 'root', '', 'tt');
        $this->db->query('SET NAMES utf8');
    }
}
