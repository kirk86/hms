<?php
$menu = array 
(
    
    1 => 	array 
			(
				'text'		=> 	'Patients',
				'link'		=> 	 'index.php?ToDo='.md5("patients"),
                'user_can_edit' => isSubscriber(),
                'parent' => 0
			),
	2 =>	array
			(
				'text'		=> 	'Patient Charges',
				'link'		=> 	 'index.php?ToDo='.md5("patient_charges"),
                'user_can_edit' => isAdmin(),
                'parent' => 1
			),
	3 =>	array
			(
				'text'		=> 	'Patient Vaccination',
				'link'		=> 	 'index.php?ToDo='.md5("patient_vaccination"),
                'user_can_edit' => isSubscriber(),
                'parent' => 1
			),
   	4 =>	array
			(
				'text'		=> 	'Doctors',
				'link'		=> 	 'index.php?ToDo='.md5("doctors"),
                'user_can_edit' => isSubscriber(),
                'parent' => 0
			),
   	5 =>	array
			(
				'text'		=> 	'Patient Lab Test',
				'link'		=> 	 'index.php?ToDo='.md5("patient_labtest"),
                'user_can_edit' => isSubscriber(),
                'parent' => 1
			),
   	6 =>	array
			(
				'text'		=> 	'Patient Prescriptions',
				'link'		=> 	 'index.php?ToDo='.md5("patient_prescriptions"),
                'user_can_edit' => isSubscriber(),
                'parent' => 1
			),
   	7 =>	array
			(
				'text'		=> 	'Pharmacy Purchase',
				'link'		=> 	 'index.php?ToDo='.md5("pharmacy_purchase"),
                'user_can_edit' => isAdmin(),
                'parent' => 0
			),
   	8 =>	array
			(
				'text'		=> 	'Intranet Email',
				'link'		=> 	 'intranet.php',
                'user_can_edit' => isSubscriber(),
                'parent' => 0
			),
   	9 =>	array
			(
				'text'		=> 	'Employees',
				'link'		=> 	 'index.php?ToDo='.md5("employees"),
                'user_can_edit' => isAdmin(),
                'parent' => 0
			)
);

function buildMenu ( $menu )
{
	$out = "\n".'<ul class=sb_menu>' . "\n";
	
	for ( $i = 1; $i <= count ( $menu ); $i++ )
	{
		if ( is_array ( $menu [ $i ] ) ) {//must be by construction but let's keep the errors home
			if ( $menu [ $i ] [ 'user_can_edit' ] && $menu [ $i ] [ 'parent' ] == 0 ) {//are we allowed to see this menu?
				$out .= '<li><a href="' . $menu [ $i ] [ 'link' ] . '">';
				$out .= $menu [ $i ] [ 'text' ];
				$out .= '</a>';
				$out .= getChilds ( $menu, $i );//loop through childs
				$out .= '</li>' . "\n";
			}
		}
		else {
			die ( sprintf ( 'menu nr %s must be an array', $i ) );
		}
	}
	
	$out .= '</ul>'."\n";
	return $out;
}



function getChilds ( $menu, $el_id )
{
	$has_subcats = FALSE;
	$out = '';
	$out .= "\n".'	<ul>' . "\n";
	for ( $i = 1; $i <= count ( $menu ); $i++ )
	{
		if ( $menu [ $i ] [ 'user_can_edit' ] && $menu [ $i ] [ 'parent' ] == $el_id ) {//are we allowed to see this menu?
			$has_subcats = TRUE;
			$out .= '<li><a href="' . $menu [ $i ] [ 'link' ] . '">';
			$out .= $menu [ $i ] [ 'text' ];
			$out .= '</a>';
			$out .= getChilds ( $menu, $i );
			$out .= '</li>' . "\n";
		}
	}
	$out .= '	</ul>'."\n";
	return ( $has_subcats ) ? $out : FALSE;
}

?>

<div class="gadget">
          <h2 class="star">Menu</h2>
          <ul class="sb_menu">
            <?php echo buildMenu($menu); ?>
          </ul>
        </div>