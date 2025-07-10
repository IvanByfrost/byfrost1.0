<?php
require_once ROOT . '/app/models/MainModel.php';

class StudentCategoryModel extends MainModel {
    public function getAllCategories() {
        return $this->getAll('student_categories');
    }
    public function getCategoryById($id) {
        return $this->getById('student_categories', $id);
    }
    public function create($name) {
        return $this->insert('student_categories', ['name' => $name]);
    }
    public function update($id, $name) {
        $stmt = $this->dbConn->prepare("UPDATE student_categories SET name = ? WHERE id = ?");
        return $stmt->execute([$name, $id]);
    }
    public function delete($id) {
        $stmt = $this->dbConn->prepare("DELETE FROM student_categories WHERE id = ?");
        return $stmt->execute([$id]);
    }
} 