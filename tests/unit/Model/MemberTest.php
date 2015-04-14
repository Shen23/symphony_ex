<?php

namespace Avid\CandidateChallenge\Model;

/**
 * @covers \Avid\CandidateChallenge\Model\Member
 *
 * @uses \Avid\CandidateChallenge\Model\Address
 * @uses \Avid\CandidateChallenge\Model\Height
 * @uses \Avid\CandidateChallenge\Model\Weight
 * @uses \Avid\CandidateChallenge\Model\Email
 *
 * @author Kevin Archer <kevin.archer@avidlifemedia.com>
 */
final class MemberTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this -> $expectedMember = new Member(
            'kovacek.keara',
            'et',
            new Address('Canada', 'Ontario', 'Hamilton', 'H4Y 5E1'),
            new \DateTime('1993-04-11'),
            'Undecided',
            new Height('4\' 5"'),
            new Weight('150 lbs'),
            'Slim',
            'Other',
            new Email('kovacek.keara@email.com')
        );
    }
	

    public function test_city(){
    	$this->assertEquals('Hamilton',
    		$this->expectedMember->getAddress()->getCity());
    }

    public function test_country(){
    	$this->assertEquals('Canada',
    		$this->expectedMember->getAddress()->getCountry());
    }

    public function test_postcode(){
    	$this->assertEquals('H4Y 5E1', 
    		$this->expectedMember->getAddress()->getPostalCode());
    }

    public function test_province(){
    	$this->assertEquals('Ontario',
    		$this->expectedMember->getAddress()->getProvince());
    }

    public function test_bodytype(){
    	$this->assertEquals('Slim',
    		$this->expectedMember->getBodyType());
    }

    public function test_DOB(){
    	$this->assertEquals('1993-04-11',
    		$this->expectedMember->getDateOfBirth());
    }

    public function test_email(){
    	$this->assertEquals('kovacek.keara@email.com',
    		$this->expectedMember->getEmail());
    }

    public function test_ethnicity(){
    	$this->assertEquals('Other',
    		$this->expectedMember->getEthnicity());
    }

    public function test_height(){
    	$this->assertEquals('4\' 5"',
    		$this->expectedMember->getHeight());
    }

    public function test_limits(){
    	$this->assertEquals('Undecided',
    		$this->expectedMember->getLimits());
    }

    public function test_password(){
    	$this->assertEquals('et',
    		$this->expectedMember->getPassword());
    }

    public function test_username(){
        $this->assertEquals('kovacek.keara', 
            $this->expectedMember->getUsername());
    }

    public function test_weight(){
    	$this->assertEquals('150 lbs',
    		$this->expectedMember->getWeight());
    }

    public function test_age(){
    	$now = new \DateTime();

        $age = $now->diff($this->dateOfBirth)->y;

        $this->assertEquals($age,
        	$this->expectedMember->getAge());
    }
}
