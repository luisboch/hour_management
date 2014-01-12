<?php

require_once 'BasicDAO.php';
require_once APP_DIR . 'entity/User.php';

/**
 * Description of UserDAO
 *
 * @author luis
 * @since Jan 7, 2014
 */
class UserDAO extends BasicDAO {

    public function __construct() {
        parent::__construct("User");
    }

    public function findById($id) {
        return $this->em->find("User", $id);
    }

    /**
     * 
     * @param string $email
     * @return User
     * @throws LogicException
     */
    public function findByEmail($email) {
        $users = $this->find(array('email = ' => $email, 'active = ' => true));
        if (count($users) == 0) {
            return null;
        } else if (count($users) == 1) {
            return $users[0];
        } else {
            throw new LogicException("Found more than one result with email: " . $email);
        }
    }

    /**
     * 
     * @param string $cpf
     * @return User
     * @throws LogicException
     */
    public function findByCPF($cpf) {
        $users = $this->find(array('cpf = ' => $cpf, 'active = ' => true));
        if (count($users) == 0) {
            return null;
        } else if (count($users) == 1) {
            return $users[0];
        } else {
            throw new LogicException("Found more than one result with cpf: " . $cpf);
        }
    }

    public function search($filters = array(), $activeOnly = NULL, $limit = NULL, $offset = NULL) {

        $where = "";
        $whereEmpty = true;

        if (count($filters) > 0) {

            $where .= "(";
            $whereEmpty = true;
            if (isset($filters["search"])) {
                if ($filters["search"] != '') {
                    $where .= "lower(x.name) like :search\n";
                    $where .= "or lower(x.email) like :search\n";
                    $whereEmpty = false;
                    $filters['search'] = '%'.   mb_convert_case(str_replace(' ', '%', $filters['search']), MB_CASE_LOWER, "UTF-8").'%';
                }
            } else {
                $i = 0;

                if (isset($filters['email']) && $filters['email'] != '') {
                    
                    $where .= 'lower(x.email) like :email ';
                    
                    $filters['email'] = '%'.  strtolower(str_replace(' ', '%', $filters['email'])).'%';
                    
                    $i++;
                    
                }

                if (isset($filters['name']) && $filters['name'] != '') {
                    
                    if ($i != 0) {
                        $where .= 'and ';
                    }
                    
                    $where .= 'lower(x.name) like :name ';
                    
                    $filters['name'] = '%'.  strtolower(str_replace(' ', '%', $filters['name'])).'%';
                    $i++;
                }

                if (isset($filters['cpf']) && $filters['cpf'] != '') {
                    if ($i != 0) {
                        $where .= 'and ';
                    }
                    $where .= 'lower(x.cpf) like :cpf ';
                    $i++;
                }

                if (isset($filters['id']) && $filters['id'] != '') {
                    if ($i != 0) {
                        $where .= 'and ';
                    }
                    $where .= 'id = :id ';
                    $i++;
                }

                if ($i > 0) {
                    $whereEmpty = false;
                }
            }

            $where .= ")";

            // Clear filters
            if ($whereEmpty) {
                $where = '';
                $filters = array();
            }
        }

        if ($activeOnly !== NULL) {
            
            if (!$whereEmpty) {
                $where .= "and ";
            }
            
            $where .= "x.active = :active";
            $filters['active'] = $activeOnly;
            $whereEmpty = false;
        }

        $dql = "select x from User x ";

        if (!$whereEmpty) {
            $dql .= "where $where ";
        }

        $dql .= "order by x.name";

        $q = $this->em->createQuery($dql);

        $this->setParams($q, $filters);

        if ($limit != NULL) {
            $q->setMaxResults($limit);
        }

        if ($offset != NULL) {
            $q->setFirstResult($offset);
        }

        return $q->getResult();
    }

    /**
     * 
     * @param array $filters
     * @param boolean $activeOnly
     * @return int
     */
    public function searchCount($filters = array(), $activeOnly = NULL) {

        $where = "";
        $whereEmpty = true;

        if (count($filters) > 0) {

            $where .= "(";
            $whereEmpty = true;
            if (isset($filters["search"])) {
                if ($filters["search"] != '') {
                    $where .= "lower(x.name) like :search\n";
                    $where .= "or lower(x.email) like :search\n";
                    $whereEmpty = false;
                    $filters['search'] = '%'.   mb_convert_case(str_replace(' ', '%', $filters['search']), MB_CASE_LOWER, "UTF-8").'%';
                }
            } else {
                $i = 0;

                if (isset($filters['email']) && $filters['email'] != '') {
                    
                    $where .= 'lower(x.email) like :email ';
                    
                    $filters['email'] = '%'.  strtolower(str_replace(' ', '%', $filters['email'])).'%';
                    
                    $i++;
                    
                }

                if (isset($filters['name']) && $filters['name'] != '') {
                    
                    if ($i != 0) {
                        $where .= 'and ';
                    }
                    
                    $where .= 'lower(x.name) like :name ';
                    
                    $filters['name'] = '%'.  strtolower(str_replace(' ', '%', $filters['name'])).'%';
                    $i++;
                }

                if (isset($filters['cpf']) && $filters['cpf'] != '') {
                    if ($i != 0) {
                        $where .= 'and ';
                    }
                    $where .= 'lower(x.cpf) like :cpf ';
                    $i++;
                }

                if (isset($filters['id']) && $filters['id'] != '') {
                    if ($i != 0) {
                        $where .= 'and ';
                    }
                    $where .= 'id = :id ';
                    $i++;
                }

                if ($i > 0) {
                    $whereEmpty = false;
                }
            }

            $where .= ")";

            // Clear filters
            if ($whereEmpty) {
                $where = '';
                $filters = array();
            }
        }

        if ($activeOnly !== NULL) {
            if (!$whereEmpty) {
                $where .= "and ";
            }
            $where .= "x.active = :active";
            $filters['active'] = $activeOnly;
            $whereEmpty = false;
        }

        $dql = "select count(x.id) as qtd from User x ";

        if (!$whereEmpty) {
            $dql .= "where $where ";
        }

        $q = $this->em->createQuery($dql);

        $this->setParams($q, $filters);

        return $q->getSingleScalarResult();
    }

}
