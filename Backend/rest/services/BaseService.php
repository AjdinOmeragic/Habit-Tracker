<?php
require_once __DIR__ . '/../dao/BaseDao.php';

class BaseService {
    protected $dao;

    public function __construct($dao) {
        $this->dao = $dao;
    }

    public function get_all() {
        return $this->dao->getAll();
    }

    public function get_by_id($id) {
        return $this->dao->getById($id);
    }

    public function add($entity) {
        return $this->dao->add($entity);
    }

    public function update($entity, $id, $id_column = "id") {
        return $this->dao->update($entity, $id, $id_column);
    }

    public function delete($id) {
        return $this->dao->delete($id);
    }
}
?>