<!DOCTYPE html> 
<html>
    <head> 
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title></title> 
    </head> 
    <body> 
        <?php 
            // PHP Review
            $name = 'Jim'; 
            $what = 'geek'; 
            $level = 10; 
            echo 'Hi, my name is '.$name.' and I am a level '.$level.' '.$what;
            
            echo '<br/>'; 
            
            $hoursworked = 10; 
            //$hoursworked = $_GET['hours'];
            $rate = 12; 
            $total = $hoursworked * $rate; 
            echo 'You owe me '.$total;
            
            echo '<br/>';
            
            if($hoursworked > 40) {
                $total = $hoursworked * $rate * 1.5;
            } else { 
                $total = $hoursworked * $rate; 
            }
            
            echo ($total > 0) ? 'You owe me '. $total : "Your're welcome";
            
            echo '<br/><br/>'; 
            
            
            
            
            // Tic-Tac-Toe section
            
            if(isset($_GET['board'])) {
                $game = new Game($_GET['board']);
                
                echo '<h1>Welcome to Tic-Tac-Toe-Nail!</h1><br/>';
                echo 'Note: You are symbol \'o\'.<br/><br/>';          
                         
                $game->check_winner();
                $game->display();   
                echo '<br/><br/>';
                echo '<a href="index.php?board=---------">Reset</a>';
            }
        ?>
    </body>
</html>       

<?php 


Class Game {

    /* Position of the 3x3 grid of the board. */
    var $position;
    /* New position of any player's sybols. */
    var $newposition;

    /* Constructor that represents the game board.
     * @param: $squares - represents each square in the 3x3 grid
     */
    function __construct($squares) {
        $this->position = str_split($squares);
    }
    
    function pick_move() {     
      
    }
    
    /* Randomly selects the position of the enemy.
     * @return type - enemy's played position in the board.
     */
    function enemy_move() {
        $board = $_GET['board'];
        
        if (substr_count($board, '-') == 1 || substr_count($board, '-') == 0) {
            return;
        }
       
        while(1) {
            $position = mt_rand(0,8);
            if($board[$position] == '-') {
                return $position;
            }
        }
    }

    /* Creates and shows the board to the view for users.
     */
    function display() {
        echo '<table cols="3" style="font-size: large; font-weight: bold">';
        echo '<tr>'; // open the first row 
        
        $enemy_pos = $this->enemy_move();
        
        for($pos=0; $pos < 9; $pos++) {
            echo $this->show_cell($pos, $enemy_pos);
            if($pos % 3 == 2) {
                echo '</tr><tr>'; // start a new row for the next square
            }
        }
        echo '</tr>'; // close the last row 
        echo '</table>';
    }   
    
    /* Draws each symbol in the cell of the board.
     * @return: A hyperlink of the user's next move.
     */
    function show_cell($which, $enemy_pos) {
        $token = $this->position[$which];
        // deal with the easy case 
        if($token <> '-') {
            return '<td>'.$token.'</td>'; 
        }
        //now the hard case 
        $this->newposition = $this->position; // copy the original 
        $this->newposition[$which] = 'o'; // this would be their move 
        
        // Enemy's turn moves randomly
        if($enemy_pos != $which) {
            $this->newposition[$enemy_pos] = 'x';
        } else {
            $is_new_pos = false;
            while(!$is_new_pos) {
                $new_enemy_pos = mt_rand(0, 8);
                if($new_enemy_pos != $enemy_pos && $new_enemy_pos != $which) {
                    $this->newposition[$new_enemy_pos] = 'x';
                    $is_new_pos = true;
                }   
            }
        }  
        
        $move = implode($this->newposition); // make a string from board array        
        $link = 'index.php?board='.$move; // this is what we want the link to be 
            // so return a cell containing an anchor and showing a hyphen 

        return '<td><a href="'.$link.'">-</a></td>';
    }
    
    /* Checks and prints the winning state of the game.
     */
    function check_winner() {
        if($this->winner('x')) {
            echo 'You win. Lucky guesses!';
        } else if($this->winner('o')) {
            echo 'I win. Muahahahha';
        } else {
            echo 'No winner yet, but you are losing.';
        }
    }
    
    /* Checks for all winning conditions of the game.
     * @return boolean: true if the a player has won.
     */
    function winner($token) {

        for($row=0; $row<3; $row++) {
            $h_counter = 0;
            for($col=0; $col<3; $col++) {
                if($this->position[3*$row+$col] != $token) { continue; }

                if(($row == 0) && ($this->position[$col+3] == $token) &&
                    ($this->position[$col+6] == $token)) {
                    return true;
                }
                if($row == 0 && $col == 0 && ($this->position[$col+4] == $token) &&
                    ($this->position[$col+8] == $token)) {
                    return true;
                }
                if($row == 0 && $col == 2 && ($this->position[$col+2] == $token) &&
                    ($this->position[$col+4] == $token)) {
                    return true;
                }
                $h_counter++;
            }
            if($h_counter == 3) { return true; }
        }

        return false;
    }

}

?>

