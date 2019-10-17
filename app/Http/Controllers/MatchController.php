<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Match;
use App\BoardWinnerHelper;

class MatchController extends Controller {
    
    const PLAYER_X = 1;
    const PLAYER_O = 2;
    const PLAYER_EMPTY = 0; 
    const DEFAULT_BOARD = [
        0, 0, 0,
        0, 0, 0,
        0, 0, 0,
    ];
    
    /**
    * Method responsible for provide view
    *
    * @author Igor Rotondo Bagliotti
    * @since 15/11/2018
    * @return View
    */
    public function index() {
        return view('index');
    }
    
    /**
    * Returns a list of matches
    *
    * @author Igor Rotondo Bagliotti
    * @since 15/11/2018
    * @return \Illuminate\Http\JsonResponse
    */
    public function matches() {       
        return response()->json(Match::get());
    }
    
    /**
    * Returns the state of a single match
    *
    * @author Igor Rotondo Bagliotti
    * @since 15/11/2018
    * @return \Illuminate\Http\JsonResponse
    */
    public function match($id) {
        return response()->json(Match::findOrFail($id));
    }
    
    /**
    * Returns the state of a single match
    *
    * @author Igor Rotondo Bagliotti
    * @since 15/11/2018
    * @param $id
    * @return Illuminate\Database\Eloquent\Model
    */
    public function getMatchById($id){
        return Match::findOrFail($id);
    }

    /**
    * Makes a move in a match
    *
    * @author Igor Rotondo Bagliotti
    * @since 15/11/2018
    * @param $id, $request
    * @return \Illuminate\Http\JsonResponse
    */
    public function move($id, Request $request) {
        $this->updateOnMatch($id,$request);
        return $this->match($id);
    }
    
    /**
    * Method responsible for verifying the winner according to the possibilities of mapped victories
    *
    * @author Igor Rotondo Bagliotti
    * @since 15/11/2018
    * @param $board
    * @return \Illuminate\Http\JsonResponse
    */
    public function checkWinner($board){
        $winner = false;
        
        //Check Lines
        if ($board[0] == $board[1] && $board[1] == $board[2] && $board[0] != 0)
            $winner = true;
        else if ($board[3] == $board[4] && $board[4] == $board[5] && $board[3] != 0)
            $winner = true;
        else if ($board[6] == $board[7] && $board[7] == $board[8] && $board[6] != 0)
            $winner = true;
        //Check columns
        else if ($board[0] == $board[3] && $board[3] == $board[6] && $board[0] != 0)
            $winner =  true;
        else if ($board[1] == $board[4] && $board[4] == $board[7] && $board[1] != 0)
            $winner =true;
        else if ($board[2] == $board[5] && $board[5] == $board[8] && $board[2] != 0)
            $winner = true;
        //checkMainDiagonal
        else if ($board[6] == $board[4] && $board[4] == $board[2] && $board[6] != 0)
            $winner =  true;
        //checkSecundaryDiagonal
        else if ($board[0] == $board[4] && $board[4] == $board[8] && $board[0] != 0)
            $winner = true;
        
        return $winner;
    }
     
    /**
    * Method responsible for assigning the game to the board
    *
    * @author Igor Rotondo Bagliotti
    * @since 15/11/2018
    * @param $id, $selectedPlayer,$request
    * @return Array
    */
    public function setPositionToBoardByPlayer($id, $selectedPlayer, $request){
        $board = $this->getMatchById($id)->board;
        $board[$request->position] = $selectedPlayer;
        return $board;
    }
    
    /**
    * Method responsible for verify the next player
    *
    * @author Igor Rotondo Bagliotti
    * @since 15/11/2018
    * @param $id
    *
    * @return Integer
    */
    public function getNextPlayer($id){
        $next= $this->getMatchById($id)->next;
        return $next == self::PLAYER_X ? $next = self::PLAYER_O : $next = self::PLAYER_X ;
    }
    
    /**
    * Method responsible for update database on play match
    *
    * @author Igor Rotondo Bagliotti
    * @since 15/11/2018
    * @param $id, $request
    *
    * @return void
    */
    public function updateOnMatch($id, $request){
        $selectedPlayer = $this->getMatchById($id)->next;
        $board =  $this->setPositionToBoardByPlayer($id, $selectedPlayer, $request);

        $match = $this->getMatchById($id);
        if ($this->checkWinner($board))
            $match->winner = $selectedPlayer;

        $match->next = $this->getNextPlayer($id);
        $match->board = $board;
        return $match->save();
    }
    
    /**
    * Creates a new match and returns the new list of matches
    *
    * @author Igor Rotondo Bagliotti
    * @since 15/11/2018
    * @param $id, $request
    * @return \Illuminate\Http\JsonResponse
    */
    public function create() {
        $idMatch = $this->getNextRegisterFromMatch(); 
        $match = new Match();
        $match->name = "Match$idMatch";
        $match->next = $this->getNextRandomPlayer();
        $match->winner = self::PLAYER_EMPTY;
        $match->board = self::DEFAULT_BOARD;
        $match->save();
        
        return response()->json(Match::get());
    }
    
    /**
    * Method responsible for get a next random Player on new match
    *
    * @author Igor Rotondo Bagliotti
    * @since 15/11/2018
    * @return \Illuminate\Http\JsonResponse
    */
    public function getNextRandomPlayer(){
        return rand(self::PLAYER_X, self::PLAYER_O);
    }
    
    /**
    * Method responsible for a sequential match id
    *
    * @author Igor Rotondo Bagliotti
    * @since 15/11/2018
    * @return Integer
    */
    private function getNextRegisterFromMatch(){
        $getLastRegister = Match::latest()->first();
        
        $idMatch = 1;
        if($getLastRegister != null)
            $idMatch = (string) $getLastRegister->id+1;            
        
        return $idMatch; 
    }
    
    /**
    * Deletes the match and returns the new list of matches
    *
    * @param $id
    * @return \Illuminate\Http\JsonResponse
    */
    public function delete($id) {
        $match = Match::findOrFail($id); 
        $match->delete();
        return response()->json(Match::get());                
    }
}
