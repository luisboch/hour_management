<?php
require_once SERVICE_DIR.'utils/DateUtils.php';
/**
 * Description of ActivityInteractions
 *
 * @author luis
 * @since Jan 13, 2014
 * @Entity @Table(name="activity_interaction")
 */
class ActivityInteraction {

    /**
     * @Id 
     * @Column(type="integer") 
     * @GeneratedValue
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     * @var User 
     */
    private $user;

    /**
     * @ManyToOne(targetEntity="Activity")
     * @JoinColumn(name="activity_id", referencedColumnName="id")
     * @var Activity 
     */
    private $activity;

    /**
     * @var DateTime 
     * @Column(type="datetime", name="creation_date")
     */
    private $creationDate;

    /**
     * @var DateTime 
     * @Column(type="datetime", name="start_date")
     */
    private $startDate;

    /**
     * @var DateTime 
     * @Column(type="datetime", name="end_date")
     */
    private $endDate;

    public function getId() {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser() {
        return $this->user;
    }

    public function getActivity() {
        return $this->activity;
    }

    public function getCreationDate() {
        return $this->creationDate;
    }

    public function getStartDate() {
        return $this->startDate;
    }

    public function getEndDate() {
        return $this->endDate;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setUser(User $user) {
        $this->user = $user;
    }

    public function setActivity(Activity $activity) {
        $this->activity = $activity;
    }

    /**
     * @param DateTime $creationDate
     */
    public function setCreationDate($creationDate) {
        $this->creationDate = $creationDate;
    }

    /**
     * @param DateTime $startDate
     */
    public function setStartDate($startDate) {
        $this->startDate = $startDate;
    }

    /**
     * @param DateTime $endDate
     */
    public function setEndDate($endDate) {
        $this->endDate = $endDate;
    }

    public function getAllocatedTime() {
        return DateUtils::getAllocatedTime($this->getStartDate(), $this->getEndDate());
    }

}
