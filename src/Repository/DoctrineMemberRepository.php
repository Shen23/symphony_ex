<?php

namespace Avid\CandidateChallenge\Repository;

use Avid\CandidateChallenge\Model\Address;
use Avid\CandidateChallenge\Model\Email;
use Avid\CandidateChallenge\Model\Height;
use Avid\CandidateChallenge\Model\Member;
use Avid\CandidateChallenge\Model\Weight;
use Doctrine\DBAL\Types\Type;

/**
 * @author Kevin Archer <kevin.archer@avidlifemedia.com>
 */
final class DoctrineMemberRepository extends DoctrineRepository implements MemberRepository
{
    const CLASS_NAME = __CLASS__;
    const TABLE_NAME = 'members';
    const ALIAS = 'member';

    /**
     * @param Member $member
     *
     * @return int Affected rows
     */
    public function add($member)
    {
        return $this->getConnection()->insert($this->getTableName(),
            $this->extractData($member));
    }

    /**
     * @param Member $member
     *
     * @return int Affected rows
     */
    public function update($member)
    {
        
        $this->getConnection()->update($this->getTableName(), 
                                    $this->extractData($member), 
                                    array('username'=>$member->getUsername()));
    }

    /**
     * @param Member $member
     *
     * @return int
     */
    public function remove($member)
    {
         return $this->createQueryBuilder()->delete($this->getTableName())
                            ->where('username=\'' . $member->getUsername() . '\'')
                            ->execute();
    
    }

    /**
     * @param string $username
     *
     * @return Member|null
     */
    public function findByUsername($username)
    {
        $qb = $this->createQueryBuilder()
                ->select('*')
                ->from($this->getTableName(), $this->getAlias())
                ->where($this->getAlias() . '.username=\''. $username . '\'')
                ->getSQL();
        
        $mem = $this->getConnection()->fetchAssoc($qb);

        if($mem){
            return $this->hydrate($mem);
        }
        else{
            return null;
        }
    }

    /**
     * @param string $keyword
     * @param int $first
     * @param int $max
     *
     * @return Member[]
     */
    public function search($keyword, $first = 0, $max = null)
    {
        $qb = $this->getBaseQuery($first, $max)
            ->where('username LIKE  "%' . $keyword . '%"')
            ->getSQL();
        $result_list = $this->hydrateAll($this->getConnection()->fetchAll($qb));
        return $result_list;
       }

    /**
     * @param string $keyword
     *
     * @return int
     */
    public function getSearchCount($keyword)
    {
        return sizeof($this->search($keyword));
    }

    /**
     * @return int
     */
    public function count()
    {
        return sizeof($this->findAll());
    }

    /**
     * @param int $first
     * @param int $max
     *
     * @return object
     */
    public function findAll($first = 0, $max = null)
    {
        $sql = $this->getBaseQuery($first,$max)->getSQL();
        $result_list = $this->getConnection()->fetchAll($sql);
        return $this->hydrateAll($result_list);
        
    }

    /**
     * @param array $row
     *
     * @return Member
     */
    protected function hydrate(array $row)
    {
        date_default_timezone_set('UTC');
        return new Member(
            $row['username'],
            $row['password'],
            new Address($row['country'], $row['province'], $row['city'], $row['postal_code']),
            new \DateTime($row['date_of_birth']),
            $row['limits'],
            new Height($row['height']),
            new Weight($row['weight']),
            $row['body_type'],
            $row['ethnicity'],
            new Email($row['email'])
        );
    }

    /**
     * @return string
     */
    protected function getTableName()
    {
        return self::TABLE_NAME;
    }

    /**
     * @return string
     */
    protected function getAlias()
    {
        return self::ALIAS;
    }

    /**
     * @param Member $member
     *
     * @return array
     */
    private function extractData($member)
    {
        return [
            'username' => $member->getUsername(),
            'password' => $member->getPassword(),
            'country' => $member->getAddress()->getCountry(),
            'province' => $member->getAddress()->getProvince(),
            'city' => $member->getAddress()->getCity(),
            'postal_code' => $member->getAddress()->getPostalCode(),
            'date_of_birth' => $member->getDateOfBirth(),
            'limits' => $member->getLimits(),
            'height' => $member->getHeight(),
            'weight' => $member->getWeight(),
            'body_type' => $member->getBodyType(),
            'ethnicity' => $member->getEthnicity(),
            'email' => $member->getEmail(),
        ];
    }

    /**
     * @return array
     */
    private function getDataTypes()
    {
        return [
            Type::STRING,
            Type::STRING,
            Type::STRING,
            Type::STRING,
            Type::STRING,
            Type::STRING,
            Type::DATE,
            Type::STRING,
            Type::STRING,
            Type::STRING,
            Type::STRING,
            Type::STRING,
            Type::STRING,
        ];
    }
}
