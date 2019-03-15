<?php
function draw_accomplishment_row($row){
	$columns = array('fully_evolved', 'full_family', 'fully_trained', 'both_genders', 'have_noble', 'have_exalted', 'full_herd');
	foreach($columns as $acc){
		echo '<td>';
		if($row["can_{$acc}"]){
			if(!$row["{$acc}"]){
				echo "<a href='/accomplishments.php?{$acc}={$row['familyID']}'><img src='/images/icons2/no.png' /></a>";
			}
			else{
				echo "<img src='/images/icons2/yes.png' />";
			}
		}
		else{
			echo "&nbsp;";
		}
		echo '</td>';
	}
}

function draw_creature_tile($creature, $with_link = ''){
	$html = '';
	if(!empty($with_link)){
		$html.= "<a href='{$with_link}'>";
	}

	$html.=		"<img src='".mkimg($creature)."' />";
	
	if(!empty($with_link)){
		$html.= "</a>";
	}
	
	$html.= 	 "<br />";

	if($creature['frozen']){
		$html.= "<span class='blue'>{$creature['nickname']}</span>";
	} else {
		$html.= $creature['nickname'];
	}
	return $html;
}

//general filters
add_filter('page_title', function($v){ return esc_html(trim($v .= " - UniCreatures")); });

add_filter('shop_navigation', function($v){
	$cats = apc_fetch('shop_categories');

	if($cats === false){
	
		//fetch cats
		$categories = cfg::$db->query("SELECT categoryID, name FROM shop_categories");

		while($row = $categories->fetch_assoc()){
			$cats[] = $row;
		}
		apc_store('shop_categories', $cats, 3600);
	}

	echo "<table class='maxwidth table-creatures' id='transmute-categories'>
			<tbody>";

	draw_fancy_table_rows($cats, 4, 
						function($cat){
							return	"<a class='transmute-category' href='/transmute.php?cat={$cat['categoryID']}'>".
									"<img src='/images/shop/".concise($cat['name']).".png' />".
									"<br />{$cat['name']}</a>";
						}
	);

	echo "	</tbody>
		</table>";
});

add_filter('profile_navigation',
			function($v){
				$username = get_profile_username();
				return	"<div id='profile-nav'>
							".get_profile_title()."
							<a href='/profile.php?username={$username}' id='profile-nav-profile'></a>
							<a href='/inventory.php?username={$username}' id='profile-nav-inventory'></a>
							<a href='/accomplishments.php?username={$username}' id='profile-nav-accomps'></a>
							<a href='/herd_list.php?username={$username}' id='profile-nav-herds'></a>
							<a href='/usercp.php?username={$username}' id='profile-nav-tools'></a>
							<a href='/campfire.php?username={$username}' id='profile-nav-campfire'></a>
							<a href='/friends.php?username={$username}' id='profile-nav-friends'></a>
						</div>";
			}
);

/* API.php */
function get_page_title(){
	echo apply_filters('page_title', '');
}

function get_profile_title(){
	echo apply_filters('profile_title', '');
}

function get_profile_navigation(){
	echo apply_filters('profile_navigation', '');
}

function get_shop_navigation(){
	echo apply_filters('shop_navigation', '');
}
/* end of API */
?>