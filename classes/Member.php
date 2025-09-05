<?php
require_once __DIR__ . '/Database.php';

/**
 * Member Model Class
 * Handles all member-related database operations using OOP concepts
 */
class Member {
    private $db;
    private $id;
    private $name;
    private $parentId;
    private $createdDate;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Set member ID
     * @param int $id
     */
    public function setId($id) {
        $this->id = $id;
    }
    
    /**
     * Get member ID
     * @return int
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * Set member name
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }
    
    /**
     * Get member name
     * @return string
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * Set parent ID
     * @param int $parentId
     */
    public function setParentId($parentId) {
        $this->parentId = $parentId;
    }
    
    /**
     * Get parent ID
     * @return int
     */
    public function getParentId() {
        return $this->parentId;
    }
    
    /**
     * Set created date
     * @param string $createdDate
     */
    public function setCreatedDate($createdDate) {
        $this->createdDate = $createdDate;
    }
    
    /**
     * Get created date
     * @return string
     */
    public function getCreatedDate() {
        return $this->createdDate;
    }
    
    /**
     * Get all members from database
     * @return array
     */
    public function getAllMembers() {
        try {
            $stmt = $this->db->prepare("SELECT * FROM Members ORDER BY Id ASC");
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new Exception("Error fetching members: " . $e->getMessage());
        }
    }
    
    /**
     * Get all members for dropdown (excluding specific member to prevent self-reference)
     * @param int $excludeId
     * @return array
     */
    public function getMembersForDropdown($excludeId = null) {
        try {
            $sql = "SELECT Id, Name FROM Members";
            $params = [];
            
            if ($excludeId !== null) {
                $sql .= " WHERE Id != :exclude_id";
                $params[':exclude_id'] = $excludeId;
            }
            
            $sql .= " ORDER BY Name ASC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new Exception("Error fetching members for dropdown: " . $e->getMessage());
        }
    }
    
    /**
     * Create a new member
     * @param string $name
     * @param int $parentId
     * @return int Last inserted ID
     */
    public function createMember($name, $parentId = null) {
        try {
            // Validate parent exists if provided
            if ($parentId !== null) {
                $stmt = $this->db->prepare("SELECT COUNT(*) FROM Members WHERE Id = :parent_id");
                $stmt->bindParam(':parent_id', $parentId, PDO::PARAM_INT);
                $stmt->execute();
                
                if ($stmt->fetchColumn() == 0) {
                    throw new Exception("Parent member does not exist");
                }
            }
            
            $stmt = $this->db->prepare("INSERT INTO Members (Name, ParentId) VALUES (:name, :parent_id)");
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':parent_id', $parentId, PDO::PARAM_INT);
            $stmt->execute();
            
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Error creating member: " . $e->getMessage());
        }
    }
    
    /**
     * Get member by ID
     * @param int $id
     * @return array|false
     */
    public function getMemberById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM Members WHERE Id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            throw new Exception("Error fetching member: " . $e->getMessage());
        }
    }
    
    /**
     * Build tree structure recursively
     * @param array $members
     * @param int $parentId
     * @return array
     */
    public function buildTree($members, $parentId = null) {
        $tree = [];
        
        foreach ($members as $member) {
            if ($member['ParentId'] == $parentId) {
                $children = $this->buildTree($members, $member['Id']);
                if (!empty($children)) {
                    $member['children'] = $children;
                }
                $tree[] = $member;
            }
        }
        
        return $tree;
    }
    
    /**
     * Generate HTML tree structure recursively
     * @param array $tree
     * @return string
     */
    public function generateTreeHTML($tree) {
        if (empty($tree)) {
            return '';
        }
        
        $html = '<ul>';
        foreach ($tree as $member) {
            $html .= '<li data-id="' . htmlspecialchars($member['Id']) . '">';
            $html .= '<strong>' . htmlspecialchars($member['Name']) . '</strong>';
            $html .= ' <small>(ID: ' . $member['Id'] . ')</small>';
            
            if (isset($member['children']) && !empty($member['children'])) {
                $html .= $this->generateTreeHTML($member['children']);
            }
            
            $html .= '</li>';
        }
        $html .= '</ul>';
        
        return $html;
    }
}
?>
