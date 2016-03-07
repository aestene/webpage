<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Department;
use AppBundle\Entity\Semester;
use Doctrine\ORM\EntityRepository;

/**
 * ApplicationInfoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ApplicationRepository extends EntityRepository {

    /**
     * Finds all applications that have a conducted interview.
     *
     * @param null $department
     * @param null $semester
     * @return array
     */
    public function findInterviewedApplicants($department = null, $semester = null) {
        $qb = $this->createQueryBuilder('a')
            ->select('a')
            ->join('a.semester', 'sem')
            ->join('sem.department', 'd')
            ->join('a.interview', 'i')
            ->where('i.interviewScore IS NOT NULL')
            ->andWhere('a.previousParticipation = 0');

            if(null !== $department) {
               $qb->andWhere('d = :department')
                   ->setParameter('department', $department);
            }

            if(null !== $semester) {
                $qb->andWhere('sem = :semester')
                    ->setParameter('semester', $semester);
            }

        return $qb->getQuery()->getResult();
    }

    public function findPreviousApplicants($department = null, $semester = null) {
        $qb = $this->createQueryBuilder('a')
            ->select('a')
            ->join('a.semester', 'sem')
            ->join('sem.department', 'd')
            ->andWhere('a.previousParticipation = 1');

        if(null !== $department) {
            $qb->andWhere('d = :department')
                ->setParameter('department', $department);
        }

        if(null !== $semester) {
            $qb->andWhere('sem = :semester')
                ->setParameter('semester', $semester);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Finds all applications that have been assigned an interview that has not yet been conducted.
     *
     * @param null $department
     * @param null $semester
     * @return array
     */
    public function findAssignedApplicants($department = null, $semester = null) {
        $qb = $this->createQueryBuilder('a')
            ->select('a')
            ->join('a.semester', 'sem')
            ->join('sem.department', 'd')
            ->join('a.user', 'u')
            ->join('a.interview', 'i')
            ->where('i.interviewed = 0');

             if(null !== $department) {
                 $qb->andWhere('d = :department')
                     ->setParameter('department', $department);
             }

            if(null !== $semester) {
                $qb->andWhere('sem = :semester')
                    ->setParameter('semester', $semester);
            }

        return $qb->getQuery()->getResult();
    }

    /**
     * Finds all applications without an interview, or without a conducted interview.
     *
     * @param null $department
     * @param null $semester
     * @return array
     */
    public function findNewApplicants($department = null, $semester = null) {
        $qb = $this->createQueryBuilder('a')
            ->select('a')
            ->join('a.semester', 'sem')
            ->join('sem.department', 'd')
            ->join('a.user', 'u')
            ->leftJoin('a.interview', 'i')
            ->where('a.previousParticipation = 0')
            ->andWhere('i is NULL OR i.interviewed = 0');


            if(null !== $department) {
                $qb->andWhere('d = :department')
                    ->setParameter('department', $department);
            }

            if(null !== $semester) {
                $qb->andWhere('sem = :semester')
                    ->setParameter('semester', $semester);
            }

        return $qb->getQuery()->getResult();
    }

    /**
     * @param Department $department
     * @param Semester $semester
     * @return array
     */
    public function findExistingApplicants(Department $department, Semester $semester){
        return $this->createQueryBuilder('a')
            ->select('a')
            ->join('a.semester', 'sem')
            ->join('sem.department', 'd')
            ->where('a.previousParticipation = 1')
            ->andWhere('d= :department')
            ->andWhere('sem = :semester')
            ->setParameter('department', $department)
            ->setParameter('semester', $semester)
            ->getQuery()
            ->getResult();
    }

    /* Disse brukes ikke lenger(?)
    public function getAllApplicants(){

        $applicants =  $this->getEntityManager()->createQuery("

		SELECT a, s
		FROM AppBundle:Application a
		JOIN a.statistic s

		")
            ->getResult();

        return $applicants;
    }

    public function findAllApplicantsForDepartment($department){

        $applicants =  $this->getEntityManager()->createQuery("

		SELECT a, s
		FROM AppBundle:Application a
		JOIN a.statistic s
		JOIN s.fieldOfStudy fos
		JOIN fos.department department
		WHERE s.fieldOfStudy = fos.id
			AND fos.department = :department
		")
            ->setParameter('department', $department)
            ->getResult();

        return $applicants;
    }*/

    public function findApplicantById($id){
        return $this->createQueryBuilder('Application')
            ->select('Application')
            ->where('Application.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();
    }

    public function findApplicantStatisticById($id){
        return $this->createQueryBuilder('ApplicationStatistic')
            ->select('ApplicationStatistic')
            ->where('ApplicationStatistic.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();
    }

    public function NumOfApplications(){
        return $this->createQueryBuilder('ApplicationStatistic')
            ->select('count(ApplicationStatistic.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function NumOfSemesters(){
        return $this->createQueryBuilder('ApplicationStatistic')
            ->select('count(ApplicationStatistic.semester)')
            ->distinct()
            ->getQuery()
            ->getSingleScalarResult();
    }


    public function NumOfSemester($semester){
        return $this->createQueryBuilder('ApplicationStatistic')
            ->select('count(ApplicationStatistic.semester)')
            ->where('ApplicationStatistic.semester = :semester')
            ->setParameter('semester', $semester)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function NumOfGender($gender){
        return $this->createQueryBuilder('ApplicationStatistic')
            ->select('count(ApplicationStatistic.gender)')
            ->where('ApplicationStatistic.gender = :gender')
            ->setParameter('gender', $gender)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function NumOfPreviousParticipation($participated){
        return $this->createQueryBuilder('ApplicationStatistic')
            ->select('count(ApplicationStatistic.gender)')
            ->where('ApplicationStatistic.previousParticipation = :participated')
            ->setParameter('participated', $participated)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function NumOfAccepted($accepted){
        return $this->createQueryBuilder('ApplicationStatistic')
            ->select('count(ApplicationStatistic.accepted)')
            ->where('ApplicationStatistic.accepted = :accepted')
            ->setParameter('accepted', $accepted)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function NumOfYearOfStudy($yearOfStudy){
        return $this->createQueryBuilder('ApplicationStatistic')
            ->select('count(ApplicationStatistic.yearOfStudy)')
            ->where('ApplicationStatistic.yearOfStudy = :yearOfStudy')
            ->setParameter('yearOfStudy', $yearOfStudy)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function NumOfFieldOfStudy($fieldOfStudy){
        return $this->createQueryBuilder('ApplicationStatistic')
            ->select('count(ApplicationStatistic.fieldOfStudy)')
            ->where('ApplicationStatistic.fieldOfStudy = :fieldOfStudy')
            ->setParameter('fieldOfStudy', $fieldOfStudy)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function NumOfDepartment($department){
        $numUsers =  $this->getEntityManager()->createQuery("

		SELECT COUNT (AppS.id)
		FROM AppBundle:ApplicationStatistic AppS
		JOIN AppS.semester s
		JOIN s.department d
		WHERE d.id = :department

		")
            ->setParameter('department', $department)
            ->getSingleScalarResult();

        return $numUsers;
    }

    public function findAllAllocatableApplicationsBySemester(Semester $semester){
        return $this->createQueryBuilder('a')
            ->select('a')
            ->join('a.interview', 'i')
            ->where('a.semester = :semester')
            ->andWhere('a.previousParticipation = 1 OR i.interviewed = 1')
            ->setParameter('semester', $semester)
            ->getQuery()
            ->getResult();
    }

}