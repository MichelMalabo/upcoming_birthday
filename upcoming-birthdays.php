<?php

$people_ids = get_users(
    array(
        'fields' => 'ids',
    )
);

?>


<?php

foreach ( $people_ids as $id )
  {  //echo "$id<br />";
    $ids[$id]=$id;
    
  }  

$total_users = count($ids);   //NUMBER OF USER ON THE INTRANET
$today = date ("d F");

for($x = 1; $x <= $total_users; $x++)
{
    $meta[$x] = get_user_meta( $x); //EACH META USERS IN ARRAYS
    //$nickname[$x] = $meta[$x]['display_name'][0]; // EACH USERS DISPLAY NAME
   $nickname[$x] = xprofile_get_field_data( 'Full Name' , $x);
    $user_pic[$x] = esc_url( get_avatar_url( $x) );// USER PROFILE PICS
    $raw_DOB[$x]= date_create( xprofile_get_field_data( 'Date of Birth' , $x ) );//FULL DATE OF BIRTH
    
    $day_month[$x] = date_format( $raw_DOB[$x], "d F");//BIRTHDAYS
    
    //$day_month[$x] = date("d F", strtotime($DOB[$x])); // BIRTHDAY DAY AND MONTH
    
    
    $C_user[$x] = array( $nickname[$x],$day_month[$x],$user_pic[$x] ); // DISPLAY NAME, BIRTHDATE, PRO PIC
    
    if ( empty($C_user[$x][0]) || empty($C_user[$x][1]) ) 
    { 
        unset($C_user[$x]) ;
        
    }
    
    $try_user[$x] = array( $nickname[$x],$day_month[$x]); //
}

$B_user = array_values($C_user);

function sortFunction( $a, $b ) { //REORDER FROM EARLIEST TO LATEST BIRTHDAY
    return strtotime($a[1]) - strtotime($b[1]);
}

usort($B_user, "sortFunction");


for($x = 0; $x <= $total_users; $x++){
    if ( (strtotime($B_user[$x][1]) < strtotime($today)) && (strtotime($B_user[$x+1][1]) >= strtotime($today)) ) {
        
        $a = $x+1; //THE USER HAVING BIRTHDAY OR WITH THE CLOSEST DAY TO THE BIRTHDAY
    }
}

$before_bday = array_slice ($B_user, 0, $a); //GROUP OF USERS WITH BIRTHDAY BEFORE TODAY
$after_bday = array_slice ($B_user, $a, $total_users); //GROUP OF USER WITH BIRTHDAY TODAY OR/AND AFTER TODAY

$bday_group = array_merge ($after_bday, $before_bday); //COMBINING THE GROUP SO IT FORM A LOOP OF BIRTHDAYS

//$DOB= xprofile_get_field_data( 'Date of Birth' , '1' );


?>



<ul class="list-bday ">

<?php 

for ($i=0; $i <= 6; $i++) //SHOW ONLY SIX(6) BIRDTHDATE
{
    if ( ( strtotime($bday_group[$i][1]) == strtotime($today) ) && !empty($bday_group[$i][1]) )//IIF IT IS THE USER BIRTHDAY
    { 
    ?>
    <li class="list-bday-item">
        <div class="list-bday-pic" >
            <img class="bday-today" alt= "<?php echo $bday_group[$i][0]?>" src="<?php echo $bday_group[$i][2]?>" >
        </div>
        <div class="list-bday-content">
            <h3><?php echo $bday_group[$i][0]?></h3>
            <span class="badge badge-success">HAPPY BIRTHDAY</span>
        </div>
        <div class="bday-cake">
            <i class="fas fa-birthday-cake"></i>
        </div>
    </li>
    <?php
    }
    else 
    {
    ?>
    <li class="list-bday-item">
        <div class="list-bday-pic" >
            <img src="<?php echo $bday_group[$i][2]?>" >
        </div>
        <div class="list-bday-content">
            <h4><?php echo $bday_group[$i][0]?></h4>
            <span>Birthday | </span>
            <span class="bday-soon"><?php echo $bday_group[$i][1]?></span>
        </div>
    </li>
    <?php
    }
}


?>

</ul>



