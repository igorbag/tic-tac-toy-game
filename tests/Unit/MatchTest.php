<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\MatchController;
use Illuminate\Http\Request;

class MatchTest extends TestCase
{
    
    protected $matchController;
    
    public function setUp() {
        
        parent::setUp();
        
        $this->matchController = $this->app->make(MatchController::class);
        
        $this->matchController->create();
    }
    
    public function testMatches(){
        $this->assertEquals(true, $this->matchController->matches()!= null);
    }
    
    public function testMatch(){
        $this->assertEquals(true, $this->matchController->match(1)!= null);
    }
    
    public function testGetMatchById(){
        $this->assertNotEmpty($this->matchController->getMatchById(1));
    }
    
    public function testMove(){
        $this->assertNotEmpty($this->matchController->move(1, new Request));
    }
    
    public function testCheckWinner(){
        $board = [
            1,1,1,
            0,0,0,
            0,0,0
        ];
        $this->assertEquals(true, $this->matchController->checkWinner($board));
    }
    
    public function testCheckWinnerX(){
        $board = [
            2,2,2,
            1,0,1,
            0,1,0
        ];
        $this->assertEquals(true, $this->matchController->checkWinner($board));
    }
    
    
    public function testCheckWinnerY(){
        $board = [
            0,0,2,
            1,1,1,
            0,0,0
        ];
        $this->assertEquals(true, $this->matchController->checkWinner($board));
    }
    
    
    public function testCheckWinnerFalseError(){
        $board = [
            0,0,2,
            0,1,1,
            0,0,0
        ];
        $this->assertFalse( $this->matchController->checkWinner($board));
    }
    
    
    public function testCheckWinnerDefault(){
        $board = [
            0,0,0,
            0,0,0,
            0,0,0
        ];
        $this->assertFalse( $this->matchController->checkWinner($board));
    }
    
    public function testSetPositionToBoardByPlayer(){
        $this->assertNotEmpty($this->matchController->setPositionToBoardByPlayer(1,1,new Request));
    }
    
    public function testeGetNextPlayer(){
        $this->assertEquals(2,$this->matchController->getNextPlayer(1));
    }
    
    
    public function testGetNextPlayerTwo(){
        $this->assertEquals(1,$this->matchController->getNextPlayer(2));
    }
    
    
    public function testUpdateOnMatch(){
        $this->assertTrue(true, $this->matchController->updateOnMatch(1, new Request));
    }
    
    public function testCreate(){
        $this->assertNotEmpty($this->matchController->create());
    }
    
    public function testGetNextRandomPlayer(){
        $this->assertInternalType('int', $this->matchController->getNextRandomPlayer());
    }
    
    public function getNextRegisterFromMatch(){
        $this->assertInternalType('int', $this->matchController->getNextRegisterFromMatch());
    }
    
    public function testDelete(){
        $this->assertEquals(true, $this->matchController->delete(1)!= null);
    }
}
